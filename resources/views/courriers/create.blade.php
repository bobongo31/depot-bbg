@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user() && Auth::user()->role !== 'agent')
        <div class="alert alert-danger">
            <i class="fa-solid fa-triangle-exclamation"></i> 
            Accès refusé. Vous devez être un agent pour ajouter un courrier.
        </div>
        <a href="{{ route('courriers.index') }}" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left"></i> Retour à la liste des courriers
        </a>
    @else
        <h2><i class="fa-solid fa-envelope-open-text"></i> Ajouter un courrier reçu</h2>

        <form action="{{ route('courriers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Afficher les erreurs de validation -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="date_reception" class="form-label">
                    <i class="fa-solid fa-calendar"></i> Date de réception
                </label>
                <input type="date" class="form-control" id="date_reception" name="date_reception" required>
            </div>

            <div class="mb-3">
                <label for="numero_enregistrement" class="form-label">
                    <i class="fa-solid fa-hashtag"></i> Numéro d'enregistrement
                </label>
                <select class="form-control" id="numero_enregistrement" name="numero_enregistrement" required>
                    <option value="" disabled selected>Choisir un numéro d'enregistrement</option>
                    @foreach($numEnregistrements as $id => $numero)
                        <option value="{{ $numero }}">{{ $numero }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nom_expediteur" class="form-label">
                    <i class="fa-solid fa-user"></i> Nom de l'expéditeur
                </label>
                <input type="text" class="form-control" id="nom_expediteur" name="nom_expediteur" required>
            </div>

            <div class="mb-3">
                <label for="numero_reference" class="form-label">
                    <i class="fa-solid fa-bookmark"></i> Numéro de référence
                </label>
                <input type="text" class="form-control" id="numero_reference" name="numero_reference">
            </div>

            <div class="mb-3">
                <label for="resume" class="form-label">
                    <i class="fa-solid fa-align-left"></i> Résumé
                </label>
                <textarea class="form-control" id="resume" name="resume" required></textarea>
            </div>

            <div class="mb-3">
                <label for="observation" class="form-label">
                    <i class="fa-solid fa-eye"></i> Observation
                </label>
                <textarea class="form-control" id="observation" name="observation"></textarea>
            </div>

            <div class="mb-3">
                <label for="annexes" class="form-label">
                    <i class="fa-solid fa-file-arrow-up"></i> Annexes (JPG, PDF, DOCX, etc.)
                </label>
                <input type="file" class="form-control" id="annexes" name="annexes[]" multiple>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fa-solid fa-check"></i> Enregistrer
            </button>
        </form>
    @endif
</div>
@endsection
