<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Annexe;
use Illuminate\Support\Facades\Storage;

class AnnexeController extends Controller
{
    public function download($id)
    {
        // Vérifier si l'annexe existe
        $annexe = Annexe::find($id);

        if (!$annexe) {
            return redirect()->back()->with('error', 'Annexe introuvable.');
        }

        $filePath = storage_path('app/annexes/' . $annexe->nom_fichier);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'Fichier introuvable.');
        }

        return response()->download($filePath, $annexe->nom_fichier);
    }
}
