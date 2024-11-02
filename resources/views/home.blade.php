@extends('layouts.app')

@section('content')
<style>
    body {
        background-image: url('{{ asset('images/fpc.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
    }
    table {
        font-size: 12px; /* Taille du texte pour le tableau */
        table-layout: fixed; /* Utiliser un layout fixe pour respecter les largeurs des colonnes */
        width: 100%; /* S'assurer que la table prend toute la largeur */
    }
    thead th {
        font-size: 10px; /* Taille du texte pour les en-têtes */
        width: 12.5%; /* Largeur fixe pour chaque colonne (total de 100% divisé par 8 colonnes) */
    }
    th, td {
        padding: 8px; /* Ajuster le padding des cellules pour contrôler l'espacement */
        text-align: center; /* Centrer le contenu des cellules */
        height: 10px; /* Hauteur fixe pour chaque ligne */
    }
    .status-label {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 5px;
        color: white;
        font-weight: bold;
    }
    .status-paye {
        background-color: #28a745;
    }
    /* Styles personnalisés pour les boutons */
    .btn-custom {
        padding: 3px 9px; /* Ajustez la taille du padding */
        font-size: 9px; /* Ajustez la taille du texte */
        min-width: 20px; /* Largeur minimale pour les boutons */
    }
</style>

<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="display-4 text-white">Tableau de Bord</h1>
    <p class="lead text-white">Visualisez et gérez vos paiements confirmés.</p>

    <!-- Bouton de déconnexion à gauche -->
    <div class="text-left mb-3">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Déconnexion</button>
        </form>
    </div>

    <!-- Autres contenus de la page -->
    <div class="mb-3">
        <a href="{{ route('web.clients.index') }}" class="btn btn-primary btn-sm">Gérer les redevables</a>
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
            <a href="{{ route('web.paiements.index') }}" class="btn btn-success btn-sm">Gérer les paiements</a>
        @else
            <p class="text-danger">Vous n'avez pas les permissions nécessaires pour gérer les paiements.</p>
        @endif
    </div>

    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
        <table class="table table-striped table-bordered table-hover bg-light">
            <thead class="thead-primary">
                <tr>
                    <th>Nom Redevable</th>
                    <th>Prix à Payer (5%)</th>
                    <th>Coût d'Opportunité (jours)</th>
                    <th>Date de Paiement</th>
                    <th>Retard de Paiement</th>
                    <th>Nom de l'Ordonnanceur</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filtrer pour ne montrer que les paiements confirmés -->
                @foreach ($paiements as $paiement)
                    @if ($paiement->status === 'validé') <!-- Vérifiez le statut ici -->
                        <tr id="paiement-row-{{ $paiement->id }}">
                            <td>{{ $paiement->client->nom_redevable }}</td>

                            @php
                                $prixMatiere = $paiement->prix_matiere;
                                $prixAPayer = $prixMatiere ? $prixMatiere * 0.05 : 0;
                            @endphp
                            <td class="prix-a-payer">{{ number_format($prixAPayer, 2, ',', ' ') }} €</td>

                            <td class="cout-opportunite">
                                {{ \Carbon\Carbon::parse($paiement->date_ordonancement)->diffInDays(\Carbon\Carbon::now()) }} jours
                            </td>

                            <td class="date-paiement">
                                {{ \Carbon\Carbon::parse($paiement->date_ordonancement)->addDays(10)->format('d/m/Y') }}
                            </td>

                            <td class="retard-paiement">
                                @php
                                    $datePrevue = \Carbon\Carbon::parse($paiement->date_ordonancement)->addDays(10);
                                    $dateActuelle = \Carbon\Carbon::now();
                                    $retard = $dateActuelle->diffInDays($datePrevue, false);
                                @endphp
                                {{ $retard > 0 ? $retard . ' jours' : 'Pas de retard' }}
                            </td>

                            <td>{{ $paiement->nom_ordonanceur }}</td>
                            <td>
                                <span class="status-label status-paye">
                                    Validé
                                </span>
                            </td>
                            
                            <td>
                                <a href="{{ route('edit', $paiement->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                                <form action="{{ route('destroy', $paiement->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
