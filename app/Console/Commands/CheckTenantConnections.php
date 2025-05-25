<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Throwable;

class CheckTenantConnections extends Command
{
    protected $signature = 'tenants:check-connections';

    protected $description = 'Teste toutes les connexions spécifiques aux entreprises définies dans config/database.php';

    public function handle()
    {
        $connections = Config::get('database.connections');

        $tenantConnections = collect($connections)
            ->filter(function ($_, $key) {
                // Tu peux adapter ici selon ton propre préfixe
                return str_starts_with($key, 'entreprise_');
            });

        if ($tenantConnections->isEmpty()) {
            $this->warn('Aucune connexion entreprise trouvée (préfixe "entreprise_").');
            return;
        }

        foreach ($tenantConnections as $name => $config) {
            try {
                DB::purge($name);
                DB::connection($name)->getPdo();
                $this->info("✅ Connexion [$name] OK");
            } catch (Throwable $e) {
                $this->error("❌ Connexion [$name] échouée : " . $e->getMessage());
            }
        }

        $this->line("\nTest terminé.");
    }
}
