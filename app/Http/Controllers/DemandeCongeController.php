<?php

namespace App\Http\Controllers;

use App\Models\DemandeConge;
use Illuminate\Http\Request;

class DemandeCongeController extends Controller
{
    public function index()
    {
        $demandes = DemandeConge::all();
        return view('demandes_conges.index', compact('demandes'));
    }

    public function create()
    {
        return view('demandes_conges.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required|exists:users,id',
            'type_conge' => 'required|in:vacances,maladie,autre',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'motif' => 'required|string',
        ]);

        DemandeConge::create([
            'agent_id' => $request->agent_id,
            'type_conge' => $request->type_conge,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'motif' => $request->motif,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('demandes_conges.index')->with('success', 'Demande de congé créée avec succès.');
    }

    public function edit(DemandeConge $demandeConge)
{
    return view('demandes_conges.edit', compact('demandeConge'));
}


public function update(Request $request, DemandeConge $demandeConge)
{
    $request->validate([
        'statut' => 'required|in:en_attente,acceptee,refusee',
    ]);

    $demandeConge->update([
        'statut' => $request->statut,
    ]);

    return redirect()->route('demandes_conges.index')->with('success', 'Statut de la demande mis à jour.');
}


    

    public function destroy(DemandeConge $demandeConge)
    {
        $demandeConge->delete();
        return back()->with('success', 'Demande supprimée.');
    }

    public function approuver($id)
{
    $demande = DemandeConge::findOrFail($id);
    $demande->statut = 'acceptee';
    $demande->save();

    return redirect()->back()->with('success', 'Demande approuvée avec succès.');
}

public function rejeter($id)
{
    $demande = DemandeConge::findOrFail($id);
    $demande->statut = 'refusee';
    $demande->save();

    return redirect()->back()->with('success', 'Demande rejetée avec succès.');
}

}
