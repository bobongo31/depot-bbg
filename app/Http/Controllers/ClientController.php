<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;  // Assurez-vous d'importer le modèle Role
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }

        // Vérifiez si l'utilisateur a le rôle nécessaire
        if (!auth()->user()->hasRole('read_write')) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom_redevable' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'nom_taxateur' => 'required|string|max:255',
            'nom_liquidateur' => 'required|string|max:255',
            'matiere_taxable' => 'required|numeric',
        ]);

        // Créez le client
        $client = Client::create($validatedData);

        // Assignez le rôle (si nécessaire)
        if ($client && Role::where('name', 'read_write')->exists()) {
            $client->assignRole('read_write');
        }

        return redirect()->route('web.clients.index')->with('success', 'Client créé avec succès.');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        // Récupération de tous les rôles pour le formulaire d'édition
        $roles = Role::all();
        return view('clients.edit', compact('client', 'roles'));
    }

    public function update(Request $request, Client $client)
    {
        $validatedData = $request->validate([
            'nom_redevable' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'nom_taxateur' => 'required|string|max:255',
            'nom_liquidateur' => 'required|string|max:255',
            'matiere_taxable' => 'required|numeric',
        ]);

        $client->update($validatedData);

        return redirect()->route('web.clients.index')->with('success', 'Client mis à jour avec succès.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('web.clients.index')->with('success', 'Client supprimé avec succès.');
    }
}
