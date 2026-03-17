<?php

namespace App\Services\System;

use App\Models\SystemTaskRun;
use Illuminate\Support\Facades\Artisan;

class ScheduleService
{
    public function __construct(
        protected AuditService $auditService
    ) {
    }

    public function listTasks(): array
    {
        $configTasks = config('system_admin.tasks', []);

        $latestRuns = SystemTaskRun::query()
            ->orderByDesc('started_at')
            ->get()
            ->groupBy('task_key')
            ->map(fn ($items) => $items->first());

        $result = [];

        foreach ($configTasks as $key => $task) {
            $run = $latestRuns->get($key);

            $result[] = [
                'key' => $key,
                'label' => $task['label'] ?? $key,
                'command' => $task['command'] ?? '',
                'arguments' => $task['arguments'] ?? [],
                'frequency' => $task['frequency'] ?? '—',
                'enabled' => (bool) ($task['enabled'] ?? true),
                'even_in_maintenance' => (bool) ($task['even_in_maintenance'] ?? false),
                'last_run' => $run,
            ];
        }

        return $result;
    }

    public function runTask(string $taskKey, ?int $userId = null): SystemTaskRun
    {
        $task = config("system_admin.tasks.{$taskKey}");

        if (!$task || !($task['enabled'] ?? false)) {
            throw new \RuntimeException('Tâche planifiée introuvable ou désactivée.');
        }

        $startedAt = now();
        $start = microtime(true);

        $run = SystemTaskRun::create([
            'task_key' => $taskKey,
            'label' => $task['label'],
            'command' => $task['command'],
            'status' => 'SUCCESS',
            'started_at' => $startedAt,
            'triggered_by' => $userId,
        ]);

        try {
            Artisan::call($task['command'], $task['arguments'] ?? []);

            $run->update([
                'status' => 'SUCCESS',
                'output' => Artisan::output(),
                'finished_at' => now(),
                'duration_ms' => (int) round((microtime(true) - $start) * 1000),
            ]);

            $this->auditService->log(
                'SYSTEM_SCHEDULE',
                'RUN_TASK',
                'Exécution manuelle d’une tâche planifiée.',
                ['task_key' => $taskKey, 'output' => Artisan::output()],
                'INFO',
                $userId
            );
        } catch (\Throwable $e) {
            $run->update([
                'status' => 'FAILED',
                'output' => $e->getMessage(),
                'finished_at' => now(),
                'duration_ms' => (int) round((microtime(true) - $start) * 1000),
            ]);

            $this->auditService->log(
                'SYSTEM_SCHEDULE',
                'RUN_TASK_FAILED',
                'Échec d’exécution d’une tâche planifiée.',
                ['task_key' => $taskKey, 'error' => $e->getMessage()],
                'ERROR',
                $userId
            );

            throw $e;
        }

        return $run->fresh();
    }
}