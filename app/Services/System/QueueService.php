<?php

namespace App\Services\System;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QueueService
{
    public function __construct(
        protected AuditService $auditService
    ) {
    }

    public function summary(): array
    {
        $driver = (string) config('queue.default');

        $waiting = Schema::hasTable('jobs') ? DB::table('jobs')->count() : null;
        $failed = Schema::hasTable('failed_jobs') ? DB::table('failed_jobs')->count() : null;

        return [
            'driver' => $driver,
            'waiting_count' => $waiting,
            'failed_count' => $failed,
            'database_jobs_available' => Schema::hasTable('jobs'),
            'failed_jobs_available' => Schema::hasTable('failed_jobs'),
        ];
    }

    public function recentJobs(int $limit = 20): Collection
    {
        if (!Schema::hasTable('jobs')) {
            return collect();
        }

        return DB::table('jobs')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function failedJobs(int $limit = 20): Collection
    {
        if (!Schema::hasTable('failed_jobs')) {
            return collect();
        }

        return DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->limit($limit)
            ->get();
    }

    public function retryFailed(string $jobId, ?int $userId = null): void
    {
        Artisan::call('queue:retry', ['id' => [$jobId]]);

        $this->auditService->log(
            'SYSTEM_QUEUE',
            'RETRY_FAILED',
            'Relance d’un job échoué.',
            ['job_id' => $jobId, 'output' => Artisan::output()],
            'INFO',
            $userId
        );
    }

    public function retryAllFailed(?int $userId = null): void
    {
        Artisan::call('queue:retry', ['id' => ['all']]);

        $this->auditService->log(
            'SYSTEM_QUEUE',
            'RETRY_ALL_FAILED',
            'Relance de tous les jobs échoués.',
            ['output' => Artisan::output()],
            'WARNING',
            $userId
        );
    }

    public function flushFailed(?int $userId = null): void
    {
        Artisan::call('queue:flush');

        $this->auditService->log(
            'SYSTEM_QUEUE',
            'FLUSH_FAILED',
            'Purge des jobs échoués.',
            ['output' => Artisan::output()],
            'WARNING',
            $userId
        );
    }
}