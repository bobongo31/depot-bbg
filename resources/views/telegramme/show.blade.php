@extends('layouts.app')

@section('content')

<style>
    body {
        background: #f8f9fa;
    }
    .card {
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        border-radius: 12px;
    }
    h1, h3 {
        color: #343a40;
    }
</style>

<div class="scroll-animated container my-5">
    <h1 class="scroll-animated mb-4"><i class="fas fa-envelope-open-text me-2"></i>Détails du Télégramme</h1>

    @if(isset($telegramme))
        <div class="scroll-animated card p-4 mb-4 bg-white">
            <table class="scroll-animated table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-hashtag me-2 text-primary"></i>Numéro d'enregistrement :</th>
                    <td>{{ $telegramme->numero_enregistrement }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-tag me-2 text-success"></i>Numéro de référence :</th>
                    <td>{{ $telegramme->numero_reference }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-building me-2 text-info"></i>Service Concerné :</th>
                    <td>{{ $telegramme->service_concerne }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-comment-dots me-2 text-secondary"></i>Commentaires :</th>
                    <td>{{ $telegramme->commentaires }}</td>
                </tr>
                <tr>
                    <th><i class="fas fa-user me-2 text-warning"></i>Expéditeur :</th>
                    <td>{{ $telegramme->observation }}</td>
                </tr>
            </table>
        </div>

        <h3 class="scroll-animated mt-5"><i class="fas fa-paperclip me-2"></i>Annexes liées à l'Accusé de Réception</h3>

        <div class="scroll-animated card p-3 bg-light">
            @if(isset($accuseReception))
                @if($accuseReception->annexes && $accuseReception->annexes->isNotEmpty())
                    <ul class="scroll-animated list-group list-group-flush">
                        @foreach ($accuseReception->annexes as $annexe)
                            <li class="list-group-item d-flex align-items-center justify-content-between">
                                <span><i class="fas fa-file-pdf text-danger me-2"></i>{{ basename($annexe->file_path) }}</span>
                                <a href="{{ asset('storage/' . $annexe->file_path) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-eye me-1"></i>Voir
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="scroll-animated text-muted">Aucune annexe liée à cet accusé de réception.</p>
                @endif
            @else
                <p class="scroll-animated text-danger">Aucun accusé de réception correspondant n'a été trouvé.</p>
            @endif
        </div>

        <a href="{{ route('reponses.index') }}" class="btn btn-outline-secondary mt-4">
            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
        </a>
    @else
        <p class="scroll-animated text-danger">Télégramme non trouvé.</p>
    @endif
</div>
@endsection
