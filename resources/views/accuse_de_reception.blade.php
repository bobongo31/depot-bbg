@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user() && Auth::user()->role !== 'agent')
        <div class="alert alert-danger">Accès refusé. Vous devez être un agent pour ajouter un accusé de réception.</div>
        <a href="{{ route('accuses.index') }}" class="btn btn-primary">Retour à la liste des accusés</a>
    @else
        <h1>Accusé de Réception</h1>
        <form action="{{ route('accuse.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Champ Date de réception -->
            <div class="mb-3">
                <label for="date_reception" class="form-label">Date de réception</label>
                <input type="date" name="date_reception" id="date_reception" class="form-control" required>
            </div>

            <!-- Champ Numéro d'enregistrement -->
            <div class="mb-3">
                <label for="numero_enregistrement" class="form-label">Numéro d'enregistrement</label>
                <input type="text" name="numero_enregistrement" id="numero_enregistrement" class="form-control" value="{{ $numeroEnregistrement }}" readonly>
            </div>

            <!-- Champ Réceptionné par -->
            <div class="mb-3">
                <label for="receptionne_par" class="form-label">Réceptionné par</label>
                <input type="text" name="receptionne_par" id="receptionne_par" class="form-control" required>
            </div>

            <!-- Champ Objet -->
            <div class="mb-3">
                <label for="objet" class="form-label">Objet</label>
                <input type="text" name="objet" id="objet" class="form-control" required>
            </div>

            <!-- Champ Annexes -->
            <div class="mb-3">
                <label for="annexes" class="form-label">Téléversez le courrier</label>
                <input type="file" name="annexes[]" id="annexes" class="form-control" multiple>
            </div>

            <!-- Champ Avis -->
            <div class="mb-3">
                <label for="avis" class="form-label">Avis</label>
                <textarea name="avis" id="avis" class="form-control"></textarea>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    @endif
</div>
@endsection
