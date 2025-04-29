<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    // Affiche le formulaire d'inscription
    public function create()
    {
        return view('inscription');
    }

    // Enregistre un nouvel utilisateur
    public function store(Request $request)
    {
        // Validation des données
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'email' => 'required|email|unique:utilisateurs,email',
            'phone' => 'required|string|max:15',
        ]);

        // Création de l'utilisateur
        Utilisateur::create($request->all());

        // Retourne un message de succès
        return redirect()->route('welcome')->with('success', 'Votre entreprise a été inscrite avec succès. Vous recevrez bientôt une confirmation par email.');    }
}
