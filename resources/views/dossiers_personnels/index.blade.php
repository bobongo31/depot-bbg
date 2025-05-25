@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette fonctionnalité vous permet de <strong>gérer les dossiers RH</strong> de chaque employé, y compris les contrats, les absences, les évaluations et les pièces administratives.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container">
    @if (session('code_acces_valide'))
        <h1><i class="fas fa-folder-open"></i> Dossiers Personnels</h1>

        <div class="scroll-animated mb-3">
            <a href="{{ route('dossiers_personnels.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Ajouter un Dossier Personnel
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="scroll-animated table-responsive">
            <table class="scroll-animated table table-bordered">
                <thead>
                    <tr>
                        <th><i class="fas fa-user"></i> Agent</th>
                        <th><i class="fas fa-briefcase"></i> Poste</th>
                        <th><i class="fas fa-id-badge"></i> Matricule</th>
                        <th><i class="fas fa-calendar-alt"></i> Date d'Embauche</th>
                        <th><i class="fas fa-file-contract"></i> Type de Contrat</th>
                        <th><i class="fas fa-paperclip"></i> Annexes</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dossiers as $dossier)
                        <tr>
                            <td>{{ $dossier->agent->name }}</td>
                            <td>{{ $dossier->poste }}</td>
                            <td>{{ $dossier->matricule }}</td>
                            <td>{{ $dossier->date_embauche ? \Carbon\Carbon::parse($dossier->date_embauche)->format('d/m/Y') : 'N/A' }}</td>
                            <td>{{ $dossier->contrat_type }}</td>
                            
                            {{-- Colonne annexes --}}
                            <td>
                                @forelse($dossier->annexes as $annexe)
                                    <div>
                                        <a href="{{ Storage::url($annexe->path) }}" target="_blank">
                                            📎 {{ basename($annexe->path) }}
                                        </a>
                                    </div>
                                @empty
                                    <em>Aucune</em>
                                @endforelse
                            </td>

                            <td>
                                <a href="{{ route('dossiers_personnels.edit', $dossier->id) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('dossiers_personnels.destroy', $dossier->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce demande ?');">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <h2 class="text-center text-dark mb-4">
            <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
        </h2>

        <div class="card shadow-lg">
        <div class="card-header text-white bg-primary text-center">
                            🔐 Authentification Sécurisée
                        </div>
            <div class="card-body">
                <form method="POST" action="{{ route('code.verifier') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="code" class="form-label">
                            <i class="fas fa-key"></i> Veuillez saisir le code d'accès
                        </label>
                        <input type="text" class="form-control" name="code" id="code" required>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle"></i> Valider
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
