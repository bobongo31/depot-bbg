<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement; // Assurez-vous que le modèle est importé

class HomeController extends Controller
{
    // Méthode index
    public function index()
    {
        // Récupérer tous les paiements depuis la base de données
        $paiements = Paiement::all(); // Assurez-vous que votre modèle et table existent;
        return view('home', compact('paiements')); // Passer la variable à la vue
    }
}
