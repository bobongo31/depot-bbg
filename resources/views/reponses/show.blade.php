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
    iframe {
        border: none;
    }
</style>

<div class="scroll-animated container my-5">

    {{-- TITRE --}}
    <h1 class="scroll-animated mb-4">
        <i class="fas fa-reply me-2 text-primary"></i> Réponse Associée
    </h1>

    @if(isset($reponse))

        {{-- ===================== DÉTAILS RÉPONSE ===================== --}}
        <div class="scroll-animated card p-4 bg-white mb-4">
            <h2 class="mb-3">
                <i class="fas fa-info-circle me-2 text-secondary"></i>
                Détails de la Réponse
            </h2>

            {{-- STATUT --}}
            @php
                $statut = $telegramme->statut ?? 'brouillon';
            @endphp

            <span class="badge mb-3
                {{ $statut === 'traité' ? 'bg-success' :
                   ($statut === 'reçu' ? 'bg-info' :
                   ($statut === 'en attente' ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                {{ ucfirst($statut) }}
            </span>

            <table class="table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-hashtag text-primary me-2"></i>Numéro d'enregistrement :</th>
                    <td>{{ $reponse->numero_enregistrement }}</td>
                </tr>

                <tr>
                    <th><i class="fas fa-tag text-success me-2"></i>Numéro de référence :</th>
                    <td>{{ $reponse->numero_reference }}</td>
                </tr>

                <tr>
                    <th><i class="fas fa-users text-info me-2"></i>Destinataire(s) :</th>
                    <td>
                        @php
                            $services = is_string($reponse->service_concerne)
                                ? json_decode($reponse->service_concerne, true)
                                : $reponse->service_concerne;
                        @endphp
                        {{ is_array($services) ? implode(', ', $services) : '-' }}
                    </td>
                </tr>

                <tr>
                    <th><i class="fas fa-user text-warning me-2"></i>Expéditeur :</th>
                    <td>{{ $reponse->observation ?? '-' }}</td>
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

        {{-- ===================== ANNEXES ACCUSÉ DE RÉCEPTION (si présent) ===================== --}}
        @php $accuse = $telegramme->accuseReception ?? null; @endphp
        @if($accuse && isset($accuse->annexes) && $accuse->annexes->isNotEmpty())
            <h3 class="scroll-animated mb-3">
                <i class="fas fa-paperclip me-2"></i>
                Annexes de l'accusé de réception
                <span class="badge bg-secondary">{{ $accuse->annexes->count() }}</span>
            </h3>

            <div class="scroll-animated card p-3 bg-light mb-4">
                <div class="row g-3">
                    @foreach ($accuse->annexes as $annexe)
                        @php
                            $path = asset('storage/' . $annexe->file_path);
                            $ext = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @if($ext === 'pdf')
                                    <iframe src="{{ $path }}" style="height:220px;" class="w-100 rounded-top"></iframe>
                                @elseif(in_array($ext, ['jpg','jpeg','png','webp']))
                                    <a href="{{ $path }}" target="_blank">
                                        <img src="{{ $path }}" class="img-fluid rounded-top" style="height:220px;object-fit:cover;">
                                    </a>
                                @else
                                    <div class="d-flex justify-content-center align-items-center" style="height:220px;">
                                        <i class="fas fa-file-alt fa-4x text-secondary"></i>
                                    </div>
                                @endif

                                <div class="card-body text-center p-2">
                                    <small class="d-block text-truncate">{{ basename($annexe->file_path) }}</small>
                                    <a href="{{ $path }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-eye me-1"></i> Ouvrir
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- ===================== ANNEXES RÉPONSE ===================== --}}
        <h3 class="scroll-animated mb-3">
            <i class="fas fa-paperclip me-2"></i>
            Annexes de la Réponse
            <span class="badge bg-secondary">{{ $reponse->annexes->count() }}</span>
        </h3>

        <div class="scroll-animated card p-3 bg-light mb-5">
            @if($reponse->annexes->isEmpty())
                <p class="text-muted mb-0">Aucune annexe pour cette réponse</p>
            @else
                <div class="row g-3">
                    @foreach ($reponse->annexes as $annexe)
                        @php
                            $path = asset('storage/' . $annexe->file_path);
                            $ext = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm">

                                {{-- PDF --}}
                                @if($ext === 'pdf')
                                    <iframe src="{{ $path }}" style="height:220px;" class="w-100 rounded-top"></iframe>

                                {{-- IMAGE --}}
                                @elseif(in_array($ext, ['jpg','jpeg','png','webp']))
                                    <a href="{{ $path }}" target="_blank">
                                        <img src="{{ $path }}"
                                             class="img-fluid rounded-top"
                                             style="height:220px;object-fit:cover;">
                                    </a>

                                {{-- AUTRE --}}
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

        {{-- ===================== RÉPONSE FINALE ===================== --}}
        @if($reponse->reponseFinale)
            <h3 class="scroll-animated mb-3">
                <i class="fas fa-check-circle text-success me-2"></i>
                Réponse Finale
            </h3>

            <div class="scroll-animated card p-4 bg-white mb-4">
                <table class="table table-borderless">
                    <tr>
                        <th><i class="fas fa-file-alt me-2 text-primary"></i>Contenu :</th>
                        <td>
                            {!! nl2br(e(
                                $reponse->reponseFinale->observation
                                ?? $reponse->reponseFinale->contenu
                                ?? ''
                            )) !!}
                        </td>
                    </tr>

                    <tr>
                        <th><i class="fas fa-calendar-check me-2 text-success"></i>Date :</th>
                        <td>{{ $reponse->reponseFinale->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>

                @if($reponse->reponseFinale->annexes && $reponse->reponseFinale->annexes->count())
                    <h4 class="mt-4">
                        <i class="fas fa-paperclip me-2"></i> Annexes de la Réponse Finale
                    </h4>

                    <div class="row g-3">
                        @foreach ($reponse->reponseFinale->annexes as $annexe)
                            @php
                                $path = asset('storage/' . $annexe->file_path);
                                $ext = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
                            @endphp

                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 shadow-sm">
                                    @if($ext === 'pdf')
                                        <iframe src="{{ $path }}" style="height:220px;" class="w-100 rounded-top"></iframe>
                                    @elseif(in_array($ext, ['jpg','jpeg','png','webp']))
                                        <a href="{{ $path }}" target="_blank">
                                            <img src="{{ $path }}" class="img-fluid rounded-top"
                                                 style="height:220px;object-fit:cover;">
                                        </a>
                                    @else
                                        <div class="d-flex justify-content-center align-items-center"
                                             style="height:220px;">
                                            <i class="fas fa-file-alt fa-4x text-secondary"></i>
                                        </div>
                                    @endif

                                    <div class="card-body text-center p-2">
                                        <small class="text-truncate d-block">
                                            {{ basename($annexe->file_path) }}
                                        </small>
                                        <a href="{{ $path }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary mt-2">
                                            <i class="fas fa-eye"></i> Ouvrir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Bouton supprimer réponse finale (admin/DG ou auteur) --}}
                @php $user = auth()->user(); @endphp
                @if($user && ($user->role === 'admin' || $user->role === 'DG' || (method_exists($user,'hasRole') && ($user->hasRole('admin') || $user->hasRole('DG'))) || $reponse->reponseFinale->user_id === $user->id))
                    <form action="{{ url('reponse-finale/' . $reponse->reponseFinale->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression de la réponse finale ?');" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> Supprimer la réponse finale
                        </button>
                    </form>
                @endif
            </div>
        @else
            <p class="text-muted">
                <i class="fas fa-info-circle me-2"></i>
                Aucune réponse finale n’a encore été enregistrée.
            </p>
        @endif

    @else
        <p class="text-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            Aucune réponse associée à ce télégramme.
        </p>
    @endif

    {{-- RETOUR --}}
    <a href="{{ route('reponses.index') }}"
       class="btn btn-outline-secondary mt-4">
        <i class="fas fa-arrow-left me-1"></i> Retour
    </a>

</div>
@endsection
