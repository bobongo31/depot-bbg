<?php

namespace App\Services\System;

use App\Models\SystemBackup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
use ZipArchive;

class BackupService
{
    public function __construct(
        protected AuditService $auditService
    ) {
    }

    public function createBackup(string $type = 'manual', ?int $userId = null, ?string $notes = null, string $scope = 'db', ?bool $allowCopy = null): SystemBackup
    {
        $globalDisk = config('system_admin.backup.disk', 'local');
        $globalPath = trim(config('system_admin.backup.path', 'backups'), '/');
        $db = config('database.connections.' . config('database.default'));
        // DB export
        if ($scope === 'db') {
            // use global backup disk/path when scope is DB
            $disk = $globalDisk;
            $path = $globalPath;
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

        // Filesystem export (storage/app)
        if ($scope === 'storage') {
            // Use storage-specific disk/path if configured
            $disk = config('system_admin.backup.storage.disk', $globalDisk);
            $path = trim(config('system_admin.backup.storage.path', 'backups/storage'), '/');

            $filename = 'backup_files_' . now()->format('Ymd_His') . '_' . Str::lower(Str::random(6)) . '.zip';
            $relativePath = $path . '/' . $filename;

            // If disk is local we can write directly to its path; otherwise create a temp file and upload later
            $useTemp = ($disk !== 'local');

            if ($useTemp) {
                $tmpDir = sys_get_temp_dir();
                $fullPath = $tmpDir . DIRECTORY_SEPARATOR . $filename;
                // ensure temp dir exists (should) and is writable
                if (!is_dir(dirname($fullPath)) || !is_writable(dirname($fullPath))) {
                    throw new RuntimeException('Le répertoire temporaire n\'est pas accessible pour créer l\'archive.');
                }
            } else {
                $fullPath = Storage::disk($disk)->path($relativePath);

                if (!is_dir(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0775, true);
                }
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

            $zip = new ZipArchive();
            if ($zip->open($fullPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                $backup->update(['status' => 'FAILED', 'finished_at' => now(), 'meta' => array_merge($backup->meta ?? [], ['zip_error' => 'open_failed', 'path' => $fullPath])]);
                $this->auditService->log(
                    'SYSTEM_BACKUP',
                    'CREATE_FAILED',
                    'Échec création archive de fichiers.',
                    ['backup_id' => $backup->id, 'filename' => $filename],
                    'ERROR',
                    $userId
                );
                throw new RuntimeException('Impossible de créer l’archive zip. Vérifiez que le chemin est accessible : ' . $fullPath);
            }

            $source = storage_path('app');
            $filesAdded = 0;

            if (is_dir($source)) {
                $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source));
                foreach ($it as $file) {
                    if (!$file->isFile()) {
                        continue;
                    }
                    $filePath = $file->getRealPath();
                    $relativeName = ltrim(str_replace($source, '', $filePath), '\\/');
                    $zip->addFile($filePath, $relativeName);
                    $filesAdded++;
                }
            }

            $zip->close();

            // If we used a temp file (disk not local), upload the created zip to the configured disk
            if ($useTemp) {
                try {
                    $stream = fopen($fullPath, 'r');
                    if ($stream === false) {
                        throw new RuntimeException('Impossible d\'ouvrir le fichier temporaire pour téléversement.');
                    }

                    Storage::disk($disk)->put($relativePath, $stream);
                    if (is_resource($stream)) fclose($stream);
                    // remove temp file after upload
                    @unlink($fullPath);
                    // set fullPath to the storage disk real path if possible
                    try {
                        $fullPath = Storage::disk($disk)->path($relativePath);
                    } catch (\Throwable $e) {
                        // ignore: some remote disks don't support path()
                    }
                } catch (\Throwable $e) {
                    $backup->update(['status' => 'FAILED', 'finished_at' => now(), 'meta' => array_merge($backup->meta ?? [], ['upload_error' => $e->getMessage()])]);
                    $this->auditService->log(
                        'SYSTEM_BACKUP',
                        'CREATE_FAILED',
                        'Échec téléversement archive sur le disque de stockage.',
                        ['backup_id' => $backup->id, 'filename' => $filename, 'error' => $e->getMessage()],
                        'ERROR',
                        $userId
                    );

                    throw $e;
                }
            }

            $durationMs = (int) round((microtime(true) - $start) * 1000);

            if (!is_file($fullPath) || filesize($fullPath) === 0) {
                $backup->update(['status' => 'FAILED', 'finished_at' => now(), 'duration_ms' => $durationMs]);
                $this->auditService->log(
                    'SYSTEM_BACKUP',
                    'CREATE_FAILED',
                    'Archive vide ou introuvable après création.',
                    ['backup_id' => $backup->id, 'filename' => $filename],
                    'ERROR',
                    $userId
                );
                throw new RuntimeException('Échec de création de l’archive de fichiers.');
            }

            clearstatcache(true, $fullPath);

            $backup->update([
                'status' => 'SUCCESS',
                'size_bytes' => filesize($fullPath),
                'finished_at' => now(),
                'duration_ms' => $durationMs,
                'meta' => [
                    'files_added' => $filesAdded,
                ],
            ]);

            $this->auditService->log(
                'SYSTEM_BACKUP',
                'CREATE_SUCCESS',
                'Archive de fichiers créée avec succès.',
                ['backup_id' => $backup->id, 'filename' => $filename, 'files_added' => $filesAdded],
                'INFO',
                $userId
            );

            // Determine whether to perform copies: parameter overrides config when not null
            $performCopy = $allowCopy ?? (bool) config('system_admin.backup.storage.copy_enabled', true);

            // If configured to copy only when source is local, skip otherwise
            $copyOnlyIfLocal = (bool) config('system_admin.backup.storage.copy_only_if_local', true);
            if ($performCopy && ($copyOnlyIfLocal && $disk !== 'local')) {
                $performCopy = false;
            }

            $copied = [];
            $copyErrors = [];

            if ($performCopy) {
                $copyTargets = config('system_admin.backup.storage.copy_to', []);

                foreach ($copyTargets as $target) {
                    $target = trim((string) $target);
                    if ($target === '') {
                        continue;
                    }

                    $normalized = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $target);
                    $destDir = rtrim($normalized, DIRECTORY_SEPARATOR);

                    if (!is_dir($destDir)) {
                        @mkdir($destDir, 0775, true);
                    }

                    $destPath = $destDir . DIRECTORY_SEPARATOR . $filename;

                    try {
                        if (@copy($fullPath, $destPath)) {
                            $copied[] = $destPath;
                            $this->auditService->log(
                                'SYSTEM_BACKUP',
                                'COPY',
                                'Copie de la sauvegarde vers destination.',
                                ['backup_id' => $backup->id, 'dest' => $destPath],
                                'INFO',
                                $userId
                            );
                        } else {
                            $copyErrors[] = $destPath;
                            $this->auditService->log(
                                'SYSTEM_BACKUP',
                                'COPY_FAILED',
                                'Échec de copie de la sauvegarde.',
                                ['backup_id' => $backup->id, 'dest' => $destPath],
                                'ERROR',
                                $userId
                            );
                        }
                    } catch (\Throwable $e) {
                        $copyErrors[] = $destPath . ' -> ' . $e->getMessage();
                        $this->auditService->log(
                            'SYSTEM_BACKUP',
                            'COPY_FAILED',
                            'Exception lors de la copie de la sauvegarde.',
                            ['backup_id' => $backup->id, 'dest' => $destPath, 'error' => $e->getMessage()],
                            'ERROR',
                            $userId
                        );
                    }
                }
            }

            if (!empty($copied) || !empty($copyErrors)) {
                $meta = $backup->meta ?? [];
                $meta['copied_to'] = $copied;
                $meta['copy_errors'] = $copyErrors;
                $backup->update(['meta' => $meta]);
            }

            return $backup->fresh();
        }

        throw new RuntimeException('Scope de sauvegarde inconnu.');
    }

