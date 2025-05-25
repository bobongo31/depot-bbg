<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class EnsureTenantConnection
{
    public function handle($request, Closure $next)
    {
        try {
            DB::connection('tenant')->getPdo();
        } catch (\Exception $e) {
            abort(500, 'La connexion à la base du tenant a échoué.');
        }

        return $next($request);
    }
}
