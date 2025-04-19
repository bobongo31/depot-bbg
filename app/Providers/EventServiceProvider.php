<?php
namespace App\Providers;

use Illuminate\Auth\Events\Login;
use App\Listeners\SetTenantDatabase;

protected $listen = [
    Login::class => [
        SetTenantDatabase::class,
    ],
];
