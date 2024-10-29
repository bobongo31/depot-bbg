<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Le chemin vers le fichier de cache des routes.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    // Définir la constante HOME
    const HOME = '/home'; // Vous pouvez ajuster ce chemin selon vos besoins.

    /**
     * Définir les liaisons de modèles de route, les filtres de motif, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Définir les routes pour l'application.
     *
     * @return void
     */
    public function map()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));

        Route::middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
