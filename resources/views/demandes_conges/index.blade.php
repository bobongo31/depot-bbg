@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet d'<strong>accéder à la liste des demandes de congé</strong> soumises par les employés. Vous pouvez approuver ou rejeter ces demandes directement depuis cette page.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="container">
    @if (session('code_acces_valide'))
        {{-- CONTENU PRINCIPAL --}}
        <div class="scroll-animated custom-box mb-4">
        <h1><i class="fas fa-calendar-check text-primary me-2"></i>Demandes de Congé</h1>
        </div>
        <div class="scroll-animated custom-box mb-4">
        <a href="{{ route('demandes_conges.create') }}" class="btn btn-primary my-3">
            <i class="fas fa-plus-circle"></i> Nouvelle Demande
        </a>
        </div>

        <table class="scroll-animated table table-hover mt-3">
            <thead class="scroll-animated table-light">
                <tr>
                    <th><i class="fas fa-user"></i> Agent</th>
                    <th><i class="fas fa-suitcase"></i> Type de Congé</th>
                    <th><i class="fas fa-calendar-day"></i> Date de Début</th>
                    <th><i class="fas fa-calendar-day"></i> Date de Fin</th>
                    <th><i class="fas fa-info-circle"></i> Statut</th>
                    <th><i class="fas fa-cogs"></i> Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($demandes as $demande)
                <tr>
                    <td>{{ $demande->agent->name }}</td>
                    <td>{{ ucfirst($demande->type_conge) }}</td>
                    <td>{{ $demande->date_debut }}</td>
                    <td>{{ $demande->date_fin }}</td>
                    <td>
                        <span class="badge bg-{{ $demande->statut == 'acceptee' ? 'success' : ($demande->statut == 'refusee' ? 'danger' : 'warning') }}">
                            {{ ucfirst($demande->statut) }}
                        </span>
                    </td>
                    <td>
                        <a href="#" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('demandes_conges.destroy', $demande) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette demande ?')">
                            @csrf
                            @method('DELETE')
                            <button type="bouton" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash"></i>
                                </button>
                        </form>

                        {{-- Bouton Approuver --}}
                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalApprouver{{ $demande->id }}">
                            <i class="fas fa-check"></i>
                        </button>

                        {{-- Bouton Rejeter --}}
                        <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalRejeter{{ $demande->id }}">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
                {{-- Modal Approuver --}}
                <div class="modal fade" id="modalApprouver{{ $demande->id }}" tabindex="-1" aria-labelledby="approuverLabel{{ $demande->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('demandes_conges.approuver', $demande->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="approuverLabel{{ $demande->id }}"><i class="fas fa-check-circle me-1"></i> Confirmation</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment <strong>approuver</strong> cette demande de congé ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-success">Oui, approuver</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal Rejeter --}}
                <div class="modal fade" id="modalRejeter{{ $demande->id }}" tabindex="-1" aria-labelledby="rejeterLabel{{ $demande->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('demandes_conges.rejeter', $demande->id) }}">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="rejeterLabel{{ $demande->id }}"><i class="fas fa-times-circle me-1"></i> Confirmation</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    Voulez-vous vraiment <strong>rejeter</strong> cette demande de congé ?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-danger">Oui, rejeter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <h2 class="scroll-animated text-center text-dark mb-4">
            <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
        </h2>

        <div class="scroll-animated card shadow-lg">
        <div class="scroll-animated card-header text-white bg-primary text-center">
                            🔐 Authentification Sécurisée
                        </div>
            <div class="card-body">
                <form method="POST" action="{{ route('code.verifier') }}">
                    @csrf
                    <div class="scroll-animated mb-3">
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
