@extends('layouts.app')

@section('content')
@php
    use Illuminate\Support\Str;

    $hasResults =
        $telegrammes->isNotEmpty() ||
        $accuses->isNotEmpty() ||
        $reponses->isNotEmpty() ||
        $reponses_finales->isNotEmpty() ||
        $courriers_expedies->isNotEmpty();
@endphp

@push('styles')
<style>
    .search-section .card-header h5 {
        font-weight: 700;
    }

    .search-result-item {
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        gap: 1rem;
    }

    .search-result-content {
        flex: 1 1 auto;
        min-width: 0;
    }

    .search-result-title {
        font-weight: 700;
        color: #212529;
        word-break: break-word;
    }

    .search-result-meta,
    .search-result-extra {
        font-size: .875rem;
        line-height: 1.45;
    }

    .search-result-meta {
        color: #6c757d;
    }

    .search-result-extra {
        color: #495057;
    }

    .annexe-preview-wrapper {
        width: 160px;
        flex-shrink: 0;
    }

    .pdf-miniature {
        width: 100%;
        height: 180px;
        background: #f8f9fa;
    }

    .pdf-placeholder {
        width: 100%;
        height: 180px;
        border: 1px dashed #ced4da;
        border-radius: .5rem;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: .75rem;
        color: #6c757d;
        font-size: .85rem;
    }

    .search-card-link {
        text-decoration: none;
        color: inherit;
    }

    .search-card-link:hover {
        text-decoration: none;
        color: inherit;
    }

    @media (max-width: 768px) {
        .search-result-item {
            flex-direction: column;
        }

        .annexe-preview-wrapper {
            width: 100%;
        }

        .pdf-miniature,
        .pdf-placeholder {
            height: 220px;
        }
    }
</style>
@endpush

