<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\AccuseReception; // Correctement importé ici
use App\Models\Annexe;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use setasign\Fpdf\Fpdf;  // Ajoute cette ligne pour FPDF


class AccuseDeReceptionController extends Controller
{


    
     // Affichage du formulaire avec un champ vide pour le numéro d'enregistrement
     public function showForm()
     {
         // Suppression de la génération automatique du numéro d'enregistrement
         return view('accuse_de_reception');
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
    // Utiliser le modèle AccuseReception
    $courrier = AccuseReception::with('annexes')->findOrFail($id);

    // Si la requête est AJAX, on renvoie une vue partielle
    if (request()->ajax()) {
        return response()->view('courriers.show', compact('courrier'));
    }

    // Sinon, on renvoie la vue complète
    return view('courriers.show', compact('courrier'));
}



    public function store(Request $request) 
{
    $validated = $request->validate([
        'date_reception' => 'required|date',
        'numero_enregistrement' => 'required|string',
        'receptionne_par' => 'required|string|max:255',
        'objet' => 'required|string|max:255',
        'annexes' => 'nullable|array',
        'annexes.*' => 'mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        'avis' => 'nullable|string',
    ]);

    // Création de l'accusé de réception
    $accuse = AccuseReception::create([
        'user_id' => auth()->id(), // 👈 Ajouté ici
        'date_reception' => $validated['date_reception'],
        'numero_enregistrement' => $validated['numero_enregistrement'],
        'receptionne_par' => $validated['receptionne_par'],
        'objet' => $validated['objet'],
        'avis' => $validated['avis'] ?? null,
    ]);

    // Initialisation du fichier PDF
    $pdf = new FPDI();
    
    if ($request->hasFile('annexes')) {
        foreach ($request->file('annexes') as $file) {
            $filePath = $file->store('annexes', 'public');
            $annexePath = storage_path("app/public/{$filePath}");

            $pageCount = $pdf->setSourceFile($annexePath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $pdf->AddPage();
                $pdf->useTemplate($tplIdx, 0, 0, 210);

                $user = Auth::user(); // récupère l'utilisateur connecté

                // Ajouter du texte en superposition
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->SetTextColor(255, 0, 0);
                $pdf->SetXY(20, 10);
                $pdf->Cell(0, 10, " " . $user->entreprise, 0, 1, 'L');
                $pdf->SetXY(20, 20);
                $pdf->Cell(0, 10, "Date : " . $accuse->date_reception, 0, 1);
                $pdf->SetXY(20, 30);
                $pdf->Cell(0, 10, "Numero : " . $accuse->numero_enregistrement, 0, 1);
                $pdf->SetXY(20, 40);
                $pdf->Cell(0, 10, "Receptionne par : " . $accuse->receptionne_par, 0, 1);
                $pdf->SetXY(20, 50);
                $pdf->MultiCell(0, 10, "Objet : " . $accuse->objet, 0, 1);

                if (!empty($accuse->avis)) {
                    $pdf->SetXY(20, 70);
                    $pdf->MultiCell(0, 10, "Avis : " . $accuse->avis, 0, 1);
                }
            }
        }
    }

    // Sauvegarde du PDF modifié
    $outputFileName = 'accuse_' . $accuse->id . '.pdf';
    $outputPath = storage_path("app/public/{$outputFileName}");
    $pdf->Output($outputPath, 'F');

    // Enregistrer le fichier annexes
    Annexe::create([
        'accuse_de_reception_id' => $accuse->id,
        'file_path' => $outputFileName,
    ]);

    /// URL du fichier PDF pour le téléchargement
    $downloadUrl = asset("storage/{$outputFileName}");

    // Redirection vers la page des accusés avec l'URL du PDF
    return redirect()->route('accuses.index')->with('download_url', $downloadUrl);
}




    // Méthode pour générer un numéro d'enregistrement unique
    //private function generateNumeroEnregistrement()
    //{
    //return 'REC-' . strtoupper(uniqid());
    //}
}
