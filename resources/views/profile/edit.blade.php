@extends('layouts.app')

@section('content')


 <!-- Bouton pour changer de thème -->
 <div class="scroll-animated mb-6">
        <button id="theme-toggle" class="bg-gray-200 p-2 rounded">
            <i class="fas fa-moon"></i> Changer le thème
        </button>
    </div>
<div class="scroll-animated container">
    <h2><i class="fa-solid fa-user"></i> Modifier votre profil</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Champ Nom -->
        <div class="scroll-animated mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ Email -->
        <div class="scroll-animated mb-3">
            <label for="email" class="form-label">Adresse email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ Mot de Passe -->
        <div class="scroll-animated mb-3">
            <label for="password" class="form-label">Nouveau mot de passe (facultatif)</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Champ de confirmation du mot de passe -->
        <div class="scroll-animated mb-3">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection
