@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-3">Outils rapides</h1>

    @include('admin.system.partials.nav')
    @include('admin.system.partials.flash')

    <div class="row g-3">
        @php
            $tools = [
                ['key' => 'cache_clear', 'label' => 'Vider cache application', 'class' => 'outline-primary'],
                ['key' => 'config_clear', 'label' => 'Vider cache config', 'class' => 'outline-primary'],
                ['key' => 'route_clear', 'label' => 'Vider cache routes', 'class' => 'outline-primary'],
                ['key' => 'view_clear', 'label' => 'Vider cache vues', 'class' => 'outline-primary'],
                ['key' => 'optimize_clear', 'label' => 'Optimize clear', 'class' => 'outline-warning'],
                ['key' => 'queue_restart', 'label' => 'Redémarrer workers queue', 'class' => 'outline-warning'],
                ['key' => 'health_sync', 'label' => 'Synchroniser alertes santé', 'class' => 'outline-success'],
            ];
        @endphp

        @foreach($tools as $tool)
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div class="fw-bold mb-3">{{ $tool['label'] }}</div>
                        <form method="POST" action="{{ route('admin.system.tools.run') }}"
                              onsubmit="return confirm('Exécuter cet outil ?')">
                            @csrf
                            <input type="hidden" name="tool" value="{{ $tool['key'] }}">
                            <button class="btn btn-{{ $tool['class'] }} w-100">Exécuter</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection