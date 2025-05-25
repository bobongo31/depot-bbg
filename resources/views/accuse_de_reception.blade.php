@extends('layouts.app')

@section('content')
<!-- Message informatif superposé -->
<div id="overlayMessage" class="overlay-message">
    <div class="message-box">
    <h5 class="mb-3">
            <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
        </h5>
        <p class="mb-4">
            Cette fonctionnalité vous permet d'accuser la réception officielle d'un courrier entrant et de le référencer correctement dans le système pour un suivi optimal.<br>
            Assurez-vous de remplir tous les champs nécessaires pour garantir une gestion efficace des documents.
        </p>
        <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
            <i class="fas fa-check-circle me-1"></i> J'ai compris
        </button>
    </div>
</div>

<div class="scroll-animated container">
    @auth
    @if(session('code_acces_valide') !== true)
    <!-- Formulaire de code d'accès -->
            <h2 class="text-center text-dark mb-4 scroll-animated">
                <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
            </h2>

            <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header text-white bg-primary text-center">
                            🔐 Authentification Sécurisée
                        </div>
                        <div class="card-body bg-light text-dark">
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
        @else
            <!-- Formulaire d'ajout d'accusé de réception -->
            @if(Auth::user()->role !== 'agent')
                <div class="alert alert-danger">
                    <i class=" fa-solid fa-triangle-exclamation"></i> 
                    Accès refusé. Vous devez être un agent pour ajouter un accusé de réception.
                </div>
                <a href="{{ route('accuses.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left"></i> Retour à la liste des accusés
                </a>
            @else
            <div class="scroll-animated custom-box text-center mb-4">
                <h1><i class="fa-solid fa-file-signature "></i> Accusé de Réception</h1>
                <form action="{{ route('accuse.store') }}" method="POST" enctype="multipart/form-data">
            </div>
                    @csrf

                    <!-- Champ Date de réception -->
                    <div class="mb-3 scroll-animated">
                        <label for="date_reception" class="form-label">
                            <i class="fa-solid fa-calendar"></i> Date de réception
                        </label>
                        <input type="date" name="date_reception" id="date_reception" class="form-control" required>
                    </div>

                    <!-- Champ Numéro d'enregistrement -->
                    <div class="mb-3 scroll-animated">
                        <label for="numero_enregistrement">
                            <i class="fa-solid fa-hashtag"></i> Numéro d'Enregistrement
                        </label>
                        <input type="text" name="numero_enregistrement" id="numero_enregistrement" class="form-control" required>
                    </div>

                    <!-- Champ Réceptionné par -->
                    <div class="mb-3 scroll-animated">
                        <label for="receptionne_par" class="form-label">
                            <i class="fa-solid fa-user"></i> Réceptionné par
                        </label>
                        <input type="text" name="receptionne_par" id="receptionne_par" class="form-control" required>
                    </div>

                    <!-- Champ Objet -->
                    <div class="mb-3 scroll-animated">
                        <label for="objet" class="form-label">
                            <i class="fa-solid fa-book"></i> Objet
                        </label>
                        <input type="text" name="objet" id="objet" class="form-control" required>
                    </div>

                    <!-- Champ Annexes -->
                    <div class="mb-3 scroll-animated">
                        <label for="annexes" class="form-label">
                            <i class="fa-solid fa-file-arrow-up"></i> Téléversez le courrier
                        </label>
                        <input type="file" name="annexes[]" id="annexes" class="form-control" multiple>
                    </div>

                    <!-- Bouton de soumission -->
                    <button type="submit" class="btn btn-success ">
                        <i class="fa-solid fa-check"></i> Enregistrer
                    </button>
                </form>
            @endif
        @endif
    @else
        <!-- Si l'utilisateur n'est pas authentifié -->
        <h2 class="text-center text-dark mb-4">
            <i class="fas fa-lock"></i> Veuillez vous connecter pour accéder à cette page
        </h2>
    @endauth
</div>
@endsection
