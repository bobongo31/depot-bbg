<?php

namespace App\Services\System;

use App\Models\SystemBackup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;

class BackupService
{
    public function __construct(
        protected AuditService $auditService
    ) {
    }

    public function createBackup(string $type = 'manual', ?int $userId = null, ?string $notes = null): SystemBackup
    {
        $disk = config('system_admin.backup.disk', 'local');
        $path = trim(config('system_admin.backup.path', 'backups'), '/');
        $db = config('database.connections.' . config('database.default'));

        if (($db['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('La sauvegarde V1 supporte MySQL uniquement.');
        }

        $filename = 'backup_' . now()->format('Ymd_His') . '_' . Str::lower(Str::random(6)) . '.sql';
        $relativePath = $path . '/' . $filename;
        $fullPath = Storage::disk($disk)->path($relativePath);

        if (!is_dir(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0775, true);
        }

        $backup = SystemBackup::create([
            'type' => $type,
            'status' => 'PENDING',
            'disk' => $disk,
            'path' => $relativePath,
            'filename' => $filename,
            'triggered_by' => $userId,
            'notes' => $notes,
            'started_at' => now(),
        ]);

        $start = microtime(true);

        $process = new Process([
            config('system_admin.backup.mysqldump_path', 'mysqldump'),
            '--user=' . ($db['username'] ?? ''),
            '--password=' . ($db['password'] ?? ''),
            '--host=' . ($db['host'] ?? '127.0.0.1'),
            '--port=' . ($db['port'] ?? 3306),
            '--single-transaction',
            '--skip-lock-tables',
            '--routines',
            '--triggers',
            '--result-file=' . $fullPath,
            $db['database'],
        ]);

        $process->setTimeout(3600);
        $process->run();

        $durationMs = (int) round((microtime(true) - $start) * 1000);

        if (!$process->isSuccessful()) {
            $backup->update([
                'status' => 'FAILED',
                'finished_at' => now(),
                'duration_ms' => $durationMs,
                'meta' => [
                    'stderr' => $process->getErrorOutput(),
                    'stdout' => $process->getOutput(),
                ],
            ]);

            $this->auditService->log(
                'SYSTEM_BACKUP',
                'CREATE_FAILED',
                'Échec de sauvegarde système.',
                ['backup_id' => $backup->id, 'filename' => $filename, 'stderr' => $process->getErrorOutput()],
                'ERROR',
                $userId
            );

            throw new RuntimeException($process->getErrorOutput() ?: 'Échec de sauvegarde.');
        }

        clearstatcache(true, $fullPath);

        $backup->update([
            'status' => 'SUCCESS',
            'size_bytes' => is_file($fullPath) ? filesize($fullPath) : 0,
            'finished_at' => now(),
            'duration_ms' => $durationMs,
            'meta' => [
                'stdout' => $process->getOutput(),
            ],
        ]);

        $this->auditService->log(
            'SYSTEM_BACKUP',
            'CREATE_SUCCESS',
            'Sauvegarde créée avec succès.',
            ['backup_id' => $backup->id, 'filename' => $filename],
            'INFO',
            $userId
        );

        return $backup->fresh();
    }

    public function restoreBackup(SystemBackup $backup, ?int $userId = null): void
    {
        if (!config('system_admin.backup.restore_enabled')) {
            throw new RuntimeException('Restauration désactivée sur cet environnement.');
        }

        if (!app()->isDownForMaintenance()) {
            throw new RuntimeException('Active d’abord le mode maintenance avant la restauration.');
        }

        $disk = $backup->disk;
        $fullPath = Storage::disk($disk)->path($backup->path);

        if (!is_file($fullPath)) {
            throw new RuntimeException('Fichier de sauvegarde introuvable.');
        }

        // Backup préventif avant restauration
        $this->createBackup('before_restore', $userId, 'Sauvegarde automatique avant restauration');

        $db = config('database.connections.' . config('database.default'));

        if (($db['driver'] ?? null) !== 'mysql') {
            throw new RuntimeException('La restauration V1 supporte MySQL uniquement.');
        }

        $sql = file_get_contents($fullPath);

        $process = new Process([
            config('system_admin.backup.mysql_path', 'mysql'),
            '--user=' . ($db['username'] ?? ''),
            '--password=' . ($db['password'] ?? ''),
            '--host=' . ($db['host'] ?? '127.0.0.1'),
            '--port=' . ($db['port'] ?? 3306),
            $db['database'],
        ]);

        $process->setInput($sql ?: '');
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->auditService->log(
                'SYSTEM_RESTORE',
                'RESTORE_FAILED',
                'Échec de restauration.',
                ['backup_id' => $backup->id, 'stderr' => $process->getErrorOutput()],
                'ERROR',
                $userId
            );

            throw new RuntimeException($process->getErrorOutput() ?: 'Échec de restauration.');
        }

        $this->auditService->log(
            'SYSTEM_RESTORE',
            'RESTORE_SUCCESS',
            'Restauration exécutée avec succès.',
            ['backup_id' => $backup->id, 'filename' => $backup->filename],
            'INFO',
            $userId
        );
    }

    public function deleteBackup(SystemBackup $backup, ?int $userId = null): void
    {
        $fullPath = Storage::disk($backup->disk)->path($backup->path);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }

        $filename = $backup->filename;
        $backupId = $backup->id;
        $backup->delete();

        $this->auditService->log(
            'SYSTEM_BACKUP',
            'DELETE',
            'Sauvegarde supprimée.',
            ['backup_id' => $backupId, 'filename' => $filename],
            'WARNING',
            $userId
        );
    }

    public function pruneOldBackups(?int $userId = null): int
    {
        $days = (int) config('system_admin.backup.retention_days', 30);

        $items = SystemBackup::query()
            ->where('created_at', '<', now()->subDays($days))
            ->orderBy('created_at')
            ->get();

        $deleted = 0;

        foreach ($items as $backup) {
            $this->deleteBackup($backup, $userId);
            $deleted++;
        }

        $this->auditService->log(
            'SYSTEM_BACKUP',
            'PRUNE',
            'Purge des anciennes sauvegardes exécutée.',
            ['deleted' => $deleted, 'retention_days' => $days],
            'INFO',
            $userId
        );

        return $deleted;
    }
}