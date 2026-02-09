@extends('layouts.app')

@section('content')
<div class="scroll-animated container mt-5">

    <!-- Titre -->
    <div class="scroll-animated text-center bg-primary text-white p-4 rounded shadow-sm mb-4">
        <h1 class="h3 mb-0"><i class="fas fa-envelope-open-text me-2"></i>Détails du Courrier</h1>
    </div>

    <!-- Informations du courrier -->
    <div class="scroll-animated card shadow-sm mb-4">
        <div class="scroll-animated card-body">
            <p><i class="fas fa-hashtag me-2 text-muted"></i><strong>Numéro d'enregistrement :</strong> {{ $courrier->numero_enregistrement }}</p>
            <p><i class="fas fa-calendar-check me-2 text-muted"></i><strong>Date d'accusé réception :</strong> {{ $courrier->date_reception }}</p>
            <p><i class="fas fa-link me-2 text-muted"></i><strong>Numéro de référence :</strong> {{ $courrier->numero_reference ?? 'N/A' }}</p>
            <p><i class="fas fa-user me-2 text-muted"></i><strong>Expéditeur :</strong> {{ $courrier->nom_expediteur }}</p>
            <p><i class="fas fa-align-left me-2 text-muted"></i><strong>Résumé :</strong> {{ $courrier->resume }}</p>
            <p><i class="fas fa-eye me-2 text-muted"></i><strong>Observation :</strong> {{ $courrier->observation ?? 'N/A' }}</p>
            <p><i class="fas fa-comment-dots me-2 text-muted"></i><strong>Commentaires :</strong> {{ $courrier->commentaires ?? 'N/A' }}</p>
            <p>
                <i class="fas fa-info-circle me-2 text-muted"></i>
                <strong>Statut :</strong>

                <span class="badge px-2 py-1 rounded-pill
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

    <!-- Liste des annexes -->
    <div class="scroll-animated card shadow-sm mb-4">
        <div class="scroll-animated card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-paperclip me-2"></i>Annexes</h5>
        </div>
        <div class="scroll-animated card-body">
            @if($courrier->annexes->isEmpty())
                <p class="text-muted">Aucune annexe disponible.</p>
            @else
                <ul class="scroll-animated list-group list-group-flush">
                    @foreach ($courrier->annexes as $annexe)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-alt me-2 text-primary"></i>{{ basename($annexe->file_path) }}</span>
                            <a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Bouton retour / Éditer -->
    <div class="scroll-animated text-end">
        <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-sm btn-outline-warning me-2" title="Éditer">
            <i class="fas fa-edit me-1"></i>Éditer
        </a>
        <a href="{{ route('courriers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>
</div>
@endsection
