<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class EntrepriseXLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_entreprise_x'); // Assure-toi que cette vue existe
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('entreprise_x')->attempt($credentials)) {
            return redirect()->intended('home');
        }

        return back()->withErrors(['email' => 'Login échoué']);
    }
}
