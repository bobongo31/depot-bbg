<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reponse;
use App\Models\Telegramme;
use App\Models\Annexe;

class ReponseController extends Controller
{
    /**
     * Affiche les détails d'un télégramme et de la réponse associée.
     */
    public function show($id)
{
    // Récupère le télégramme avec ses annexes
    $telegramme = Telegramme::with('annexes')->find($id);
    
    // Récupère la première réponse associée au télégramme
    $reponse = Reponse::where('telegramme_id', $id)->first();

    // Vérifie si le télégramme ou la réponse existe, sinon redirige
    if (!$telegramme || !$reponse) {
        return redirect()->route('reponses.index')->with('error', 'Télégramme ou réponse non trouvée.');
    }

    // Récupère la tâche associée au télégramme (à ajouter si nécessaire)
    // $tache = Tache::where('telegramme_id', $id)->first(); 

    // Initialise la variable $isLate
    $isLate = false;

    // Exemple de logique de retard (ajuster selon ton modèle)
    if ($reponse->created_at > $telegramme->due_date) {
        $isLate = true;
    }

    // Retourne la vue avec les données
    return view('telegrammes.show', compact('telegramme', 'reponse', 'isLate'));
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
        return view('reponses.create', compact('telegramme_id'));
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
            'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'telegramme_id'         => 'nullable|exists:telegrammes,id',
        ]);
   
        $reponse = new Reponse();
        $reponse->numero_enregistrement = $validated['numero_enregistrement'];
        $reponse->numero_reference      = $validated['numero_reference'];
        $reponse->service_concerne      = $validated['service_concerne'];
        $reponse->observation           = $validated['observation'];
        $reponse->commentaires          = $validated['commentaires'];
        
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
        return view('telegramme.create');
    }
   
    /**
     * Stocke un télégramme dans la base de données et enregistre ses annexes.
     */
    public function storeTelegramme(Request $request)
    {
        $validated = $request->validate([
            'numero_enregistrement' => 'required|string|unique:telegrammes,numero_enregistrement',
            'numero_reference'      => 'required|string|unique:telegrammes,numero_reference',
            'service_concerne'      => 'required|string',
            'observation'           => 'nullable|string',
            'commentaires'          => 'nullable|string',
            'annexes'               => 'nullable|array',
            'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
   
        $telegramme = new Telegramme();
        $telegramme->numero_enregistrement = $validated['numero_enregistrement'];
        $telegramme->numero_reference      = $validated['numero_reference'];
        $telegramme->service_concerne      = $validated['service_concerne'];
        $telegramme->observation           = $validated['observation'];
        $telegramme->commentaires          = $validated['commentaires'];
        $telegramme->save();
   
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                if ($file->isValid()) {
                    $filePath = $file->store('annexes', 'public');
                    $telegramme->annexes()->create([
                        'file_path' => $filePath,
                    ]);
                }
            }
        }
   
        return redirect()->route('reponses.index')->with('success', 'Télégramme enregistré avec succès !');
    }
   
    /**
     * Supprime une réponse et, si elle est associée à un télégramme,
     * supprime également le télégramme et ses annexes.
     */
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
