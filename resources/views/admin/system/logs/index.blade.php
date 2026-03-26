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
            <pre id="technical-log-pre" class="bg-dark text-light p-3 rounded" style="max-height:500px; overflow:auto; white-space:pre-wrap;">@foreach($technicalLogLines as $line){{ $line }}
@endforeach</pre>
        </div>
        @push('scripts')
        <script>
            (function(){
                const pre = document.getElementById('technical-log-pre');
                if (!pre) return;

                const url = '{{ url("admin/system/logs/tail") }}';
                let autoScroll = true;

                // Detect if user scrolled up: if so, don't auto-scroll
                pre.addEventListener('scroll', function() {
                    const threshold = 30; // px from bottom
                    const atBottom = (pre.scrollHeight - pre.scrollTop - pre.clientHeight) < threshold;
                    autoScroll = atBottom;
                });

                async function fetchTail() {
                    try {
                        const res = await fetch(url, { credentials: 'same-origin' });
                        if (!res.ok) return;
                        const json = await res.json();
                        if (!json || !Array.isArray(json.lines)) return;

                        // Join lines and replace content
                        const content = json.lines.join('\n');

                        // Only update if content changed to reduce flicker
                        if (pre.innerText !== content) {
                            pre.innerText = content;
                            if (autoScroll) {
                                pre.scrollTop = pre.scrollHeight;
                            }
                        }
                    } catch (e) {
                        // ignore errors silently
                        console.error('Log tail fetch error', e);
                    }
                }

                // Poll every 2 seconds
                setInterval(fetchTail, 2000);
            })();
        </script>
        @endpush
    </div>
</div>
@endsection