@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <style>
        /* Styles personnalisés */
        body {
            background: url('{{ asset('images/fpc.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.9;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.80);
            padding: 30px;
            border-radius: 40px;
        }

        .table thead th, .table-title {
            background-color: #3b93d2;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            padding: 3px;
        }

        .table tbody tr {
            font-size: 12px;
        }

        .table tbody tr:hover {
            background-color: #3697cf;
        }

        .table tfoot th {
            background-color: #66798d;
            font-weight: bold;
            font-size: 11px;
        }

        .btn-primary {
            background-color: #3b93d2;
            border: none;
        }

        .table {
            margin-bottom: 0;
        }

        .btn-sm {
            font-size: 12px;
            padding: 5px 10px;
        }

        .search-bar {
            max-width: 400px;
            margin-bottom: 20px;
        }

        .search-container {
            display: flex;
            justify-content: flex-end;
        }

        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>

<div class="container-fluid" style="position: relative;">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h3 class="text-center display-2 text-dark mb-2">Liste des notes des Débits</h3>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('web.paiements.create') }}" class="btn btn-primary">Ajouter une note Débit</a>
                    <form action="{{ route('web.paiements.index') }}" method="GET" class="d-flex w-50">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-secondary ml-2">Rechercher</button>
                    </form>
                </div>
                <div class="text-left mb-3">
                    <a href="{{ url('/') }}" class="btn btn-secondary">Retourner vers le Tableau de Bord</a>
                </div>
            </div>
        </div>

        <div class="mb-3">
        <!-- Bouton de tri -->
        <form method="GET" action="{{ route('web.paiements.index') }}" class="d-inline">
            <select name="sort" onchange="this.form.submit()" class="form-control form-control-sm d-inline" style="width: auto;">
                <option value="">Trier par</option>
                <option value="date_paiement" {{ request('sort') == 'date_paiement' ? 'selected' : '' }}>Date de Paiement</option>
                <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Statut</option>
            </select>
        </form>
    </div>  
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="table-title mb-3 p-2">NOTES DES DEBITS</div>
        <div class="table-responsive mt-7">
            <table class="table table-striped table-bordered table-hover bg-light">
                <thead class="thead-primary">
                    <tr>
                        <th>Matière Taxable</th>
                        <th>Prix de la Matière</th>
                        <th>Prix à Payer</th>
                        <th>Date d'Ordonnancement</th>
                        <th>Date d'Accusé de Réception</th>
                        <th>Coût d'Opportunité (jours)</th>
                        <th>Date de Paiement</th>
                        <th>Retard de Paiement</th>
                        <th>Nom de l'Ordonnanceur</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paiements as $paiement)
                        <tr id="paiement-row-{{ $paiement->id }}">
                            <td>{{ $paiement->matiere_taxable }}</td>
                            <td class="prix-matiere">{{ number_format($paiement->prix_matiere, 2, ',', ' ') }} FC</td>
                            <td class="prix-a-payer">{{ number_format($paiement->prix_a_payer, 2, ',', ' ') }} FC</td>
                            <td>{{ \Carbon\Carbon::parse($paiement->date_ordonancement)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($paiement->date_accuse_reception)->format('d/m/Y') }}</td>
                            <td class="cout-opportunite"></td>
                            <td class="date-paiement"></td>
                            <td class="retard-paiement"></td>
                            <td>{{ $paiement->nom_ordonanceur }}</td>
                            <td>
                                @if ($paiement->status === 'validé')
                                    <span class="badge badge-success">Validé</span>
                                @elseif ($paiement->status === 'en attente')
                                    <span class="badge badge-warning">En Attente</span>
                                @else
                                    <span class="badge badge-danger">Rejeté</span>
                                @endif
                            </td>
                            <td>
                            @if (auth()->user()->hasRole('payment_validator'))
                                <!-- Bouton pour afficher le modal -->
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#visaModal" data-id="{{ $paiement->id }}">
                                 VISA
                                </button>
                            @endif
                        </td>
                        </tr>
                        @if ($paiement->status === 'rejeté')
                            <tr>
                                <td colspan="10" class="text-danger text-center">
                                    Attention : Ce paiement a été rejeté. Veuillez vérifier les détails.
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modal pour modifier la date d'accusé de réception -->
        <div class="modal fade" id="accuseModal" tabindex="-1" role="dialog" aria-labelledby="accuseModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="accuseModalLabel">Modifier la Date d'Accusé de Réception</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="accuse-form">
                            <div class="form-group">
                                <label for="date_accuse_reception">Nouvelle Date d'Accusé de Réception</label>
                                <input type="text" class="form-control" id="date_accuse_reception" name="date_accuse_reception" required>
                            </div>
                            <input type="hidden" id="paiement-id" name="paiement_id">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="update-accuse">Mettre à jour</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="visaModal" tabindex="-1" role="dialog" aria-labelledby="visaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visaModalLabel">Confirmation de Paiement</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Êtes-vous sûr que le paiement est valide ?
                        <div class="form-group mt-3">
                            <label for="avis">Votre avis</label>
                            <select name="avis" class="form-control" id="avis" required>
                                <option value="">Sélectionnez...</option>
                                <option value="validé">Validé</option>
                                <option value="rejeté">Rejeté</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" id="confirm-paiement-button">Confirmer le paiement</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            {{ $paiements->appends(request()->input())->links() }}
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function updatePaiements() {
            const currentDate = moment();
            $('tbody tr').each(function() {
                const $row = $(this);
                const dateAccuseReception = moment($row.find('td:nth-child(5)').text(), 'DD/MM/YYYY');

                // Calcul du coût d'opportunité
                const coutOpportunite = currentDate.diff(dateAccuseReception, 'days');
                $row.find('.cout-opportunite').text(coutOpportunite + ' jours');

                // Calcul de la date de paiement
                let datePaiement = dateAccuseReception.clone().add(10, 'days');
                if (datePaiement.day() === 0) { // Si c'est dimanche
                    datePaiement.add(1, 'day'); // Report au lundi
                }
                $row.find('.date-paiement').text(datePaiement.format('DD/MM/YYYY'));

                // Calcul du retard de paiement
                const retardPaiement = currentDate.diff(datePaiement, 'days');
                $row.find('.retard-paiement').text(retardPaiement > 0 ? `${retardPaiement} jours` : 'Aucun retard');
            });
        }

        // Mettre à jour les paiements lors du chargement de la page
        updatePaiements();

        // Gestion de la modal
    let PaiementId;

    // Ouvrir le modal lors du clic sur "VISA"
    $('.btn-success').on('click', function() {
        // Récupérer l'ID du paiement
        PaiementId = $(this).data('id');
        // Afficher le modal
        $('#visaModal').modal('show');
    });

    // Gestion de la soumission du formulaire après confirmation
    $('#confirm-paiement-button').on('click', function(event) {
        event.preventDefault(); // Empêche la soumission par défaut
        const avis = $('#avis').val().trim(); // Utiliser trim() pour enlever les espaces

        if (avis && PaiementId) {
            // Envoyer une requête PUT pour mettre à jour le paiement
            $.ajax({
                url: '/paiements/' + PaiementId + '/confirm', // Remplacer par votre route Laravel
                method: 'PUT',
                data: {
                    avis: avis,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Mettre à jour l'affichage dans le tableau
                    $(`#paiement-row-${PaiementId} td:last-child`).html(`<span class="badge badge-${avis === 'validé' ? 'success' : 'danger'}">${avis.charAt(0).toUpperCase() + avis.slice(1)}</span>`);
                    // Fermer le modal
                    $('#visaModal').modal('hide');
                    // Recharger la page après la validation
                    location.reload(); // Rechargement de la page
                },
                error: function(xhr) {
                    console.error(xhr);
                    alert('Une erreur est survenue. Veuillez réessayer.');
                }
            });
        } else {
            alert('Veuillez sélectionner un avis.');
        }
    });
});
</script>
@endsection
