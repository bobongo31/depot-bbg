<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    abort_unless($user, 403, 'Utilisateur non authentifié.');

    $services = $user->service;

    if (is_string($services)) {
        $services = json_decode($services, true);
    }

    $services = is_array($services) ? $services : [];

    $hasInformatique = collect($services)
        ->map(fn ($service) => mb_strtolower(trim((string) $service)))
        ->contains('informatique');

    abort_unless(
        $hasInformatique,
        403,
        'Accès réservé au service Informatique.'
    );

    return $next($request);
}
}