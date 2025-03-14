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
        $telegramme = Telegramme::with('annexes')->find($id);
        $reponse = Reponse::where('telegramme_id', $id)->first();
        
        if (!$telegramme || !$reponse) {
            return redirect()->route('reponses.index')->with('error', 'Télégramme ou réponse non trouvée.');
        }
        
        return view('telegramme.show', compact('telegramme', 'reponse'));
    }
   
    /**
     * Affiche la liste des réponses et des télégrammes.
     */
    public function index()
    {
        $reponses = Reponse::all();
        foreach ($reponses as $reponse) {
            $reponse->statut = (now()->diffInHours($reponse->created_at) > 72) ? 'en retard' : 'dans le délai';
        }
        
        $telegrammes = Telegramme::with('annexes')->get();
        
        return view('reponses.index', compact('reponses', 'telegrammes'));
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
            'commentaires'          => 'required|string',
            'annexes'               => 'nullable|array',
            'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'telegramme_id'         => 'nullable|exists:telegrammes,id',
        ]);
   
        $reponse = new Reponse();
        $reponse->numero_enregistrement = $validated['numero_enregistrement'];
        $reponse->numero_reference      = $validated['numero_reference'];
        $reponse->service_concerne      = $validated['service_concerne'];
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
            'contenu'               => 'required|string',
            'annexes'               => 'nullable|array',
            'annexes.*'             => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);
   
        $telegramme = new Telegramme();
        $telegramme->numero_enregistrement = $validated['numero_enregistrement'];
        $telegramme->numero_reference      = $validated['numero_reference'];
        $telegramme->service_concerne      = $validated['service_concerne'];
        $telegramme->observation           = 'Télégramme envoyé';
        $telegramme->commentaires          = $validated['contenu'];
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
