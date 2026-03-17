@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h2><i class="fas fa-calendar-plus"></i> Nouvelle Demande de Congé</h2>

    <form action="{{ route('demandes_conges.store') }}" method="POST">
        @csrf

        <div class="scroll-animated mb-3">
            <label for="agent_id" class="form-label">
                <i class="fas fa-user"></i> Agent
            </label>
            <select name="agent_id" class="form-control" required>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="scroll-animated mb-3">
            <label for="type_conge" class="form-label">
                <i class="fas fa-plane-departure"></i> Type de Congé
            </label>
            <select name="type_conge" class="form-control" required>
                <option value="vacances"><i class="fas fa-umbrella-beach"></i> Vacances</option>
                <option value="maladie"><i class="fas fa-notes-medical"></i> Maladie</option>
                <option value="autre"><i class="fas fa-question-circle"></i> Autre</option>
            </select>
        </div>

        <div class="scroll-animated mb-3">
            <label for="date_debut" class="form-label">
                <i class="fas fa-calendar-day"></i> Date de Début
            </label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>

        <div class="scroll-animated mb-3">
            <label for="date_fin" class="form-label">
                <i class="fas fa-calendar-check"></i> Date de Fin
            </label>
            <input type="date" name="date_fin" class="form-control" required>
        </div>

        <div class="scroll-animated mb-3">
            <label for="motif" class="form-label">
                <i class="fas fa-align-left"></i> Motif
            </label>
            <textarea name="motif" class="form-control" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-success">
            <i class="fas fa-paper-plane"></i> Envoyer la Demande
        </button>
    </form>
</div>
@endsection
