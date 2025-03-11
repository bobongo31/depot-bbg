@extends('layouts.app')

@section('content')
<div class="container">
    @if(Auth::user() && Auth::user()->role !== 'agent')
        <div class="alert alert-danger">Accès refusé. Vous devez être un agent pour ajouter un courrier.</div>
        <a href="{{ route('courriers.index') }}" class="btn btn-primary">Retour à la liste des courriers</a>
    @else
        <h2>Ajouter un courrier reçu</h2>

        <form action="{{ route('courriers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Afficher les erreurs de validation -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label for="date_reception" class="form-label">Date de réception</label>
                <input type="date" class="form-control" id="date_reception" name="date_reception" required>
            </div>

            <div class="mb-3">
                <label for="numero_enregistrement" class="form-label">Numéro d'enregistrement</label>
                <select class="form-control" id="numero_enregistrement" name="numero_enregistrement" required>
                    <option value="" disabled selected>Choisir un numéro d'enregistrement</option>
                    @foreach($numEnregistrements as $id => $numero)
                        <option value="{{ $numero }}">{{ $numero }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="nom_expediteur" class="form-label">Nom de l'expéditeur</label>
                <input type="text" class="form-control" id="nom_expediteur" name="nom_expediteur" required>
            </div>

            <div class="mb-3">
                <label for="numero_reference" class="form-label">Numéro de référence</label>
                <input type="text" class="form-control" id="numero_reference" name="numero_reference">
            </div>

            <div class="mb-3">
                <label for="resume" class="form-label">Résumé</label>
                <textarea class="form-control" id="resume" name="resume" required></textarea>
            </div>

            <div class="mb-3">
                <label for="observation" class="form-label">Observation</label>
                <textarea class="form-control" id="observation" name="observation"></textarea>
            </div>

            <div class="mb-3">
                <label for="commentaires" class="form-label">Commentaires (Réservé au Chef de Service)</label>
                <textarea class="form-control" id="commentaires" name="commentaires"></textarea>
            </div>

            <div class="mb-3">
                <label for="annexes" class="form-label">Annexes (JPG, PDF, DOCX, etc.)</label>
                <input type="file" class="form-control" id="annexes" name="annexes[]" multiple>
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    @endif
</div>
@endsection
