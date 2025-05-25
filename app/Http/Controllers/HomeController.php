<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AccuseReception;

class HomeController extends Controller
{
    public function index()
{
    // Courriers reçus par mois
    $courriersParMois = AccuseReception::selectRaw('MONTH(date_reception) as mois, COUNT(*) as total')
        ->whereNotNull('date_reception')
        ->groupBy('mois')
        ->orderBy('mois')
        ->pluck('total', 'mois')
        ->toArray();

    // Courriers reçus par jour
    $courriersParJour = AccuseReception::selectRaw('DATE(date_reception) as jour, COUNT(*) as total')
        ->whereNotNull('date_reception')
        ->groupBy('jour')
        ->orderBy('jour')
        ->pluck('total', 'jour')
        ->toArray();

    // Courriers reçus par semaine
    $courriersParSemaine = AccuseReception::selectRaw('YEARWEEK(date_reception, 1) as semaine, COUNT(*) as total')
        ->whereNotNull('date_reception')
        ->groupBy('semaine')
        ->orderBy('semaine')
        ->pluck('total', 'semaine')
        ->toArray();

    // Courriers traités par mois (status = 'traite')
        $courriersTraitesParMois = AccuseReception::where('statut', 'traite')
        ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->groupBy('mois')
        ->orderBy('mois')
        ->pluck('total', 'mois')
        ->toArray();


    // 🌐 Calcul de la Vue Globale
    $totalReçus = array_sum($courriersParMois);
    $totalTraités = array_sum($courriersTraitesParMois);
    $totalEnAttente = $totalReçus - $totalTraités;

    $vueGlobale = [
        'Reçus'     => $totalReçus,
        'Traités'   => $totalTraités,
        'En attente' => max(0, $totalEnAttente),
    ];

    // Retourner les données à la vue
    return view('home', [
        'courriersParMois'       => $courriersParMois,
        'courriersParJour'       => $courriersParJour,
        'courriersParSemaine'    => $courriersParSemaine,
        'courriersTraitesParMois'=> $courriersTraitesParMois,
        'vueGlobale'             => $vueGlobale
    ]);
}
}
