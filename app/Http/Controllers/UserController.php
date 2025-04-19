<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

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

}
