<?php
namespace App\Http\Controllers;

use App\Models\Paiement;

class HomeController extends Controller
{
    public function index()
    {
        // Vérifiez si l'utilisateur est authentifié
        if (!auth()->check()) {
            return redirect()->route('login'); // Redirigez vers la page de connexion si l'utilisateur n'est pas authentifié
        }

        // Récupérer les paiements pour la page d'accueil
        $sort = request('sort'); // Récupère le paramètre de tri de la requête
        $paiements = Paiement::orderBy($sort ?? 'created_at', 'desc')->paginate(6); // Paginer les paiements

        return view('home', compact('paiements')); // Assurez-vous que 'home' est la vue d'accueil
    }
}

