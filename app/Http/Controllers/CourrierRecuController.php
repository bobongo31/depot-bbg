<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccuseReception;
use App\Models\Annexe;

class CourrierRecuController extends Controller
{
    public function create()
    {
        // Récupérer la liste des numéros d'enregistrement existants
        // On suppose que la table accuse_receptions contient déjà ces numéros.
        $numEnregistrements = AccuseReception::pluck('numero_enregistrement', 'id');
        return view('courriers.create', compact('numEnregistrements'));
    }

    // Affichage de la liste des courriers reçus
    public function indexCourriers()
    {
        $courriers = AccuseReception::with('annexes')->get(); // Récupérer les courriers et leurs annexes
        return view('courriers.index', compact('courriers'));
    }

    public function store(Request $request)
    {
        // Validation des champs
        // Ici, on utilise "exists" pour s'assurer que le numéro d'enregistrement existe déjà
        $validated = $request->validate([
            'date_reception' => 'required|date',
            'numero_enregistrement' => 'required|string|exists:accuse_receptions,numero_enregistrement',
            'nom_expediteur' => 'required|string|max:255',
            'numero_reference' => 'nullable|string|max:255',
            'resume' => 'required|string',
            'observation' => 'nullable|string',
            'commentaires' => 'nullable|string',
            'annexes' => 'nullable|array',
            'annexes.*' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Recherche l'accusé existant grâce au numéro d'enregistrement
        $accuse = AccuseReception::where('numero_enregistrement', $validated['numero_enregistrement'])->first();

        if (!$accuse) {
            // Bien que la validation "exists" devrait empêcher ce cas, on peut ajouter une sécurité
            return redirect()->back()->withErrors(['numero_enregistrement' => 'Le numéro d\'enregistrement sélectionné est invalide.']);
        }

        // Mise à jour de l'enregistrement existant avec les nouvelles données
        $accuse->update([
            'date_reception' => $validated['date_reception'],
            'nom_expediteur' => $validated['nom_expediteur'],
            'numero_reference'=> $validated['numero_reference'] ?? null,
            'resume' => $validated['resume'],
            'observation' => $validated['observation'],
            'commentaires' => $validated['commentaires'],
            'statut' => 'reçu', // Par exemple, définir ou conserver le statut "reçu"
        ]);

        // Enregistrer les annexes si elles sont présentes
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                $filePath = $file->store('annexes', 'public');
                // Utiliser la relation définie dans le modèle pour créer l'annexe associée
                $accuse->annexes()->create([
                    'file_path' => $filePath,
                    'accuse_de_reception_id' => $accuse->id,
                ]);
            }
        }

        return redirect()->route('courriers.create')->with('success', 'Courrier reçu mis à jour avec succès !');
    }
}
