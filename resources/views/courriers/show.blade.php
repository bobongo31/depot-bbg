@extends('layouts.app')

@section('content')
<div class="scroll-animated container mt-5">

    {{-- TITRE BLEU --}}
    <div class="scroll-animated text-center bg-primary text-white p-4 rounded shadow-sm mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-envelope-open-text me-2"></i>
            Détails du Courrier
        </h1>
    </div>

    <div class="card shadow-sm rounded-4">
        <div class="card-body">

            {{-- INFORMATIONS GÉNÉRALES --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p>
                        <strong>Numéro d'enregistrement :</strong>
                        {{ $courrier->numero_enregistrement }}
                    </p>

                    <p>
                        <strong>Date de réception :</strong>
                        {{ $courrier->date_reception ? \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p>
                        <strong>Numéro de référence :</strong>
                        {{ $courrier->numero_reference ?? 'N/A' }}
                    </p>

                    <p>
                        <strong>Réceptionné par :</strong>
                        {{ $courrier->receptionne_par ?? 'N/A' }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- EXPÉDITEUR / SERVICE --}}
            <div class="mb-3">
                <p class="mb-1"><strong>Expéditeur :</strong></p>

                <span class="badge bg-primary mb-1 d-inline-block">
                    <i class="fa-solid fa-user"></i>
                    {{ $courrier->nom_expediteur ?? 'N/A' }}
                </span>

                @if(!empty($courrier->service_concerne))
                    <div class="mt-2">
                        <span class="badge bg-warning text-dark me-1 mb-1">
                            <i class="fa-solid fa-building"></i>
                            {{ $courrier->service_concerne }}
                        </span>
                    </div>
                @endif
            </div>

            <hr>

            {{-- CONTENU --}}
            @if(!empty($courrier->objet))
                <p>
                    <strong>Objet :</strong><br>
                    {{ $courrier->objet }}
                </p>
            @endif

            

            @if($courrier->observation)
                <p class="mt-3">
                    <strong>Observation :</strong><br>
                    {{ $courrier->observation }}
                </p>
            @endif

            @if($courrier->commentaires)
                <p class="mt-3">
                    <strong>Commentaires :</strong><br>
                    {{ $courrier->commentaires }}
                </p>
            @endif

            @if(!empty($courrier->avis))
                <p class="mt-3">
                    <strong>Avis :</strong><br>
                    {{ $courrier->avis }}
                </p>
            @endif

            <hr>

            {{-- STATUT --}}
            <div class="mb-3">
                <p class="mb-1"><strong>Statut :</strong></p>

                <span class="badge px-3 py-2 rounded-pill
                    {{
                        $courrier->statut === 'en attente' ? 'bg-danger' :
                        ($courrier->statut === 'reçu' ? 'bg-success' :
                        ($courrier->statut === 'traité' ? 'bg-info' : 'bg-secondary'))
                    }}">
                    {{ ucfirst($courrier->statut ?? 'non défini') }}
                </span>
            </div>

            <hr>

            {{-- ANNEXES --}}
            <h5 class="mb-3">
                <i class="fa-solid fa-paperclip"></i>
                Annexes
                <span class="badge bg-secondary ms-1">
                    {{ $courrier->annexes->count() }}
                </span>
            </h5>

            @if($courrier->annexes->isNotEmpty())
                <div class="row g-3">
                    @foreach ($courrier->annexes as $annexe)
                        @php
                            $url = asset('storage/' . $annexe->file_path);
                            $ext = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100 rounded-4">

                                @if($ext === 'pdf')
                                    <iframe
                                        src="{{ $url }}"
                                        class="w-100 rounded-top"
                                        style="height:220px; border:none;">
                                    </iframe>

                                @elseif(in_array($ext, ['jpg','jpeg','png','webp']))
                                    <a href="{{ $url }}" target="_blank">
                                        <img
                                            src="{{ $url }}"
                                            class="img-fluid rounded-top"
                                            style="height:220px; object-fit:cover;"
                                            alt="Annexe">
                                    </a>

                                @else
                                    <div class="d-flex align-items-center justify-content-center"
                                         style="height:220px;">
                                        <i class="fa-solid fa-file-lines fa-4x text-secondary"></i>
                                    </div>
                                @endif

                                <div class="card-body p-2 text-center">
                                    <small class="text-truncate d-block">
                                        {{ basename($annexe->file_path) }}
                                    </small>

                                    <a href="{{ $url }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fa-solid fa-eye"></i> Ouvrir
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <span class="text-muted">Aucune annexe</span>
            @endif

            <hr>

            {{-- ACTIONS --}}
            <div class="mt-4 d-flex gap-2">
                <a href="{{ route('courriers.edit', $courrier->id) }}"
                   class="btn btn-warning">
                    <i class="fa-solid fa-pen"></i> Modifier
                </a>

                <a href="{{ route('courriers.index') }}"
                   class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>
</div>
@endsection