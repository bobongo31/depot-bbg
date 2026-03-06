@extends('layouts.app')

@section('content')

@if(!Auth::user() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'agent'))
    <div class="alert alert-danger">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Accès refusé.
    </div>
@else

<h1 class="scroll-animated mb-4">
    <i class="fa-solid fa-pen-to-square"></i> Modifier le courrier expédié
</h1>

<form action="{{ route('courrier_expedie.update', $courrierExpedie->id) }}" method="POST">
@csrf
@method('PUT')

<div class="scroll-animated mb-3">
    <label><i class="fa-solid fa-list-ol"></i> N°</label>
    <input type="text" name="numero_ordre"
        value="{{ old('numero_ordre', $courrierExpedie->numero_ordre) }}"
        class="form-control">
</div>

<div class="scroll-animated mb-3">
    <label><i class="fa-solid fa-calendar"></i> Date d’expédition</label>
    <input type="date" name="date_expedition"
        value="{{ old('date_expedition', $courrierExpedie->date_expedition) }}"
        class="form-control">
</div>

<div class="scroll-animated mb-3">
    <label><i class="fa-solid fa-hashtag"></i> Numéro lettre</label>
    <input type="text" name="numero_lettre"
        value="{{ old('numero_lettre', $courrierExpedie->numero_lettre) }}"
        class="form-control">
</div>

<div class="scroll-animated mb-3">
    <label><i class="fa-solid fa-user"></i> Destinataire</label>
    <input type="text" name="destinataire"
        value="{{ old('destinataire', $courrierExpedie->destinataire) }}"
        class="form-control">
</div>

<div class="scroll-animated mb-3">
    <label><i class="fa-solid fa-file-lines"></i> Résumé</label>
    <textarea name="resume" class="form-control">{{ old('resume', $courrierExpedie->resume) }}</textarea>
</div>

<div class="scroll-animated mb-3">
    <label><i class="fa-solid fa-eye"></i> Observation</label>
    <textarea name="observation" class="form-control">{{ old('observation', $courrierExpedie->observation) }}</textarea>
</div>

<button class="btn btn-success mt-3">
    <i class="fa-solid fa-check"></i> Mettre à jour
</button>

<a href="{{ route('courrier_expedie.show', $courrierExpedie->id) }}"
   class="btn btn-secondary mt-3">
    <i class="fa-solid fa-arrow-left"></i> Annuler
</a>

</form>
@endif
@endsection
