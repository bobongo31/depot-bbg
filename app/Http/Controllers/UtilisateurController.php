<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use App\Models\User;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()
    {
        $utilisateurs = Utilisateur::orderBy('created_at', 'desc')->get();
        $users = User::orderBy('created_at', 'desc')->get();

        return view('utilisateurs.index', compact('utilisateurs', 'users'));
    }

    public function create()
    {
        return view('inscription');
    }

    public function store(Request $request)
    {
        // Validation avec messages personnalisés
        $validatedData = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'company_name'  => 'required|string|max:255',
            'country'       => 'required|string|max:255',
            'city'          => 'required|string|max:255',
            'province'      => 'required|string|max:255',
            'postal_code'   => 'required|string|max:10',
            'email'         => 'required|email|unique:utilisateurs,email',
            'phone'         => 'required|string|max:15',
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'company_name.required' => "Le nom de l'entreprise est obligatoire.",
            'country.required' => 'Le pays est obligatoire.',
            'city.required' => 'La ville est obligatoire.',
            'province.required' => 'La province est obligatoire.',
            'postal_code.required' => 'Le code postal est obligatoire.',
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email' => 'L’adresse email n’est pas valide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.required' => 'Le numéro de téléphone est obligatoire.',
        ]);

        // Création de l'utilisateur
        Utilisateur::create($validatedData);

        return redirect()->route('welcome')->with('success', 'Votre entreprise a été inscrite avec succès. Vous recevrez bientôt une confirmation par email.');
    }

    public function supprimer($id)
    {
        $utilisateur = Utilisateur::find($id);
        if ($utilisateur) {
            $utilisateur->delete();
            return redirect()->back()->with('success', 'Utilisateur supprimé avec succès.');
        }

        return redirect()->back()->with('error', 'Utilisateur non trouvé.');
    }
}
