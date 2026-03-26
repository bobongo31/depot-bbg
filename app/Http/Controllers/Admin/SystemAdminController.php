<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemAlert;
use App\Models\SystemAudit;
use App\Models\SystemBackup;
use App\Models\SystemIncident;
use App\Services\System\AuditService;
use App\Services\System\BackupService;
use App\Services\System\HealthService;
use App\Services\System\QueueService;
use App\Services\System\ScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

class SystemAdminController extends Controller
{
    public function __construct(
        protected BackupService $backupService,
        protected HealthService $healthService,
        protected AuditService $auditService,
        protected QueueService $queueService,
        protected ScheduleService $scheduleService,
    ) {
    }

    public function dashboard(): View
    {
        $health = $this->healthService->check(false);
        $queue = $this->queueService->summary();
        $latestBackups = SystemBackup::query()->latest()->take(5)->get();
        $openAlerts = SystemAlert::query()->where('status', 'OPEN')->latest('last_seen_at')->take(5)->get();
        $openIncidents = SystemIncident::query()->whereIn('status', ['OPEN', 'IN_PROGRESS', 'RESOLVED'])->latest('opened_at')->take(5)->get();
        $recentAudits = SystemAudit::query()->latest()->take(10)->get();
        $tasks = $this->scheduleService->listTasks();

        return view('admin.system.dashboard', compact(
            'health',
            'queue',
            'latestBackups',
            'openAlerts',
            'openIncidents',
            'recentAudits',
            'tasks'
        ));
    }

    public function backupsIndex(): View
    {
        $backups = SystemBackup::query()->latest()->paginate(20);

        return view('admin.system.backups.index', compact('backups'));
    }

