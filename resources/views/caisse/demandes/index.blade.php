@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface permet à la <strong>caisse de soumettre une demande de décaissement ou de financement</strong> pour une activité spécifique, en toute sécurité et traçabilité.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="container-fluid" style="background: linear-gradient(to right,rgb(255, 255, 255),rgb(255, 255, 255)); padding: 20px;">
    @if (session('code_acces_valide'))
        <!-- CONTENU PRINCIPAL : Demandes de Fonds -->
        <h1 class="scroll-animated text-dark"><i class="fas fa-wallet"></i> Demandes de Fonds</h1>
        <a href="{{ route('caisse.demandes.create') }}" class="scroll-animated btn btn-primary mb-3">
            <i class="fas fa-plus-circle"></i> Nouvelle Demande
        </a>
        
        @if($demandes->isEmpty())
            <p class="scroll-animated text-dark">Aucune demande de fonds trouvée.</p>
        @else
            <table class="scroll-animated table table-bordered">
                <thead>
                    <tr>
                        <th><i class="fas fa-euro-sign"></i> Montant</th>
                        <th><i class="fas fa-align-left"></i> Motif</th>
                        <th><i class="fas fa-flag"></i> Statut</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                        <tr>
                            <td>{{ number_format($demande->montant, 2, ',', ' ') }} €</td>
                            <td>{{ $demande->motif }}</td>
                            <td>
                                @switch($demande->statut)
                                    @case('en_attente')
                                        <span class="badge bg-warning"><i class="fas fa-clock"></i> En attente</span>
                                        @break
                                    @case('approuve')
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approuvé</span>
                                        @break
                                    @case('rejete')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Rejeté</span>
                                        @break
                                @endswitch
                            </td>
                            <td>
                                <a href="{{ route('caisse.demandes.edit', $demande->id) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('caisse.demandes.destroy', $demande->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce demande ?');">
                                    <i class="fas fa-trash"></i>
                                    </button>
                                    <!-- Boutons Approuver et Rejeter -->
                                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#approuverModal{{ $demande->id }}">
                                        <i class="fas fa-check"></i> 
                                    </button>

                                    <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rejeterModal{{ $demande->id }}">
                                        <i class="fas fa-times"></i> 
                                    </button>

                                </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @foreach($demandes as $demande)
                    <!-- Modal Approuver -->
                    <div class="modal fade" id="approuverModal{{ $demande->id }}" tabindex="-1" aria-labelledby="approuverModalLabel{{ $demande->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('caisse.demandes.approuver', $demande->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approuverModalLabel{{ $demande->id }}"><i class="fas fa-check"></i> Confirmer l'approbation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                            Voulez-vous vraiment <strong>approuver</strong> cette demande de {{ number_format($demande->montant, 2, ',', ' ') }} € ?
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Oui, approuver</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>

                    <!-- Modal Rejeter -->
                    <div class="modal fade" id="rejeterModal{{ $demande->id }}" tabindex="-1" aria-labelledby="rejeterModalLabel{{ $demande->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('caisse.demandes.rejeter', $demande->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="rejeterModalLabel{{ $demande->id }}"><i class="fas fa-times"></i> Confirmer le rejet</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                            </div>
                            <div class="modal-body">
                            Êtes-vous sûr de vouloir <strong>rejeter</strong> cette demande ?
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-danger">Oui, rejeter</button>
                            </div>
                        </div>
                        </form>
                    </div>
                    </div>
                    @endforeach

                </tbody>
            </table>
        @endif
    @else
        <!-- FORMULAIRE DE CODE D'ACCÈS -->
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
