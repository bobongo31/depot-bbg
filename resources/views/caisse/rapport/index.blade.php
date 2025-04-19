@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>consulter les rapports financiers journaliers ou mensuels</strong> liés à la gestion de la caisse, pour un suivi détaillé des flux financiers.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container">
@if (session('code_acces_valide'))
{{-- Titre principal stylisé --}}
<div class="scroll-animated custom-box text-center mb-4 p-4 rounded shadow bg-white">
    <h1 class="text-primary">
        <i class="fas fa-file-invoice-dollar fa-lg me-2"></i> Rapport des Mouvements de Caisse
    </h1>
</div>

{{-- Formulaire de sélection de date --}}
<form method="GET" action="{{ route('caisse.rapport.index') }}" class="row g-3 mb-4 bg-light p-3 rounded shadow-sm">
    <div class="scroll-animated col-md-4">
        <label for="date_debut" class="form-label">
            <i class="fas fa-calendar-alt"></i> Date de début
        </label>
        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
    </div>
    <div class="scroll-animated col-md-4">
        <label for="date_fin" class="form-label">
            <i class="fas fa-calendar-alt"></i> Date de fin
        </label>
        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
    </div>
    <div class="scroll-animated col-md-4 align-self-end d-grid">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-chart-line me-1"></i> Générer le rapport
        </button>
    </div>
</form>

{{-- Résumé Bilan --}}
<div class="scroll-animated p-4 bg-white rounded shadow-sm mb-4">
    <h4 class="mb-3"><i class="fas fa-balance-scale"></i> Bilan</h4>
    <p>
        <strong><i class="fas fa-arrow-down text-success"></i> Total Entrées :</strong>
        {{ number_format($total_entrees, 2, ',', ' ') }} €
    </p>
    <p>
        <strong><i class="fas fa-arrow-up text-danger"></i> Total Sorties :</strong>
        {{ number_format($total_sorties, 2, ',', ' ') }} €
    </p>
    <p>
        <strong><i class="fas fa-wallet"></i> Solde :</strong>
        <span class="{{ $solde >= 0 ? 'text-success' : 'text-danger' }}">
            {{ number_format($solde, 2, ',', ' ') }} €
        </span>
    </p>
</div>

{{-- Conteneurs de graphiques --}}
<div class="scroll-animated row row-cols-1 row-cols-md-2 g-3 mb-4">
    <div class="col">
        <div class="chart-container p-3 border rounded shadow-sm bg-light">
            <h5 class="text-center mb-3">
                <i class="fas fa-chart-bar text-primary"></i> Histogramme des mouvements
            </h5>
            <canvas id="barChart"></canvas>
        </div>
    </div>
    <div class="col">
        <div class="chart-container p-3 border rounded shadow-sm bg-light">
            <h5 class="text-center mb-3">
                <i class="fas fa-chart-pie text-danger"></i> Répartition globale
            </h5>
            <canvas id="pieChart"></canvas>
        </div>
    </div>
    <div class="col">
        <div class="chart-container p-3 border rounded shadow-sm bg-light">
            <h5 class="text-center mb-3">
                <i class="fas fa-chart-line text-success"></i> Évolution des montants
            </h5>
            <canvas id="lineChart"></canvas>
        </div>
    </div>
    <div class="col">
        <div class="chart-container p-3 border rounded shadow-sm bg-light">
            <h5 class="text-center mb-3">
                <i class="fas fa-bullseye text-info"></i> Vue radar des transactions
            </h5>
            <canvas id="radarChart"></canvas>
        </div>
    </div>
</div>


{{-- Section détail des transactions --}}
<div class="scroll-animated custom-box text-center mb-3 p-3 rounded bg-white shadow-sm">
    <h4><i class="fas fa-list-ul"></i> Détail des Transactions</h4>
</div>
<table class="scroll-animated table table-bordered table-hover bg-white shadow-sm">
    <thead class="table-primary">
        <tr>
            <th><i class="fas fa-calendar-day"></i> Date</th>
            <th><i class="fas fa-exchange-alt"></i> Type</th>
            <th><i class="fas fa-euro-sign"></i> Montant</th>
            <th><i class="fas fa-tag"></i> Rubrique / Motif</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $t)
            <tr>
                <td>{{ $t['date'] }}</td>
                <td>
                    @if($t['type'] == 'entree')
                        <span class="badge bg-success"><i class="fas fa-plus-circle"></i> Entrée</span>
                    @else
                        <span class="badge bg-danger"><i class="fas fa-minus-circle"></i> Sortie</span>
                    @endif
                </td>
                <td>{{ number_format($t['montant'], 2, ',', ' ') }} €</td>
                <td>{{ $t['libelle'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@else
    {{-- Formulaire de code d’accès --}}
    <h2 class="scroll-animated text-center text-dark mb-4">
        <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
    </h2>
    <div class="scroll-animated card shadow-lg">
    <div class="scroll-animated card-header text-white bg-primary text-center">
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
                        {{ session('error') }}
                    </div>
                @endif
                <button type="submit" class="btn btn-primary w-100">Valider</button>
            </form>
        </div>
    </div>
@endif
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Styles personnalisés pour les petits graphiques --}}
<style>
.chart-container {
    max-width: 300px;
    height: auto;
    margin: auto;
}
canvas {
    width: 100% !important;
    height: 180px !important;
}
</style>

{{-- Script pour générer les 4 graphiques --}}
<script>
    const transactions = @json($transactions->toArray());

    const dates = transactions.map(t => t.date);
    const montants = transactions.map(t => parseFloat(t.montant));
    const types = transactions.map(t => t.type);

    const entrees = montants.map((m, i) => types[i] === 'entree' ? m : 0);
    const sorties = montants.map((m, i) => types[i] === 'sortie' ? m : 0);

    // Bar Chart
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: dates,
            datasets: [
                { label: 'Entrées', data: entrees, backgroundColor: 'rgba(40, 167, 69, 0.6)' },
                { label: 'Sorties', data: sorties, backgroundColor: 'rgba(220, 53, 69, 0.6)' }
            ]
        }
    });

    // Pie Chart
    const totalEntree = entrees.reduce((a, b) => a + b, 0);
    const totalSortie = sorties.reduce((a, b) => a + b, 0);
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: {
            labels: ['Entrées', 'Sorties'],
            datasets: [{
                data: [totalEntree, totalSortie],
                backgroundColor: ['#28a745', '#dc3545']
            }]
        }
    });

    // Line Chart
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Montants',
                data: montants,
                borderColor: '#007bff',
                fill: false
            }]
        }
    });

    // Radar Chart
    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: {
            labels: dates.slice(0, 5), // Limiter à 5 dates pour lisibilité
            datasets: [{
                label: 'Montants',
                data: montants.slice(0, 5),
                backgroundColor: 'rgba(0,123,255,0.2)',
                borderColor: '#007bff'
            }]
        }
    });
</script>
@endsection
