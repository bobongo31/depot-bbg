<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Afficher le formulaire de connexion
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Gérer la tentative de connexion
    public function login(Request $request)
    {
        // Valider les informations de connexion
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Tenter de connecter l'utilisateur
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Rediriger vers la page d'accueil après connexion réussie
            return redirect()->intended('/');
        }

        // Si la connexion échoue, retourner au formulaire avec un message d'erreur
        return back()->withErrors([
            'email' => 'Les informations d\'identification sont incorrectes.',
        ]);
    }

    // Déconnexion de l'utilisateur
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
}
