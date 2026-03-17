@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Administration / Exploitation système</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Environnement</div>
                    <div class="fs-5 fw-bold">{{ $health['env'] }}</div>
                    <div class="small">Debug: {{ $health['debug'] ? 'ON' : 'OFF' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Santé</div>
                    <div class="fs-5 fw-bold">{{ $health['ok_count'] }} OK / {{ $health['fail_count'] }} KO</div>
                    <div class="small">Maintenance: {{ $health['maintenance'] ? 'OUI' : 'NON' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Files d’attente</div>
                    <div class="fs-5 fw-bold">{{ $queue['driver'] }}</div>
                    <div class="small">En attente: {{ $queue['waiting_count'] ?? 'N/A' }} / Échoués: {{ $queue['failed_count'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <div class="text-muted small">Alertes / Incidents</div>
                    <div class="fs-5 fw-bold">{{ $openAlerts->count() }} / {{ $openIncidents->count() }}</div>
                    <div class="small">Ouverts actuellement</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Actions rapides</div>
        <div class="card-body d-flex flex-wrap gap-2">
            <form method="POST" action="{{ route('admin.system.backups.store') }}">
                @csrf
                <button class="btn btn-primary btn-sm">Créer une sauvegarde</button>
            </form>

            <form method="POST" action="{{ route('admin.system.health.sync-alerts') }}">
                @csrf
                <button class="btn btn-outline-primary btn-sm">Synchroniser alertes santé</button>
            </form>

            <a href="{{ route('admin.system.tools.index') }}" class="btn btn-outline-secondary btn-sm">Outils rapides</a>
            <a href="{{ route('admin.system.logs.index') }}" class="btn btn-outline-dark btn-sm">Voir journaux</a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Dernières sauvegardes</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Fichier</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestBackups as $item)
                                <tr>
                                    <td>{{ $item->filename }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>{{ $item->status }}</td>
                                    <td>{{ $item->created_at?->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">Aucune sauvegarde.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Alertes ouvertes</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Niveau</th>
                                <th>Composant</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($openAlerts as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->level }}</td>
                                    <td>{{ $item->component }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($item->message, 60) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">Aucune alerte ouverte.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Incidents ouverts</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Titre</th>
                                <th>Niveau</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($openIncidents as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->level }}</td>
                                    <td>{{ $item->status }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted">Aucun incident actif.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Tâches planifiées</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Libellé</th>
                                <th>Fréquence</th>
                                <th>Dernière exécution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>{{ $task['label'] }}</td>
                                    <td>{{ $task['frequency'] }}</td>
                                    <td>
                                        {{ $task['last_run']?->started_at?->format('d/m/Y H:i') ?? 'Jamais' }}
                                        @if($task['last_run'])
                                            <span class="badge text-bg-{{ $task['last_run']->status === 'SUCCESS' ? 'success' : 'danger' }}">
                                                {{ $task['last_run']->status }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted">Aucune tâche configurée.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Derniers audits</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Module</th>
                                <th>Action</th>
                                <th>Niveau</th>
                                <th>Message</th>
                                <th>Utilisateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAudits as $audit)
                                <tr>
                                    <td>{{ $audit->created_at?->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $audit->module }}</td>
                                    <td>{{ $audit->action }}</td>
                                    <td>{{ $audit->level }}</td>
                                    <td>{{ $audit->message }}</td>
                                    <td>{{ $audit->user_name ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-muted">Aucun audit.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection