@extends('layouts.app')

@section('content')
<style>
    /* Styles globaux */
    body {
        background-image: url('{{ asset('images/fpc.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
        background-attachment: fixed;
        color: white;
    }

    .card {
        background-color: rgba(0, 0, 255, 0.7);
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #1b7bdc;
        color: white;
    }

    .badge {
        font-size: 0.8rem;
    }

    .btn-modifier {
        font-size: 14px;
        padding: 5px 15px;
    }

    .chart-container {
        position: relative;
        margin: auto;
        width: 80%;
        max-width: 800px;
        margin-bottom: 20px;
    }

    .btn-legend {
        font-size: 8px; /* Taille du texte plus grande */
        padding: 4px 10px; /* Plus d'espace autour du texte */
        margin: 5px; /* Espacement autour des boutons */
        width: auto; /* Largeur automatique en fonction du contenu */
    }

    .badge-warning {
        background-color: #f1c40f !important; /* Jaune plus éclatant */
        color: #fff !important; /* Texte en blanc pour contraste */
    }



    /* Styles pour le modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 300px;
        text-align: center;
    }
</style>

<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 style="color: white; font-weight: bold; text-align: center;">Tableau de Bord</h1>
    <p class="lead text-center" style="color: #2ecc71;">Visualisez et gérez vos paiements.</p>

    <!-- Grid Layout: Boutons à gauche et Graphique à droite -->
    <div class="row">
        <!-- Colonne des boutons (à gauche) -->
        <div class="col-md-4">
            <div style="text-align: left;">
                @if (auth()->user()->hasRole('payment_validator'))
                <button type="button" onclick="openModal()" class="btn btn-info mb-3">Générer un Rapport PDF</button>
                @endif          
            </div>

            <div class="text-left mb-3">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Déconnexion</button>
                </form>
            </div>

            <div class="mb-3">
                <a href="{{ route('web.clients.index') }}" class="btn btn-primary btn-sm">Gérer les redevables</a>
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
                    <a href="{{ route('web.paiements.index') }}" class="btn btn-success btn-sm">Gérer les paiements</a>
                @else
                    <p class="text-danger">Vous n'avez pas les permissions nécessaires pour gérer les paiements.</p>
                @endif
            </div>
        </div>

        <!-- Colonne du graphique (à droite) -->
        <div class="col-md-8">
            <!-- Graphique des paiements -->
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
                <div class="chart-container">
                    <canvas id="paymentBarChart"></canvas>
                </div>

                <!-- Légende sous forme de boutons -->
                <div style="text-align: center;">
                    <button id="toggleValid" class="btn btn-info btn-legend">Afficher/Masquer Validé</button>
                    <button id="toggleRejet" class="btn btn-danger btn-legend">Afficher/Masquer Rejeté</button>
                    <button id="toggleEntente" class="btn btn-warning btn-legend">Afficher/Masquer En Entente</button>
                </div>
            @else
                <p class="text-danger">Vous n'avez pas les permissions nécessaires pour afficher le graphique.</p>
            @endif
        </div>
    </div>

    <!-- Modal pour choix de type de rapport -->
    <div id="rapportModal" style="display: none;" class="modal-overlay">
        <div class="modal-content">
            <h2 style="color: white; font-weight: bold;">Choisissez le Type de Rapport</h2>
            <button onclick="generateReport('tous_paiements')" class="btn btn-primary mb-2">Tous les Paiements</button>
            <button onclick="openSpecificReport()" class="btn btn-secondary mb-2">Paiement Spécifique</button>
            <button onclick="generateReport('tous_clients')" class="btn btn-success mb-2">Tous les Redevables</button>
            <button onclick="openClientSpecificReport()" class="btn btn-secondary mb-2">Redevables Spécifique</button>
            <button onclick="closeModal()" class="btn btn-danger">Fermer</button>
        </div>
    </div>

    <!-- Liste des paiements -->
    @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
        <div class="row">
            @foreach ($paiements as $paiement)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">{{ $paiement->client->nom_redevable }}</h5>
                            <a href="{{ route('web.paiements.edit', $paiement->id) }}" class="btn btn-warning btn-modifier">Modifier</a>
                        </div>
                        <div class="card-body">
                            <p><strong>Matière Taxable:</strong> {{ $paiement->matiere_taxable }}</p>
                            <p><strong>Prix à Payer:</strong> {{ number_format($paiement->prix_a_payer) }} FC</p>
                            <p><strong>Date de Paiement:</strong> {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
                            <p><strong>Statut:</strong> 
                                <span class="badge 
                                    @if ($paiement->status === 'validé') badge-success
                                    @elseif ($paiement->status === 'rejeté') badge-danger
                                    @elseif ($paiement->status === 'en entente') badge-warning
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst($paiement->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $paiements->links() }}
        </div>
    @else
        <p class="text-warning">Les détails des paiements ne sont pas accessibles pour votre rôle.</p>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const paymentData = {
        labels: ['Validé', 'Rejeté', 'En Entente'],
        datasets: [{
            label: 'État des Paiements',
            data: [
                {{ $paiements->where('status', 'validé')->count() }},
                {{ $paiements->where('status', 'rejeté')->count() }},
                {{ $paiements->where('status', 'en entente')->count() }}
            ],
            backgroundColor: ['rgba(46, 204, 113, 0.8)', 'rgba(231, 76, 60, 0.8)', 'rgba(241, 196, 15, 0.8)'],
            borderColor: ['rgba(46, 204, 113, 1)', 'rgba(231, 76, 60, 1)', 'rgba(241, 196, 15, 1)'],
            borderWidth: 1
        }]
    };

        const config = {
        type: 'bar',
        data: paymentData,
        options: { 
            responsive: true, 
            scales: { y: { beginAtZero: true } },
            plugins: {
                legend: {
                    labels: {
                        color: 'red'
                    }
                }
            },
            elements: {
                bar: {
                    backgroundColor: 'rgba(255, 255, 255, 1)' // Arrière-plan des barres en blanc
                }
            },
            backgroundColor: 'white', // Arrière-plan global du graphique en blanc
        }
    };


    const paymentBarChart = new Chart(document.getElementById('paymentBarChart'), config);
    document.getElementById('paymentBarChart').style.backgroundColor = 'white';

    document.getElementById('toggleValid').addEventListener('click', function() {
        paymentBarChart.data.datasets[0].data[0] = paymentBarChart.data.datasets[0].data[0] === null ? {{ $paiements->where('status', 'validé')->count() }} : null;
        paymentBarChart.update();
    });

    document.getElementById('toggleRejet').addEventListener('click', function() {
        paymentBarChart.data.datasets[0].data[1] = paymentBarChart.data.datasets[0].data[1] === null ? {{ $paiements->where('status', 'rejeté')->count() }} : null;
        paymentBarChart.update();
    });

    document.getElementById('toggleEntente').addEventListener('click', function() {
        // Vérification de la présence de données pour "En Entente"
        const ententeCount = {{ $paiements->where('status', 'en entente')->count() }};
        // Si la donnée est actuellement masquée (null), on la remet avec la valeur de count
        paymentBarChart.data.datasets[0].data[2] = paymentBarChart.data.datasets[0].data[2] === null ? ententeCount : null;
        paymentBarChart.update();
    });
    console.log('Validé:', validData, 'Rejeté:', rejetData, 'Entente:', ententeData);


    function openModal() {
        document.getElementById('rapportModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('rapportModal').style.display = 'none';
    }

    function generateReport(type) {
        window.location.href = `/rapport/rapport_${type}`;
    }

    function openSpecificReport() {
        let paiementId = prompt("Entrez l'ID du paiement:");
        if (paiementId) {
            window.location.href = `/rapport/rapport_paiement/${paiementId}`;
        }
    }

    function openClientSpecificReport() {
        let clientId = prompt("Entrez l'ID du redevable:");
        if (clientId) {
            window.location.href = `/rapport/rapport_client/${clientId}`;
        }
    }
</script>

@endsection
