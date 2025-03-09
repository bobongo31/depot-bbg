<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccuseReception; // Correctement importé ici
use App\Models\Annexe;
use PDF;

class AccuseDeReceptionController extends Controller
{
    public function showForm()
    {
        $numeroEnregistrement = $this->generateNumeroEnregistrement();
        return view('accuse_de_reception', compact('numeroEnregistrement'));
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
        // Récupérer tous les accusés de réception
        $accuses = AccuseReception::all();
        return view('accuses.index', compact('accuses'));
    }

        public function show($id)
    {
        $courrier = AccuseReception::with('annexes')->findOrFail($id);
        return view('courriers.show', compact('courrier'));
    }



    public function store(Request $request)
    {
        // Validation des champs
        $validated = $request->validate([
            'date_reception' => 'required|date',
            'numero_enregistrement' => 'required|string',
            'receptionne_par' => 'required|string|max:255',
            'objet' => 'required|string|max:255',
            'annexes' => 'nullable|array',
            'annexes.*' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'avis' => 'nullable|string',
        ]);

        // Enregistrement des données dans la base de données
        $accuse = AccuseReception::create([  // Assurez-vous d'utiliser AccuseReception ici
            'date_reception' => $validated['date_reception'],
            'numero_enregistrement' => $validated['numero_enregistrement'],
            'receptionne_par' => $validated['receptionne_par'],
            'objet' => $validated['objet'],
            'avis' => $validated['avis'] ?? null,
        ]);

        // Enregistrer les annexes si elles sont présentes
        if ($request->hasFile('annexes')) {
            foreach ($request->file('annexes') as $file) {
                $filePath = $file->store('annexes', 'public');
                Annexe::create([
                    'accuse_de_reception_id' => $accuse->id,  // Utilisation du bon champ relationnel
                    'file_path' => $filePath,
                ]);
            }
        }

        // Générer le PDF
        $pdf = PDF::loadView('pdf.accuse', ['accuse' => $accuse]);

        // Retourner le PDF en téléchargement
        return $pdf->download('accuse_de_reception.pdf');
    }

    // Méthode pour générer un numéro d'enregistrement unique
    private function generateNumeroEnregistrement()
    {
        return 'REC-' . strtoupper(uniqid());
    }
}
