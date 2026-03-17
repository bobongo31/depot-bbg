<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\AccuseReception; // Correctement importé ici
use App\Models\Annexe;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdf\Fpdf;  // Ajoute cette ligne pour FPDF
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule; // added for unique ignore



class AccuseDeReceptionController extends Controller
{


    
     // Affichage du formulaire avec un champ vide pour le numéro d'enregistrement
     public function showForm()
     {
         // Suppression de la génération automatique du numéro d'enregistrement
         $draft = null;
         if (auth()->check()) {
             $draft = AccuseReception::with('annexes')
                 ->where('user_id', auth()->id())
                 ->where('statut', 'brouillon')
                 ->orderBy('updated_at', 'desc')
                 ->first();
         }

         return view('accuse_de_reception', compact('draft'));
     }

    public function indexTraite()
    {
        // Récupérer les accusés de réception dont le statut est "traité"
        $courriersTraites = AccuseReception::with('annexes')
                                ->where('statut', 'traité')
                                ->get();

        // Retourner la vue correspondante en passant les données
        return view('courriers.traites', compact('courriersTraites'));
    }


    public function edit($id)
    {
        $courrier = AccuseReception::findOrFail($id);
        return view('courriers.edit', compact('courrier'));
    }
    
    public function destroy($id)
    {
        $courrier = AccuseReception::findOrFail($id);
        $courrier->delete();

        return redirect()->route('courriers.index')->with('success', 'Courrier supprimé avec succès.');
    }

