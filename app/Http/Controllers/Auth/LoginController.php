<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // Import de la classe Request

class LoginController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | Login Controller
    |----------------------------------------------------------------------
    |
    | Ce contrôleur gère l'authentification des utilisateurs pour l'application
    | et leur redirection vers la page d'accueil.
    |
    | Le contrôleur utilise un trait pour fournir facilement ses fonctionnalités.
    |
    */

    use AuthenticatesUsers;

    /**
     * Où rediriger les utilisateurs après leur connexion.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Créer une nouvelle instance du contrôleur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Authentifier l'utilisateur en fonction de son nom ou de son e-mail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function credentials(Request $request)
    {
        // Si l'utilisateur saisit un nom, on l'utilise dans la requête d'authentification
        if (filter_var($request->get('name'), FILTER_VALIDATE_EMAIL)) {
            return [
                'email' => $request->get('name'),
                'password' => $request->get('password'),
            ];
        }

        return [
            'name' => $request->get('name'),
            'password' => $request->get('password'),
        ];
    }
}
