@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Journaux & audit</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="q" class="form-control" value="{{ request('q') }}" placeholder="Recherche">
                </div>
                <div class="col-md-2">
                    <select name="level" class="form-select">
                        <option value="">Tous niveaux</option>
                        @foreach(['INFO','WARNING','ERROR','CRITICAL'] as $level)
                            <option value="{{ $level }}" @selected(request('level') === $level)>{{ $level }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="module" class="form-control" value="{{ request('module') }}" placeholder="Module">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filtrer</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.system.logs.index') }}" class="btn btn-outline-secondary w-100">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Audit exploitation</div>
        <div class="card-body p-0">
            <table class="table table-sm align-middle mb-0">
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
                    @forelse($audits as $audit)
                        <tr>
                            <td>{{ $audit->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td>{{ $audit->module }}</td>
                            <td>{{ $audit->action }}</td>
                            <td>{{ $audit->level }}</td>
                            <td>{{ $audit->message }}</td>
                            <td>{{ $audit->user_name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-muted text-center py-4">Aucune trace.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $audits->links() }}</div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Log technique Laravel (200 dernières lignes)</div>
        <div class="card-body">
            <pre class="bg-dark text-light p-3 rounded" style="max-height:500px; overflow:auto;">@foreach($technicalLogLines as $line){{ $line }}
@endforeach</pre>
        </div>
    </div>
</div>
@endsection