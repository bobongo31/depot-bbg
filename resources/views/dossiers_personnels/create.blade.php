@extends('layouts.app')

@section('content')
<div class="container">
    <div class="scroll-animated custom-box mb-4">
    <h1><i class="fas fa-folder-plus"></i> Ajouter un Dossier Personnel</h1>
    </div>

    <form action="{{ route('dossiers_personnels.store') }}" method="POST">
        @csrf

        <div class="scroll-animated mb-3">
            <label for="agent_id" class="form-label">
                <i class="fas fa-user"></i> Agent
            </label>
            <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror">
                <option value="">Sélectionner un agent</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
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
            <input type="text" name="poste" id="poste" class="form-control @error('poste') is-invalid @enderror" value="{{ old('poste') }}">
            @error('poste')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="date_embauche" class="form-label">
                <i class="fas fa-calendar-alt"></i> Date d'Embauche
            </label>
            <input type="date" name="date_embauche" id="date_embauche" class="form-control @error('date_embauche') is-invalid @enderror" value="{{ old('date_embauche') }}">
            @error('date_embauche')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="matricule" class="form-label">
                <i class="fas fa-id-card"></i> Matricule
            </label>
            <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}">
            @error('matricule')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="contrat_type" class="form-label">
                <i class="fas fa-file-signature"></i> Type de Contrat
            </label>
            <input type="text" name="contrat_type" id="contrat_type" class="form-control @error('contrat_type') is-invalid @enderror" value="{{ old('contrat_type') }}">
            @error('contrat_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="scroll-animated mb-3">
            <label for="notes" class="form-label">
                <i class="fas fa-sticky-note"></i> Notes
            </label>
            <textarea name="notes" id="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
         <!-- Bouton pour ouvrir le modal -->
        <button type="button" class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#annexeModal">
            <i class="fas fa-paperclip"></i> Ajouter une Annexe
        </button>
   

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Ajouter
        </button>
    </form>
</div>
<!-- Modal -->
<div class="modal fade" id="annexeModal" tabindex="-1" aria-labelledby="annexeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="annexeModalLabel"><i class="fas fa-file-upload"></i> Ajouter une Annexe</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-center">

        <p>Choisissez une méthode :</p>

        <div class="d-grid gap-2">
            <!-- Option 1: Upload -->
            <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('fileInput').click();">
                <i class="fas fa-upload"></i> Télécharger un fichier
            </button>



        <!-- Champ caché pour upload -->
        <input type="file" name="annexe" id="fileInput" class="form-control mt-3 d-none">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

@endsection
