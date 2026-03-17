<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users_main',
        ],

        'fpc' => [
            'driver' => 'session',
            'provider' => 'users_fpc',
        ],

        'entreprise_x' => [
            'driver' => 'session',
            'provider' => 'users_entreprise_x',
        ],

        'gestion_courrier' => [
            'driver' => 'session',
            'provider' => 'users_gestion_courrier_fpc',
        ],
    ],

    'providers' => [
        'users_main' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'users_fpc' => [
            'driver' => 'eloquent',
            'model' => App\Models\FpcUser::class, // Assurez-vous que ce modèle existe
            'connection' => 'mysql_fpc',
        ],

        'users_entreprise_x' => [
            'driver' => 'eloquent',
            'model' => App\Models\EntrepriseXUser::class, // Assurez-vous que ce modèle existe
            'connection' => 'mysql_entreprise_x',
        ],

        'users_gestion_courrier_fpc' => [
            'driver' => 'eloquent',
            'model' => App\Models\GestionCourrierUser::class, // Assurez-vous que ce modèle existe
            'connection' => 'gestion_courrier_fpc',
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users_main', // Choisissez entre 'users_main', 'users_fpc', 'users_entreprise_x', ou 'users_gestion_courrier_fpc'
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
