@extends('layouts.app')

@section('content')
<style>
    body {
        background: #f5f7fa;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    h1, h2, h3 {
        color: #333;
    }
</style>

<div class="scroll-animated container my-5">
    <h1 class="scroll-animated mb-4"><i class="fas fa-reply me-2 text-primary"></i>Réponse Finale</h1>

    @if(isset($reponse) && $reponse->reponseFinale)
        {{-- Affichage de la réponse finale --}}
        <div class="scroll-animated card p-4 bg-white mb-4">
            <h2 class="mb-3"><i class="fas fa-info-circle me-2 text-secondary"></i>Détails de la Réponse Finale</h2>
            <table class="scroll-animated table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-comment-dots text-warning me-2"></i>Observation :</th>
                    <td>{!! nl2br(e($reponse->reponseFinale->observation ?? $reponse->reponseFinale->contenu ?? '')) !!}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-calendar-alt text-danger me-2"></i>Date de réponse :</th>
                    <td>{{ $reponse->reponseFinale->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        {{-- Annexes de la réponse finale --}}
        @if($reponse->reponseFinale->annexes && $reponse->reponseFinale->annexes->count())
            <h3 class="scroll-animated mb-3"><i class="fas fa-paperclip me-2"></i>Annexes de la Réponse Finale</h3>
            <div class="scroll-animated card p-3 bg-light mb-4">
                <ul class="list-group list-group-flush">
                    @foreach ($reponse->reponseFinale->annexes as $annexe)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf text-danger me-2"></i>{{ basename($annexe->file_path) }}</span>
                            <a href="{{ asset('storage/' . $annexe->file_path) }}" class="btn btn-sm btn-outline-primary" download>
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ensuite afficher la réponse initiale --}}
        <h1 class="scroll-animated mb-4"><i class="fas fa-reply me-2 text-primary"></i>Réponse Initiale</h1>
        <div class="scroll-animated card p-4 bg-white mb-4">
            <h2 class="mb-3"><i class="fas fa-info-circle me-2 text-secondary"></i>Détails de la Réponse Initiale</h2>
            <table class="scroll-animated table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-hashtag text-primary me-2"></i>Numéro d'enregistrement :</th>
                    <td>{{ $reponse->numero_enregistrement }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-tag text-success me-2"></i>Numéro de référence :</th>
                    <td>{{ $reponse->numero_reference }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-building text-info me-2"></i>Service concerné :</th>
                    <td>{{ $reponse->service_concerne }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-comment-dots text-warning me-2"></i>Observation :</th>
                    <td>{!! nl2br(e($reponse->observation)) !!}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-comments text-muted me-2"></i>Commentaires :</th>
                    <td>{!! nl2br(e($reponse->commentaires)) !!}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-calendar-alt text-danger me-2"></i>Date de réponse :</th>
                    <td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        {{-- Annexes de la réponse initiale --}}
        <h3 class="scroll-animated mb-3"><i class="fas fa-paperclip me-2"></i>Annexes de la Réponse Initiale</h3>
        <div class="scroll-animated card p-3 bg-light">
            @if($reponse->annexes->isNotEmpty())
                <ul class="list-group list-group-flush">
                    @foreach ($reponse->annexes as $annexe)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf text-danger me-2"></i>{{ basename($annexe->file_path) }}</span>
                            <a href="{{ asset('storage/' . $annexe->file_path) }}" class="btn btn-sm btn-outline-primary" download>
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="scroll-animated text-muted mb-0">Aucune annexe pour cette réponse initiale</p>
            @endif
        </div>

    @else
        {{-- Si pas de réponse finale, afficher la réponse initiale uniquement --}}
        <h1 class="scroll-animated mb-4"><i class="fas fa-reply me-2 text-primary"></i>Réponse Initiale</h1>
        <div class="scroll-animated card p-4 bg-white mb-4">
            <h2 class="mb-3"><i class="fas fa-info-circle me-2 text-secondary"></i>Détails de la Réponse Initiale</h2>
            <table class="scroll-animated table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-hashtag text-primary me-2"></i>Numéro d'enregistrement :</th>
                    <td>{{ $reponse->numero_enregistrement }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-tag text-success me-2"></i>Numéro de référence :</th>
                    <td>{{ $reponse->numero_reference }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-building text-info me-2"></i>Service concerné :</th>
                    <td>{{ $reponse->service_concerne }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-comment-dots text-warning me-2"></i>Observation :</th>
                    <td>{!! nl2br(e($reponse->observation)) !!}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-comments text-muted me-2"></i>Commentaires :</th>
                    <td>{!! nl2br(e($reponse->commentaires)) !!}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-calendar-alt text-danger me-2"></i>Date de réponse :</th>
                    <td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        {{-- Annexes de la réponse initiale --}}
        <h3 class="scroll-animated mb-3"><i class="fas fa-paperclip me-2"></i>Annexes de la Réponse Initiale</h3>
        <div class="scroll-animated card p-3 bg-light">
            @if($reponse->annexes->isNotEmpty())
                <ul class="list-group list-group-flush">
                    @foreach ($reponse->annexes as $annexe)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-file-pdf text-danger me-2"></i>{{ basename($annexe->file_path) }}</span>
                            <a href="{{ asset('storage/' . $annexe->file_path) }}" class="btn btn-sm btn-outline-primary" download>
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="scroll-animated text-muted mb-0">Aucune annexe pour cette réponse initiale</p>
            @endif
        </div>
    @endif

    <a href="{{ route('reponses.index') }}" class="btn btn-outline-secondary mt-4 scroll-animated">
        <i class="fas fa-arrow-left me-1"></i>Retour
    </a>
</div>
@endsection
