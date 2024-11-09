<?php 

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use PDF;

class ClientController extends Controller
{


    public function genererRapportTousClients()
    {
        $clients = Client::all();
        $pdf = PDF::loadView('rapport.rapport_tous_clients', compact('clients'))->setPaper('A4', 'landscape');
        return $pdf->download('rapport.rapport_tous_clients.pdf');
    }

    public function genererRapportClient($id)
    {
        $client = Client::with('paiements')->findOrFail($id);
        $pdf = PDF::loadView('rapport.rapport_client', compact('client'))->setPaper('A4', 'portrait');
        return $pdf->download("rapport.rapport_client_{$id}.pdf");
    }

    public function index()
    {
        // Récupération de tous les clients
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        // Vérifiez si l'utilisateur est authentifié
         if (!auth()->check() || !auth()->user()->hasRole('read_write')) {
            return redirect('/')
                ->with('error', 'Accès refusé.');
        }

        return view('clients.create');
    }

    public function store(Request $request)
    {
        // Validation des données d'entrée
        $validatedData = $request->validate([
            'nom_redevable' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'nom_taxateur' => 'required|string|max:255',
            'nom_liquidateur' => 'required|string|max:255',
            'matiere_taxable' => 'required|string|max:255',
            'prix_matiere' => 'required|numeric|min:0',
        ]);

        // Calculer le prix à payer (5% du prix de la matière)
        $prix_a_payer = $validatedData['prix_matiere'] * 0.05;

        // Créer le client avec les données validées
        Client::create([
            'nom_redevable' => $validatedData['nom_redevable'],
            'adresse' => $validatedData['adresse'],
            'telephone' => $validatedData['telephone'],
            'nom_taxateur' => $validatedData['nom_taxateur'],
            'nom_liquidateur' => $validatedData['nom_liquidateur'],
            'matiere_taxable' => $validatedData['matiere_taxable'],
            'prix_matiere' => $validatedData['prix_matiere'],
            'prix_a_payer' => $prix_a_payer, // Stockez le prix à payer
        ]);

        // Redirigez après la création
        return redirect()->route('web.clients.index')->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        if (!auth()->check() || !auth()->user()->hasRole('read_write')) {
            return redirect('/')
                ->with('error', 'Accès refusé.');
        }
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        // Validation des données d'entrée
        $validatedData = $request->validate([
            'nom_redevable' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'nom_taxateur' => 'required|string|max:255',
            'nom_liquidateur' => 'required|string|max:255',
            'matiere_taxable' => 'required|string|max:255',
            'prix_matiere' => 'required|numeric|min:0',
        ]);

        // Mettez à jour le client avec les nouvelles données
        $client->update($validatedData);

        // Calculer à nouveau le prix à payer après mise à jour
        $prix_a_payer = $validatedData['prix_matiere'] * 0.05;
        $client->update(['prix_a_payer' => $prix_a_payer]); // Mettre à jour le prix à payer

        // Redirigez après la mise à jour
        return redirect()->route('web.clients.index')->with('success', 'Client mis à jour avec succès.');
    }

      // Méthode pour récupérer les données du client
    public function getClientData($clientId)
    {
        $client = Client::find($clientId);
        if ($client) {
            return response()->json([
                'matiere_taxable' => $client->matiere_taxable,
                'prix_matiere' => $client->prix_matiere,
                'prix_a_payer' => $client->prix_a_payer,
            ]);
        }
        return response()->json([], 404); // Si le client n'est pas trouvé
    }  
    public function destroy(Client $client)
    {
        if (!auth()->check() || !auth()->user()->hasRole('read_write')) {
            return redirect('/')
                ->with('error', 'Accès refusé.');
        }
        $client->delete();

        // Redirigez après la suppression
        return redirect()->route('web.clients.index')->with('success', 'Client supprimé avec succès.');
    }
}
