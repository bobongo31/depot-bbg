@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Tâches planifiées</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Clé</th>
                        <th>Libellé</th>
                        <th>Commande</th>
                        <th>Fréquence</th>
                        <th>Maintenance</th>
                        <th>Dernière exécution</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                        <tr>
                            <td>{{ $task['key'] }}</td>
                            <td>{{ $task['label'] }}</td>
                            <td>
                                <code>{{ $task['command'] }}</code>
                            </td>
                            <td>{{ $task['frequency'] }}</td>
                            <td>{{ $task['even_in_maintenance'] ? 'Oui' : 'Non' }}</td>
                            <td>
                                @if($task['last_run'])
                                    {{ $task['last_run']->started_at?->format('d/m/Y H:i:s') }}
                                    <span class="badge text-bg-{{ $task['last_run']->status === 'SUCCESS' ? 'success' : 'danger' }}">
                                        {{ $task['last_run']->status }}
                                    </span>
                                @else
                                    Jamais
                                @endif
                            </td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.system.schedules.run', $task['key']) }}"
                                      onsubmit="return confirm('Exécuter cette tâche ?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary" {{ !$task['enabled'] ? 'disabled' : '' }}>
                                        Exécuter maintenant
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted text-center py-4">Aucune tâche configurée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection