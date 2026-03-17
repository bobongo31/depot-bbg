<?php

namespace App\Services\System;

use App\Models\SystemAudit;
use Illuminate\Support\Facades\Log;

class AuditService
{
    public function log(
        string $module,
        string $action,
        string $message,
        array $context = [],
        string $level = 'INFO',
        ?int $userId = null,
        ?string $userName = null
    ): SystemAudit {
        $level = strtoupper($level);

        $resolvedUser = auth()->user();
        $actualUserId = $userId ?? $resolvedUser?->id;
        $actualUserName = $userName ?? $resolvedUser?->name;

        $ip = app()->runningInConsole() ? null : request()->ip();
        $userAgent = app()->runningInConsole() ? 'artisan' : request()->userAgent();

        Log::log(strtolower($level), "[{$module}] {$action} - {$message}", $context);

        return SystemAudit::create([
            'module' => $module,
            'action' => $action,
            'level' => $level,
            'message' => $message,
            'context' => $context,
            'user_id' => $actualUserId,
            'user_name' => $actualUserName,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
        ]);
    }

    public function tailLaravelLog(int $lines = 200): array
    {
        $path = storage_path('logs/laravel.log');

        if (!is_file($path)) {
            return [];
        }

        $size = filesize($path) ?: 0;
        $readBytes = min($size, 1024 * 1024); // 1 Mo max
        $handle = fopen($path, 'rb');

        if (!$handle) {
            return [];
        }

        if ($size > $readBytes) {
            fseek($handle, -$readBytes, SEEK_END);
        }

        $content = stream_get_contents($handle) ?: '';
        fclose($handle);

        $rows = preg_split('/\r\n|\r|\n/', $content) ?: [];

        return collect($rows)
            ->filter(fn ($line) => trim($line) !== '')
            ->take(-$lines)
            ->values()
            ->all();
    }
}