    public function backupStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'scope' => ['nullable', 'string', 'in:db,storage'],
            'copy' => ['nullable'],
        ]);

        $scope = $validated['scope'] ?? config('system_admin.backup.default_scope', 'db');

        // If 'copy' input is present, treat it as explicit request to copy to targets.
        // HTML checkbox will only be present when checked; if absent, we pass null to use config default.
        $allowCopy = $request->has('copy') ? true : null;

        try {
            $this->backupService->createBackup('manual', auth()->id(), 'Sauvegarde manuelle via interface', $scope, $allowCopy);
            return back()->with('success', 'Sauvegarde créée avec succès.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function backupDownload(SystemBackup $backup)
    {
        $path = \Storage::disk($backup->disk)->path($backup->path);

        abort_unless(is_file($path), 404, 'Fichier introuvable.');

        $this->auditService->log(
            'SYSTEM_BACKUP',
            'DOWNLOAD',
            'Téléchargement d’une sauvegarde.',
            ['backup_id' => $backup->id, 'filename' => $backup->filename],
            'INFO',
            auth()->id()
        );

        return response()->download($path, $backup->filename);
    }

    public function backupDestroy(SystemBackup $backup): RedirectResponse
    {
        try {
            $this->backupService->deleteBackup($backup, auth()->id());
            return back()->with('success', 'Sauvegarde supprimée.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function restoresIndex(): View
    {
        $backups = SystemBackup::query()
            ->where('status', 'SUCCESS')
            ->latest()
            ->get();

        $restoreAudits = SystemAudit::query()
            ->where('module', 'SYSTEM_RESTORE')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.system.restores.index', compact('backups', 'restoreAudits'));
    }

    public function restoreStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'backup_id' => ['required', 'exists:system_backups,id'],
            'restore_type' => ['nullable', 'string', 'in:auto,db,storage'],
            'confirm_overwrite' => ['nullable'],
        ]);

        $backup = SystemBackup::findOrFail($validated['backup_id']);

        // If user requested storage restore but has not yet confirmed overwrite,
        // render a preview page listing files that would be overwritten.
        $restoreType = $validated['restore_type'] ?? 'auto';
        $force = null;
        if ($restoreType === 'storage') {
            // If it's a storage restore and user didn't confirm, show preview
            if (!$request->has('confirm_overwrite')) {
                try {
                    $files = $this->backupService->listArchiveContents($backup);
                } catch (\Throwable $e) {
                    return back()->with('error', $e->getMessage());
                }

                return view('admin.system.restores.preview', compact('backup', 'files'));
            }

            $force = 'storage';
        } elseif ($restoreType === 'db') {
            $force = 'db';
        }

        try {
            $this->backupService->restoreBackup($backup, auth()->id(), $force);

            return back()->with('success', 'Restauration terminée avec succès.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function maintenanceIndex(): View
    {
        $maintenanceMeta = Cache::get('system:maintenance_meta');
        $recentAudits = SystemAudit::query()
            ->where('module', 'SYSTEM_MAINTENANCE')
            ->latest()
            ->take(20)
            ->get();

        return view('admin.system.maintenance.index', compact('maintenanceMeta', 'recentAudits'));
    }

    public function maintenanceEnable(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $secret = (string) Str::uuid();

        Artisan::call('down', [
            '--refresh' => 15,
            '--retry' => 60,
            '--secret' => $secret,
        ]);

        Cache::put('system:maintenance_meta', [
            'enabled_at' => now()->toDateTimeString(),
            'enabled_by' => auth()->id(),
            'enabled_by_name' => auth()->user()?->name,
            'reason' => $validated['reason'] ?? null,
            'secret' => $secret,
        ], now()->addDays(7));

        $this->auditService->log(
            'SYSTEM_MAINTENANCE',
            'ENABLE',
            'Mode maintenance activé.',
            ['reason' => $validated['reason'] ?? null, 'secret' => $secret],
            'WARNING',
            auth()->id()
        );

        return back()->with('success', 'Mode maintenance activé. Secret bypass: ' . $secret);
    }

    public function maintenanceDisable(): RedirectResponse
    {
        Artisan::call('up');
        Cache::forget('system:maintenance_meta');

        $this->auditService->log(
            'SYSTEM_MAINTENANCE',
            'DISABLE',
            'Mode maintenance désactivé.',
            [],
            'INFO',
            auth()->id()
        );

        return back()->with('success', 'Mode maintenance désactivé.');
    }

    public function healthIndex(): View
    {
        $health = $this->healthService->check(false);
        $alerts = SystemAlert::query()->latest('last_seen_at')->take(20)->get();

        return view('admin.system.health.index', compact('health', 'alerts'));
    }

    public function healthSyncAlerts(): RedirectResponse
    {
        try {
            $health = $this->healthService->check(false);
            $this->healthService->syncAlerts($health, auth()->id());

            return back()->with('success', 'Alertes santé synchronisées.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function logsIndex(Request $request): View
    {
        $query = SystemAudit::query()->latest();

        if ($request->filled('level')) {
            $query->where('level', strtoupper((string) $request->level));
        }

        if ($request->filled('module')) {
            $query->where('module', 'like', '%' . $request->module . '%');
        }

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $needle = '%' . $request->q . '%';
                $q->where('message', 'like', $needle)
                  ->orWhere('action', 'like', $needle)
                  ->orWhere('user_name', 'like', $needle);
            });
        }

        $audits = $query->paginate(25)->withQueryString();
        $technicalLogLines = $this->auditService->tailLaravelLog(200);

        return view('admin.system.logs.index', compact('audits', 'technicalLogLines'));
    }

    /**
     * Return the latest Laravel log lines as JSON for live tailing.
     */
    public function logsTail(Request $request)
    {
        $lines = $this->auditService->tailLaravelLog(200);

        return response()->json(['lines' => $lines]);
    }

    public function schedulesIndex(): View
    {
        $tasks = $this->scheduleService->listTasks();

        return view('admin.system.schedules.index', compact('tasks'));
    }

    public function schedulesRun(string $taskKey): RedirectResponse
    {
        try {
            $this->scheduleService->runTask($taskKey, auth()->id());

            return back()->with('success', 'Tâche exécutée avec succès.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function queuesIndex(): View
    {
        $summary = $this->queueService->summary();
        $recentJobs = $this->queueService->recentJobs(25);
        $failedJobs = $this->queueService->failedJobs(25);

        return view('admin.system.queues.index', compact('summary', 'recentJobs', 'failedJobs'));
    }

    public function queueRetry(string $jobId): RedirectResponse
    {
        try {
            $this->queueService->retryFailed($jobId, auth()->id());

            return back()->with('success', 'Job relancé.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function queueRetryAll(): RedirectResponse
    {
        try {
            $this->queueService->retryAllFailed(auth()->id());

            return back()->with('success', 'Tous les jobs échoués ont été relancés.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function queueFlushFailed(): RedirectResponse
    {
        try {
            $this->queueService->flushFailed(auth()->id());

            return back()->with('success', 'Liste des jobs échoués purgée.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function alertsIndex(): View
    {
        $alerts = SystemAlert::query()->latest('last_seen_at')->paginate(20);
        $incidents = SystemIncident::query()->latest('opened_at')->paginate(20);

        return view('admin.system.alerts.index', compact('alerts', 'incidents'));
    }

    public function alertResolve(SystemAlert $alert): RedirectResponse
    {
        $alert->update([
            'status' => 'RESOLVED',
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
        ]);

        $this->auditService->log(
            'SYSTEM_ALERT',
            'RESOLVE',
            'Alerte résolue manuellement.',
            ['alert_id' => $alert->id, 'code' => $alert->code],
            'INFO',
            auth()->id()
        );

        return back()->with('success', 'Alerte marquée comme résolue.');
    }

    public function incidentStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:20'],
            'source' => ['nullable', 'string', 'max:100'],
            'impact' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $incident = SystemIncident::create([
            'code' => 'INC-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
            'title' => $validated['title'],
            'level' => strtoupper($validated['level']),
            'source' => $validated['source'] ?? null,
            'impact' => $validated['impact'] ?? null,
            'status' => 'OPEN',
            'opened_at' => now(),
            'notes' => $validated['notes'] ?? null,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $this->auditService->log(
            'SYSTEM_INCIDENT',
            'CREATE',
            'Incident créé.',
            ['incident_id' => $incident->id, 'code' => $incident->code],
            'WARNING',
            auth()->id()
        );

        return back()->with('success', 'Incident créé.');
    }

    public function incidentUpdate(Request $request, SystemIncident $incident): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:20'],
            'source' => ['nullable', 'string', 'max:100'],
            'impact' => ['nullable', 'string'],
            'status' => ['required', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ]);

        $incident->update([
            'title' => $validated['title'],
            'level' => strtoupper($validated['level']),
            'source' => $validated['source'] ?? null,
            'impact' => $validated['impact'] ?? null,
            'status' => strtoupper($validated['status']),
            'notes' => $validated['notes'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        $this->auditService->log(
            'SYSTEM_INCIDENT',
            'UPDATE',
            'Incident mis à jour.',
            ['incident_id' => $incident->id, 'code' => $incident->code],
            'INFO',
            auth()->id()
        );

        return back()->with('success', 'Incident mis à jour.');
    }

    public function incidentClose(SystemIncident $incident): RedirectResponse
    {
        $incident->update([
            'status' => 'CLOSED',
            'closed_at' => now(),
            'resolved_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        $this->auditService->log(
            'SYSTEM_INCIDENT',
            'CLOSE',
            'Incident clôturé.',
            ['incident_id' => $incident->id, 'code' => $incident->code],
            'INFO',
            auth()->id()
        );

        return back()->with('success', 'Incident clôturé.');
    }

    public function toolsIndex(): View
    {
        return view('admin.system.tools.index');
    }

    public function toolsRun(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tool' => ['required', 'string'],
        ]);

        $tool = $validated['tool'];

        try {
            match ($tool) {
                'cache_clear' => Artisan::call('cache:clear'),
                'config_clear' => Artisan::call('config:clear'),
                'route_clear' => Artisan::call('route:clear'),
                'view_clear' => Artisan::call('view:clear'),
                'optimize_clear' => Artisan::call('optimize:clear'),
                'queue_restart' => Artisan::call('queue:restart'),
                'health_sync' => $this->healthService->syncAlerts($this->healthService->check(false), auth()->id()),
                default => throw new \RuntimeException('Outil inconnu.'),
            };

            $this->auditService->log(
                'SYSTEM_TOOLS',
                'RUN',
                'Outil rapide exécuté.',
                ['tool' => $tool, 'output' => Artisan::output()],
                'INFO',
                auth()->id()
            );

            return back()->with('success', 'Outil exécuté avec succès.');
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}