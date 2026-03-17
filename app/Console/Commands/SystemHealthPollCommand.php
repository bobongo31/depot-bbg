<?php

namespace App\Console\Commands;

use App\Services\System\HealthService;
use Illuminate\Console\Command;

class SystemHealthPollCommand extends Command
{
    protected $signature = 'system:health:poll';
    protected $description = 'Contrôler la santé et synchroniser les alertes système';

    public function handle(HealthService $healthService): int
    {
        $health = $healthService->check(syncAlerts: true);

        $this->info('Contrôle santé terminé. Anomalies: ' . $health['fail_count']);

        return self::SUCCESS;
    }
}