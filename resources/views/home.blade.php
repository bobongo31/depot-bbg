@extends('layouts.app')

@section('content')
<style>
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

    /* Styles pour la page */
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
</style>

<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="display-4" style="color: #3498db;">Tableau de Bord</h1>
    <p class="lead" style="color: #2ecc71;">Visualisez et gérez vos paiements.</p>

    <!-- Bouton pour ouvrir le modal -->
    <div style="text-align: right;">
    <button type="button" onclick="openModal()" class="btn btn-info mb-3">Générer un Rapport PDF</button>
    </div>

    <!-- Modal pour choix de type de rapport -->
    <div id="rapportModal" style="display: none;" class="modal-overlay">
        <div class="modal-content">
        <h2 style="color: white;">Choisissez le Type de Rapport</h2>
            <button onclick="generateReport('all')" class="btn btn-primary mb-2">Tous les Paiements</button>
            <button onclick="openSpecificReport()" class="btn btn-secondary mb-2">Paiement Spécifique</button>
            <button onclick="generateReport('clients')" class="btn btn-success mb-2">Tous les Redevables</button>
            <button onclick="openClientSpecificReport()" class="btn btn-secondary mb-2">Redevables Spécifique</button>
            <button onclick="closeModal()" class="btn btn-danger">Fermer</button>
        </div>
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

    @if (!auth()->user()->hasRole('read_write'))
        <div class="mb-3">
            <!-- Filtrage et tri des paiements -->
            <form method="GET" action="{{ route('web.paiements.index') }}" class="d-inline">
                <select name="sort" onchange="this.form.submit()" class="form-control form-control-sm d-inline" style="width: auto;">
                    <option value="">Trier par</option>
                    <option value="date_paiement" {{ request('sort') == 'date_paiement' ? 'selected' : '' }}>Date de Paiement</option>
                    <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Statut</option>
                </select>
            </form>
        </div>

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

<script>
    function openModal() {
        document.getElementById('rapportModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('rapportModal').style.display = 'none';
    }

    function generateReport(type) {
        if (type === 'all') {
            window.location.href = '/rapport/rapport_tous_paiements';  // URL pour tous les rapports (pouvant inclure paiements et clients)
        } else if (type === 'clients') {
            window.location.href = '/rapport/rapport_tous_clients';  // URL pour les rapports des clients
        } else if (type === 'paiements') {
            window.location.href = '/rapport/rapport_tous_paiements';  // URL pour les rapports des paiements
        }
    }

    function openSpecificReport() {
        let paiementId = prompt("Veuillez entrer l'ID du paiement :");
        if (paiementId) {
            window.location.href = `/rapport/rapport_paiement/${paiementId}`;  // URL pour un rapport de paiement spécifique
        }
    }

    function openClientSpecificReport() {
        let clientId = prompt("Veuillez entrer l'ID du client :");
        if (clientId) {
            window.location.href = `/rapport/rapport_client/${clientId}`;  // URL pour un rapport de client spécifique
        }
    }
</script>
@endsection
