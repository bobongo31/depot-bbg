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
                <tr onclick="window.location='{{ route('courriers.show', $courrier->id) }}'" style="cursor: pointer;">
                    <td>{{ $courrier->date_reception }}</td>
                    <td>{{ $courrier->numero_enregistrement }}</td>
                    <td>{{ $courrier->numero_reference ?? 'N/A' }}</td>
                    <td>{{ $courrier->nom_expediteur }}</td>
                    <td>{{ $courrier->resume }}</td>
                    <td>{{ $courrier->observation ?? 'Aucune observation' }}</td>
                    <td>{{ $courrier->commentaires ?? 'Aucun commentaire' }}</td>
                    <td>{{ ucfirst($courrier->statut) }}</td>
                    <td>
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
    <!-- Ajouter DataTables et FontAwesome -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#courriersTable').DataTable({
                "paging": true,
                "ordering": true,
                "searching": true
            });
        });
    </script>

    <!-- Ajout du CSS personnalisé -->
<style>
    /* Style personnalisé pour le conteneur */
    .custom-box {
        background-color: #007BFF; /* Bleu clair */
        padding: 20px; /* Espacement autour du texte */
        border-radius: 10px; /* Coins arrondis */
        text-align: center; /* Centrer le contenu */
        margin: 20px 0; /* Marge autour du conteneur */
    }

    /* Style pour le titre */
    .custom-box h1 {
        color: white; /* Texte en blanc */
        font-size: 2rem; /* Taille de police */
        font-weight: bold; /* Police en gras */
        margin: 0; /* Enlever la marge par défaut */
    }
</style>
@endpush
