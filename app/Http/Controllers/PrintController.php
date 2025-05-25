<?php
namespace App\Http\Controllers;

use App\Models\AccuseReception;
use App\Models\Telegramme;
use App\Models\Reponse;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    public function printAnnexes()
    {
        // Récupérer toutes les annexes des trois tables
        $accuses = AccuseReception::with('annexes')->get();
        $telegrammes = Telegramme::with('annexes')->get();
        $reponses = Reponse::with('annexes')->get();

        // Générer la vue pour le PDF
        $pdf = Pdf::loadView('prints.annexes', compact('accuses', 'telegrammes', 'reponses'));

        // Retourner le PDF
        return $pdf->download('annexes.pdf');
    }
}