    public function restoreBackup(SystemBackup $backup, ?int $userId = null, ?string $forceScope = null): void
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

        // Determine restore type: forced by caller, else by filename
        if ($forceScope === 'storage') {
            $isZip = true;
        } elseif ($forceScope === 'db') {
            $isZip = false;
        } else {
            $isZip = str_ends_with(strtolower($backup->filename), '.zip') || str_starts_with($backup->filename, 'backup_files_');
        }

        if ($isZip) {
            $zip = new ZipArchive();
            if ($zip->open($fullPath) !== true) {
                $this->auditService->log(
                    'SYSTEM_RESTORE',
                    'RESTORE_FAILED',
                    'Impossible d’ouvrir l’archive de restauration.',
                    ['backup_id' => $backup->id, 'filename' => $backup->filename],
                    'ERROR',
                    $userId
                );

                throw new RuntimeException('Impossible d’ouvrir l’archive de restauration.');
            }

            $target = storage_path('app');

            // Extract files (will overwrite existing files)
            $result = $zip->extractTo($target);
            $zip->close();

            if ($result === false) {
                $this->auditService->log(
                    'SYSTEM_RESTORE',
                    'RESTORE_FAILED',
                    'Échec d’extraction de l’archive de restauration.',
                    ['backup_id' => $backup->id, 'filename' => $backup->filename],
                    'ERROR',
                    $userId
                );

                throw new RuntimeException('Échec d’extraction de l’archive de restauration.');
            }

            $this->auditService->log(
                'SYSTEM_RESTORE',
                'RESTORE_SUCCESS',
                'Restauration des fichiers exécutée avec succès.',
                ['backup_id' => $backup->id, 'filename' => $backup->filename],
                'INFO',
                $userId
            );

            return;
        }

        // Default: assume SQL dump for MySQL
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

    /**
     * List archive contents for a storage backup ZIP.
     * Returns an array of file paths inside the archive.
     */
    public function listArchiveContents(SystemBackup $backup): array
    {
        $disk = $backup->disk;
        $fullPath = Storage::disk($disk)->path($backup->path);

        if (!is_file($fullPath)) {
            throw new RuntimeException('Fichier de sauvegarde introuvable.');
        }

        $zip = new ZipArchive();
        $files = [];

        if ($zip->open($fullPath) !== true) {
            throw new RuntimeException('Impossible d’ouvrir l’archive pour prévisualisation.');
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $stat = $zip->statIndex($i);
            if ($stat === false) continue;
            $name = $stat['name'] ?? null;
            if ($name && substr($name, -1) !== '/') { // skip directories
                $files[] = $name;
            }
        }

        $zip->close();

        return $files;
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