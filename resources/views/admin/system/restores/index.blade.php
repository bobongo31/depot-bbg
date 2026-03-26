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

                <div class="mb-3">
                    <label class="form-label">Type de restauration</label>
                    <select name="restore_type" class="form-select">
                        <option value="auto">Détection automatique (recommandé)</option>
                        <option value="db">Base de données (SQL)</option>
                        <option value="storage">Fichiers (storage/app)</option>
                    </select>
                    <div class="form-text">Choisir "Fichiers" pour forcer la restauration des archives ZIP.</div>
                </div>

                <div id="storage-warning" class="alert alert-danger d-none">
                    <strong>Attention — restauration fichiers</strong>
                    <div>
                        La restauration des fichiers va extraire une archive et peut écraser des fichiers existants dans <code>storage/app</code>.
                        Assurez-vous d'avoir une sauvegarde récente. Cette action est irréversible.
                    </div>
                </div>

                <div id="storage-confirm" class="mb-3 d-none">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="confirm_overwrite" id="confirm_overwrite" value="1">
                        <label class="form-check-label" for="confirm_overwrite">Je confirme vouloir écraser les fichiers existants dans <code>storage/app</code></label>
                    </div>
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

@push('scripts')
<script>
    (function(){
        const select = document.querySelector('select[name="restore_type"]');
        const warn = document.getElementById('storage-warning');
        const confirmBox = document.getElementById('storage-confirm');
        if (!select) return;

        function update() {
            const v = select.value;
            if (v === 'storage') {
                warn.classList.remove('d-none');
                confirmBox.classList.remove('d-none');
            } else {
                warn.classList.add('d-none');
                confirmBox.classList.add('d-none');
                const cb = document.getElementById('confirm_overwrite');
                if (cb) cb.checked = false;
            }
        }

        select.addEventListener('change', update);
        update();
    })();
</script>
</div>
@endpush
</div>
@endsection