        public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'date_reception' => 'required|date',
            'numero_enregistrement' => 'required|string|max:50',
            'numero_reference' => 'nullable|string|max:50',
            'nom_expediteur' => 'required|string|max:255',
            'resume' => 'required|string|max:1000',
            'observation' => 'nullable|string|max:500',
            'commentaires' => 'nullable|string|max:500',
            'statut' => 'required|string|in:reçu,en attente,traité',
        ]);

        $courrier = AccuseReception::findOrFail($id);
        $courrier->update($validated);

        return redirect()->route('courriers.index')->with('success', 'Courrier mis à jour avec succès.');
    }




    public function indexAccuses()
    {
        // Récupérer les accusés de réception paginés (10 par page) et les trier par date de réception
        $perPage = 10; // modifier si nécessaire
        $accuses = AccuseReception::orderBy('date_reception', 'desc')
                                 ->paginate($perPage)
                                 ->withQueryString();

        return view('accuses.index', compact('accuses'));
    }

    public function show($id)
{
    // Utiliser le modèle AccuseReception
    $courrier = AccuseReception::with('annexes')->findOrFail($id);

    // Si la requête est AJAX, on renvoie une vue partielle
    if (request()->ajax()) {
        return response()->view('courriers.show', compact('courrier'));
    }

    // Sinon, on renvoie la vue complète
    return view('courriers.show', compact('courrier'));
}



    public function store(Request $request)
{
    logger()->info('Upload debug', [
        'files' => $request->allFiles(),
        'content_length' => $_SERVER['CONTENT_LENGTH'] ?? null,
    ]);

    // check if a draft id was provided and ensure it belongs to the current user
    $draftId = $request->input('draft_id');
    $draft = null;
    $sessionDraft = null;

    if ($draftId) {
        if (strpos($draftId, 'session-') === 0) {
            // draft persisted in session for this user
            $sessionKey = 'accuse_draft_' . auth()->id();
            $sessionDraft = $request->session()->get($sessionKey, null);
        } else {
            // legacy / db-stored draft id
            $draft = AccuseReception::where('id', $draftId)
                ->where('user_id', auth()->id())
                ->first();
        }
    } else {
        // if no draft_id provided, prefer session draft for this user (one draft in memory)
        $sessionKey = 'accuse_draft_' . auth()->id();
        $sessionDraft = $request->session()->get($sessionKey, null);
    }

    // build validation rules and allow the draft to keep/replace its numero without tripping the unique index
    $rules = [
        'date_reception' => 'required|date',
        'numero_enregistrement' => ['required', 'string'],
        'receptionne_par' => 'required|string|max:255',
        'objet' => 'required|string|max:2550',
        'annexes' => 'nullable|array',
        'annexes.*' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:99200',
        'avis' => 'nullable|string',
        'save_as_draft' => 'sometimes|boolean',
        'draft_id' => 'nullable|integer',
    ];

    // If we are finalizing a DB draft, ignore its id for unique check.
    // For session-only drafts we must still enforce uniqueness against DB.
    if ($draft) {
        $rules['numero_enregistrement'][] = Rule::unique('accuse_receptions', 'numero_enregistrement')->ignore($draft->id);
    } else {
        $rules['numero_enregistrement'][] = 'unique:accuse_receptions,numero_enregistrement';
    }

    $validated = $request->validate($rules);

    try {
        DB::beginTransaction();

        $statutVal = !empty($validated['save_as_draft']) ? 'brouillon' : 'reçu';

        if ($draft) {
            // update existing draft (owner already checked)
            $draft->update([
                'date_reception' => $validated['date_reception'] ?? now(),
                'numero_enregistrement' => $validated['numero_enregistrement'] ?? $draft->numero_enregistrement,
                'receptionne_par' => $validated['receptionne_par'] ?? $draft->receptionne_par,
                'objet' => $validated['objet'] ?? $draft->objet,
                'avis' => $validated['avis'] ?? $draft->avis,
                'statut' => $statutVal,
            ]);

            $accuse = $draft;
        } else {
            // If this is a session draft (in-memory) we will create the record now.
            $accuse = AccuseReception::create([
                'user_id' => auth()->id(),
                'date_reception' => $validated['date_reception'] ?? ($sessionDraft['date_reception'] ?? now()),
                'numero_enregistrement' => $validated['numero_enregistrement'] ?? ($sessionDraft['numero_enregistrement'] ?? ''),
                'receptionne_par' => $validated['receptionne_par'] ?? ($sessionDraft['receptionne_par'] ?? ''),
                'objet' => $validated['objet'] ?? ($sessionDraft['objet'] ?? null),
                'avis' => $validated['avis'] ?? ($sessionDraft['avis'] ?? null),
                'statut' => $statutVal,
            ]);
        }

        $pdf = new FPDI();

        // Read uploaded paths from chunk uploader (JSON array expected)
        $uploadedPaths = json_decode($request->input('uploaded_paths'), true);

        logger()->info('uploaded_paths reçus', ['paths' => $uploadedPaths]);

        if (!is_array($uploadedPaths) || empty($uploadedPaths)) {
            throw new \Exception('Aucune annexe reçue');
        }

        // Deduplicate by file content hash (sha256) to avoid duplicate uploads with different filenames
        $uniqueFiles = [];
        $seenHashes = [];

        foreach ($uploadedPaths as $path) {
            $full = storage_path("app/public/{$path}");

            if (!file_exists($full)) {
                logger()->warning('Annexe manquante ignorée', ['path' => $path]);
                continue;
            }

            try {
                $hash = hash_file('sha256', $full);
            } catch (\Throwable $e) {
                logger()->warning('Impossible de hasher le fichier', [
                    'path' => $path,
                    'error' => $e->getMessage()
                ]);
                continue;
            }

            if (!isset($seenHashes[$hash])) {
                $seenHashes[$hash] = true;
                $uniqueFiles[] = $path;
            } else {
                logger()->warning('Annexe dupliquée ignorée (même contenu)', [
                    'path' => $path,
                    'hash' => $hash
                ]);
            }
        }

        // Remove any generated combined PDF (safety) and keep canonical unique list
        $uniqueFiles = array_values(array_filter($uniqueFiles, function ($p) {
            return !str_starts_with(basename($p), 'accuse_');
        }));

        // KEEP ONLY THE FIRST UNIQUE FILE — do not process or store multiple uploaded files
        $uploadedPaths = !empty($uniqueFiles) ? [$uniqueFiles[0]] : [];

        if (empty($uploadedPaths)) {
            throw new \Exception('Aucun fichier valide à traiter.');
        }

        // Nom et chemin du PDF généré
        $outputFileName = "accuse_{$accuse->id}.pdf";
        $outputPath = storage_path("app/public/{$outputFileName}");

        // Génération du PDF seulement s'il n'existe pas encore
        if (!$accuse->pdf_generated_at || !file_exists($outputPath)) {
            foreach ($uploadedPaths as $filePath) {
                $annexePath = storage_path("app/public/{$filePath}");

                if (!file_exists($annexePath)) {
                    continue;
                }

                // Import du PDF source SANS ajouter de texte dessus
                $pageCount = $pdf->setSourceFile($annexePath);

                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplIdx = $pdf->importPage($i);
                    $size = $pdf->getTemplateSize($tplIdx);

                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                    $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                    $pdf->useTemplate($tplIdx, 0, 0, $size['width'], $size['height']);
                }
            }

            $pdf->Output($outputPath, 'F');

            // Create a single Annexe record pointing to the generated combined PDF
            Annexe::firstOrCreate([
                'accuse_de_reception_id' => $accuse->id,
                'file_path' => $outputFileName,
            ]);

            $accuse->update([
                'pdf_generated_at' => now()
            ]);
        }

        // NOTE: Do NOT create Annexe entries for original uploaded files here.
        // The single canonical annex is the generated combined PDF above.

        DB::commit();

        $downloadUrl = asset("storage/{$outputFileName}");

        return redirect()->route('accuses.index')->with([
            'download_url' => $downloadUrl,
            'success' => 'Accusé enregistré avec succès.'
        ]);

    } catch (QueryException $e) {
        DB::rollBack();
        logger()->error('DB error while storing accuse', [
            'exception' => $e->getMessage()
        ]);

        return back()->withInput()->withErrors([
            'database' => 'Erreur base de données : ' . $e->getMessage()
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        logger()->error('Unexpected error while storing accuse', [
            'exception' => $e->getMessage()
        ]);

        return back()->withInput()->withErrors([
            'database' => 'Erreur serveur : ' . $e->getMessage()
        ]);
    }
}

// Chunk upload endpoint: accepts chunks, merges, processes file and returns stored path
   public function uploadChunk(Request $request)
{
    $request->validate([
        'chunk'        => 'required|file',
        'file_id'      => 'required|string',
        'chunk_index'  => 'required|integer|min:0',
        'total_chunks' => 'required|integer|min:1',
        'filename'     => 'required|string',
    ]);

    // 1️⃣ Dossier des chunks (unique par fichier)
    $chunkDir = storage_path("app/chunks/{$request->file_id}");
    if (!is_dir($chunkDir)) {
        mkdir($chunkDir, 0777, true);
        chmod($chunkDir, 0777);
    }

    // 2️⃣ Sauvegarde du chunk (index = nom)
    $request->file('chunk')->move($chunkDir, (string) $request->chunk_index);

    // 3️⃣ Pas encore le dernier chunk → stop ici
    if ($request->chunk_index + 1 < $request->total_chunks) {
        return response()->json(['status' => 'chunk_received']);
    }

    /**
     * ==========================
     * DERNIER CHUNK → FUSION
     * ==========================
     */

    // 4️⃣ Fichier temporaire UNIQUE (jamais append)
    $tmpDir = storage_path('app/uploads');
    if (!is_dir($tmpDir)) {
        mkdir($tmpDir, 0777, true);
        chmod($tmpDir, 0777);
    }

    $finalTemp = "{$tmpDir}/{$request->file_id}.tmp";

    // 5️⃣ Fusion sécurisée (ordre garanti)
    $out = fopen($finalTemp, 'wb');

    for ($i = 0; $i < $request->total_chunks; $i++) {
        $chunkPath = "{$chunkDir}/{$i}";

        if (!file_exists($chunkPath)) {
            fclose($out);
            throw new \Exception("Chunk manquant : {$i}");
        }

        fwrite($out, file_get_contents($chunkPath));
    }

    fclose($out);

    // 6️⃣ Traitement final (PDF → compression)
    $extension = strtolower(pathinfo($request->filename, PATHINFO_EXTENSION));
    $storedRelative = null;

    if ($extension === 'pdf') {
        $compressedPath = storage_path("app/temp_{$request->file_id}.pdf");

        $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook "
            . "-dDownsampleColorImages=true -dColorImageResolution=100 "
            . "-dDownsampleGrayImages=true -dGrayImageResolution=100 "
            . "-dDownsampleMonoImages=true -dMonoImageResolution=100 "
            . "-dNOPAUSE -dQUIET -dBATCH "
            . "-sOutputFile=" . escapeshellarg($compressedPath) . " "
            . escapeshellarg($finalTemp);

        exec($command, $output, $resultCode);

        $destName = "annexes/{$request->file_id}.pdf";

        if ($resultCode === 0 && file_exists($compressedPath)) {
            Storage::disk('public')->put($destName, file_get_contents($compressedPath));
            @unlink($compressedPath);
        } else {
            // fallback sans compression
            Storage::disk('public')->put($destName, file_get_contents($finalTemp));
        }

        $storedRelative = $destName;
    } else {
        // Autres fichiers (jpg, doc, etc)
        $destName = "annexes/{$request->file_id}." . $extension;
        Storage::disk('public')->put($destName, file_get_contents($finalTemp));
        $storedRelative = $destName;
    }

    // 7️⃣ Nettoyage TOTAL
    @unlink($finalTemp);
    array_map('unlink', glob("{$chunkDir}/*"));
    @rmdir($chunkDir);

    // 8️⃣ Réponse UNIQUE (1 seul path)
    return response()->json([
        'status' => 'merged',
        'path'   => $storedRelative
    ]);
}

    /**
     * Save a draft via AJAX (autosave or explicit Save as Draft button)
     */
    public function saveDraft(Request $request)
    {
        $data = $request->only(['date_reception','numero_enregistrement','receptionne_par','objet','avis','uploaded_paths','draft_id']);

        $userId = auth()->id();
        $sessionKey = 'accuse_draft_' . $userId;

        // load existing session draft
        $draft = $request->session()->get($sessionKey, []);

        // merge incoming fields
        $draft['date_reception'] = $data['date_reception'] ?? ($draft['date_reception'] ?? null);
        $draft['numero_enregistrement'] = $data['numero_enregistrement'] ?? ($draft['numero_enregistrement'] ?? null);
        $draft['receptionne_par'] = $data['receptionne_par'] ?? ($draft['receptionne_par'] ?? null);
        $draft['objet'] = $data['objet'] ?? ($draft['objet'] ?? null);
        $draft['avis'] = $data['avis'] ?? ($draft['avis'] ?? null);

        // merge uploaded_paths arrays
        $incomingPaths = !empty($data['uploaded_paths']) ? json_decode($data['uploaded_paths'], true) : [];
        if (!is_array($incomingPaths)) { $incomingPaths = []; }
        $existingPaths = is_array($draft['uploaded_paths'] ?? null) ? $draft['uploaded_paths'] : [];
        $merged = array_values(array_unique(array_merge($existingPaths, $incomingPaths)));
        $draft['uploaded_paths'] = $merged;

        $draft['updated_at'] = now()->toDateTimeString();

        // persist in session only (in-memory per user)
        $request->session()->put($sessionKey, $draft);

        return response()->json(['status' => 'ok', 'draft_key' => 'session-' . $userId]);
    }

}
