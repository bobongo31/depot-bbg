<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CodeAccesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si le code d'accès a été validé dans la session
        if (!$request->session()->has('access_code_verified') || !$request->session()->get('access_code_verified')) {
            // Rediriger vers la page où l'utilisateur peut entrer le code d'accès
            return redirect()->route('code.form');
        }

        // Si le code d'accès est validé, continuer la requête
        return $next($request);
    }
}
