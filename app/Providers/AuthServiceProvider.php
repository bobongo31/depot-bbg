<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Client;
use App\Policies\ClientPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Les politiques de modèle.
     *
     * @var array
     */
    protected $policies = [
        Client::class => ClientPolicy::class,
    ];

    /**
     * Enregistrez les services d'authentification et d'autorisation.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
