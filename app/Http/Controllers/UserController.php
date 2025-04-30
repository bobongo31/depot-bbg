<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Utilisateur;

class UserController extends Controller
{

    protected static function booted()
    {
        static::addGlobalScope('entreprise', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('entreprise', Auth::user()->entreprise);
            }
        });
    }
    // Affiche le formulaire d'édition
    public function edit()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    // Met à jour les informations utilisateur
    public function update(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . auth()->user()->id,
        'password' => 'nullable|confirmed|min:8',
    ]);

    $user = auth()->user();
    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->password) {
        $user->password = bcrypt($request->password);
    }

    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Profil mis à jour avec succès.');
}

public function valider($id)
    {
        // Récupérer l'utilisateur inscrit
        $utilisateur = Utilisateur::find($id);

        // Vérifier si l'utilisateur existe
        if (!$utilisateur) {
            return redirect()->back()->with('error', 'Utilisateur non trouvé.');
        }

        // Créer un nouvel utilisateur dans la table `users` avec un mot de passe par défaut
        $user = new User();
        $user->name = $utilisateur->first_name . ' ' . $utilisateur->last_name;
        $user->email = $utilisateur->email;
        $user->password = Hash::make('12345'); // Mot de passe par défaut
        $user->role = 'agent'; // Rôle par défaut (peut être ajusté)
        $user->entreprise = $utilisateur->company_name;
        $user->abonnement_expires_at = now()->addDays(7); // Abonnement expirant dans 7 jours
        $user->save();

        // Optionnel: Supprimer l'utilisateur validé de la table `utilisateurs` après création
        $utilisateur->delete();

        // Retourner à la liste avec un message de succès
        return redirect()->route('utilisateur.index')->with('success', 'Utilisateur validé et créé avec succès.');
    }

}
