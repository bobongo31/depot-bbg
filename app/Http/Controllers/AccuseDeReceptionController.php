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
                $draft = AccuseReception::where('id', $draftId)->where('user_id', auth()->id())->first();
            }
        } else {
            // if no draft_id provided, prefer session draft for this user (one draft in memory)
            $sessionKey = 'accuse_draft_' . auth()->id();
            $sessionDraft = $request->session()->get($sessionKey, null);
        }

        // build validation rules and allow the draft to keep/replace its numero without tripping the unique index
        $rules = [
            'date_reception' => 'required|date',
            'numero_enregistrement' => ['required','string'],
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
                // assign the final numero when finalizing; validation allowed same as draft due to ignore
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

        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {

        // On ne compresse que si c'est un PDF
        if ($file->getClientOriginalExtension() === 'pdf') {
            $tempOriginalPath = storage_path('app/temp_original_' . uniqid() . '.pdf');
            copy($file->getPathname(), $tempOriginalPath);

            $compressedPath = storage_path('app/temp_compressed_' . uniqid() . '.pdf');

            $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook "
                . "-dDownsampleColorImages=true -dColorImageResolution=100 "
                . "-dDownsampleGrayImages=true -dGrayImageResolution=100 "
                . "-dDownsampleMonoImages=true -dMonoImageResolution=100 "
                . "-dNOPAUSE -dQUIET -dBATCH -sOutputFile="
                . escapeshellarg($compressedPath) . ' ' . escapeshellarg($tempOriginalPath);

            exec($command, $output, $resultCode);
            logger()->info('Ghostscript command:', [
                'command' => $command,
                'output' => $output,
                'result' => $resultCode
            ]);

            if ($resultCode !== 0 || !file_exists($compressedPath)) {
                logger()->error('Compression échouée ou fichier introuvable.', ['compressedPath' => $compressedPath]);
                $filePath = $file->store('annexes', 'public');
            } else {
                $filePath = 'annexes/compressed_' . uniqid() . '.pdf';
                Storage::disk('public')->put($filePath, file_get_contents($compressedPath));
                unlink($compressedPath);
            }

            unlink($tempOriginalPath); // Nettoyage
            $annexePath = storage_path("app/public/{$filePath}");
        } else {
            // Gestion des autres types de fichiers (images, doc, etc)
            $filePath = $file->store('annexes', 'public');
            $annexePath = storage_path("app/public/{$filePath}");
        }


            $pageCount = $pdf->setSourceFile($annexePath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx, 0, 0, 210);

                $user = auth()->user();

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->SetXY(20, 10);
                $pdf->Cell(0, 10, " " . $user->entreprise, 0, 1, 'L');
                $pdf->SetXY(20, 20);
                $pdf->Cell(0, 10, "Date : " . $accuse->date_reception, 0, 1);
                $pdf->SetXY(20, 30);
                $pdf->Cell(0, 10, "Numero : " . $accuse->numero_enregistrement, 0, 1);
                $pdf->SetXY(20, 40);
                $pdf->Cell(0, 10, "Receptionne par : " . $accuse->receptionne_par, 0, 1);
                $pdf->SetXY(20, 50);
                $pdf->MultiCell(0, 10, "Objet : " . $accuse->objet, 0, 1);

                if (!empty($accuse->avis)) {
                    $pdf->SetXY(20, 70);
                    $pdf->MultiCell(0, 10, "Avis : " . $accuse->avis, 0, 1);
                }
            }
        }
    }

    

        $outputFileName = 'accuse_' . $accuse->id . '.pdf';
        $outputPath = storage_path("app/public/{$outputFileName}");
        $pdf->Output($outputPath, 'F');

        Annexe::create([
            'accuse_de_reception_id' => $accuse->id,
            'file_path' => $outputFileName,
        ]);

        // If there was a session draft with uploaded_paths, attach them now to the created record
        if (!empty($sessionDraft['uploaded_paths']) && is_array($sessionDraft['uploaded_paths'])) {
            foreach ($sessionDraft['uploaded_paths'] as $p) {
                Annexe::create([
                    'accuse_de_reception_id' => $accuse->id,
                    'file_path' => $p,
                ]);
            }
            // clear session draft
            $request->session()->forget($sessionKey ?? '');
        }

        // Handle any files uploaded via chunked uploader: uploaded_paths is JSON array of stored paths
        $uploadedPaths = $request->input('uploaded_paths');
        if ($uploadedPaths) {
            $paths = json_decode($uploadedPaths, true);
            if (is_array($paths)) {
                foreach ($paths as $p) {
                    // create Annexe record linking to this accuse
                    Annexe::create([
                        'accuse_de_reception_id' => $accuse->id,
                        'file_path' => $p,
                    ]);
                }
            }
        }

        DB::commit();

        $downloadUrl = asset("storage/{$outputFileName}");

        return redirect()->route('accuses.index')->with([
            'download_url' => $downloadUrl,
            'success' => 'Accusé enregistré avec succès.'
        ]);
    } catch (QueryException $e) {
        DB::rollBack();
        logger()->error('DB error while storing accuse', ['exception' => $e->getMessage()]);
        return back()->withInput()->withErrors(['database' => 'Erreur base de données : ' . $e->getMessage()]);
    } catch (\Exception $e) {
        DB::rollBack();
        logger()->error('Unexpected error while storing accuse', ['exception' => $e->getMessage()]);
        return back()->withInput()->withErrors(['database' => 'Erreur serveur : ' . $e->getMessage()]);
    }
}

