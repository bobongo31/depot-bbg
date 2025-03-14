<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Définir les commandes Artisan disponibles pour votre application.
     *
     * @var array
     */
    protected $commands = [
        // Ajoute tes commandes ici
    ];

    /**
     * Définir la planification des tâches.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Exemple de tâche planifiée
        $schedule->call(function () {
            // Logic de ton alerte pour les réponses en retard
            $reponses = \App\Models\Reponse::where('created_at', '<', now()->subHours(72))->get();
            foreach ($reponses as $reponse) {
                // Envoyer un e-mail ou faire autre chose
            }
        })->hourly(); // La tâche sera exécutée toutes les heures
    }

    /**
     * Définir les commandes Artisan que vous souhaitez exécuter en ligne de commande.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
