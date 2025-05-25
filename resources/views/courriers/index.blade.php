@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>consulter tous les courriers enregistrés</strong>, triés par date ou par service émetteur, pour un accès rapide et organisé.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

@auth
    @if(session('code_acces_valide'))
    <div class="container my-4">
            <div class="scroll-animated custom-box text-center mb-4">
                <h1><i class="fas fa-envelope-open-text"></i> Liste des courriers reçus</h1>
            </div>

            <div class="scroll-animated table-responsive">
                <table id="courriersTable" class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                <tr>
                    <th>Date d'accusé réception</th>
                    <th>Numéro d'enregistrement</th>
                    <th>Numéro de référence</th>
                    <th>Nom de l'expéditeur</th>
                    <th>Résumé</th>
                    <th>Observation</th>
                    <th>Commentaires</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courriers as $courrier)
                    <tr>
                        <td>{{ $courrier->date_reception }}</td>
                        <td>{{ $courrier->numero_enregistrement }}</td>
                        <td>{{ $courrier->numero_reference ?? 'N/A' }}</td>
                        <td>{{ $courrier->nom_expediteur }}</td>
                        <td>{{ $courrier->resume }}</td>
                        <td>{{ $courrier->observation ?? 'Aucune observation' }}</td>
                        <td>
                        <div class="scroll-animated commentaires-section mb-2">
                            @if($courrier->commentaires)
                                @foreach(explode("\n", $courrier->commentaires) as $commentaire)
                                    <p class="mb-1">📝 {{ $commentaire }}</p>
                                @endforeach
                            @endif
                        </div>

                        @if(Auth::user()->role === 'admin')
                            <form class="comment-form" data-id="{{ $courrier->id }}">
                                <div class="input-group">
                                    <input type="text" name="commentaire" placeholder="Ajouter un commentaire" class="form-control" />
                                    <button type="submit" class="btn btn-outline-info btn-sm">Ajouter</button>
                                </div>
                            </form>
                        @endif
                    </td>

                        <td>
                            {{ ucfirst($courrier->statut) }}
                            @if(Auth::user()->role === 'admin')
                                <select class="form-control mt-2 status-select" data-id="{{ $courrier->id }}">
                                    <option value="reçu" {{ $courrier->statut == 'reçu' ? 'selected' : '' }}>Reçu</option>
                                    <option value="en attente" {{ $courrier->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="traité" {{ $courrier->statut == 'traité' ? 'selected' : '' }}>Traité</option>
                                </select>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?');">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <div class="scroll-animated container">
            <h2 class="scroll-animated text-center text-dark mb-4">
                <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
            </h2>

            <div class="scroll-animated card shadow-lg">
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
        </div>
    @endif
@endauth
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Ajouter un commentaire via AJAX
        $('.comment-form').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const commentaire = form.find('input[name="commentaire"]').val();
            const courrier_id = form.data('id');

            if(commentaire) {
                $.ajax({
                    url: '/courriers/' + courrier_id + '/commentaire',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        commentaire: commentaire
                    },
                    success: function(response) {
                        if(response.success) {
                            let section = form.closest('td').find('.commentaires-section');
                            if (section.length === 0) {
                                section = $('<div class="commentaires-section mt-2"></div>');
                                form.before(section);
                            }
                            section.append('<p>' + response.commentaire + '</p>');
                            form.find('input[name="commentaire"]').val('');
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Erreur lors de l\'ajout du commentaire.');
                    }
                });
            }
        });
    });
</script>
@endpush
