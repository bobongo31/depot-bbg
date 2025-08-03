<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RechercheController extends Controller
{
    public function globale(Request $request)
    {
        $query = $request->input('q');

        // 🔎 Recherche dans la table telegrammes
        $telegrammes = DB::table('telegrammes')
            ->select('id', 'numero_reference', 'numero_enregistrement')
            ->whereRaw("CONCAT_WS(' ', numero_reference, numero_enregistrement, observation, commentaires, service_concerne) LIKE ?", ["%$query%"])
            ->get();

        // 🔎 Recherche dans la table accuse_receptions
        $accuses = DB::table('accuse_receptions')
            ->select('id', 'numero_enregistrement', 'numero_reference')
            ->whereRaw("CONCAT_WS(' ', numero_enregistrement, numero_reference, objet, nom_expediteur, service_concerne, resume, observation, commentaires) LIKE ?", ["%$query%"])
            ->get();

        // 🔎 Recherche dans la table archives
        $archives = DB::table('archives')
            ->select('id', 'numero_enregistrement', 'numero_reference')
            ->whereRaw("CONCAT_WS(' ', numero_enregistrement, numero_reference, resume, service_concerne, commentaires, categorie) LIKE ?", ["%$query%"])
            ->get();

        // 🔎 Recherche dans la table reponses
        $reponses = DB::table('reponses')
            ->select('id', 'numero_enregistrement', 'numero_reference', 'commentaires', 'observation')
            ->whereRaw("CONCAT_WS(' ', numero_enregistrement, numero_reference, service_concerne, observation, commentaires) LIKE ?", ["%$query%"])
            ->get();


        // 🔎 Recherche dans la table reponses_finales
        $reponses_finales = DB::table('reponses_finales')
            ->select('id', 'numero_enregistrement', 'numero_reference')
            ->whereRaw("CONCAT_WS(' ', numero_enregistrement, numero_reference, service_concerne, observation) LIKE ?", ["%$query%"])
            ->get();

        // ✅ Redirection automatique si un seul résultat
        $total = $telegrammes->count() + $accuses->count() + $archives->count() + $reponses->count() + $reponses_finales->count();

        if ($total === 1) {
            if ($telegrammes->count() === 1) {
                return redirect()->route('telegramme.show', $telegrammes->first()->id);
            }
            if ($accuses->count() === 1) {
                return redirect()->route('courriers.show', $accuses->first()->id);
            }
            if ($archives->count() === 1) {
                return redirect()->route('archives.show', $archives->first()->id);
            }
            if ($reponses->count() === 1) {
                return redirect()->route('reponse.show', $reponses->first()->id);
            }
            if ($reponses_finales->count() === 1) {
                return redirect()->route('reponses.showFinale', $reponses_finales->first()->id);
            }
        }

        // Sinon, afficher tous les résultats
        return view('recherche.resultats', compact(
            'query',
            'telegrammes',
            'accuses',
            'archives',
            'reponses',
            'reponses_finales'
        ));
    }
}
