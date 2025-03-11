@extends('layouts.app')

@section('content')
    <div class="custom-box">
        <h1 class="text-center">Liste des courriers reçus</h1>
    </div>

    <table id="courriersTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Date d'accuse réception</th>
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
                    <!-- Affichage des commentaires sous forme de section (si non vide) -->
                    @if($courrier->commentaires)
                        <div class="commentaires-section mt-2">
                            @foreach(explode("\n", $courrier->commentaires) as $commentaire)
                                <p>{{ $commentaire }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- Formulaire pour ajouter un commentaire (visible seulement pour le chef_service) -->
                    @if(Auth::user() && Auth::user()->role === 'chef_service')
                        <form class="comment-form" data-id="{{ $courrier->id }}">
                            <input type="text" name="commentaire" placeholder="Ajouter un commentaire" class="form-control" />
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Ajouter Commentaire</button>
                        </form>
                    @endif
                </td>
                    <td>
                        {{ ucfirst($courrier->statut) }}
                        @if(Auth::user() && Auth::user()->role === 'chef_service')
                            <select class="form-control mt-2 status-select" data-id="{{ $courrier->id }}">
                                <option value="reçu" {{ $courrier->statut == 'reçu' ? 'selected' : '' }}>Reçu</option>
                                <option value="en attente" {{ $courrier->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                                <option value="traité" {{ $courrier->statut == 'traité' ? 'selected' : '' }}>Traité</option>
                            </select>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>

                        <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?');">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
    // Ajouter un commentaire via AJAX
    $('.comment-form').submit(function(e) {
        e.preventDefault();
        var commentaire = $(this).find('input[name="commentaire"]').val();
        var courrier_id = $(this).data('id');

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
                        // Ajoute le nouveau commentaire sous forme de paragraphe
                        var newComment = '<p>' + response.commentaire + '</p>';
                        $(this).closest('td').find('.commentaires-section').append(newComment);
                        $(this).find('input[name="commentaire"]').val(''); // Vide le champ commentaire
                        alert(response.message);  // Affiche le message de succès
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


