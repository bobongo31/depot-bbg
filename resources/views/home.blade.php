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

    /* Boutons avec effet au survol */
    .btn-hover-effect {
        background-color: #6d7474; /* Couleur de fond par défaut */
        color: white; /* Couleur du texte et des icônes par défaut */
        transition: background-color 0.3s ease, transform 0.3s ease; /* Transition de fond et agrandissement */
    }

    .btn-hover-effect:hover {
        background-color: #5a6363; /* Couleur plus foncée pour l'effet hover */
        transform: scale(1.1); /* Agrandir légèrement le bouton */
    }

    .btn-hover-effect i {
        transition: transform 0.3s ease, color 0.3s ease; /* Transition pour l'icône */
        color: white; /* Couleur des icônes par défaut */
    }

    .btn-hover-effect:hover i {
        transform: scale(1.2); /* Agrandir l'icône pendant le survol */
        color: #f0ad4e; /* Changer la couleur des icônes au survol */
    }

    .btn-hover-effect span {
        display: inline-block;
        transition: opacity 0.3s ease; /* Transition pour le texte */
        opacity: 0; /* Le texte est caché par défaut */
    }

    .btn-hover-effect:hover span {
        opacity: 1; /* Afficher le texte au survol */
        color: #f0ad4e; /* Couleur du texte au survol */
    }

    .btn-hover-effect i {
        display: inline-block;
        transition: opacity 0.3s ease;
        opacity: 1; /* L'icône est visible par défaut */
    }

    .btn-hover-effect:hover i {
        opacity: 0; /* Masquer l'icône au survol */
    }

    /* Carte */
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
        font-size: 8px;
        padding: 4px 10px;
        margin: 5px;
        width: auto;
    }

    .badge-warning {
        background-color: #f1c40f !important;
        color: #fff !important;
    }

    /* Alignement horizontal des boutons */
    .btn-container {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin: 20px 0;
    }

    /* Interaction au survol des boutons */
    .btn-container .btn {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .btn-container .btn:hover {
        background-color: #007bff !important;
        color: #fff !important;
    }

    /* Modal Background */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.3s ease-in-out;
    z-index: 1050;
}

.modal-content {
    background: #333;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 100%;
    text-align: center;
    color: white;
}

.modal-content h2 {
    font-size: 1.5rem;
    margin-bottom: 20px;
}

