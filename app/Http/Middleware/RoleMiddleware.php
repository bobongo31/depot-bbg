<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        // Séparer les rôles (en cas de plusieurs rôles séparés par une virgule)
        $rolesArray = explode(',', $roles);

        // Vérifier si l'utilisateur est authentifié et a l'un des rôles
        if (!auth()->check()) {
            return redirect()->route('home')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        // Vérifier si l'utilisateur a l'un des rôles requis
        if (!in_array(auth()->user()->role, $rolesArray)) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}
