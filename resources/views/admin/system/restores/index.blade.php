@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Restaurations</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="alert alert-warning">
        La restauration est une opération sensible. Elle exige le mode maintenance et l’activation explicite de
        <code>ADMIN_RESTORE_ENABLED=true</code> dans l’environnement.
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Lancer une restauration</div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.system.restores.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Sauvegarde à restaurer</label>
                    <select name="backup_id" class="form-select" required>
                        <option value="">Choisir...</option>
                        @foreach($backups as $backup)
                            <option value="{{ $backup->id }}">
                                #{{ $backup->id }} — {{ $backup->filename }} — {{ $backup->created_at?->format('d/m/Y H:i') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button class="btn btn-warning" onclick="return confirm('Confirmer la restauration ?')">
                    Restaurer
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Historique restauration</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Niveau</th>
                        <th>Message</th>
                        <th>Utilisateur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restoreAudits as $audit)
                        <tr>
                            <td>{{ $audit->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $audit->action }}</td>
                            <td>{{ $audit->level }}</td>
                            <td>{{ $audit->message }}</td>
                            <td>{{ $audit->user_name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted text-center py-4">Aucune restauration tracée.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection