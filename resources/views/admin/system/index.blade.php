@extends('layouts.app')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Administration système</h1>
            <div class="text-muted">Sauvegardes, restauration, maintenance, logs et santé application</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{!! nl2br(e(session('success'))) !!}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{!! nl2br(e(session('error'))) !!}</div>
    @endif

    <div class="row g-4">
        {{-- Sauvegardes --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Sauvegardes</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.system.backup') }}" class="mb-3">
                        @csrf
                        <button class="btn btn-primary">
                            <i class="fa fa-database me-1"></i> Créer une sauvegarde
                        </button>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Fichier</th>
                                    <th>Taille</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($backups as $file)
                                <tr>
                                    <td>{{ $file->getFilename() }}</td>
                                    <td>{{ number_format($file->getSize() / 1024, 1, ',', ' ') }} Ko</td>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($file->getMTime())->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted">Aucune sauvegarde</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Restauration --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100 border-warning">
                <div class="card-header fw-bold text-warning">Restauration</div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        Action sensible. Active d’abord le mode maintenance.
                    </div>

                    <form method="POST" action="{{ route('admin.system.restore') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Sauvegarde à restaurer</label>
                            <select name="file" class="form-select" required>
                                <option value="">Choisir...</option>
                                @foreach($backups as $file)
                                    <option value="{{ $file->getFilename() }}">{{ $file->getFilename() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button class="btn btn-warning"
                                onclick="return confirm('Confirmer la restauration ?')">
                            <i class="fa fa-rotate-left me-1"></i> Restaurer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Maintenance --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Maintenance</div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="badge {{ $health['maintenance'] ? 'bg-warning text-dark' : 'bg-success' }}">
                            {{ $health['maintenance'] ? 'Maintenance active' : 'Application active' }}
                        </span>
                    </div>

                    <div class="d-flex gap-2">
                        <form method="POST" action="{{ route('admin.system.maintenance.on') }}">
                            @csrf
                            <button class="btn btn-outline-warning"
                                    onclick="return confirm('Activer le mode maintenance ?')">
                                Activer
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.system.maintenance.off') }}">
                            @csrf
                            <button class="btn btn-outline-success"
                                    onclick="return confirm('Désactiver le mode maintenance ?')">
                                Désactiver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Santé application --}}
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-header fw-bold">Santé application</div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Base de données</span>
                            <span class="badge {{ $health['database'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $health['database'] ? 'OK' : 'KO' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Cache</span>
                            <span class="badge {{ $health['cache'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $health['cache'] ? 'OK' : 'KO' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Storage écrivable</span>
                            <span class="badge {{ $health['storage_writable'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $health['storage_writable'] ? 'OK' : 'KO' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Logs écrivable</span>
                            <span class="badge {{ $health['logs_writable'] ? 'bg-success' : 'bg-danger' }}">
                                {{ $health['logs_writable'] ? 'OK' : 'KO' }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Queue driver</span>
                            <span>{{ $health['queue_driver'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Espace disque libre</span>
                            <span>{{ $health['disk_free_gb'] ?? 'N/A' }} Go</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Environnement</span>
                            <span>{{ $health['app_env'] }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Journal système --}}
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Journal système</div>
                <div class="card-body">
                    <div class="bg-dark text-light rounded p-3" style="max-height: 420px; overflow:auto; font-family: monospace; font-size: 13px;">
                        @forelse($logs as $line)
                            <div>{{ $line }}</div>
                        @empty
                            <div class="text-muted">Aucun log disponible.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection