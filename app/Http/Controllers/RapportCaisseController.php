<?php
namespace App\Http\Controllers;

use App\Models\DepenseCaisse;
use App\Models\FondsDemande;
use Illuminate\Http\Request;

class RapportCaisseController extends Controller
{
    /**
     * Affiche le rapport des mouvements de caisse pour un utilisateur.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // On récupère toutes les dépenses et les demandes de fonds pour l'utilisateur connecté
        $user_id = auth()->id();

        // Récupérer les dépenses par rubrique
        $depenses = DepenseCaisse::where('user_id', $user_id)
                                 ->selectRaw('rubrique, SUM(montant) as total_depense')
                                 ->groupBy('rubrique')
                                 ->get();

        // Récupérer les demandes de fonds
        $demandes_fonds = FondsDemande::where('user_id', $user_id)
                                      ->selectRaw('statut, SUM(montant) as total_demande')
                                      ->groupBy('statut')
                                      ->get();

        // Calculer les totaux des entrées et des sorties
        $total_entrees = $demandes_fonds->where('statut', 'approuve')->sum('total_demande');
        $total_sorties = $depenses->sum('total_depense');
        $solde = $total_entrees - $total_sorties;

        // Créer une collection de transactions
        $transactions = collect();

        // Ajouter les entrées (demandes approuvées)
        foreach ($demandes_fonds as $demande) {
            if ($demande->statut == 'approuve') {
                $transactions->push([
                    'date' => now()->toDateString(), // Exemple, tu peux ajuster la date si nécessaire
                    'type' => 'entree',
                    'montant' => $demande->total_demande,
                    'libelle' => 'Demande de fonds approuvée',
                ]);
            }
        }

        // Ajouter les sorties (dépenses)
        foreach ($depenses as $depense) {
            $transactions->push([
                'date' => now()->toDateString(), // Exemple, tu peux ajuster la date si nécessaire
                'type' => 'sortie',
                'montant' => $depense->total_depense,
                'libelle' => $depense->rubrique,
            ]);
        }

        // Retourner la vue avec les variables nécessaires
        return view('caisse.rapport.index', compact('transactions', 'total_entrees', 'total_sorties', 'solde'));
    }
}
