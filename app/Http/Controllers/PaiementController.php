<?php

namespace App\Http\Controllers;

use App\Models\Paiement; 
use App\Models\Client; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;
use PDF; 

class PaiementController extends Controller
{
    

    public function genererRapportTousPaiements()
    {
        // Récupérer tous les paiements avec leurs clients
        $paiements = Paiement::with('client')->get();

        // Charger la vue pour tous les paiements
        $pdf = PDF::loadView('rapport.rapport_tous_paiements', compact('paiements'))
                ->setPaper('A4', 'landscape'); // Format A4, orientation portrait

        // Retourner le PDF pour le téléchargement
        return $pdf->download('rapport.rapport_tous_paiements.pdf');
    }


    public function genererRapportPaiement($id)
    {
        // Trouver le paiement avec son client associé
        $paiements = Paiement::where('client_id', $id)->get();
        // Charger la vue pour un paiement spécifique
        $pdf = PDF::loadView('rapport.rapport_paiement', compact('paiements'))
                ->setPaper('A4', 'landscape'); // Format A4

        // Retourner le PDF pour le téléchargement
        return $pdf->download("rapport.rapport_paiement_{$id}.pdf");
    } 


    // Afficher la liste des paiements
    public function index(Request $request)
    {
        // Vérifiez si l'utilisateur est authentifié et possède les rôles requis
        if (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('payment_validator'))) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        $query = Paiement::query();

     // Vérifier si un tri est demandé et si la colonne est valide
     $sort = $request->input('sort');
     $validColumns = ['date_paiement', 'status']; // Les colonnes autorisées pour le tri
 
     if (in_array($sort, $validColumns)) {
         $query = $query->orderBy($sort);
     }

    // Pagination (6 paiements par page)
    $paiements = $query->paginate(6);

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
        if (!auth()->user()->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        // Récupérer tous les clients
        $clients = Client::all(); 
        return view('web.paiements.create', compact('clients'));
    }
    public function getClientData($clientId)
    {
        // Récupérer les données du client par son ID
        $client = Client::find($clientId);
        
        if ($client) {
            // Retourner les informations du client sous forme de JSON
            return response()->json([
                'matiere_taxable' => $client->matiere_taxable,
                'prix_matiere' => $client->prix_matiere
            ]);
        }
    
        // Si le client n'est pas trouvé
        return response()->json([], 404);
    }
    

    // Stocker le paiement dans la base de données
    public function store(Request $request)
    {
        // Journaliser les données du formulaire pour le débogage
        Log::info('Données du formulaire : ', $request->all());

        // Validez les données du formulaire
        $validatedData = $request->validate([
            'matiere_taxable' => 'required|string|max:255',
            'prix_matiere' => 'required|numeric|min:0',
            'date_ordonancement' => 'required|date',
            'date_accuse_reception' => 'nullable|date|after_or_equal:date_ordonancement',
            'nom_ordonanceur' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
        ]);

        // Journaliser les données validées pour confirmation
        Log::info('Données validées : ', $validatedData);

        try {
            // Calculez le prix à payer
            $prix_a_payer = $validatedData['prix_matiere'] * 0.05;
            $date_ordonancement = Carbon::parse($validatedData['date_ordonancement']);
            $date_accuse_reception = Carbon::parse($validatedData['date_accuse_reception']);

            // Calculez la date de paiement en ajoutant 10 jours ouvrables
            $joursAjoutes = 0;
            $date_paiement = $date_accuse_reception->copy();

            while ($joursAjoutes < 10) {
                $date_paiement->addDay(); // Ajoute un jour
                if (!$date_paiement->isSunday()) {
                    $joursAjoutes++; // Incrémente le compteur seulement si ce n'est pas un dimanche
                }
            }

            // Calculez le coût d'opportunité
            $cout_opportunite = $date_ordonancement->diffInDays($date_accuse_reception);

            // Créez l'enregistrement dans la base de données
            $paiement = Paiement::create([
                'matiere_taxable' => $validatedData['matiere_taxable'],
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
            return redirect('/')
            ->with('error', 'Accès refusé.');
                }
        $paiement = Paiement::findOrFail($id);
        return view('web.paiements.edit', compact('paiement'));
    }

    // Mettre à jour le paiement
    public function update(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->hasRole('payment_validator')) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'matiere_taxable' => 'required|string',
            'prix_matiere' => 'required|numeric',
            'date_ordonancement' => 'required|date',
            'date_accuse_reception' => 'nullable|date|after_or_equal:date_ordonancement',
            'nom_ordonanceur' => 'required|string',
            'client_id' => 'required|exists:clients,id'
        ]);

