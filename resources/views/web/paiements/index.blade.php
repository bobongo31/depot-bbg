@extends('layouts.app')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <style>
        body {
            background: url('images/fpc.jpg') no-repeat center center fixed;
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
                <h3 class="text-center display-2 text-dark mb-2">Liste des Débits</h3>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('web.paiements.create') }}" class="btn btn-primary">Ajouter un Débit</a>
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

        <!-- Affichage des messages de succès ou d'erreur -->
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
                            <td>{{ $paiement->matieres_taxables }}</td>
                            <td class="prix-matiere">{{ number_format($paiement->prix_matiere, 2, ',', ' ') }} €</td>
                            <td class="prix-a-payer">{{ number_format($paiement->prix_a_payer, 2, ',', ' ') }} €</td>
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
                                <button type="button" class="btn btn-success btn-sm confirm-button" data-toggle="modal" data-target="#visaModal" data-id="{{ $paiement->id }}">
                                    VISA
                                </button>
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
        let paiementId;

        // Fonction de mise à jour des prix à payer et des dates
        function updatePaiements() {
            const currentDate = moment(); // Récupère la date actuelle
            $('tbody tr').each(function() {
                const $row = $(this);
                const prixMatiere = parseFloat($row.find('.prix-matiere').text().replace(',', '.').replace(' €', '').trim());

                // Calcul du prix à payer (5 % du prix de la matière)
                const prixAPayer = prixMatiere * 0.05;
                $row.find('.prix-a-payer').text(number_format(prixAPayer, 2, ',', ' ') + ' €');

                const dateOrdonnancement = moment($row.find('td:nth-child(4)').text(), 'DD/MM/YYYY');
                const dateAccuseReception = moment($row.find('td:nth-child(5)').text(), 'DD/MM/YYYY');

                // Coût d'Opportunité (jours)
                const coutOpportunite = dateAccuseReception.diff(dateOrdonnancement, 'days');
                $row.find('.cout-opportunite').text(coutOpportunite);

                // Date de Paiement
                const datePaiement = moment($row.find('td:nth-child(7)').text(), 'DD/MM/YYYY');
                $row.find('.date-paiement').text(datePaiement.format('DD/MM/YYYY'));

                // Retard de Paiement
                const retardPaiement = currentDate.diff(datePaiement, 'days');
                $row.find('.retard-paiement').text(retardPaiement > 0 ? `${retardPaiement} jours` : 'Aucun retard');
            });
        }

        // Fonction pour formater les nombres avec des séparateurs
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            const n = !isFinite(+number) ? 0 : +number;
            const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
            const sep = typeof thousands_sep === 'undefined' ? ',' : thousands_sep;
            const dec = typeof dec_point === 'undefined' ? '.' : dec_point;
            let toFixedFix = function(n, prec) {
                const k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
            const s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

        // Initialisation de l'affichage des paiements
        updatePaiements();

        // Gestionnaire de clics pour le bouton de confirmation
        $('.confirm-button').on('click', function() {
            paiementId = $(this).data('id'); // Récupération de l'ID du paiement
        });

        // Gestionnaire de clics pour le bouton de confirmation dans le modal
        $('#confirm-paiement-button').on('click', function() {
            const avis = $('#avis').val();
            if (!avis) {
                alert('Veuillez sélectionner un avis.');
                return;
            }

            $.ajax({
                url: '/paiements/' + paiementId + '/visa',
                method: 'POST',
                data: {
                    avis: avis,
                    _token: '{{ csrf_token() }}' // Ajout du token CSRF
                },
                success: function(response) {
                    // Mettez à jour l'interface utilisateur en fonction de la réponse
                    $('#visaModal').modal('hide');
                    alert('Mise à jour réussie : ' + response.message);
                    location.reload(); // Recharger la page pour voir les modifications
                },
                error: function(xhr) {
                    $('#visaModal').modal('hide');
                    alert('Erreur : ' + xhr.responseJSON.message);
                }
            });
        });
    });
</script>
@endsection
