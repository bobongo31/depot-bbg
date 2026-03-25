<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Telegramme;
use App\Models\AccuseReception;
use App\Models\Reponse;
use App\Models\ReponseFinale;

class RechercheController extends Controller
{
    public function search(Request $request)
    {
        $query = trim((string) $request->input('query', ''));

        if ($query === '') {
            return back()->with('warning', 'Veuillez saisir un mot-clé pour la recherche.');
        }

        $telegrammes = Telegramme::with(['annexes', 'accuseReception'])
            ->whereRaw(
                "CONCAT_WS(' ', numero_enregistrement, numero_reference, service_concerne, observation, commentaires, statut) LIKE ?",
                ["%{$query}%"]
            )
            ->latest()
            ->get();

        $accuses = AccuseReception::with('annexes')
            ->whereRaw(
                "CONCAT_WS(' ', numero_enregistrement, numero_reference, nom_expediteur, objet, resume, observation, commentaires, avis, statut, receptionne_par) LIKE ?",
                ["%{$query}%"]
            )
            ->latest()
            ->get();

        $reponses = Reponse::with('annexes')
            ->whereRaw(
                "CONCAT_WS(' ', numero_enregistrement, numero_reference, service_concerne, observation, commentaires) LIKE ?",
                ["%{$query}%"]
            )
            ->latest()
            ->get();

        $reponses_finales = ReponseFinale::with('annexes')
            ->whereRaw(
                "CONCAT_WS(' ', numero_enregistrement, numero_reference, service_concerne, observation) LIKE ?",
                ["%{$query}%"]
            )
            ->latest()
            ->get();

        $courriers_expedies = DB::table('courrier_expedies')
            ->select(
                'id',
                'numero_ordre',
                'numero_lettre',
                'destinataire',
                'resume',
                'observation'
            )
            ->whereRaw(
                "CONCAT_WS(' ', numero_ordre, numero_lettre, destinataire, resume, observation) LIKE ?",
                ["%{$query}%"]
            )
            ->orderByDesc('id')
            ->get();

        $totalResults =
            $telegrammes->count() +
            $accuses->count() +
            $reponses->count() +
            $reponses_finales->count() +
            $courriers_expedies->count();

        // Redirection seulement si UN SEUL résultat total existe
        if ($totalResults === 1) {
            if ($telegrammes->count() === 1) {
                return redirect()->route('telegramme.show', $telegrammes->first()->id);
            }

            if ($accuses->count() === 1) {
                return redirect()->route('courriers.show', $accuses->first()->id);
            }

            if ($reponses->count() === 1) {
                return redirect()->route('reponse.show', $reponses->first()->id);
            }

            if ($reponses_finales->count() === 1) {
                return redirect()->route('reponses.showFinale', $reponses_finales->first()->id);
            }

            if ($courriers_expedies->count() === 1) {
                return redirect()->route('courrier_expedie.show', $courriers_expedies->first()->id);
            }
        }

        // S'il y a plusieurs résultats, on affiche la page de résultats
        return view('recherche.resultats', compact(
            'query',
            'telegrammes',
            'accuses',
            'reponses',
            'reponses_finales',
            'courriers_expedies'
        ));
    }
}