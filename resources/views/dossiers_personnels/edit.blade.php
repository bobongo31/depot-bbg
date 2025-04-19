@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h1><i class="fas fa-folder-open"></i> Modifier le Dossier Personnel</h1>

    <form action="{{ route('dossiers_personnels.update', $dossierPersonnel->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="scroll-animated mb-3">
            <label for="agent_id" class="form-label">
                <i class="fas fa-user"></i> Agent
            </label>
            <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror">
                <option value="">Sélectionner un agent</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ $dossierPersonnel->agent_id == $agent->id ? 'selected' : '' }}>
                        {{ $agent->name }}
                    </option>
                @endforeach
            </select>
            @error('agent_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="poste" class="form-label">
                <i class="fas fa-briefcase"></i> Poste
            </label>
            <input type="text" name="poste" id="poste" class="form-control @error('poste') is-invalid @enderror" value="{{ old('poste', $dossierPersonnel->poste) }}">
            @error('poste')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="date_embauche" class="form-label">
                <i class="fas fa-calendar-alt"></i> Date d'Embauche
            </label>
            <input type="date" name="date_embauche" id="date_embauche" class="form-control @error('date_embauche') is-invalid @enderror" value="{{ old('date_embauche', $dossierPersonnel->date_embauche) }}">
            @error('date_embauche')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="matricule" class="form-label">
                <i class="fas fa-id-badge"></i> Matricule
            </label>
            <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule', $dossierPersonnel->matricule) }}">
            @error('matricule')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="contrat_type" class="form-label">
                <i class="fas fa-file-contract"></i> Type de Contrat
            </label>
            <input type="text" name="contrat_type" id="contrat_type" class="form-control @error('contrat_type') is-invalid @enderror" value="{{ old('contrat_type', $dossierPersonnel->contrat_type) }}">
            @error('contrat_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="notes" class="form-label">
                <i class="fas fa-sticky-note"></i> Notes
            </label>
            <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $dossierPersonnel->notes) }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Mettre à jour
        </button>
    </form>
</div>
@endsection
