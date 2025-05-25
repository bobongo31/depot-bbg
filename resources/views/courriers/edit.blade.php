@extends('layouts.app')

@section('content')
    @if(Auth::user() && Auth::user()->role !== 'admin')
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i> Accès refusé. Vous n'avez pas les permissions nécessaires pour modifier ce courrier.
        </div>
        <a href="{{ route('courriers.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left"></i> Retour à la liste des courriers
        </a>
    @else
        <h1 class="scroll-animated mb-4">
            <i class="fa-solid fa-pen-to-square"></i> Modifier le courrier
        </h1>

        <form action="{{ route('courriers.update', $courrier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="scroll-animated form-group">
                <label for="date_reception">
                    <i class="fa-solid fa-calendar-day"></i> Date d'accusé de réception
                </label>
                <input type="date" name="date_reception" value="{{ old('date_reception', $courrier->date_reception) }}" class="form-control" readonly>
            </div>

            <div class="scroll-animated form-group">
                <label for="numero_enregistrement">
                    <i class="fa-solid fa-hashtag"></i> Numéro d'enregistrement
                </label>
                <input type="text" name="numero_enregistrement" value="{{ old('numero_enregistrement', $courrier->numero_enregistrement) }}" class="form-control" readonly>
            </div>

            <div class="scroll-animated form-group">
                <label for="numero_reference">
                    <i class="fa-solid fa-barcode"></i> Numéro de référence
                </label>
                <input type="text" name="numero_reference" value="{{ old('numero_reference', $courrier->numero_reference) }}" class="form-control" readonly>
            </div>

            <div class="scroll-animated form-group">
                <label for="nom_expediteur">
                    <i class="fa-solid fa-user"></i> Nom de l'expéditeur
                </label>
                <input type="text" name="nom_expediteur" value="{{ old('nom_expediteur', $courrier->nom_expediteur) }}" class="form-control" readonly>
            </div>

            <div class="scroll-animated form-group">
                <label for="resume">
                    <i class="fa-solid fa-file-lines"></i> Résumé
                </label>
                <textarea name="resume" class="form-control" readonly>{{ old('resume', $courrier->resume) }}</textarea>
            </div>

            <div class="scroll-animated form-group">
                <label for="observation">
                    <i class="fa-solid fa-eye"></i> Observation
                </label>
                <textarea name="observation" class="form-control" readonly>{{ old('observation', $courrier->observation) }}</textarea>
            </div>

            <div class="scroll-animated form-group">
                <label for="commentaires">
                    <i class="fa-solid fa-comments"></i> Commentaires
                </label>
                <textarea name="commentaires" class="form-control">{{ old('commentaires', $courrier->commentaires) }}</textarea>
            </div>

            <div class="scroll-animated form-group">
                <label for="statut">
                    <i class="fa-solid fa-flag"></i> Statut
                </label>
                <select name="statut" class="form-control">
                    <option value="reçu" {{ $courrier->statut == 'reçu' ? 'selected' : '' }}>Reçu</option>
                    <option value="en attente" {{ $courrier->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                    <option value="traité" {{ $courrier->statut == 'traité' ? 'selected' : '' }}>Traité</option>
                </select>
            </div>

            <button type="submit" class="scroll-animated btn btn-success mt-3">
                <i class="fa-solid fa-check"></i> Mettre à jour
            </button>
        </form>
    @endif
@endsection
