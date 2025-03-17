@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-reply"></i> Enregistrer une Réponse</h2>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    <form action="{{ route('reponses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="numero_enregistrement" class="form-label">
                <i class="fas fa-hashtag"></i> Numéro d'enregistrement
            </label>
            <input type="text" name="numero_enregistrement" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="numero_reference" class="form-label">
                <i class="fas fa-bookmark"></i> Numéro de Référence
            </label>
            <input type="text" name="numero_reference" class="form-control" required>
        </div>

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

        <div class="mb-3">
            <label for="observation" class="form-label">
                <i class="fas fa-user"></i> Expéditeur
            </label>
            <textarea name="observation" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="commentaires" class="form-label">
                <i class="fas fa-align-left"></i> Résumé
            </label>
            <textarea name="commentaires" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label for="annexes" class="form-label">
                <i class="fas fa-paperclip"></i> Annexes (fichiers acceptés : jpg, png, pdf, docx)
            </label>
            <input type="file" name="annexes[]" class="form-control" multiple>
        </div>

        @if(isset($telegramme_id))
            <input type="hidden" name="telegramme_id" value="{{ $telegramme_id }}">
        @elseif(isset($telegrammes) && $telegrammes->isNotEmpty())
            <div class="mb-3">
                <label for="telegramme_id" class="form-label">
                    <i class="fas fa-envelope"></i> Télégramme
                </label>
                <select name="telegramme_id" class="form-control" required>
                    @foreach($telegrammes as $telegramme)
                        <option value="{{ $telegramme->id }}">{{ $telegramme->numero_enregistrement }} - {{ $telegramme->numero_reference }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Soumettre
        </button>
    </form>
</div>
@endsection
