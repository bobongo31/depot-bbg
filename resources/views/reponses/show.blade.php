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
    <h1 class="scroll-animated mb-4"><i class="fas fa-reply me-2 text-primary"></i>Réponse Associée</h1>

    @if(isset($reponse))
        <div class="scroll-animated card p-4 bg-white mb-4">
            <h2 class="mb-3"><i class="fas fa-info-circle me-2 text-secondary"></i>Détails de la Réponse</h2>
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

        <h3 class="scroll-animated mb-3"><i class="fas fa-paperclip me-2"></i>Annexes de la Réponse</h3>

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
                <p class="scroll-animated text-muted mb-0">Aucune annexe pour cette réponse</p>
            @endif
        </div>
    @else
        <p class="scroll-animated text-danger mt-4"><i class="fas fa-exclamation-circle me-2"></i>Aucune réponse associée à ce télégramme.</p>
    @endif

    @if(isset($reponse) && $reponse->reponseFinale)
        <h3 class="scroll-animated mt-5 mb-3"><i class="fas fa-check-circle text-success me-2"></i>Réponse Finale</h3>
        <div class="scroll-animated card p-4 bg-white mb-4">
            <table class="scroll-animated table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-file-alt me-2 text-primary"></i>Observation :</th>
                    <td>{!! nl2br(e($reponse->reponseFinale->observation ?? $reponse->reponseFinale->contenu ?? '')) !!}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-calendar-check me-2 text-success"></i>Date de soumission :</th>
                    <td>{{ $reponse->reponseFinale->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            @if($reponse->reponseFinale->annexes && $reponse->reponseFinale->annexes->count())
                <h4 class="mt-4"><i class="fas fa-paperclip me-2"></i>Annexes de la Réponse Finale</h4>
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
            @endif
        </div>
    @else
        <p class="scroll-animated text-muted mt-4"><i class="fas fa-info-circle me-2"></i>Aucune réponse finale n’a encore été enregistrée.</p>
    @endif

    <a href="{{ route('reponses.index') }}" class="btn btn-outline-secondary mt-4 scroll-animated">
        <i class="fas fa-arrow-left me-1"></i>Retour
    </a>
</div>
@endsection