// Chunk upload endpoint: accepts chunks, merges, processes file and returns stored path
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'chunk' => 'required|file',
            'file_id' => 'required|string',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'filename' => 'required|string',
        ]);

        $dir = storage_path("app/chunks/{$request->file_id}");
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true); // crée avec droits complets
            chmod($dir, 0777);       // assure que PHP peut écrire dedans
        }

        $request->file('chunk')->move($dir, $request->chunk_index);

        // If last chunk, merge and process
        if ($request->chunk_index + 1 == $request->total_chunks) {
            $chunkDir = $dir;
            $uploadDir = storage_path("app/uploads");
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
                chmod($uploadDir, 0777);
            }

            $finalTemp = storage_path("app/uploads/" . basename($request->filename));

            $out = fopen($finalTemp, 'ab');
            for ($i = 0; $i < $request->total_chunks; $i++) {
                $chunkPath = "$chunkDir/$i";
                if (file_exists($chunkPath)) {
                    fwrite($out, file_get_contents($chunkPath));
                }
            }
            fclose($out);

            // Process file: if PDF, try compression via ghostscript and save to public annexes, else save directly
            $extension = pathinfo($finalTemp, PATHINFO_EXTENSION);
            $storedRelative = null;

            if (strtolower($extension) === 'pdf') {
                $tempOriginalPath = $finalTemp;
                $compressedPath = storage_path('app/temp_compressed_' . uniqid() . '.pdf');

                $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/ebook "
                    . "-dDownsampleColorImages=true -dColorImageResolution=100 "
                    . "-dDownsampleGrayImages=true -dGrayImageResolution=100 "
                    . "-dDownsampleMonoImages=true -dMonoImageResolution=100 "
                    . "-dNOPAUSE -dQUIET -dBATCH -sOutputFile="
                    . escapeshellarg($compressedPath) . ' ' . escapeshellarg($tempOriginalPath);

                exec($command, $output, $resultCode);

                if ($resultCode !== 0 || !file_exists($compressedPath)) {
                    // fallback: move original into public annexes
                    $destName = 'annexes/' . uniqid() . '_' . basename($finalTemp);
                    Storage::disk('public')->put($destName, file_get_contents($finalTemp));
                    $storedRelative = $destName;
                } else {
                    $destName = 'annexes/compressed_' . uniqid() . '.pdf';
                    Storage::disk('public')->put($destName, file_get_contents($compressedPath));
                    unlink($compressedPath);
                    $storedRelative = $destName;
                }

                // cleanup temp upload
                @unlink($finalTemp);
            } else {
                // non-pdf: move into public annexes
                $destName = 'annexes/' . uniqid() . '_' . basename($finalTemp);
                Storage::disk('public')->put($destName, file_get_contents($finalTemp));
                @unlink($finalTemp);
                $storedRelative = $destName;
            }

            // cleanup chunks
            array_map('unlink', glob("$chunkDir/*"));
            @rmdir($chunkDir);

            return response()->json(['status' => 'merged', 'path' => $storedRelative]);
        }

        return response()->json(['status' => 'chunk received']);
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
