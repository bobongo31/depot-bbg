<?php

namespace App\Http\Controllers;

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
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_redevable' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'nom_taxateur' => 'required|string|max:255',
            'nom_liquidateur' => 'required|string|max:255',
            'matiere_taxable' => 'required|numeric',
        ]);

        Client::create($request->all());
        return redirect()->route('clients.index');
    }

    // Ajouter d'autres méthodes pour show, edit, update, destroy...
}
