@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
@if (session('alerte_abonnement'))
    <div class="alert alert-warning">
        {{ session('alerte_abonnement') }}
    </div>
@endif

  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-home text-primary"></i> Bienvenue sur votre espace de gestion de courriers 📬
    </h5>
    <p class="mb-4">
      Cette interface vous permet de gérer efficacement tous les courriers et documents de l'organisation. Vous pourrez facilement enregistrer, suivre et consulter les courriers, soumettre des demandes, ainsi que collaborer avec vos collègues de manière optimale.
      <br><br>
      Profitez de toutes les fonctionnalités qui vous sont proposées, telles que l'envoi de télégrammes, la gestion des congés, la consultation des rapports financiers et bien plus encore !
    </p>
    <button class="btn btn-primary btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> Commencer
    </button>
  </div>
</div>

    @if(!session('code_acces_valide'))
        <div class="scroll-animated container">
            <div class="row justify-content-center">
                <div class="col-md-6 mt-5">
                    <h2 class="scroll-animated text-center mb-4">
                        <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
                    </h2>

                    <div class="card shadow-lg border-0 rounded-4">
                        <div class="card-header text-white bg-primary text-center">
                            🔐 Authentification Sécurisée
                        </div>
                        <div class="card-body bg-light text-dark">
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

                                <button type="submit" class="btn btn-primary w-100 scroll-animated">
                                    <i class="fas fa-check-circle"></i> Valider
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
    {{-- Contenu de la page Home --}}
    <div class="scroll-animated container mx-auto mt-5">
        <!-- Barre de recherche -->
        <div class="flex justify-center mb-6">
            <form action="{{ route('search') }}" method="GET" class="w-full flex items-center bg-white shadow-md rounded-lg px-4 py-2 max-w-screen-lg">
                <input type="text" name="query" class="w-full px-3 py-2 border-none outline-none text-gray-700"
                    placeholder="Rechercher un courrier ou un accusé de réception..." required>
                    <button id="btn-recherche" type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                        Rechercher 🔍
                    </button>
            </form>
        </div>

        <!-- Graphiques modernes -->
        <div class="row">
            <!-- Graphique des accusés de réception traités -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white">
                        📈 Statistiques des accusés de réception traités
                    </div>
                    <div class="card-body">
                        <canvas id="accusesTraitesChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graphique des courriers par statut -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white">
                        📊 Statistiques des courriers par statut
                    </div>
                    <div class="card-body">
                        <canvas id="courriersStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique des accusés de réception par mois -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white">
                        📆 Accusés de réception par mois
                    </div>
                    <div class="card-body">
                        <canvas id="accusesParMoisChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Graphique des courriers reçus par type -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white">
                        📨 Courriers reçus par type
                    </div>
                    <div class="card-body">
                        <canvas id="courriersTypeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique des accusés de réception traités
    const ctx1 = document.getElementById('accusesTraitesChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Accusés de réception traités',
                data: [5, 12, 8, 15, 7],
                backgroundColor: '#007bff',
                borderColor: '#0056b3',
                borderWidth: 1
            }]
        }
    });

    // Graphique des courriers par statut
    const ctx2 = document.getElementById('courriersStatusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Reçu', 'En attente', 'Traité'],
            datasets: [{
                label: 'Statut des courriers',
                data: [30, 15, 50],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderColor: ['#218838', '#e0a800', '#c82333'],
                borderWidth: 1
            }]
        }
    });

    // Graphique des accusés de réception par mois
    const ctx3 = document.getElementById('accusesParMoisChart').getContext('2d');
    new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Accusés de réception par mois',
                data: [3, 5, 2, 8, 4],
                borderColor: '#28a745',
                borderWidth: 2,
                fill: false
            }]
        }
    });

    // Graphique des courriers reçus par type
    const ctx4 = document.getElementById('courriersTypeChart').getContext('2d');
    new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: ['Personnel', 'Affaires', 'Commercial', 'Autre'],
            datasets: [{
                label: 'Types de courriers reçus',
                data: [25, 35, 20, 20],
                backgroundColor: ['#17a2b8', '#ffc107', '#007bff', '#6c757d'],
                borderColor: ['#138496', '#e0a800', '#0056b3', '#5a6268'],
                borderWidth: 1
            }]
        }
    });
</script>
@endsection