@extends('layouts.app')

@section('content')
<style>
    body {
        background: #f8f9fa;
    }
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    iframe {
        border: none;
    }
</style>

<div class="scroll-animated container my-5">

    {{-- TITRE --}}
    <h1 class="scroll-animated mb-4">
        <i class="fas fa-envelope-open-text me-2"></i>
        Détails du Télégramme
    </h1>

    @if(isset($telegramme))

        {{-- ===================== INFOS TÉLÉGRAMME ===================== --}}
        <div class="scroll-animated card p-4 mb-4 bg-white">
            <table class="table table-borderless mb-0">
                <tr>
                    <th><i class="fas fa-hashtag me-2 text-primary"></i>Numéro d'enregistrement :</th>
                    <td>{{ $telegramme->numero_enregistrement }}</td>
                </tr>

                <tr>
                    <th><i class="fas fa-tag me-2 text-success"></i>Numéro de référence :</th>
                    <td>{{ $telegramme->numero_reference }}</td>
                </tr>

                <tr>
                    <th><i class="fas fa-building me-2 text-info"></i>Service concerné :</th>
                    <td>
                        @if(is_string($telegramme->service_concerne))
                            {{ implode(', ', json_decode($telegramme->service_concerne, true)) }}
                        @else
                            {{ $telegramme->service_concerne }}
                        @endif
                    </td>
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

        {{-- ===================== DOCUMENT PRINCIPAL ===================== --}}
        <h3 class="scroll-animated mb-3">
            <i class="fas fa-file me-2"></i> Document principal
        </h3>

        <div class="scroll-animated card p-3 bg-light mb-5">
            @if($telegramme->document_path)
                @php
                    $docPath = asset('storage/' . $telegramme->document_path);
                    $docExt  = strtolower(pathinfo($telegramme->document_path, PATHINFO_EXTENSION));
                @endphp

                @if($docExt === 'pdf')
                    <iframe src="{{ $docPath }}" class="w-100 rounded" style="height:420px;"></iframe>

                @elseif(in_array($docExt, ['jpg','jpeg','png','webp']))
                    <a href="{{ $docPath }}" target="_blank">
                        <img src="{{ $docPath }}" class="img-fluid rounded shadow-sm">
                    </a>

                @else
                    <div class="d-flex align-items-center justify-content-between">
                        <span>
                            <i class="fas fa-file-alt fa-2x text-secondary me-2"></i>
                            {{ basename($telegramme->document_path) }}
                        </span>
                        <a href="{{ $docPath }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i> Ouvrir
                        </a>
                    </div>
                @endif
            @else
                <p class="text-muted mb-0">Aucun document principal</p>
            @endif
        </div>

        {{-- ===================== ANNEXES TÉLÉGRAMME ===================== --}}
        <h3 class="scroll-animated mb-3">
            <i class="fas fa-paperclip me-2"></i>
            Annexes du Télégramme
            <span class="badge bg-secondary">
                {{ $telegramme->annexes ? $telegramme->annexes->count() : 0 }}
            </span>
        </h3>

        <div class="scroll-animated card p-3 bg-light mb-5">
            @if($telegramme->annexes && $telegramme->annexes->isNotEmpty())
                <div class="row g-3">
                    @foreach ($telegramme->annexes as $annexe)
                        @php
                            $path = asset('storage/' . $annexe->file_path);
                            $ext  = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
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

                                    <div class="d-flex justify-content-center gap-2 mt-2">
                                        <a href="{{ $path }}" target="_blank"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('annexes.download', $annexe->id) }}"
                                           class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">Aucune annexe liée à ce télégramme.</p>
            @endif
        </div>

        {{-- ===================== ANNEXES ACCUSÉ DE RÉCEPTION ===================== --}}
        <h3 class="scroll-animated mb-3">
            <i class="fas fa-paperclip me-2"></i>
            Annexes de l’Accusé de Réception
        </h3>

        <div class="scroll-animated card p-3 bg-light mb-5">
            @if(isset($accuseReception) && $accuseReception->annexes && $accuseReception->annexes->isNotEmpty())
                <div class="row g-3">
                    @foreach ($accuseReception->annexes as $annexe)
                        @php
                            $path = asset('storage/' . $annexe->file_path);
                            $ext  = strtolower(pathinfo($annexe->file_path, PATHINFO_EXTENSION));
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
            @else
                <p class="text-muted mb-0">Aucune annexe liée à l’accusé de réception.</p>
            @endif
        </div>

        {{-- RETOUR --}}
        <a href="{{ route('reponses.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>

    @else
        <p class="text-danger">
            <i class="fas fa-exclamation-circle me-2"></i>
            Télégramme non trouvé.
        </p>
    @endif

</div>
@endsection
