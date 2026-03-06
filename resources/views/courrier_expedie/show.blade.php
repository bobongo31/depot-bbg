@extends('layouts.app')

@section('content')
<div class="scroll-animated container">

    <h1 class="mb-4">
        <i class="fa-solid fa-envelope-open-text"></i>
        Détails du courrier expédié
    </h1>

    <div class="card shadow-sm rounded-4">
        <div class="card-body">

            {{-- INFORMATIONS GÉNÉRALES --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>N° :</strong> {{ $courrierExpedie->numero_ordre }}</p>
                    <p>
                        <strong>Date d’expédition :</strong>
                        {{ $courrierExpedie->date_expedition?->format('d/m/Y') }}
                    </p>
                </div>

                <div class="col-md-6">
                    <p><strong>N° Lettre :</strong> {{ $courrierExpedie->numero_lettre }}</p>
                </div>
            </div>

            <hr>

            {{-- 🎯 DESTINATAIRES --}}
            <div class="mb-3">
                <p class="mb-1"><strong>Destinataires :</strong></p>

                {{-- 🔵 Destinataire principal --}}
                <span class="badge bg-primary mb-1 d-inline-block">
                    <i class="fa-solid fa-building"></i>
                    {{ $courrierExpedie->destinataire }}
                </span>

                {{-- 🟡 Copies --}}
                @if($courrierExpedie->copies && $courrierExpedie->copies->count())
                    <div class="mt-2">
                        @foreach($courrierExpedie->copies as $copy)
                            <span class="badge bg-warning text-dark me-1 mb-1">
                                <i class="fa-solid fa-building"></i>
                                {{ $copy->service }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted mt-1">
                        <small>Aucune copie</small>
                    </div>
                @endif
            </div>

            <hr>

            {{-- CONTENU --}}
            <p>
                <strong>Résumé :</strong><br>
                {{ $courrierExpedie->resume }}
            </p>

            @if($courrierExpedie->observation)
                <p class="mt-3">
                    <strong>Observation :</strong><br>
                    {{ $courrierExpedie->observation }}
                </p>
            @endif

            <hr>

            {{-- 📎 ANNEXES --}}
            <h5 class="mb-3">
                <i class="fa-solid fa-paperclip"></i>
                Annexes
                <span class="badge bg-secondary ms-1">
                    {{ $courrierExpedie->annexesCount() }}
                </span>
            </h5>

            @if($courrierExpedie->hasAnnexes())
                <div class="row g-3">
                    @foreach($courrierExpedie->annexes as $file)
                        @php
                            $url = asset('storage/' . $file);
                            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        @endphp

                        <div class="col-md-6 col-lg-4">
                            <div class="card shadow-sm h-100 rounded-4">

                                {{-- PDF --}}
                                @if($ext === 'pdf')
                                    <iframe
                                        src="{{ $url }}"
                                        class="w-100 rounded-top"
                                        style="height:220px; border:none;">
                                    </iframe>

                                {{-- IMAGES --}}
                                @elseif(in_array($ext, ['jpg','jpeg','png','webp']))
                                    <a href="{{ $url }}" target="_blank">
                                        <img
                                            src="{{ $url }}"
                                            class="img-fluid rounded-top"
                                            style="height:220px; object-fit:cover;"
                                            alt="Annexe">
                                    </a>

                                {{-- AUTRES --}}
                                @else
                                    <div class="d-flex align-items-center justify-content-center"
                                         style="height:220px;">
                                        <i class="fa-solid fa-file-lines fa-4x text-secondary"></i>
                                    </div>
                                @endif

                                <div class="card-body p-2 text-center">
                                    <small class="text-truncate d-block">
                                        {{ basename($file) }}
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
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'agent')
                    <a href="{{ route('courrier_expedie.edit', $courrierExpedie->id) }}"
                       class="btn btn-warning">
                        <i class="fa-solid fa-pen"></i> Modifier
                    </a>
                @endif

                <a href="{{ route('courrier_expedie.index') }}"
                   class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Retour
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
