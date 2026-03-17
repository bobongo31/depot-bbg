@php
$items = [
    ['label' => 'Tableau de bord', 'route' => 'admin.system.dashboard', 'active' => 'admin.system.dashboard'],
    ['label' => 'Sauvegardes', 'route' => 'admin.system.backups.index', 'active' => 'admin.system.backups.*'],
    ['label' => 'Restaurations', 'route' => 'admin.system.restores.index', 'active' => 'admin.system.restores.*'],
    ['label' => 'Maintenance', 'route' => 'admin.system.maintenance.index', 'active' => 'admin.system.maintenance.*'],
    ['label' => 'Santé application', 'route' => 'admin.system.health.index', 'active' => 'admin.system.health.*'],
    ['label' => 'Journaux & audit', 'route' => 'admin.system.logs.index', 'active' => 'admin.system.logs.*'],
    ['label' => 'Tâches planifiées', 'route' => 'admin.system.schedules.index', 'active' => 'admin.system.schedules.*'],
    ['label' => 'Files d’attente', 'route' => 'admin.system.queues.index', 'active' => 'admin.system.queues.*'],
    ['label' => 'Alertes & incidents', 'route' => 'admin.system.alerts.index', 'active' => 'admin.system.alerts.*'],
    ['label' => 'Outils rapides', 'route' => 'admin.system.tools.index', 'active' => 'admin.system.tools.*'],
];
@endphp

<div class="card shadow-sm mb-4">
    <div class="card-body p-2">
        <div class="d-flex flex-wrap gap-2">
            @foreach($items as $item)
                <a href="{{ route($item['route']) }}"
                   class="btn {{ request()->routeIs($item['active']) ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>