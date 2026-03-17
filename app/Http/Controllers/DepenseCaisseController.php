<?php

namespace App\Http\Controllers;

use App\Models\DepenseCaisse;
use Illuminate\Http\Request;
use App\Models\Justificatif;


class DepenseCaisseController extends Controller
{
    public function index()
    {
        $depenses = DepenseCaisse::all();
        return view('caisse.depenses.index', compact('depenses'));
    }

    public function create()
    {
        return view('caisse.depenses.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'rubrique' => 'required|string',
        'montant' => 'required|numeric',
        'date_depense' => 'required|date',
        'justificatifs.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        'scans.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Création de la dépense
    $depense = DepenseCaisse::create([
        'rubrique' => $request->rubrique,
        'montant' => $request->montant,
        'date_depense' => $request->date_depense,
        'description' => $request->description,
        'user_id' => auth()->id(),
    ]);

    // Fusion des fichiers justificatifs et scans
    $fichiers = array_merge(
        $request->file('justificatifs') ?? [],
        $request->file('scans') ?? []
    );

    // Enregistrement des fichiers justificatifs
    foreach ($fichiers as $fichier) {
        $nomFichier = $fichier->store('justificatifs', 'public');
        Justificatif::create([
            'depense_caisse_id' => $depense->id,
            'fichier' => $nomFichier,
        ]);
        \Log::info('Fichier enregistré : ' . $nomFichier);

    }

    // Récupération des dépenses avec leurs justificatifs et redirection
    $depenses = DepenseCaisse::with('justificatifs')->get();
    return view('caisse.depenses.index', compact('depenses'));
}



    public function show($id)
    {
        $depense = DepenseCaisse::findOrFail($id);
        return view('caisse.depenses.show', compact('depense'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rubrique' => 'required|string',
            'montant' => 'required|numeric',
            'date_depense' => 'required|date',
        ]);

        $depense = DepenseCaisse::findOrFail($id);
        $depense->update([
            'rubrique' => $request->rubrique,
            'montant' => $request->montant,
            'date_depense' => $request->date_depense,
            'description' => $request->description,
        ]);

        return redirect()->route('caisse.depenses.index');
    }

    // Ajout de la méthode edit dans DepenseCaisseController
public function edit($id)
{
    // Récupérer la dépense par son ID
    $depense = DepenseCaisse::findOrFail($id);

    // Retourner la vue d'édition avec la dépense à modifier
    return view('caisse.depenses.edit', compact('depense'));
}


    public function destroy($id)
    {
        DepenseCaisse::destroy($id);
        return redirect()->route('caisse.depenses.index');
    }


}
