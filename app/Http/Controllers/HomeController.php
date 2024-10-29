<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Méthode index
    public function index()
    {
        return view('home'); // Remplacez 'home' par le nom de votre vue
    }
}
