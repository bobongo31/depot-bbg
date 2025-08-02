<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponse;
use App\Models\Telegramme;
use App\Models\Annexe;
use App\Models\AccuseReception;
use Illuminate\Support\Facades\Log;
use App\Models\ReponseFinale;



class ReponseController extends Controller
{
    /**
     * Affiche les détails d'un télégramme et de la réponse associée.
     */
    
    
     public function afficherFormulaireReponse($accuseDeReceptionId)
     {
         $accuseDeReception = AccuseReception::findOrFail($accuseDeReceptionId);
         
         return view('reponses.reponse_form', compact('accuseDeReception'));
     }

     public function formAjouterReponseFinale($reponseId)
{
    $reponse = Reponse::findOrFail($reponseId);
    return view('reponses.ajouter_finale', compact('reponse'));
}

     
     public function ajouterReponseFinale(Request $request, $reponseId)
{
    // Validation des données d'entrée
    $request->validate([
        'numero_enregistrement' => 'required|string|max:255', // Validation pour numéro d'enregistrement
        'numero_reference' => 'required|string|max:255', // Validation pour numéro de référence
        'service_concerne' => 'required|string|max:255',
        'observation' => 'nullable|string',
        'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,docx|max:10240', // Validation pour les fichiers
    ]);

    // Récupérer la réponse existante par son ID
    $reponseExistante = Reponse::findOrFail($reponseId);

    // Créer une nouvelle réponse finale
    $reponseFinale = new ReponseFinale();
    $reponseFinale->numero_enregistrement = $request->input('numero_enregistrement');
    $reponseFinale->numero_reference = $request->input('numero_reference');
    $reponseFinale->service_concerne = $request->input('service_concerne');
    $reponseFinale->observation = $request->input('observation');
    $reponseFinale->telegramme_id = $reponseExistante->telegramme_id;
    $reponseFinale->reponse_id = $reponseId;
    $reponseFinale->user_id = auth()->id(); // ✅ Lien vers l'utilisateur connecté
    $reponseFinale->save();


    // Lier l'annexe si un fichier est téléchargé
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filePath = $file->store('annexes', 'public'); // Sauvegarde dans le répertoire 'annexes'

        // Créer une nouvelle annexe
        $annexe = new Annexe();
        $annexe->file_path = $filePath;
        $annexe->reponse_finale_id = $reponseFinale->id;
        $annexe->telegramme_id = $reponseExistante->telegramme_id; // Lier l'annexe au télégramme
        $annexe->save();
    }

    // Retourner une réponse pour confirmer la création
        return redirect()->route('reponses.showFinale', ['id' => $reponseFinale->id])
    ->with('success', 'Réponse finale ajoutée avec succès et annexe téléchargée.');

}


public function show($id)
{
    // Récupère la première réponse associée au télégramme
    $reponse = Reponse::where('id', $id)->first();
    $reponseFinale = ReponseFinale::where('reponse_id', $id)->first();
    // Retourne la vue avec la réponse
    return view('reponses.show', compact('reponse'));
}

public function showFinale($id)
{
    $reponseFinale = ReponseFinale::with(['reponse', 'annexes'])->findOrFail($id);
    $reponse = $reponseFinale->reponse; // ← cette ligne
    return view('reponses.show_finale', compact('reponseFinale', 'reponse'));
}

