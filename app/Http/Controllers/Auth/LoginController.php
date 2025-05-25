<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirection après connexion.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Constructeur du contrôleur.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Personnalise les identifiants de connexion.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'entreprise' => $request->get('entreprise'),
            'name' => $request->get('name'),
            'password' => $request->get('password'),
        ];
    }

    /**
     * Surcharge la méthode login pour inclure la validation par entreprise + name + password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
{
    // Validation des champs
    $request->validate([
        'entreprise' => 'required|string',
        'name' => 'required|string',
        'password' => 'required|string',
        'g-recaptcha-response' => 'required|captcha',
    ]);

    // Récupération des informations
    $credentials = $this->credentials($request);

    // Recherche de l'utilisateur
    $user = User::where('entreprise', $credentials['entreprise'])
                ->where('name', $credentials['name'])
                ->first();

    // Vérification du mot de passe
    if ($user && Hash::check($credentials['password'], $user->password)) {

        // Vérifie si l'abonnement est expiré
        if ($user->abonnement_expires_at && now()->greaterThan($user->abonnement_expires_at)) {
            return redirect()->back()->withErrors([
                'message' => 'Votre abonnement a expiré. Veuillez le renouveler pour continuer.',
            ])->withInput($request->only('entreprise', 'name'));
        }

        // Vérifie si l'abonnement expire dans moins de 7 jours
        if ($user->abonnement_expires_at && now()->diffInDays($user->abonnement_expires_at, false) <= 7) {
            session()->flash('alerte_abonnement', '⚠️ Votre abonnement expire dans moins de 7 jours.');
        }

        // Connexion autorisée
        auth()->login($user);
        return redirect()->intended($this->redirectTo);
    }

    // En cas d'échec
    return redirect()->back()->withErrors([
        'message' => 'Nom d\'utilisateur, entreprise ou mot de passe incorrect.',
    ])->withInput($request->only('entreprise', 'name'));
}
}
