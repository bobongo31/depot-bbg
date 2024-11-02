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
        $rolesArray = explode(',', $roles);

        // Vérifier si l'utilisateur est authentifié et a l'un des rôles
        if (!auth()->check() || !auth()->user()->hasAnyRole($rolesArray)) {
            return redirect()->route('home')->with('error', 'Accès refusé.');
        }

        return $next($request);
    }
}
