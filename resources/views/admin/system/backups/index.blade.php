@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Sauvegardes</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.system.backups.store') }}">
                @csrf
                <div class="row g-2 align-items-center">
                    <div class="col-auto">
                        <select name="scope" class="form-select">
                            <option value="db">Base de données</option>
                            <option value="storage">Fichiers (storage/app)</option>
                        </select>
                    </div>

                    <div class="col-auto form-check">
                        <input class="form-check-input" type="checkbox" name="copy" id="backup-copy" value="1" checked>
                        <label class="form-check-label" for="backup-copy">Copier vers Documents/Bureau</label>
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-primary">Créer une sauvegarde</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Fichier</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Taille</th>
                        <th>Durée</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                        <tr>
                            <td>#{{ $backup->id }}</td>
                            <td>{{ $backup->filename }}</td>
                            <td>{{ $backup->type }}</td>
                            <td>{{ $backup->status }}</td>
                            <td>{{ $backup->size_human }}</td>
                            <td>{{ $backup->duration_ms ? $backup->duration_ms . ' ms' : '—' }}</td>
                            <td>{{ $backup->created_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.system.backups.download', $backup) }}"
                                   class="btn btn-sm btn-outline-primary">Télécharger</a>

                                <form method="POST"
                                      action="{{ route('admin.system.backups.destroy', $backup) }}"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer cette sauvegarde ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-muted text-center py-4">Aucune sauvegarde.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $backups->links() }}</div>
    </div>
</div>
@endsection