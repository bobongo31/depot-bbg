@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Maintenance</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">État actuel</div>
                <div class="card-body">
                    <p>
                        Statut :
                        <span class="badge text-bg-{{ app()->isDownForMaintenance() ? 'warning' : 'success' }}">
                            {{ app()->isDownForMaintenance() ? 'Maintenance active' : 'Application active' }}
                        </span>
                    </p>

                    @if($maintenanceMeta)
                        <div class="small text-muted mb-3">
                            Activée le {{ \Carbon\Carbon::parse($maintenanceMeta['enabled_at'])->format('d/m/Y H:i') }}
                            par {{ $maintenanceMeta['enabled_by_name'] ?? '—' }}
                            @if(!empty($maintenanceMeta['reason']))
                                <br>Motif : {{ $maintenanceMeta['reason'] }}
                            @endif
                            @if(!empty($maintenanceMeta['secret']))
                                <br>Secret bypass : <code>{{ $maintenanceMeta['secret'] }}</code>
                            @endif
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.system.maintenance.enable') }}" class="mb-3">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Motif</label>
                            <input type="text" name="reason" class="form-control" placeholder="Maintenance applicative / DB / déploiement...">
                        </div>
                        <button class="btn btn-warning" onclick="return confirm('Activer le mode maintenance ?')">
                            Activer maintenance
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.system.maintenance.disable') }}">
                        @csrf
                        <button class="btn btn-success" onclick="return confirm('Désactiver le mode maintenance ?')">
                            Désactiver maintenance
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Historique maintenance</div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Action</th>
                                <th>Niveau</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentAudits as $audit)
                                <tr>
                                    <td>{{ $audit->created_at?->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $audit->action }}</td>
                                    <td>{{ $audit->level }}</td>
                                    <td>{{ $audit->message }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center py-4">Aucune trace maintenance.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection