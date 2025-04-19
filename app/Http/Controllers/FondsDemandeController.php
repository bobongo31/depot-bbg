<?php

namespace App\Http\Controllers;

use App\Models\FondsDemande;
use Illuminate\Http\Request;

class FondsDemandeController extends Controller
{
    public function index()
    {
        $demandes = FondsDemande::all();
        return view('caisse.demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('caisse.demandes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'motif' => 'required|string',
        ]);

        FondsDemande::create([
            'montant' => $request->montant,
            'motif' => $request->motif,
            'statut' => 'en_attente',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('caisse.demandes.index');
    }

    public function show($id)
    {
        $demande = FondsDemande::findOrFail($id);
        return view('caisse.demandes.show', compact('demande'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'montant' => 'required|numeric',
            'motif' => 'required|string',
        ]);

        $demande = FondsDemande::findOrFail($id);
        $demande->update([
            'montant' => $request->montant,
            'motif' => $request->motif,
        ]);

        return redirect()->route('caisse.demandes.index');
    }

    public function edit($id)
    {
        // Trouver la demande de fonds par son ID
        $demande = FondsDemande::findOrFail($id);

        // Retourner la vue avec la demande de fonds à modifier
        return view('caisse.demandes.edit', compact('demande'));
    }
    public function destroy($id)
    {
        FondsDemande::destroy($id);
        return redirect()->route('caisse.demandes.index');
    }

    public function approuver($id)
{
    $demande = FondsDemande::findOrFail($id);
    $demande->statut = 'approuve';
    $demande->save();

    return redirect()->back()->with('success', 'Demande approuvée avec succès.');
}

public function rejeter($id)
{
    $demande = FondsDemande::findOrFail($id);
    $demande->statut = 'rejete';
    $demande->save();

    return redirect()->back()->with('success', 'Demande rejetée avec succès.');
}
}
