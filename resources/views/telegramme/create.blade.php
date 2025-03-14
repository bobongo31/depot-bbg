@extends('layouts.app')

@section('content')
<div class="container">
    <h2>📨 Envoyer un Télégramme</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('telegramme.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Champ numero_enregistrement -->
        <div class="mb-3">
            <label for="numero_enregistrement" class="form-label">📝 Numéro d'Enregistrement</label>
            <input type="text" class="form-control" name="numero_enregistrement" required>
        </div>

        <!-- Champ numero_reference -->
        <div class="mb-3">
            <label for="numero_reference" class="form-label">📑 Numéro de Référence</label>
            <input type="text" class="form-control" name="numero_reference" required>
        </div>

        <!-- Champ service_concerne -->
        <div class="mb-3">
            <label for="service_concerne" class="form-label">📍 Service Concerné</label>
            <input type="text" class="form-control" name="service_concerne" required>
        </div>

        <!-- Champ observation -->
        <div class="mb-3">
            <label for="observation" class="form-label">📝 Observation</label>
            <textarea class="form-control" name="observation" rows="3" required></textarea>
        </div>

        <!-- Champ commentaires -->
        <div class="mb-3">
            <label for="commentaires" class="form-label">🖊 Commentaires</label>
            <textarea class="form-control" name="commentaires" rows="4" required></textarea>
        </div>

        <!-- Champ contenu -->
        <div class="mb-3">
            <label for="contenu" class="form-label">✍ Contenu du Télégramme</label>
            <textarea class="form-control" name="contenu" rows="4" required></textarea>
        </div>

        <!-- Champ annexes -->
        <div class="mb-3">
            <label for="annexes" class="form-label">📎 Ajouter des Annexes</label>
            <input type="file" class="form-control" name="annexes[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        </div>

        <button type="submit" class="btn btn-primary">📤 Envoyer</button>
        <a href="{{ route('reponses.index') }}" class="btn btn-secondary">🔙 Retour</a>
    </form>
</div>
@endsection
