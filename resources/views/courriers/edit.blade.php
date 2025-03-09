@extends('layouts.app')

@section('content')
    <h1>Modifier le courrier</h1>

    <form action="{{ route('courriers.update', $courrier->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="date_reception">Date d'accuse réception</label>
            <input type="date" name="date_reception" value="{{ old('date_reception', $courrier->date_reception) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="numero_enregistrement">Numéro d'enregistrement</label>
            <input type="text" name="numero_enregistrement" value="{{ old('numero_enregistrement', $courrier->numero_enregistrement) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="numero_reference">Numéro de référence</label>
            <input type="text" name="numero_reference" value="{{ old('numero_reference', $courrier->numero_reference) }}" class="form-control">
        </div>

        <div class="form-group">
            <label for="nom_expediteur">Nom de l'expéditeur</label>
            <input type="text" name="nom_expediteur" value="{{ old('nom_expediteur', $courrier->nom_expediteur) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="resume">Résumé</label>
            <textarea name="resume" class="form-control" required>{{ old('resume', $courrier->resume) }}</textarea>
        </div>

        <div class="form-group">
            <label for="observation">Observation</label>
            <textarea name="observation" class="form-control">{{ old('observation', $courrier->observation) }}</textarea>
        </div>

        <div class="form-group">
            <label for="commentaires">Commentaires</label>
            <textarea name="commentaires" class="form-control">{{ old('commentaires', $courrier->commentaires) }}</textarea>
        </div>

        <div class="form-group">
        <label for="statut">Statut</label>
        <select name="statut" class="form-control" required>
            <option value="reçu" {{ $courrier->statut == 'reçu' ? 'selected' : '' }}>Reçu</option>
            <option value="en attente" {{ $courrier->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
            <option value="traité" {{ $courrier->statut == 'traité' ? 'selected' : '' }}>Traité</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>

    </form>
@endsection
