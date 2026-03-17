@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Alertes & incidents</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header fw-bold">Créer un incident</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.system.incidents.store') }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Titre</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Niveau</label>
                            <select name="level" class="form-select" required>
                                <option value="MINEUR">MINEUR</option>
                                <option value="MAJEUR" selected>MAJEUR</option>
                                <option value="CRITIQUE">CRITIQUE</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Source</label>
                            <input type="text" name="source" class="form-control" placeholder="DB, APP, CACHE, QUEUE...">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Impact</label>
                            <textarea name="impact" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <button class="btn btn-primary">Créer incident</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header fw-bold">Alertes système</div>
                <div class="card-body p-0">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Niveau</th>
                                <th>Statut</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alerts as $alert)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $alert->code }}</div>
                                        <div class="small text-muted">{{ $alert->component }}</div>
                                    </td>
                                    <td>{{ $alert->level }}</td>
                                    <td>{{ $alert->status }}</td>
                                    <td class="text-end">
                                        @if($alert->status === 'OPEN')
                                            <form method="POST" action="{{ route('admin.system.alerts.resolve', $alert) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success">Résoudre</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted text-center py-4">Aucune alerte.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">{{ $alerts->links() }}</div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header fw-bold">Incidents</div>
                <div class="card-body">
                    @forelse($incidents as $incident)
                        <div class="border rounded p-3 mb-3">
                            <form method="POST" action="{{ route('admin.system.incidents.update', $incident) }}">
                                @csrf
                                @method('PUT')

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="fw-bold">{{ $incident->code }}</div>
                                    <div>
                                        <span class="badge text-bg-secondary">{{ $incident->status }}</span>
                                        <span class="badge text-bg-dark">{{ $incident->level }}</span>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Titre</label>
                                        <input type="text" name="title" class="form-control" value="{{ $incident->title }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Niveau</label>
                                        <select name="level" class="form-select">
                                            @foreach(['MINEUR','MAJEUR','CRITIQUE'] as $level)
                                                <option value="{{ $level }}" @selected($incident->level === $level)>{{ $level }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Statut</label>
                                        <select name="status" class="form-select">
                                            @foreach(['OPEN','IN_PROGRESS','RESOLVED','CLOSED'] as $status)
                                                <option value="{{ $status }}" @selected($incident->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Source</label>
                                        <input type="text" name="source" class="form-control" value="{{ $incident->source }}">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label">Impact</label>
                                        <input type="text" name="impact" class="form-control" value="{{ $incident->impact }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control" rows="3">{{ $incident->notes }}</textarea>
                                    </div>
                                </div>

                                <div class="mt-3 d-flex gap-2">
                                    <button class="btn btn-primary btn-sm">Mettre à jour</button>
                            </form>

                            @if($incident->status !== 'CLOSED')
                                <form method="POST" action="{{ route('admin.system.incidents.close', $incident) }}">
                                    @csrf
                                    <button class="btn btn-outline-success btn-sm"
                                            onclick="return confirm('Clôturer cet incident ?')">
                                        Clôturer
                                    </button>
                                </form>
                            @endif
                                </div>
                        </div>
                    @empty
                        <div class="text-muted">Aucun incident.</div>
                    @endforelse
                </div>
                <div class="card-footer">{{ $incidents->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection