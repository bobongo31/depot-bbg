<?php

namespace App\Console\Commands;

use App\Services\System\BackupService;
use Illuminate\Console\Command;

class SystemBackupCommand extends Command
{
    protected $signature = 'system:backup {--trigger=scheduled} {--scope=db}';
    protected $description = 'Créer une sauvegarde système';

    public function handle(BackupService $backupService): int
    {
        $scope = (string) $this->option('scope') ?: 'db';

        $backup = $backupService->createBackup(
            (string) $this->option('trigger'),
            null,
            'Sauvegarde lancée par Artisan',
            $scope
        );

        $this->info('Sauvegarde créée : ' . $backup->filename);

        return self::SUCCESS;
    }
}