<div class="container py-4 search-section">

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('recherche.globale') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="query"
                            value="{{ $query }}"
                            class="form-control"
                            placeholder="Rechercher un numéro, référence, service, objet, observation, destinataire..."
                        >
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            Rechercher
                        </button>
                    </div>
                </div>
            </form>

            @if($query !== '')
                <div class="mt-3">
                    <h2 class="mb-1 fw-bold">Résultats de recherche</h2>
                    <p class="text-muted mb-0">
                        Mot recherché :
                        <span class="fw-semibold text-dark">"{{ $query }}"</span>
                    </p>
                </div>
            @endif
        </div>
    </div>

    @if($query !== '' && $hasResults)
        <div class="mb-3">
            <span class="badge bg-primary fs-6 px-3 py-2">Résultats trouvés</span>
        </div>
    @endif

    {{-- Télégrammes --}}
    @if($telegrammes->count())
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📨 Télégrammes</h5>
                <span class="badge bg-light text-primary">{{ $telegrammes->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @foreach($telegrammes as $t)
                    <a href="{{ route('telegramme.show', $t->id) }}" class="list-group-item list-group-item-action py-3 search-card-link">
                        <div class="search-result-item">
                            <div class="search-result-content">
                                <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                    <div class="search-result-title">
                                        {{ $t->numero_enregistrement ?? 'N/A' }}
                                        @if(!empty($t->numero_reference))
                                            - {{ $t->numero_reference }}
                                        @endif
                                    </div>

                                    @if(!empty($t->statut_final))
                                        <span class="badge bg-primary-subtle text-primary border">
                                            {{ $t->statut_final }}
                                        </span>
                                    @elseif(!empty($t->statut))
                                        <span class="badge bg-primary-subtle text-primary border">
                                            {{ $t->statut }}
                                        </span>
                                    @endif
                                </div>

                                <div class="search-result-meta mt-1">
                                    @if(!empty($t->service_concerne))
                                            @php
                                                $__sc = $t->service_concerne;
                                                $__sc_arr = is_array($__sc) ? $__sc : (json_decode($__sc, true) ?: null);
                                            @endphp
                                            Service concerné : {{ is_array($__sc_arr) ? implode(', ', $__sc_arr) : $__sc }}
                                        @elseif(!empty($t->observation))
                                        {{ Str::limit($t->observation, 110) }}
                                    @elseif(!empty($t->commentaires))
                                        {{ Str::limit($t->commentaires, 110) }}
                                    @else
                                        Télégramme #{{ $t->id }}
                                    @endif
                                </div>

                                @if(!empty($t->observation) && !empty($t->service_concerne))
                                    <div class="search-result-extra mt-1">
                                        Observation : {{ Str::limit($t->observation, 100) }}
                                    </div>
                                @endif
                            </div>

                            @include('partials.annexe-pdf-preview', ['annexes' => $t->annexes ?? collect()])
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Accusés de réception --}}
    @if($accuses->count())
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📥 Accusés de réception</h5>
                <span class="badge bg-light text-success">{{ $accuses->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @foreach($accuses as $a)
                    <a href="{{ route('courriers.show', $a->id) }}" class="list-group-item list-group-item-action py-3 search-card-link">
                        <div class="search-result-item">
                            <div class="search-result-content">
                                <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                    <div class="search-result-title">
                                        {{ $a->numero_enregistrement ?? 'N/A' }}
                                        @if(!empty($a->numero_reference))
                                            - {{ $a->numero_reference }}
                                        @endif
                                    </div>

                                    @if(!empty($a->statut))
                                        <span class="badge bg-success-subtle text-success border">
                                            {{ $a->statut }}
                                        </span>
                                    @endif
                                </div>

                                <div class="search-result-meta mt-1">
                                    @if(!empty($a->objet))
                                        Objet : {{ Str::limit($a->objet, 110) }}
                                    @elseif(!empty($a->resume))
                                        Résumé : {{ Str::limit($a->resume, 110) }}
                                    @elseif(!empty($a->nom_expediteur))
                                        Expéditeur : {{ $a->nom_expediteur }}
                                    @else
                                        Accusé #{{ $a->id }}
                                    @endif
                                </div>

                                <div class="search-result-extra mt-1">
                                    @if(!empty($a->nom_expediteur))
                                        Expéditeur : {{ $a->nom_expediteur }}
                                    @endif

                                    @if(!empty($a->date_reception))
                                        @if(!empty($a->nom_expediteur)) • @endif
                                        Reçu le : {{ \Carbon\Carbon::parse($a->date_reception)->format('d/m/Y') }}
                                    @endif
                                </div>
                            </div>

                            @include('partials.annexe-pdf-preview', ['annexes' => $a->annexes ?? collect()])
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    

    {{-- Réponses --}}
    @if($reponses->count())
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📝 Réponses</h5>
                <span class="badge bg-light text-info">{{ $reponses->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @foreach($reponses as $r)
                    <a href="{{ route('reponse.show', $r->id) }}" class="list-group-item list-group-item-action py-3 search-card-link">
                        <div class="search-result-item">
                            <div class="search-result-content">
                                <div class="search-result-title">
                                    {{ $r->numero_enregistrement ?? 'N/A' }}
                                    @if(!empty($r->numero_reference))
                                        - {{ $r->numero_reference }}
                                    @endif
                                </div>

                                <div class="search-result-meta mt-1">
                                    @if(!empty($r->service_concerne))
                                            @php
                                                $__sc = $r->service_concerne;
                                                $__sc_arr = is_array($__sc) ? $__sc : (json_decode($__sc, true) ?: null);
                                            @endphp
                                            Service concerné : {{ is_array($__sc_arr) ? implode(', ', $__sc_arr) : $__sc }}
                                        @elseif(!empty($r->observation))
                                        {{ Str::limit($r->observation, 110) }}
                                    @elseif(!empty($r->commentaires))
                                        {{ Str::limit($r->commentaires, 110) }}
                                    @else
                                        Réponse #{{ $r->id }}
                                    @endif
                                </div>

                                @if(!empty($r->observation) && !empty($r->service_concerne))
                                    <div class="search-result-extra mt-1">
                                        Observation : {{ Str::limit($r->observation, 100) }}
                                    </div>
                                @endif
                            </div>

                            @include('partials.annexe-pdf-preview', ['annexes' => $r->annexes ?? collect()])
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Réponses finales --}}
    @if($reponses_finales->count())
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">✅ Réponses finales</h5>
                <span class="badge bg-light text-dark">{{ $reponses_finales->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @foreach($reponses_finales as $rf)
                    <a href="{{ route('reponses.showFinale', $rf->id) }}" class="list-group-item list-group-item-action py-3 search-card-link">
                        <div class="search-result-item">
                            <div class="search-result-content">
                                <div class="search-result-title">
                                    {{ $rf->numero_enregistrement ?? 'N/A' }}
                                    @if(!empty($rf->numero_reference))
                                        - {{ $rf->numero_reference }}
                                    @endif
                                </div>

                                <div class="search-result-meta mt-1">
                                    @if(!empty($rf->service_concerne))
                                            @php
                                                $__sc = $rf->service_concerne;
                                                $__sc_arr = is_array($__sc) ? $__sc : (json_decode($__sc, true) ?: null);
                                            @endphp
                                            Service concerné : {{ is_array($__sc_arr) ? implode(', ', $__sc_arr) : $__sc }}
                                        @elseif(!empty($rf->observation))
                                        {{ Str::limit($rf->observation, 110) }}
                                    @else
                                        Réponse finale #{{ $rf->id }}
                                    @endif
                                </div>

                                @if(!empty($rf->observation) && !empty($rf->service_concerne))
                                    <div class="search-result-extra mt-1">
                                        Observation : {{ Str::limit($rf->observation, 100) }}
                                    </div>
                                @endif
                            </div>

                            @include('partials.annexe-pdf-preview', ['annexes' => $rf->annexes ?? collect()])
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Courriers expédiés --}}
    @if($courriers_expedies->count())
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">📤 Courriers expédiés</h5>
                <span class="badge bg-light text-secondary">{{ $courriers_expedies->count() }}</span>
            </div>

            <div class="list-group list-group-flush">
                @foreach($courriers_expedies as $ce)
                    <a href="{{ route('courrier_expedie.show', $ce->id) }}" class="list-group-item list-group-item-action py-3 search-card-link">
                        <div class="search-result-item">
                            <div class="search-result-content">
                                <div class="search-result-title">
                                    {{ $ce->numero_ordre ?? 'N/A' }}
                                    @if(!empty($ce->numero_lettre))
                                        - {{ $ce->numero_lettre }}
                                    @endif
                                </div>

                                <div class="search-result-meta mt-1">
                                    Destinataire : {{ $ce->destinataire ?? '—' }}
                                </div>

                                @if(!empty($ce->resume) || !empty($ce->observation))
                                    <div class="search-result-extra mt-1">
                                        {{ Str::limit($ce->resume ?? $ce->observation, 100) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($query !== '' && !$hasResults)
        <div class="alert alert-warning shadow-sm border-0 mt-4">
            <h5 class="mb-1">Aucun résultat trouvé</h5>
            <p class="mb-0">
                Aucun résultat ne correspond à
                <strong>"{{ $query }}"</strong>.
            </p>
        </div>
    @endif

</div>
@endsection