@extends('layouts.app')

@section('content')
<style>
    /* Appliquez l'image d'arrière-plan avec une opacité de 50 % */
    .background {
        position: fixed; /* Fixe l'image à l'arrière-plan */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('/images/fpc.jpg'); /* Chemin vers votre image */
        background-size: cover; /* Ajuste l'image pour couvrir tout l'écran */
        background-position: center; /* Centre l'image */
        opacity: 100%; /* Opacité de 50 % */
        z-index: -1; /* Assurez-vous que l'image est derrière le contenu */
    }

    /* Style pour le contenu */
    .content-wrapper {
        position: relative; /* Position relative pour le contenu */
        z-index: 1; /* Assurez-vous que le contenu est au-dessus de l'image */
    }

    /* Style pour le formulaire de connexion */
    .card {
        background-color: rgba(255, 255, 255, 0.8); /* Blanc avec opacité pour le fond du formulaire */
        border-radius: 10px; /* Bords arrondis pour le formulaire */
        backdrop-filter: blur(10px); /* Flou d'arrière-plan pour une meilleure lisibilité */
    }
</style>

<div class="background"></div> <!-- Image d'arrière-plan -->

<div class="content-wrapper"> <!-- Wrapper pour le contenu -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Connexion') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Nom') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Adresse email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Mot de passe') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Se souvenir de moi') }}
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Connexion') }}
                            </button>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="mt-3">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Mot de passe oublié ?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
