@extends('layouts.app') 

@section('content')
@if (session('alerte_abonnement'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('alerte_abonnement') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
@endif

<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>vous connecter avec les identifiants de votre entreprise</strong> afin d’accéder à la gestion intelligente et sécurisée du courrier.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>


<!-- Arrière-plan avec l'icône d'automatisation -->
<div class="automation-bg">
    <i class="fas fa-gears"></i>
</div>

<div class="container scroll-animated" style="position: relative; z-index: 1;">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Icône d'automatisation -->
            <div class="text-center my-4">
                <div style="display: inline-block; background-color: rgba(255,255,255,0.1); border-radius: 50%; padding: 20px;">
                    <i class="fas fa-gears fa-3x text-primary"></i>
                </div>
                <h5 class="mt-3 text-muted">GESTION INTELLIGENTE DE COURRIER</h5>
            </div>

            <div class="card">
                <div class="card-header">{{ __('Connectez-vous') }}</div>

                <div class="card-body scroll-animated">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Entreprise -->
                        <div class="row mb-3 scroll-animated">
                            <label for="entreprise" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-building me-2"></i>{{ __('Entreprise') }}
                            </label>
                            <div class="col-md-6">
                                <input id="entreprise" type="text" class="form-control @error('entreprise') is-invalid @enderror" name="entreprise" value="{{ old('entreprise') }}" required autofocus>
                                @error('entreprise')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Nom -->
                        <div class="row mb-3 scroll-animated">
                            <label for="name" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-user me-2"></i>{{ __('Nom') }}
                            </label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Mot de passe -->
                        <div class="row mb-3 scroll-animated">
                            <label for="password" class="col-md-4 col-form-label text-md-end">
                                <i class="fas fa-lock me-2"></i>{{ __('Mot de Passe') }}
                            </label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Remember me -->
                        <div class="row mb-3 scroll-animated">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('Souviens-toi de moi') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton -->
                        <div class="row mb-0 scroll-animated">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-right-to-bracket me-2"></i>{{ __('Se connecter') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Mot de passe oublié?') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- CSS intégré dans la vue pour tests -->
<style>
    /* Fond de la page */
    body {
        background-color: #f0f4f8;
        background-size: cover;
        background-attachment: fixed;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        color: #000;
    }

    /* Icône d'automatisation */
    .automation-bg {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 30vw;
        color: rgba(0, 0, 0, 0.1);
        z-index: 0;
        pointer-events: none;
    }

    .automation-bg i {
        animation: spin 60s linear infinite;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

            /* Conteneur principal avec une légère opacité */
.container {
    opacity: 0.45; /* Applique une opacité de 45% sur tout le conteneur */
}

/* Carte (formulaire) avec une opacité ajustée à 45% */
.card {
    background: rgba(255, 255, 255, 0.45); /* Réduit l'opacité de l'arrière-plan de la carte à 45% */
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25); /* Ombre légère pour donner du relief */
    color: #000;
    border-radius: 20px;
}

/* Corps de la carte avec opacité ajustée */
.card-body {
    background-color: rgba(255, 255, 255, 0.45); /* Applique une opacité de 45% sur l'arrière-plan du formulaire */
    color: #000; /* Texte noir pour garantir la lisibilité */
}

/* Champs de formulaire avec opacité ajustée à 90% et une bordure fine avec 80% d'opacité */
.form-control {
    background-color: rgb(255, 255, 255); /* Opacité de 90% sur les champs de saisie */
    color:rgb(5, 26, 46); /* Texte noir dans les champs */
    border: 1px solid rgb(255, 255, 255); /* Bordure avec 80% d'opacité */
    border-radius: 5px; /* Coins arrondis pour un aspect plus doux */
}

/* Placeholder avec une opacité ajustée */
.form-control::placeholder {
    color: rgba(0, 0, 0, 0.6); /* Légèrement transparent pour le texte du placeholder */
}

/* Bouton de soumission avec une couleur de fond légèrement modifiée */
.btn-primary {
    background-color: rgba(0, 123, 255, 0.85); /* Opacité légèrement réduite pour un fond plus léger */
    border-color: rgba(0, 123, 255, 0.85);
}

/* Lien de mot de passe oublié avec couleur adaptée */
.btn-link {
    color: rgba(255, 255, 255, 0.85); /* Lien légèrement transparent */
}



    /* Animation au scroll */
    .scroll-animated {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }

    .scroll-animated.visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
@endsection