        // Trouver le paiement par ID
        $paiement = Paiement::findOrFail($id);

        // Calculez le prix à payer et le coût d'opportunité
        $prix_a_payer = $validatedData['prix_matiere'] * 0.05;
        $date_ordonancement = Carbon::parse($validatedData['date_ordonancement']);
        $date_accuse_reception = Carbon::parse($validatedData['date_accuse_reception']);
        $cout_opportunite = $date_ordonancement->diffInDays($date_accuse_reception);

        // Calculez la date de paiement en ajoutant 10 jours ouvrables
        $joursAjoutes = 0;
        $date_paiement = $date_accuse_reception->copy();

        while ($joursAjoutes < 10) {
            $date_paiement->addDay(); // Ajoute un jour
            if (!$date_paiement->isSunday()) {
                $joursAjoutes++; // Incrémente le compteur seulement si ce n'est pas un dimanche
            }
        }

        Log::info('Retard de paiement: ', ['retard_de_paiement' => $date_paiement->isPast()]);

        // Mettre à jour le paiement
        $paiement->update([
            'matiere_taxable' => $validatedData['matiere_taxable'],
            'prix_matiere' => $validatedData['prix_matiere'],
            'prix_a_payer' => $prix_a_payer,
            'date_ordonancement' => $date_ordonancement,
            'date_accuse_reception' => $date_accuse_reception,
            'cout_opportunite' => $cout_opportunite,
            'date_paiement' => $date_paiement,
            'retard_de_paiement' => $date_paiement->isPast() ? 1 : 0,
            'nom_ordonanceur' => $validatedData['nom_ordonanceur'],
            'client_id' => $validatedData['client_id'],
            'status' => 'en attente'
        ]);

        return redirect()->route('web.paiements.index')->with('success', 'Paiement mis à jour avec succès.');
    }
        public function confirm(Request $request, $id)
    {
        // Vérification de l'authentification et des rôles
        if (!auth()->check() || !auth()->user()->hasRole('payment_validator')) {
        return redirect('/')
            ->with('error', 'Accès refusé.');
        }

        try {
        // Trouver le paiement ou renvoyer une erreur 404
        $paiement = Paiement::findOrFail($id);

        // Validation des données d'entrée
        $request->validate([
            'avis' => 'required|in:validé,rejeté',
            // Optionnel : retirer ou conserver si vous attendez un statut du client
            // 'status' => 'required|in:confirmé,pending',
        ]);

        // Ajouter des logs pour vérifier les valeurs reçues
        Log::info('Avis reçu : ' . $request->input('avis'));

        // Calculer le statut basé sur l'avis
        $status = ($request->input('avis') === 'validé') ? 'validé' : 'rejeté';
        Log::info('Statut calculé : ' . $status);

        // Mettre à jour le paiement avec le nouvel état
        $paiement->update([
            'status' => $status,
            'avis' => $request->input('avis'),
        ]);

        // Vérification de l'état après mise à jour
        Log::info('Paiement après mise à jour : ', $paiement->toArray());

        // Retourner une réponse JSON après mise à jour
        return response()->json([
            'message' => 'Le paiement a été validé.',
            'paiement' => $paiement,
        ]);
        
    } catch (\Exception $e) {
        // En cas d'erreur, on logue l'exception et on renvoie une réponse d'erreur
        Log::error('Erreur lors de la confirmation du paiement : ' . $e->getMessage());
        return response()->json([
            'error' => 'Une erreur est survenue lors de la validation du paiement.',
        ], 500);
    }
    }

    // Supprimer un paiement
    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        $paiement = Paiement::findOrFail($id);
        $paiement->delete();

        return redirect()->route('web.paiements.index')->with('success', 'Paiement supprimé avec succès.');
    }

    public function updateAccuseReception(Request $request, $id)
    {
    // Validation de la date d'accusé de réception
    $request->validate([
        'date_accuse_reception' => 'required|date',
    ]);

    // Trouver le paiement à mettre à jour
    $paiement = Paiement::findOrFail($id);

    // Mise à jour de la date d'accusé de réception
    $paiement->date_accuse_reception = $request->date_accuse_reception;

    // Sauvegarde des modifications dans la base de données
    $paiement->save();

    // Rediriger vers la liste des paiements avec un message de succès
    return redirect()->route('web.paiements.index')->with('success', 'La date d\'accusé de réception a été mise à jour avec succès.');
    }
}
