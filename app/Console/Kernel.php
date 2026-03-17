<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Reponse;

class Kernel extends ConsoleKernel
{
    /**
     * Définir les commandes Artisan disponibles.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\CheckTenantConnections::class,
        \App\Console\Commands\SystemBackupCommand::class,
        \App\Console\Commands\SystemRestoreCommand::class,
    ];

    /**
     * Définir la planification des tâches.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup système chaque jour à 01:30
        $schedule->command('system:backup')->dailyAt('01:30');

        // Vérification horaire des réponses anciennes
        $schedule->call(function () {
            $reponses = Reponse::where('created_at', '<', now()->subHours(72))->get();

            foreach ($reponses as $reponse) {
                // Envoyer un e-mail ou déclencher une alerte ici
            }
        })->hourly();
    }

    /**
     * Charger les commandes Artisan.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}