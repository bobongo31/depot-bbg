<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Enregistre tous les services de l'application.
     */
    public function register(): void
    {
        // Désactive Jetstream et Breeze si ils sont installés
        if (class_exists(\Laravel\Jetstream\JetstreamServiceProvider::class)) {
            $this->app->register(\Laravel\Jetstream\JetstreamServiceProvider::class, false);
        }

        if (class_exists(\Laravel\Breeze\BreezeServiceProvider::class)) {
            $this->app->register(\Laravel\Breeze\BreezeServiceProvider::class, false);
        }
    }

    /**
     * Bootstrap toutes les applications services.
     */
    public function boot(): void
    {
        // Précharge les ressources avec Vite
        Vite::prefetch(concurrency: 3);
    }
}
