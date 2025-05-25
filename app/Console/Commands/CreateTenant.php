<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use App\Models\Tenant;

class CreateTenant extends Command
{
    protected $signature = 'tenant:create {name} {database}';
    protected $description = 'Créer un tenant, une base de données et lancer les migrations';

    public function handle()
    {
        $name = $this->argument('name');
        $database = $this->argument('database');

        // 1. Créer la base de données
        $this->info("Création de la base de données '$database'...");
        DB::statement("CREATE DATABASE IF NOT EXISTS `$database`");

        // 2. Ajouter le tenant à la base principale
        $tenant = Tenant::create([
            'name' => $name,
            'database' => $database,
        ]);
        $this->info("Tenant '$name' créé avec succès.");

        // 3. Configuration temporaire de la connexion à cette base
        Config::set("database.connections.tenant", [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => $database,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // 4. Lancer toutes les migrations sur la base du tenant.
        // On retire l'option --path pour que Laravel utilise l'ordre de timestamp naturel.
        $this->info("Lancement des migrations...");
        Artisan::call('migrate', [
            '--database' => 'tenant',
            '--force' => true,
        ]);

        $this->info("Migrations exécutées avec succès pour '$database'.");
    }
}
