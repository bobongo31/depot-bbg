@extends('layouts.app')

@section('content')
<div class="scroll-animated container mt-5">

    {{-- TITRE --}}
    <div class="scroll-animated text-center bg-primary text-white p-4 rounded shadow-sm mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-envelope-open-text me-2"></i>
            Détails du Courrier
        </h1>
    </div>

    {{-- INFORMATIONS DU COURRIER --}}
    <div class="scroll-animated card shadow-sm mb-4">
        <div class="scroll-animated card-body">

            <p>
                <i class="fas fa-hashtag me-2 text-muted"></i>
                <strong>Numéro d'enregistrement :</strong>
                {{ $courrier->numero_enregistrement }}
            </p>

            <p>
                <i class="fas fa-calendar-check me-2 text-muted"></i>
                <strong>Date d'accusé réception :</strong>
                {{ \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') }}
            </p>

            <p>
                <i class="fas fa-link me-2 text-muted"></i>
                <strong>Numéro de référence :</strong>
                {{ $courrier->numero_reference ?? 'N/A' }}
            </p>

            <p>
                <i class="fas fa-user me-2 text-muted"></i>
                <strong>Expéditeur :</strong>
                {{ $courrier->nom_expediteur }}
            </p>

            <p>
                <i class="fas fa-align-left me-2 text-muted"></i>
                <strong>Résumé :</strong><br>
                {{ $courrier->resume }}
            </p>

            <p>
                <i class="fas fa-eye me-2 text-muted"></i>
                <strong>Observation :</strong><br>
                {{ $courrier->observation ?? 'N/A' }}
            </p>

            <p>
                <i class="fas fa-comment-dots me-2 text-muted"></i>
                <strong>Commentaires :</strong><br>
                {{ $courrier->commentaires ?? 'N/A' }}
            </p>

            <p class="mt-3">
                <i class="fas fa-info-circle me-2 text-muted"></i>
                <strong>Statut :</strong>
                <span class="badge px-3 py-2 rounded-pill
                    {{
                        $courrier->statut === 'en attente' ? 'bg-danger' :
                        ($courrier->statut === 'reçu' ? 'bg-success' :
                        ($courrier->statut === 'traité' ? 'bg-info' : 'bg-secondary'))
                    }}">
                    {{ ucfirst($courrier->statut) }}
                </span>
            </p>

        </div>
    </div>

    {{-- ANNEXES AVEC APERÇU --}}
    <div class="scroll-animated card shadow-sm mb-4">
        <div class="scroll-animated card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-paperclip me-2"></i>
                Annexes
                <span class="badge bg-secondary ms-2">
                    {{ $courrier->annexes->count() }}
                </span>
            </h5>
        </div>

        <div class="scroll-animated card-body">
            @if($courrier->annexes->isEmpty())
                <p class="text-muted">Aucune annexe disponible.</p>
            @else
                <div class="row g-3">

                    @foreach ($courrier->annexes as $annexe)
                        @php
                            $path = asset('storage/' . $annexe->file_path);
                            $ext = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm rounded-4">

                                {{-- PDF --}}
                                @if($ext === 'pdf')
                                    <iframe
                                        src="{{ $path }}"
                                        class="w-100 rounded-top"
                                        style="height:220px;border:none;">
                                    </iframe>

                                {{-- IMAGES --}}
                                @elseif(in_array($ext, ['jpg','jpeg','png','webp']))
                                    <a href="{{ $path }}" target="_blank">
                                        <img
                                            src="{{ $path }}"
                                            class="img-fluid rounded-top"
                                            style="height:220px;object-fit:cover;"
                                            alt="Annexe"
                                        >
                                    </a>

                                {{-- AUTRES FICHIERS --}}
                                @else
                                    <div class="d-flex justify-content-center align-items-center"
                                         style="height:220px;">
                                        <i class="fas fa-file-alt fa-4x text-secondary"></i>
                                    </div>
                                @endif

                                <div class="card-body text-center p-2">
                                    <small class="d-block text-truncate">
                                        {{ basename($annexe->file_path) }}
                                    </small>

                                    <a href="{{ $path }}"
                                       target="_blank"
                                       class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-eye me-1"></i> Ouvrir
                                    </a>
                                </div>

                            </div>
                        </div>
                    @endforeach

                </div>
            @endif
        </div>
    </div>

    {{-- ACTIONS --}}
    <div class="scroll-animated text-end">
        <a href="{{ route('courriers.edit', $courrier->id) }}"
           class="btn btn-outline-warning me-2">
            <i class="fas fa-edit me-1"></i> Éditer
        </a>

        <a href="{{ route('courriers.index') }}"
           class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>

</div>
@endsection