public function showWithTelegramme($id)
{
    // Récupération du télégramme avec ses annexes
    $telegramme = Telegramme::with('annexes')->findOrFail($id);

    // Récupération de l'accusé de réception correspondant avec ses annexes
    $accuseReception = AccuseReception::where('numero_enregistrement', $telegramme->numero_enregistrement)
        ->where('numero_reference', $telegramme->numero_reference)
        ->with('annexes')
        ->first();

    // Retourne la vue avec les données
    return view('telegramme.show', compact('telegramme', 'accuseReception'));
}

   
    /**
     * Affiche la liste des réponses et des télégrammes.
     */
    public function index()
{
    $user = auth()->user();

    // Si l'utilisateur est un administrateur, on récupère toutes les réponses et tous les télégrammes
    if ($user->hasRole('admin')) {
        $reponses = Reponse::with('telegramme')->orderBy('created_at', 'desc')->paginate(10);
        $telegrammesEnAttente = Telegramme::with('annexes')->whereDoesntHave('reponses')->get();
    } else {
        // Sinon, on filtre les réponses et télégrammes en fonction du service de l'utilisateur
        $reponses = Reponse::with('telegramme')
            ->where('service_concerne', $user->service)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $telegrammesEnAttente = Telegramme::with('annexes')
            ->whereDoesntHave('reponses')
            ->where('service_concerne', $user->service)
            ->get();
    }

    // Définir la variable $isLate, $isWarning et $remainingHours pour chaque télégramme
    foreach ($telegrammesEnAttente as $telegramme) {
        // Calculer la date limite (72 heures après la date de création)
        $dueDate = $telegramme->created_at->addHours(72);
        
        // Calculer les heures restantes jusqu'à la date limite, arrondi à l'entier le plus proche
        $telegramme->remainingHours = round($dueDate->diffInHours(now())); // Arrondi à l'entier
    
        // Vérifier si le télégramme est en retard
        $telegramme->isLate = $dueDate < now();
    
        // Vérifier si un avertissement est nécessaire (moins de 24 heures restantes)
        $telegramme->isWarning = $telegramme->remainingHours <= 24 && $telegramme->remainingHours > 0;
    }

    // Regrouper les réponses par date après avoir paginé
    $reponsesGrouped = $reponses->getCollection()->groupBy(function ($reponse) {
        return $reponse->created_at->format('Y-m-d'); // Regroupe par jour
    });

    // Passer la pagination de Reponse et les groupes à la vue
    return view('reponses.index', compact('telegrammesEnAttente', 'reponses', 'reponsesGrouped'));
}

   
    /**
     * Affiche le formulaire de création d'une réponse.
     * Si un `telegramme_id` est passé en paramètre, il est inclus dans le formulaire.
     */
        public function create(Request $request) 
    {
        $telegramme_id = $request->query('telegramme_id') ?? null;
        
        // Récupère les numéros d'enregistrement depuis la table telegrammes
        $telegrammes = Telegramme::all(); 

        return view('reponses.create', compact('telegramme_id', 'telegrammes'));
    }
   
    /**
     * Stocke une réponse dans la base de données et enregistre les annexes associées.
     * Si un telegramme_id est fourni, la réponse est associée à ce télégramme.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_enregistrement' => 'required|string',
            'numero_reference'      => 'required|string',
            'service_concerne'      => 'required|string',
            'observation'           => 'nullable|string',
            'commentaires'          => 'required|string',
            'annexes'               => 'nullable|array',
            'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
            'telegramme_id'         => 'nullable|exists:telegrammes,id',
        ]);
   
        $reponse = new Reponse();
        $reponse->numero_enregistrement = $validated['numero_enregistrement'];
        $reponse->numero_reference      = $validated['numero_reference'];
        $reponse->service_concerne      = $validated['service_concerne'];
        $reponse->observation           = $validated['observation'];
        $reponse->commentaires          = $validated['commentaires'];
        $reponse->user_id               = auth()->id(); // 👈 Associe l'utilisateur connecté
        
        if (!empty($validated['telegramme_id'])) {
            $reponse->telegramme_id = $validated['telegramme_id'];
        }
        
        $reponse->save();
   
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                if ($file->isValid()) {
                    $filePath = $file->store('annexes', 'public');
                    $reponse->annexes()->create([
                        'file_path' => $filePath,
                    ]);
                }
            }
        }
   
        return redirect()->route('reponses.index')->with('success', 'Réponse enregistrée avec succès !');
    }
   
    /**
     * Affiche le formulaire de création d'un télégramme.
     */
    public function createTelegramme()
    {
        $accuse_receptions = AccuseReception::all(); // Récupère tous les enregistrements
        return view('telegramme.create', compact('accuse_receptions'));    }
   
    /**
     * Stocke un télégramme dans la base de données et enregistre ses annexes.
     */
    public function storeTelegramme(Request $request)
{
    $validated = $request->validate([
        'numero_enregistrement' => 'required|string',
        'numero_reference'      => 'required|string',
        'service_concerne'      => 'required|array|min:1', // Au moins un service obligatoire
        'service_concerne.*'    => 'string',
        'observation'           => 'nullable|string',
        'commentaires'          => 'nullable|string',
        'annexes'               => 'nullable|array',
        'annexes.*'             => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    ]);

    foreach ($validated['service_concerne'] as $service) {
        $telegramme = Telegramme::create([
            'numero_enregistrement' => $validated['numero_enregistrement'],
            'numero_reference'      => $validated['numero_reference'],
            'service_concerne'      => $service,
            'observation'           => $validated['observation'] ?? null,
            'commentaires'          => $validated['commentaires'] ?? null,
            'user_id'               => auth()->id(),
        ]);

        // Si des fichiers sont présents, on les enregistre
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                if ($file->isValid()) {
                    $filePath = $file->store('annexes', 'public');

                    Annexe::create([
                        'file_path'     => $filePath,
                        'telegramme_id' => $telegramme->id,
                    ]);

                    \Log::info("Annexe enregistrée : $filePath pour télégramme ID {$telegramme->id}");
                }
            }
        }
    }

    return redirect()->route('reponses.index')->with('success', 'Télégramme(s) enregistré(s) avec succès !');
}


   
    /**
     * Supprime une réponse et, si elle est associée à un télégramme,
     * supprime également le télégramme et ses annexes.
     */
        public function destroyTelegramme($id)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return redirect()->route('telegrammes.index')->with('error', 'Vous n’avez pas l’autorisation de supprimer ce télégramme.');
        }

        $telegramme = Telegramme::findOrFail($id);

        // Supprimer les annexes associées au télégramme
        Annexe::where('telegramme_id', $telegramme->id)->delete();

        // Supprimer le télégramme
        $telegramme->delete();

        return redirect()->route('telegrammes.index')->with('success', 'Télégramme supprimé avec succès.');
    }

    
     public function destroy($id)
    {
        $reponse = Reponse::findOrFail($id);
       
        if ($reponse->telegramme_id) {
            $telegramme = Telegramme::find($reponse->telegramme_id);
            if ($telegramme) {
                // Supprimer les annexes associées au télégramme
                Annexe::where('telegramme_id', $telegramme->id)->delete();
                $telegramme->delete();
            }
        }
   
        $reponse->delete();
   
        return redirect()->route('reponses.index')->with('success', 'Réponse et son télégramme associé supprimés avec succès.');
    }
    
}
