<?php

namespace App\Console\Commands;

use App\Services\System\BackupService;
use Illuminate\Console\Command;

class SystemPruneBackupsCommand extends Command
{
    protected $signature = 'system:backups-prune';
    protected $description = 'Supprimer les anciennes sauvegardes selon la rétention';

    public function handle(BackupService $backupService): int
    {
        $count = $backupService->pruneOldBackups();

        $this->info("Sauvegardes supprimées : {$count}");

        return self::SUCCESS;
    }
}