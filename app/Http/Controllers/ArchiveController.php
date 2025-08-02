<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccuseReception;
use App\Models\Telegramme;
use App\Models\Reponse;
use Carbon\Carbon;

class ArchiveController extends Controller
{

    // Afficher la liste des archives
    public function index(Request $request)
{
    // Initialisez les requêtes pour chaque type de donnée
    $accusesQuery = AccuseReception::query();
    $telegrammesQuery = Telegramme::query();
    $reponsesQuery = Reponse::query();

    // Filtrer par archive (qui contient la catégorie)
    if ($request->filled('categorie')) {
        $categorie = $request->input('categorie');
        $accusesQuery->where('archive', $categorie);
        $telegrammesQuery->where('archive', $categorie);
        $reponsesQuery->where('archive', $categorie);
    }

    // Filtrer par numéro d'enregistrement ou référence
    if ($request->filled('search')) {
        $search = $request->input('search');
        $accusesQuery->where(function($query) use ($search) {
            $query->where('numero_enregistrement', 'like', "%{$search}%")
                  ->orWhere('numero_reference', 'like', "%{$search}%");
        });
        $telegrammesQuery->where(function($query) use ($search) {
            $query->where('numero_enregistrement', 'like', "%{$search}%")
                  ->orWhere('numero_reference', 'like', "%{$search}%");
        });
        $reponsesQuery->where(function($query) use ($search) {
            $query->where('numero_enregistrement', 'like', "%{$search}%")
                  ->orWhere('numero_reference', 'like', "%{$search}%");
        });
    }

    // Récupérer les données filtrées
    $accuses = $accusesQuery->get();
    $telegrammes = $telegrammesQuery->get();
    $reponses = $reponsesQuery->get();

    // Retourner la vue avec les résultats filtrés
    return view('archives.index', compact('accuses', 'telegrammes', 'reponses'));
}
    // Mettre à jour la colonne 'archive' dans les 3 tables
    
    public function archiverDossier(Request $request, $numero_enregistrement)
    {
        $request->validate([
            'categorie' => 'required|string|max:255',
        ]);

        // Récupérer les enregistrements dans chaque table avec le même numéro d'enregistrement
        $accuse = AccuseReception::where('numero_enregistrement', $numero_enregistrement)->first();
        $telegrammes = Telegramme::where('numero_enregistrement', $numero_enregistrement)->get();
        $reponses = Reponse::where('numero_enregistrement', $numero_enregistrement)->get();

        if (!$accuse || $telegrammes->isEmpty() || $reponses->isEmpty()) {
            return redirect()->back()->with('error', 'Impossible d’archiver : certaines données sont manquantes.');
        }

        // Mettre à jour la colonne 'archive' avec la catégorie choisie
        $categorie = $request->categorie;
        $accuse->update(['archive' => $categorie]);
        foreach ($telegrammes as $telegramme) {
            $telegramme->update(['archive' => $categorie]);
        }
        foreach ($reponses as $reponse) {
            $reponse->update(['archive' => $categorie]);
        }

        return redirect()->back()->with('success', 'Dossier archivé avec succès sous la catégorie "' . $categorie . '".');
    }

    // Mettre à jour la colonne 'status_archive' pour déclarer le dossier clos ou autre

    public function declarerClos(Request $request, $numero_enregistrement)
    {
        // Par exemple, on peut envoyer un input qui définit le statut (ex: 'clos')
        $request->validate([
            'status_archive' => 'required|string|max:255',
        ]);

        $status = $request->status_archive;

        $accuse = AccuseReception::where('numero_enregistrement', $numero_enregistrement)->first();
        $telegrammes = Telegramme::where('numero_enregistrement', $numero_enregistrement)->get();
        $reponses = Reponse::where('numero_enregistrement', $numero_enregistrement)->get();

        if (!$accuse || $telegrammes->isEmpty() || $reponses->isEmpty()) {
            return redirect()->back()->with('error', 'Impossible de mettre à jour le statut : certaines données sont manquantes.');
        }

        $accuse->update(['status_archive' => $status]);
        foreach ($telegrammes as $telegramme) {
            $telegramme->update(['status_archive' => $status]);
        }
        foreach ($reponses as $reponse) {
            $reponse->update(['status_archive' => $status]);
        }

        return redirect()->back()->with('success', 'Dossier mis à jour avec le statut "' . $status . '".');
    }
}
