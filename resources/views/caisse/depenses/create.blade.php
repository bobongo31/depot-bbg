@extends('layouts.app')

@section('content')
<div class="container">
@if (session('code_acces_valide'))

    {{-- Formulaire de Dépense --}}
    <div class="scroll-animated custom-box mb-4">
    <h1><i class="fas fa-money-bill-wave text-success"></i> Nouvelle Dépense</h1>
    </div>

    {{-- ⚠️ enctype ajouté ici pour permettre l'envoi de fichiers --}}
    <form action="{{ route('caisse.depenses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="scroll-animated mb-3">
            <label for="rubrique" class="form-label">
                <i class="fas fa-tag"></i> Rubrique
            </label>
            <input type="text" class="form-control" id="rubrique" name="rubrique" value="{{ old('rubrique') }}" required>
            @error('rubrique')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="montant" class="form-label">
                <i class="fas fa-euro-sign"></i> Montant (€)
            </label>
            <input type="number" step="0.01" class="form-control" id="montant" name="montant" value="{{ old('montant') }}" required>
            @error('montant')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="date_depense" class="form-label">
                <i class="fas fa-calendar-day"></i> Date
            </label>
            <input type="date" class="form-control" id="date_depense" name="date_depense" value="{{ old('date_depense') }}" required>
            @error('date_depense')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="description" class="form-label">
                <i class="fas fa-align-left"></i> Description
            </label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Bouton d'ouverture du modal --}}
        <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#justificatifModal">
            <i class="fas fa-file-upload"></i> Ajouter un Justificatif
        </button>

        {{-- Bouton de soumission --}}
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Enregistrer
        </button>
    </form>

    {{-- Modal pour justificatifs (en dehors du <form>) --}}
    <div class="modal fade" id="justificatifModal" tabindex="-1" aria-labelledby="justificatifModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="justificatifModalLabel">Ajouter un justificatif</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-tools fa-2x text-warning mb-3"></i>
                    <p class="fs-5">🚧 Cette fonctionnalité est en cours de développement.</p>
                    <p class="text-muted">Merci de votre patience pendant que nous travaillons sur cet espace.</p>
                </div>

            </div>
        </div>
    </div>

@else

    {{-- FORMULAIRE DE CODE D'ACCÈS --}}
    <div class="scroll-animated container">
        <h2 class="scroll-animated text-center text-dark mb-4">
            <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
        </h2>

        <div class="scroll-animated card shadow-lg">
            <div class="scroll-animated card-header text-dark bg-light">
                <i class="fas fa-shield-alt"></i> 🔐 Authentification Sécurisée
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('code.verifier') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="code" class="form-label">
                            <i class="fas fa-key"></i> Veuillez saisir le code d'accès
                        </label>
                        <input type="text" class="form-control" name="code" id="code" required>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle"></i> Valider
                    </button>
                </form>
            </div>
        </div>
    </div>

@endif
</div>
@endsection
