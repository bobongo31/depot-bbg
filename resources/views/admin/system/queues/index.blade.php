@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Files d’attente</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Driver</div>
                    <div class="fs-5 fw-bold">{{ $summary['driver'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Jobs en attente</div>
                    <div class="fs-5 fw-bold">{{ $summary['waiting_count'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="text-muted small">Jobs échoués</div>
                    <div class="fs-5 fw-bold">{{ $summary['failed_count'] ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body d-flex gap-2">
            <form method="POST" action="{{ route('admin.system.queues.retry-all') }}">
                @csrf
                <button class="btn btn-outline-primary" onclick="return confirm('Relancer tous les jobs échoués ?')">
                    Relancer tous les échecs
                </button>
            </form>

            <form method="POST" action="{{ route('admin.system.queues.flush-failed') }}">
                @csrf
                <button class="btn btn-outline-danger" onclick="return confirm('Purger tous les jobs échoués ?')">
                    Purger les échecs
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header fw-bold">Jobs en attente</div>
        <div class="card-body p-0">
            <table class="table table-sm mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Queue</th>
                        <th>Tentatives</th>
                        <th>Créé</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentJobs as $job)
                        <tr>
                            <td>{{ $job->id }}</td>
                            <td>{{ $job->queue }}</td>
                            <td>{{ $job->attempts }}</td>
                            <td>{{ \Carbon\Carbon::createFromTimestamp($job->created_at)->format('d/m/Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted text-center py-4">Aucun job en attente ou table jobs absente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header fw-bold">Jobs échoués</div>
        <div class="card-body p-0">
            <table class="table table-sm align-middle mb-0">
                <thead>
                    <tr>
                        <th>UUID</th>
                        <th>Queue</th>
                        <th>Date échec</th>
                        <th>Exception</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($failedJobs as $job)
                        <tr>
                            <td>{{ $job->uuid }}</td>
                            <td>{{ $job->queue }}</td>
                            <td>{{ \Carbon\Carbon::parse($job->failed_at)->format('d/m/Y H:i:s') }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($job->exception, 120) }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('admin.system.queues.retry', $job->uuid) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary">Relancer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-muted text-center py-4">Aucun job échoué ou table failed_jobs absente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection