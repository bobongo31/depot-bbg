@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-paper-plane"></i> Envoyer un Télégramme</h2>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <form action="{{ route('telegramme.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Champ numero_enregistrement -->
        <div class="mb-3">
            <label for="numero_enregistrement" class="form-label">
                <i class="fas fa-hashtag"></i> Numéro d'Enregistrement
            </label>
            <input type="text" class="form-control" name="numero_enregistrement" required>
        </div>

        <!-- Champ numero_reference -->
        <div class="mb-3">
            <label for="numero_reference" class="form-label">
                <i class="fas fa-bookmark"></i> Numéro de Référence
            </label>
            <input type="text" class="form-control" name="numero_reference" required>
        </div>

        <!-- Champ service_concerne -->
        <div class="mb-3">
            <label for="service_concerne" class="form-label">
                <i class="fas fa-building"></i> Service Concerné
            </label>
            <select name="service_concerne" class="form-control">
                <option value="RH">Ressources Humaines</option>
                <option value="Comptabilité">Comptabilité</option>
                <option value="Informatique">Informatique</option>
                <option value="Logistique">Logistique</option>
            </select>
        </div>

        <!-- Champ observation -->
        <div class="mb-3">
            <label for="observation" class="form-label">
                <i class="fas fa-user"></i> Expéditeur
            </label>
            <textarea class="form-control" name="observation" rows="3" required></textarea>
        </div>

        <!-- Champ commentaires -->
        <div class="mb-3">
            <label for="commentaires" class="form-label">
                <i class="fas fa-align-left"></i> Résumé
            </label>
            <textarea class="form-control" name="commentaires" rows="4" required></textarea>
        </div>

        <!-- Champ annexes -->
        <div class="mb-3">
            <label for="annexes" class="form-label">
                <i class="fas fa-paperclip"></i> Ajouter des Annexes
            </label>
            <input type="file" class="form-control" name="annexes[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Envoyer
        </button>
        <a href="{{ route('reponses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </form>
</div>
@endsection
