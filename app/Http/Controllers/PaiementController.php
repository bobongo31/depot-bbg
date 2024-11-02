<?php

namespace App\Http\Controllers;

use App\Models\Paiement; 
use App\Models\Client; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; 

class PaiementController extends Controller
{
    // Afficher la liste des paiements
    public function index()
    {
        // Vérifiez si l'utilisateur est authentifié et possède les rôles requis
        if (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('payment_validator'))) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        // Récupérer tous les paiements avec pagination
        $paiements = Paiement::paginate(10); 

        // Passer la variable $paiements à la vue
        return view('web.paiements.index', compact('paiements'));
    }

    // Afficher le formulaire de création de paiement
    public function create()
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifiez si l'utilisateur a les rôles nécessaires
        if (!auth()->user()->hasRole('payment_validator') && !auth()->user()->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        // Récupérer tous les clients
        $clients = Client::all(); 
        return view('web.paiements.create', compact('clients'));
    }

    // Stocker le paiement dans la base de données
    public function store(Request $request)
    {
        // Journaliser les données du formulaire pour le débogage
        Log::info('Données du formulaire : ', $request->all());

        // Validez les données du formulaire
        $validatedData = $request->validate([
            'matieres_taxables' => 'required|string|max:255',
            'prix_matiere' => 'required|numeric|min:0',
            'date_ordonancement' => 'required|date',
            'date_accuse_reception' => 'required|date|after_or_equal:date_ordonancement',
            'nom_ordonanceur' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ]);

        // Journaliser les données validées pour confirmation
        Log::info('Données validées : ', $validatedData);

        try {
            // Calculez le prix à payer et le coût d'opportunité
            $prix_a_payer = $validatedData['prix_matiere'] * 0.05;
            $date_ordonancement = Carbon::parse($validatedData['date_ordonancement']);
            $date_accuse_reception = Carbon::parse($validatedData['date_accuse_reception']);
            $cout_opportunite = $date_ordonancement->diffInDays($date_accuse_reception);
            $date_paiement = $date_accuse_reception->copy()->addDays(10);
            if ($date_paiement->isSunday()) {
                $date_paiement->addDay(); 
            }

            // Créez l'enregistrement dans la base de données
            $paiement = Paiement::create([
                'matieres_taxables' => $validatedData['matieres_taxables'],
                'prix_matiere' => $validatedData['prix_matiere'],
                'prix_a_payer' => $prix_a_payer,
                'date_ordonancement' => $date_ordonancement,
                'date_accuse_reception' => $date_accuse_reception,
                'cout_opportunite' => $cout_opportunite,
                'date_paiement' => $date_paiement,
                'retard_de_paiement' => $date_paiement->isPast(),
                'nom_ordonanceur' => $validatedData['nom_ordonanceur'],
                'client_id' => $validatedData['client_id'],
                'status' => 'en attente'
            ]);

            Log::info('Paiement créé avec succès : ', $paiement->toArray());

            return redirect()->route('web.paiements.index')->with('success', 'Le paiement a été créé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du paiement : ', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Une erreur est survenue lors de la création du paiement. Veuillez réessayer.']);
        }
    }

    // Afficher les détails d'un paiement spécifique
    public function show($id)
    {
        $paiement = Paiement::findOrFail($id);
        return view('web.paiements.show', compact('paiement'));
    }

    // Afficher le formulaire d'édition d'un paiement
    public function edit($id)
    {
        if (!auth()->check() || !auth()->user()->hasRole('payment_validator')) {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }
        $paiement = Paiement::findOrFail($id);
        return view('web.paiements.edit', compact('paiement'));
    }

    // Mettre à jour le paiement
    public function update(Request $request, $id)
    {
        // Validez les données du formulaire
        $request->validate([
            'matieres_taxables' => 'string',
            'prix_matiere' => 'required|numeric',
            'date_ordonancement' => 'required|date',
            'date_accuse_reception' => 'date|after_or_equal:date_ordonancement',
            'nom_ordonanceur' => 'required|string',
            'client_id' => 'required|exists:clients,id'
        ]);

        // Trouver le paiement par ID
        $paiement = Paiement::findOrFail($id);

        // Calculez le prix à payer et le coût d'opportunité
        $prix_a_payer = $request->prix_matiere * 0.05;
        $date_ordonancement = Carbon::parse($request->date_ordonancement);
        $date_accuse_reception = Carbon::parse($request->date_accuse_reception);
        $cout_opportunite = $date_ordonancement->diffInDays($date_accuse_reception);
        $date_paiement = $date_accuse_reception->copy()->addDays(10);
        if ($date_paiement->isSunday()) {
            $date_paiement->addDay();
        }

        // Mettre à jour le paiement
        $paiement->update([
            'matieres_taxables' => $request->matieres_taxables,
            'prix_matiere' => $request->prix_matiere,
            'prix_a_payer' => $prix_a_payer,
            'date_ordonancement' => $date_ordonancement,
            'date_accuse_reception' => $date_accuse_reception,
            'cout_opportunite' => $cout_opportunite,
            'date_paiement' => $date_paiement,
            'retard_de_paiement' => $date_paiement->isPast(),
            'nom_ordonanceur' => $request->nom_ordonanceur,
            'client_id' => $request->client_id,
            'status' => 'en attente'
        ]);

        return redirect()->route('web.paiements.index')->with('success', 'Paiement mis à jour avec succès.');
    }

    // Confirmer le paiement
    public function confirm(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->hasRole('payment_validator')) {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        $paiement = Paiement::findOrFail($id);

        $request->validate([
            'avis' => 'required|in:validé,rejeté',
            'status' => 'required|in:confirmé,pending',
        ]);

        // Ajouter des logs pour vérifier les valeurs reçues
        Log::info('Avis reçu : ' . $request->avis);

        $status = ($request->avis === 'validé') ? 'confirmé' : 'rejeté';
        Log::info('Statut calculé : ' . $status);

        $paiement->update([
            'status' => $status,
            'avis' => $request->avis,
        ]);

        // Vérification de l'état après mise à jour
        Log::info('Paiement mis à jour : ', $paiement->toArray());

        return redirect()->route('web.paiements.index')->with('success', 'Paiement ' . $status . ' avec succès.');
    }

    // Annuler le paiement
    public function destroy($id)
    {
        $paiement = Paiement::findOrFail($id);
        $paiement->delete();
        return redirect()->route('web.paiements.index')->with('success', 'Paiement supprimé avec succès.');
    }

    // Méthode pour valider le paiement (ajouter les vérifications ici)
    private function validerPaiement($id)
    {
        // Implémentez la logique de validation ici
    }
}
