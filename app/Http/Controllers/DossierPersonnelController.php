<?php

namespace App\Http\Controllers;

use App\Models\DossierPersonnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DossierPersonnelController extends Controller
{
    public function index()
    {
        $dossiers = DossierPersonnel::with('annexes', 'agent')->latest()->get();
        return view('dossiers_personnels.index', compact('dossiers'));
    }

    public function create()
    {
        $agents = User::where('id', '!=', Auth::id())->get();
        return view('dossiers_personnels.create', compact('agents'));
    }

    public function store(Request $request)
{
    // Valider les entrées du formulaire
    $request->validate([
        'agent_id' => 'required|exists:users,id',
        'poste' => 'required|string|max:255',
        'date_embauche' => 'nullable|date',
        'matricule' => 'nullable|string|max:50',
        'contrat_type' => 'nullable|string|max:100',
        'notes' => 'nullable|string',
        'annexe' => 'nullable|file|mimes:pdf,jpeg,png,jpg',  // Validation du fichier
    ]);

    // Créer le dossier personnel
    $dossierPersonnel = DossierPersonnel::create([
        'user_id' => Auth::id(),
        'agent_id' => $request->agent_id,
        'poste' => $request->poste,
        'date_embauche' => $request->date_embauche,
        'matricule' => $request->matricule,
        'contrat_type' => $request->contrat_type,
        'notes' => $request->notes,
    ]);

    // Si un fichier a été téléchargé
    if ($request->hasFile('annexe')) {
        $path = $request->file('annexe')->store('annexes', 'public');
        
        // Ajouter l'annexe à la table annexes_dossier_personnel
        \DB::table('annexes_dossier_personnel')->insert([
            'dossier_personnel_id' => $dossierPersonnel->id,
            'path' => $path,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('dossiers_personnels.index')->with('success', 'Dossier enregistré avec succès.');
}


    public function edit(DossierPersonnel $dossierPersonnel)
    {
        $agents = User::where('id', '!=', Auth::id())->get();
        return view('dossiers_personnels.edit', compact('dossierPersonnel', 'agents'));
    }

    public function update(Request $request, DossierPersonnel $dossierPersonnel)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'poste' => 'required|string|max:255',
            'date_embauche' => 'nullable|date',
            'matricule' => 'nullable|string|max:50',
            'contrat_type' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $dossierPersonnel->update($request->only([
            'agent_id', 'poste', 'date_embauche', 'matricule', 'contrat_type', 'notes'
        ]));

        return redirect()->route('dossiers_personnels.index')->with('success', 'Dossier mis à jour.');
    }

    public function destroy(DossierPersonnel $dossierPersonnel)
    {
        $dossierPersonnel->delete();
        return redirect()->route('dossiers_personnels.index')->with('success', 'Dossier supprimé.');
    }
}
