<?php

namespace App\Http\Controllers;

use App\Models\Role; // Assurez-vous que vous importez le modèle Role
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Récupérer tous les rôles
        return view('roles.index', compact('roles')); // Renvoie la vue avec les rôles
    }

    // Ajoutez d'autres méthodes comme create, store, show, edit, update, destroy...
}
