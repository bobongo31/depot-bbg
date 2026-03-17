<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccuseReception;
use App\Models\Annexe;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;

class CourrierRecuController extends Controller
{
    
    public function create()
    {
        // Récupérer la liste des numéros d'enregistrement existants
        // On suppose que la table accuse_receptions contient déjà ces numéros.
        $numEnregistrements = AccuseReception::pluck('numero_enregistrement', 'id');
        $draft = null;
        if (auth()->check()) {
            // Prefer session (in-memory) draft to avoid DB drafts
            $sessionKey = 'accuse_draft_' . auth()->id();
            $sessionDraft = session($sessionKey, null);
            if ($sessionDraft) {
                // build a lightweight object compatible with the view
                $draft = (object) [
                    'date_reception' => $sessionDraft['date_reception'] ?? null,
                    'numero_enregistrement' => $sessionDraft['numero_enregistrement'] ?? null,
                    // match keys used by saveDraft()/JS
                    'nom_expediteur' => $sessionDraft['nom_expediteur'] ?? null,
                    'numero_reference' => $sessionDraft['numero_reference'] ?? null,
                    'resume' => $sessionDraft['resume'] ?? null,
                    'annexes' => collect(array_map(function($p){ return (object)['file_path' => $p]; }, $sessionDraft['uploaded_paths'] ?? [])),
                    'id' => 'session-'.auth()->id(),
                ];
            } else {
                $draft = AccuseReception::with('annexes')
                    ->where('user_id', auth()->id())
                    ->where('statut', 'brouillon')
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }
        }
        return view('courriers.create', compact('numEnregistrements', 'draft'));
    }

    public function addCommentaire(Request $request, $id)
{
    // Récupérer l'accusé de réception
    $courrier = AccuseReception::findOrFail($id);
    
    // Validation de l'entrée
    $request->validate([
        'commentaire' => 'required|string|max:255',
    ]);
    
    // Récupérer les commentaires existants et ajouter le nouveau commentaire
    $newCommentaire = $request->commentaire;
    $existingCommentaires = $courrier->commentaires ?? '';  // Si aucun commentaire, initialiser à vide
    
    // Ajouter le nouveau commentaire à la fin des anciens (en ajoutant un saut de ligne pour la séparation)
    $courrier->commentaires = $existingCommentaires . "\n" . $newCommentaire;
    
    // Sauvegarder la mise à jour
    $courrier->save();
    
    // Retourner la réponse en JSON
    return response()->json([
        'success' => true,
        'message' => 'Commentaire ajouté avec succès!',
        'commentaire' => nl2br($courrier->commentaires) // Retourner les commentaires avec des sauts de ligne formatés
    ]);
}


        // Affichage de la liste des courriers reçus
        public function indexCourriers()
        {
            // Pagination des courriers (10 par page par défaut) et tri par date de réception décroissante
            $perPage = 10; // modifier si nécessaire
            $courriers = AccuseReception::with('annexes')
                            ->orderBy('date_reception', 'desc')
                            ->paginate($perPage)
                            ->withQueryString();

            return view('courriers.index', compact('courriers'));
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


    public function store(Request $request)
{
    // =========================
    // VALIDATION
    // =========================
    $rules = [
        'date_reception'        => 'required|date',
        'numero_enregistrement' => 'required|string|exists:accuse_receptions,numero_enregistrement',
        'nom_expediteur'        => 'required|string|max:255',
        'numero_reference'      => 'nullable|string|max:255',
        'resume'                => 'required|string',
        'annexes'               => 'nullable|array',
        'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:55048',
    ];

    $validated = $request->validate($rules);

    try {
        // =========================
        // Récupération / mise à jour de l'accusé
        // =========================
        $accuse = AccuseReception::where('numero_enregistrement', $validated['numero_enregistrement'])->first();

        if (!$accuse) {
            // sécurité: création si inexistant
            $accuse = AccuseReception::create([
                'user_id'              => auth()->id(),
                'date_reception'       => $validated['date_reception'],
                'numero_enregistrement'=> $validated['numero_enregistrement'],
                'nom_expediteur'       => $validated['nom_expediteur'],
                'numero_reference'     => $validated['numero_reference'] ?? null,
                'resume'               => $validated['resume'],
                'statut'               => 'reçu',
            ]);
        } else {
            // mise à jour
            $accuse->update([
                'date_reception'   => $validated['date_reception'],
                'nom_expediteur'   => $validated['nom_expediteur'],
                'numero_reference' => $validated['numero_reference'] ?? null,
                'resume'           => $validated['resume'],
                'statut'           => 'reçu',
            ]);
        }

        // =========================
        // ANNEXES UPLOADÉES (FORM)
        // =========================
        if ($request->hasFile('annexes')) {
            // Pour éviter doublon: supprimer les annexes existantes si tu veux remplacer
            Annexe::where('accuse_de_reception_id', $accuse->id)->delete();

            foreach ($request->file('annexes') as $file) {
                $filePath = $file->store('annexes', 'public');

                Annexe::create([
                    'accuse_de_reception_id' => $accuse->id,
                    'file_path' => $filePath,
                ]);
            }
        }

        return redirect()
            ->route('courriers.index')
            ->with('success', 'Courrier enregistré avec succès !');

    } catch (QueryException $e) {
        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'database' => 'Erreur base de données : ' . $e->getMessage()
            ]);
    }
}

    // Save draft in session (autosave or explicit Save as Draft button)
    public function saveDraft(Request $request)
    {
        $data = $request->only(['date_reception','numero_enregistrement','nom_expediteur','numero_reference','resume','uploaded_paths','draft_id']);

        $userId = auth()->id();
        $sessionKey = 'accuse_draft_' . $userId;

        // load existing session draft
        $draft = $request->session()->get($sessionKey, []);

        // merge incoming fields
        $draft['date_reception'] = $data['date_reception'] ?? ($draft['date_reception'] ?? null);
        $draft['numero_enregistrement'] = $data['numero_enregistrement'] ?? ($draft['numero_enregistrement'] ?? null);
        $draft['nom_expediteur'] = $data['nom_expediteur'] ?? ($draft['nom_expediteur'] ?? null);
        $draft['numero_reference'] = $data['numero_reference'] ?? ($draft['numero_reference'] ?? null);
        $draft['resume'] = $data['resume'] ?? ($draft['resume'] ?? null);

        // merge uploaded_paths arrays
        $incomingPaths = !empty($data['uploaded_paths']) ? json_decode($data['uploaded_paths'], true) : [];
        if (!is_array($incomingPaths)) { $incomingPaths = []; }
        $existingPaths = is_array($draft['uploaded_paths'] ?? null) ? $draft['uploaded_paths'] : [];
        $merged = array_values(array_unique(array_merge($existingPaths, $incomingPaths)));
        $draft['uploaded_paths'] = $merged;

        $draft['updated_at'] = now()->toDateTimeString();

        // persist in session only
        $request->session()->put($sessionKey, $draft);

        return response()->json(['status' => 'ok', 'id' => 'session-' . $userId, 'draft_key' => 'session-' . $userId]);
    }

}
