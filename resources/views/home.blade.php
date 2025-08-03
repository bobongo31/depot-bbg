@extends('layouts.app')

@section('content')

@if(session('error'))
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

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
        <div class="container mt-5">
        <h2 class="mb-4 scroll-animated">📊 Statistiques Courriers</h2>

        {{-- Barre de recherche --}}
        <div class="scroll-animated row mb-4">
            <div class="col-md-12">
                <form method="GET" action="{{ route('recherche.globale') }}">
                    <div class="input-group shadow-lg">
                        <input type="text" class="form-control" name="query" placeholder="Rechercher un accusé de réception..." aria-label="Rechercher avancée" value="{{ request('query') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i> Rechercher
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('agent'))
        <div class="row row-cols-1 row-cols-md-2 g-3 mb-4">
            {{-- Graphique 1 --}}
            <div class="col scroll-animated">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-chart-bar text-white"></i> Courriers Reçus par mois
                    </div>
                    <div class="card-body">
                        <canvas id="courriersParMoisChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Graphique 2 --}}
            <div class="col scroll-animated">
                <div class="card shadow-lg">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-chart-line text-white"></i> Courriers Traités par mois
                    </div>
                    <div class="card-body">
                        <canvas id="courriersTraitesParMoisChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Graphique 3 --}}
            <div class="col scroll-animated">
                <div class="card shadow-lg">
                    <div class="card-header bg-secondary text-white">
                        📅 Courriers Reçus par Jour (7 derniers jours)
                    </div>
                    <div class="card-body">
                        <canvas id="courriersParJourChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Graphique 4 --}}
            <div class="col scroll-animated">
                <div class="card shadow-lg">
                    <div class="card-header bg-warning text-white">
                        🗓️ Courriers Reçus par Semaine (6 dernières semaines)
                    </div>
                    <div class="card-body">
                        <canvas id="courriersParSemaineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
            <div class="row mb-4 justify-content-center">
            <div class="col-md-8 scroll-animated">
                <div class="card shadow-lg">
                    <div class="card-header bg-info text-white text-center">
                        <i class="fas fa-globe"></i> Vue Globale des Courriers
                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center" style="max-height: 400px; padding: 0;">
                        <canvas id="vueGlobaleChart" style="max-height: 350px; width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @endif
    @endif
@endsection

@push('scripts')
<script>
    // Déjà existants
    const moisRecu         = @json(array_keys($courriersParMois));
    const dataRecu         = @json(array_values($courriersParMois));
    const moisTraites      = @json(array_keys($courriersTraitesParMois));
    const dataTraites      = @json(array_values($courriersTraitesParMois));

    // Nouveaux
    const jours            = @json(array_keys($courriersParJour));
    const dataParJour      = @json(array_values($courriersParJour));
    const semaines         = @json(array_keys($courriersParSemaine));
    const dataParSemaine   = @json(array_values($courriersParSemaine));

    const vueGlobaleLabels = @json(array_keys($vueGlobale));
    const vueGlobaleData   = @json(array_values($vueGlobale));



    const cfg = { responsive: true, plugins: { legend: { display: false } } };

        new Chart(document.getElementById('vueGlobaleChart'), {
        type: 'doughnut',
        data: {
            labels: vueGlobaleLabels,
            datasets: [{
                label: 'Vue Globale',
                data: vueGlobaleData,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107'], // Bootstrap colors
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { enabled: true }
            }
        }
    });


    new Chart(document.getElementById('courriersParMoisChart'), {
        type: 'bar',
        data: { labels: moisRecu, datasets: [{ label: 'Reçus', data: dataRecu }] },
        options: cfg
    });

    new Chart(document.getElementById('courriersTraitesParMoisChart'), {
        type: 'bar',
        data: { labels: moisTraites, datasets: [{ label: 'Traités', data: dataTraites }] },
        options: cfg
    });

    new Chart(document.getElementById('courriersParJourChart'), {
        type: 'line',
        data: { labels: jours, datasets: [{ label: 'Reçus par Jour', data: dataParJour, borderColor: 'blue', fill: false }] },
        options: cfg
    });

    new Chart(document.getElementById('courriersParSemaineChart'), {
        type: 'bar',
        data: { labels: semaines, datasets: [{ label: 'Reçus par Semaine', data: dataParSemaine, backgroundColor: 'orange' }] },
        options: cfg
    });
</script>

@endpush