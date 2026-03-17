<?php

namespace App\Services\System;

use App\Models\SystemAlert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HealthService
{
    public function __construct(
        protected AuditService $auditService
    ) {
    }

    public function check(bool $syncAlerts = false): array
    {
        $components = [];

        $add = function (
            string $key,
            string $label,
            bool $ok,
            string $message,
            string $level = 'ERROR',
            array $meta = []
        ) use (&$components) {
            $components[] = [
                'key' => $key,
                'label' => $label,
                'ok' => $ok,
                'message' => $message,
                'level' => $level,
                'meta' => $meta,
            ];
        };

        try {
            DB::select('SELECT 1');
            $add('database', 'Base de données', true, 'Connexion DB OK', 'ERROR');
        } catch (\Throwable $e) {
            $add('database', 'Base de données', false, $e->getMessage(), 'CRITICAL');
        }

        try {
            Cache::put('system_health_test_key', 'ok', now()->addMinute());
            $ok = Cache::get('system_health_test_key') === 'ok';
            Cache::forget('system_health_test_key');
            $add('cache', 'Cache', $ok, $ok ? 'Cache OK' : 'Lecture/écriture cache KO', 'ERROR');
        } catch (\Throwable $e) {
            $add('cache', 'Cache', false, $e->getMessage(), 'ERROR');
        }

        $storageWritable = is_dir(storage_path()) && is_writable(storage_path());
        $add(
            'storage',
            'Storage',
            $storageWritable,
            $storageWritable ? 'Storage accessible en écriture' : 'Storage non accessible en écriture',
            'ERROR'
        );

        $logDir = storage_path('logs');
        $logsWritable = is_dir($logDir) && is_writable($logDir);
        $add(
            'logs',
            'Logs',
            $logsWritable,
            $logsWritable ? 'Répertoire des logs accessible' : 'Répertoire des logs non accessible',
            'ERROR'
        );

        $freeGb = null;
        $minFree = (float) config('system_admin.health.disk_min_free_gb', 5);

        try {
            $free = @disk_free_space(base_path());
            if ($free !== false) {
                $freeGb = round($free / 1024 / 1024 / 1024, 2);
            }
        } catch (\Throwable $e) {
            $freeGb = null;
        }

        $diskOk = $freeGb === null ? false : $freeGb >= $minFree;
        $add(
            'disk',
            'Espace disque',
            $diskOk,
            $freeGb === null
                ? 'Impossible de lire l’espace disque libre.'
                : "Espace libre: {$freeGb} Go",
            $diskOk ? 'WARNING' : 'CRITICAL',
            ['free_gb' => $freeGb, 'min_free_gb' => $minFree]
        );

        $queueDriver = (string) config('queue.default');
        $failedJobsCount = Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : 0;
        $queueOk = $failedJobsCount < 10;

        $add(
            'queue',
            'Files d’attente',
            $queueOk,
            "Driver: {$queueDriver} / Échecs: {$failedJobsCount}",
            $queueOk ? 'WARNING' : 'ERROR',
            ['driver' => $queueDriver, 'failed_jobs' => $failedJobsCount]
        );

        $debug = (bool) config('app.debug');
        $env = (string) app()->environment();
        $debugOk = !($env === 'production' && $debug);

        $add(
            'app_debug',
            'Configuration debug',
            $debugOk,
            "ENV={$env} / DEBUG=" . ($debug ? 'ON' : 'OFF'),
            'WARNING',
            ['env' => $env, 'debug' => $debug]
        );

        $result = [
            'generated_at' => now()->toDateTimeString(),
            'maintenance' => app()->isDownForMaintenance(),
            'env' => $env,
            'debug' => $debug,
            'queue_driver' => $queueDriver,
            'components' => $components,
            'ok_count' => collect($components)->where('ok', true)->count(),
            'fail_count' => collect($components)->where('ok', false)->count(),
        ];

        if ($syncAlerts) {
            $this->syncAlerts($result);
        }

        return $result;
    }

    public function syncAlerts(?array $health = null, ?int $userId = null): void
    {
        $health ??= $this->check(false);

        foreach ($health['components'] as $component) {
            $code = 'HEALTH_' . strtoupper($component['key']);

            $existing = SystemAlert::query()->where('code', $code)->first();

            if (!$component['ok']) {
                if (!$existing) {
                    SystemAlert::create([
                        'code' => $code,
                        'title' => 'Anomalie santé: ' . $component['label'],
                        'level' => strtoupper($component['level']),
                        'component' => $component['key'],
                        'message' => $component['message'],
                        'status' => 'OPEN',
                        'first_seen_at' => now(),
                        'last_seen_at' => now(),
                        'meta' => $component['meta'] ?? [],
                    ]);
                } else {
                    $existing->update([
                        'title' => 'Anomalie santé: ' . $component['label'],
                        'level' => strtoupper($component['level']),
                        'message' => $component['message'],
                        'status' => 'OPEN',
                        'last_seen_at' => now(),
                        'resolved_at' => null,
                        'resolved_by' => null,
                        'meta' => $component['meta'] ?? [],
                    ]);
                }
            } elseif ($existing && $existing->status === 'OPEN') {
                $existing->update([
                    'status' => 'RESOLVED',
                    'resolved_at' => now(),
                    'resolved_by' => $userId,
                    'last_seen_at' => now(),
                ]);
            }
        }

        $this->auditService->log(
            'SYSTEM_HEALTH',
            'SYNC_ALERTS',
            'Synchronisation des alertes santé exécutée.',
            ['generated_at' => $health['generated_at'], 'fail_count' => $health['fail_count']],
            'INFO',
            $userId
        );
    }
}