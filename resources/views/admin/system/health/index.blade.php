@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Santé application</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex flex-wrap gap-3">
            <div><strong>ENV</strong> : {{ $health['env'] }}</div>
            <div><strong>DEBUG</strong> : {{ $health['debug'] ? 'ON' : 'OFF' }}</div>
            <div><strong>QUEUE</strong> : {{ $health['queue_driver'] }}</div>
            <div><strong>Maintenance</strong> : {{ $health['maintenance'] ? 'OUI' : 'NON' }}</div>
            <div><strong>Contrôle</strong> : {{ $health['generated_at'] }}</div>

            <form method="POST" action="{{ route('admin.system.health.sync-alerts') }}">
                @csrf
                <button class="btn btn-sm btn-outline-primary">Synchroniser alertes</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Composants</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Composant</th>
                        <th>Statut</th>
                        <th>Niveau</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($health['components'] as $item)
                        <tr>
                            <td>{{ $item['label'] }}</td>
                            <td>
                                <span class="badge text-bg-{{ $item['ok'] ? 'success' : 'danger' }}">
                                    {{ $item['ok'] ? 'OK' : 'KO' }}
                                </span>
                            </td>
                            <td>{{ $item['level'] }}</td>
                            <td>{{ $item['message'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Alertes récentes</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Niveau</th>
                        <th>Composant</th>
                        <th>Statut</th>
                        <th>Dernière vue</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $alert)
                        <tr>
                            <td>{{ $alert->code }}</td>
                            <td>{{ $alert->level }}</td>
                            <td>{{ $alert->component }}</td>
                            <td>{{ $alert->status }}</td>
                            <td>{{ $alert->last_seen_at?->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted text-center py-4">Aucune alerte.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection