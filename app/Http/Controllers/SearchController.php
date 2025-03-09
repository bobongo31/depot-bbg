<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccuseReception; // Correctement importé ici

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer la recherche
        $query = trim($request->input('query'));

        if (!$query) {
            return redirect()->back()->with('error', 'Veuillez entrer un mot-clé pour rechercher.');
        }

        // Rechercher dans les accusés de réception (AccuseReception)
        $accuses = AccuseReception::where('numero_enregistrement', 'like', "%{$query}%")
                        ->orWhere('nom_expediteur', 'like', "%{$query}%")
                        ->orWhere('resume', 'like', "%{$query}%")  // Utilisation de "resume" à la place de "objet"
                        ->get();

        // Si aucun résultat, retourner une erreur
        if ($accuses->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun résultat trouvé pour votre recherche.');
        }

        // Retourner les résultats à la vue
        return view('results', compact('accuses', 'query'));
    }
}
