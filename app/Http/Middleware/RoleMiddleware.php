<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login'); // Rediriger vers la page de connexion si non authentifié
        }

        $userRole = Auth::user()->role;

        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!in_array($userRole, $roles)) {
            abort(403); // Accès refusé
        }

        return $next($request);
    }
}