/* Boutons avec icônes et effet hover */
.hover-btn {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.hover-btn i {
    margin-right: 8px; /* Espacement entre l'icône et le texte */
}

.hover-btn:hover {
    transform: scale(1.1); /* Agrandissement au survol */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Effet d'ombrage */
}

/* Effet de flou sur le fond du modal */
.modal-overlay.show {
    backdrop-filter: blur(5px); /* Ajout d'un flou pour le fond */
}

/* Boutons avec effet hover */
.hover-btn {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 1rem;
}

.hover-btn:hover {
    transform: scale(1.1); /* Agrandissement au survol */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Effet d'ombrage */
}

/* Effet de flou sur le fond du modal */
.modal-overlay.show {
    backdrop-filter: blur(5px); /* Ajout d'un flou pour le fond */
}

</style>


<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 style="color: white; font-weight: bold; text-align: center;">Tableau de Bord</h1>
    <p class="lead text-center" style="color: white; font-weight: bold; text-align: center;">Visualisez et gérez vos paiements.</p>



    <div class="container mt-5">
    <div class="row">
        <!-- Barre de navigation à gauche -->
        <div class="col-md-3 col-lg-2" id="sidebar">
            <div class="list-group" style="position: fixed; top: 0; left: 0; height: 100%; width: 250px; background-color: #343a40; color: white; padding-top: 20px;">
                <!-- Bouton de génération de rapport PDF (visible si le rôle est 'payment_validator') -->
                @if (auth()->user()->hasRole('payment_validator'))
                    <button type="button" onclick="openModal()" class="list-group-item list-group-item-action btn-hover-effect" style="background-color: #6d7474; color: white; border: none;">
                        <i class="fas fa-file-pdf fa-2x"></i> <!-- Ajuster la taille de l'icône ici -->
                        <span>Générer un Rapport PDF</span>
                    </button>
                @endif

                <!-- Lien pour gérer les redevables -->
                <a href="{{ route('web.clients.index') }}" class="list-group-item list-group-item-action btn-hover-effect" style="background-color: #6d7474; color: white; border: none;">
                    <i class="fas fa-users fa-2x"></i> <!-- Ajuster la taille de l'icône ici -->
                    <span>Gérer les redevables</span>
                </a>

                <!-- Gestion des paiements et options supplémentaires -->
                @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
                    <a href="{{ route('web.paiements.index') }}" class="list-group-item list-group-item-action btn-hover-effect" style="background-color: #6d7474; color: white; border: none;">
                        <i class="fas fa-money-check-alt fa-2x"></i> <!-- Ajuster la taille de l'icône ici -->
                        <span>Gérer les paiements</span>
                    </a>
                    <button type="button" class="list-group-item list-group-item-action btn-hover-effect" style="background-color: #6d7474; color: white; border: none;" onclick="toggleGraph()">
                        <i class="fas fa-chart-line fa-2x"></i> <!-- Ajuster la taille de l'icône ici -->
                        <span>Afficher/Masquer le graphique</span>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action btn-hover-effect" style="background-color: #6d7474; color: white; border: none;" onclick="togglePaymentTable()">
                        <i class="fas fa-table fa-2x"></i> <!-- Ajuster la taille de l'icône ici -->
                        <span>Afficher/Masquer le tableau des paiements</span>
                    </button>
                @else
                    <p class="text-danger">Vous n'avez pas les permissions nécessaires pour effectuer ces actions.</p>
                @endif

                <!-- Formulaire de déconnexion avec icône -->
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action btn-hover-effect" style="background-color: #dc3545; color: white; border: none;">
                        <i class="fas fa-sign-out-alt fa-2x"></i> <!-- Ajuster la taille de l'icône ici -->
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="col-md-9 col-lg-10">
            <!-- Liste des paiements -->
            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
                <div id="payment-table" class="row" style="display: none;">
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

                <!-- Graphique des paiements -->
                <div class="mt-4">
                    <canvas id="paymentChart"></canvas>
                </div>
            @else
                <p class="text-warning">Les détails des paiements ne sont pas accessibles pour votre rôle.</p>
            @endif

        </div>
    </div>
</div>


  <!-- Modal pour choix de type de rapport -->
<div id="rapportModal" style="display: none;" class="modal-overlay">
    <div class="modal-content">
        <h2 style="color: white; font-weight: bold;">Choisissez le Type de Rapport</h2>
        
        <button onclick="generateReport('tous_paiements')" class="btn btn-primary mb-2 hover-btn">
            <i class="fas fa-list-alt"></i> Tous les Paiements
        </button>
        
        <button onclick="openSpecificReport()" class="btn btn-secondary mb-2 hover-btn">
            <i class="fas fa-search"></i> Paiement Spécifique
        </button>
        
        <button onclick="generateReport('tous_clients')" class="btn btn-success mb-2 hover-btn">
            <i class="fas fa-users"></i> Tous les Redevables
        </button>
        
        <button onclick="openClientSpecificReport()" class="btn btn-secondary mb-2 hover-btn">
            <i class="fas fa-user"></i> Redevables Spécifique
        </button>
        
        <button onclick="closeModal()" class="btn btn-danger hover-btn">
            <i class="fas fa-times"></i> Fermer
        </button>
    </div>
</div>


    <!-- Liste des paiements -->
@if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
        <div id="payment-table" class="row" style="display: none;">
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

    <!-- Graphique des paiements -->
    <div class="mt-4">
        <canvas id="paymentChart"></canvas>
    </div>
    
@else
    <p class="text-warning">Les détails des paiements ne sont pas accessibles pour votre rôle.</p>
@endif

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

        function toggleGraph() {
        const graphContainer = document.getElementById('graph-container');
        if (graphContainer.style.display === 'none' || graphContainer.style.display === '') {
            graphContainer.style.display = 'block'; // Affiche le graphique
        } else {
            graphContainer.style.display = 'none'; // Masque le graphique
        }
    }

    // Fonction pour ouvrir/fermer les modals
    function openModal() {
        document.getElementById('rapportModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('rapportModal').style.display = 'none';
    }

    // Fonction pour afficher/masquer le graphique
    function toggleGraph() {
        const graphContainer = document.getElementById('graph-container');
        graphContainer.style.display = graphContainer.style.display === 'none' || graphContainer.style.display === '' ? 'block' : 'none';
    }

    // Fonction pour générer un rapport
    function generateReport(type) {
        window.location.href = `/rapport/rapport_${type}`;
    }

    // Fonction pour afficher/masquer la table des paiements
    function togglePaymentTable() {
        const paymentTable = document.getElementById('payment-table');
        paymentTable.style.display = paymentTable.style.display === 'none' || paymentTable.style.display === '' ? 'flex' : 'none';
    }

    // Fonction pour ouvrir un rapport spécifique
    function openSpecificReport() {
        let paiementId = prompt("Entrez l'ID du client:");
        if (paiementId) {
            window.location.href = `/rapport/rapport_paiement/${paiementId}`;
        }
    }

    // Fonction pour ouvrir un rapport pour un client spécifique
    function openClientSpecificReport() {
        let clientId = prompt("Entrez l'ID du redevable:");
        if (clientId) {
            window.location.href = `/rapport/rapport_client/${clientId}`;
        }
    }
</script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


@endsection
