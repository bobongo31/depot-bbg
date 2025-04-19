<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CodeAccesController extends Controller
{
    // Tu peux le mettre dans .env et utiliser config('acces.code')
    private $codeAcces = 'SECURE2025';

    public function afficherFormulaire()
    {
        // Si la session a expiré (plus de 20 min), on l'invalide
        if (session('code_acces_valide') && session('code_acces_time')) {
            $diff = now()->diffInMinutes(session('code_acces_time'));
            if ($diff > 20) {
                session()->forget(['code_acces_valide', 'code_acces_time']);
            }
        }

        // Si toujours valide, on va à la page Home
        if (session('code_acces_valide')) {
            return redirect()->route('home');
        }

        // Sinon on reste sur la page avec formulaire
        return view('home'); // Le formulaire est inclus dans cette vue
    }

    public function verifierCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        if ($request->code === $this->codeAcces) {
            // Enregistre dans la session
            session([
                'code_acces_valide' => true,
                'code_acces_time' => now()
            ]);

            return redirect()->intended('/home');
        }

        return back()->with('error', 'Code invalide. Veuillez réessayer.');
    }
}
