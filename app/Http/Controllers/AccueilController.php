<?php
namespace App\Http\Controllers;

use App\Models\Paiement; // N'oubliez pas d'importer le modèle Paiement
use Illuminate\Http\Request;

class AccueilController extends Controller
{
    public function index()
    {
            // Récupérer les paiements pour la page d'accueil
        $sort = request('sort'); // Récupère le paramètre de tri de la requête
        $paiements = Paiement::orderBy($sort ?? 'created_at', 'desc')->paginate(50); // Paginer les paiements
    
        return view('home', compact('paiements')); // Passer la variable $paiements à la vue
    }
}
