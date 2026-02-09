@extends('layouts.app')

@section('content')
<style>
  /* Modern, professional government palette and typography */
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
  :root{
    --gov-blue:#0b3b66; /* primary dark blue */
    --muted-gray:#f3f5f7; /* light gray */
    --success-green:#198754; /* validated */
    --warn-orange:#f59e0b; /* near deadline */
    --danger-red:#dc3545; /* alert */
  }
  body, .container, .card { font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; }
  .overlay-message .message-box{ max-width:520px; }
  .table thead th{ background: var(--muted-gray); color: #111; }
  .badge.badge-on-time{ background-color: var(--success-green); }
  .badge.badge-warning-deadline{ background-color: var(--warn-orange); color: #111; }
  .badge.badge-late{ background-color: var(--danger-red); }
  /* consistent spacing for action forms inside dropdown items */
  .dropdown .dropdown-item form { display:inline-block; width:100%; margin:0; }
  .filter-row .form-control, .filter-row .form-select{ min-height: 38px; }
  @media (max-width:768px){
    .filter-row .col-auto{ width:100%; }
  }

  /* Ensure dropdowns aren't clipped by the responsive table wrapper */
  .table-responsive { overflow: visible; }
  .dropdown-menu { z-index: 3000; }

  /* Small action modal: rendre le modal d'actions beaucoup plus compact */
  #actionModal .modal-dialog { max-width: 360px; width: 90%; }
  #actionModal .modal-content { border-radius: 8px; }
  #actionModal .modal-body { padding: 0.75rem; }
  #actionModal .modal-footer { padding: 0.5rem 0.75rem; }
</style>

<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet d’<strong>accéder à votre boîte de réception</strong> pour traiter les courriers qui vous sont adressés en tant que chef de service.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container py-4">

    <!-- Search & Filters -->
    @if(session('code_acces_valide'))
    <form method="GET" action="{{ route('reponses.index') }}" class="row mb-4 filter-row align-items-center">
        <div class="col-md-5 col-12 mb-2 mb-md-0">
            <label for="search" class="visually-hidden">Recherche</label>
            <input id="search" name="q" value="{{ request('q') }}" type="search" class="form-control" placeholder="Rechercher par référence, expéditeur, résumé..." aria-label="Recherche">
        </div>
        <div class="col-md-3 col-6 mb-2 mb-md-0">
            <label for="serviceFilter" class="visually-hidden">Service</label>
            <select id="serviceFilter" name="service" class="form-select" aria-label="Filtrer par service">
                <option value="">Tous les services</option>
                @foreach($services as $svc)
                    <option value="{{ $svc }}" {{ request('service') == $svc ? 'selected' : '' }}>{{ $svc }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-6 d-flex gap-2">
            <label for="dateFrom" class="visually-hidden">Du</label>
            <input id="dateFrom" name="from" value="{{ request('from') }}" type="date" class="form-control" aria-label="Date de début">
            <label for="dateTo" class="visually-hidden">Au</label>
            <input id="dateTo" name="to" value="{{ request('to') }}" type="date" class="form-control" aria-label="Date de fin">
        </div>
        <div class="col-md-1 col-12 mt-2 mt-md-0 d-grid">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('code_acces_valide'))

        <h2 class="mb-4"><i class="fas fa-list-alt"></i> Liste des Réponses</h2>

        {{-- Afficher les services de l'utilisateur --}}
        <div class="mb-3">
            @foreach(json_decode(auth()->user()->service, true) ?? [] as $svc)
                <span class="badge bg-primary me-1">{{ $svc }}</span>
            @endforeach
        </div>

        <div id="reponsesResults">
        @foreach ($reponsesGrouped as $date => $reponsesDuJour)
            <div class="mb-5">
                <h5 class="text-primary fw-bold">
                    <i class="fas fa-calendar-day"></i>
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="scroll-animated table-light">
                            <tr>
                                <th># Enr.</th>
                                <th>Référence</th>
                                <th>Service</th>
                                <th>Expéditeur</th>
                                <th>Résumé</th>
                                <th>Heure</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                            <tbody>
                            @foreach ($reponsesDuJour as $reponse)
                                @php
                                    $user = auth()->user();
                                    $visible = false;

                                    // 🔹 1. Admin voit tout
                                    if ($user->role === 'admin') {
                                        $visible = true;
                                    }

                                    // 🔹 2. Chef de service : ne voit que ses propres services
                                    elseif (in_array($user->role, ['chef_service', 'agent'])) {
                                        $userServices = explode(',', $user->service ?? '');
                                        $userServices = array_map(fn($s) => mb_strtolower(trim($s)), $userServices);

                                        $servicesReponse = json_decode($reponse->service_concerne, true) ?? [];
                                        if (!is_array($servicesReponse)) {
                                            $servicesReponse = [$reponse->service_concerne];
                                        }
                                        $servicesReponse = array_map(fn($s) => mb_strtolower(trim($s)), $servicesReponse);

                                        $visible = count(array_intersect($userServices, $servicesReponse)) > 0;
                                    }

                                    // 🔹 3. Chef de direction : voit les services liés à sa direction
                                    elseif ($user->role === 'chef_direction') {
                                        // Mapping par CODE de direction (utiliser users.name comme clé fiable)
                                        $servicesParDirection = [
                                            'DRHSG' => ['Ressources Humaines', 'Services Généraux', 'Ressources Humaines et Services Généraux'],
                                            'DF'    => ['Comptabilité', 'Trésorerie'],
                                            'DCP'   => ['Coordination'],
                                            'DPC'   => ['Services de la Promotion Culturelle', 'Production et Animation Culturelle'],
                                            'CI'    => ['Audit interne'],
                                            'DMR'   => ['Taxation'],
                                            'DR'    => ['Recouvrement'],
                                            'DEFP'  => ['Études', 'Planification', 'Formation'],
                                            'Autres'=> ['Informatique', 'Juridique et Contentieux', 'Secrétariat DG','Assistant DG','Assistant DGA'],
                                        ];

                                        // Normalisation : translitération + minuscules + suppression non-alphanumériques
                                        $normalize = function($v) {
                                            $s = trim((string)$v);
                                            $trans = @iconv('UTF-8', 'ASCII//TRANSLIT', $s);
                                            if ($trans === false) { $trans = $s; }
                                            $trans = mb_strtolower($trans);
                                            return preg_replace('/[^a-z0-9]+/u', '', $trans);
                                        };

                                        // ✅ IDENTIFICATION FIABLE PAR CODE DE DIRECTION (utiliser users.name)
                                        $directionCode = trim((string) $user->name); // ex: 'DRHSG'
                                        $servicesDirection = $servicesParDirection[$directionCode] ?? [];

                                        // Normaliser listes et services du télégramme
                                        $servicesDirectionNorm = array_map($normalize, $servicesDirection);
                                        $servicesTelegramme = json_decode($reponse->service_concerne, true) ?? [];
                                        if (!is_array($servicesTelegramme)) {
                                            $servicesTelegramme = [$reponse->service_concerne];
                                        }
                                        $servicesTelegrammeNorm = array_map($normalize, $servicesTelegramme);

                                        // Visible si au moins un service de la direction apparaît dans service_concerne
                                        $visible = count(array_intersect($servicesDirectionNorm, $servicesTelegrammeNorm)) > 0;
                                    }
                                @endphp

                                {{-- 🔹 Affichage si autorisé --}}
                                @if ($visible)
                                    <tr class="{{ $reponse->statut == 'en retard' ? 'table-danger' : '' }}">
                                        <td>{{ $reponse->numero_enregistrement }}</td>
                                        <td>{{ $reponse->numero_reference }}</td>
                                        <td>
                                            @php
                                                $services = json_decode($reponse->service_concerne, true);
                                                if (is_array($services)) {
                                                    echo implode(', ', $services);
                                                } else {
                                                    echo e($reponse->service_concerne);
                                                }
                                            @endphp
                                        </td>
                                        <td>{{ $reponse->observation }}</td>
                                        <td>{{ $reponse->commentaires }}</td>
                                        <td>{{ $reponse->created_at->format('H:i') }}</td>
                                        <td>
                                            @php
                                                $dateTelegramme = $reponse->telegramme ? \Carbon\Carbon::parse($reponse->telegramme->created_at) : null;
                                                $dateReponse = \Carbon\Carbon::parse($reponse->created_at);

                                                // Calculer la date limite à 1 semaine (exactement) depuis la date/heure du télégramme
                                                $deadline = $dateTelegramme ? $dateTelegramme->copy()->addWeek() : null;

                                                // Déterminer si la réponse est en retard (après la deadline)
                                                $isLate = $deadline ? $dateReponse->greaterThan($deadline) : false;

                                                // Format lisible pour la deadline
                                                $deadlineFormatted = $deadline ? $deadline->translatedFormat('d/m/Y H:i') : null;
                                            @endphp

                                            @if ($dateTelegramme)
                                                @if ($isLate)
                                                    <span class="badge badge-late"><i class="fas fa-times-circle"></i> En retard (limite: {{ $deadlineFormatted }})</span>
                                                @else
                                                    <span class="badge badge-on-time"><i class="fas fa-check-circle"></i> Dans le délai (limite: {{ $deadlineFormatted }})</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary"><i class="fas fa-question-circle"></i> Télégramme absent</span>
                                            @endif

                                        </td>

                                        <td class="d-flex flex-wrap gap-2">
                                            <div class="d-flex gap-2 flex-wrap align-items-center">
                                                <a class="btn btn-outline-info btn-sm" href="{{ route('reponse.show', $reponse->id) }}" title="Voir"><i class="fas fa-eye"></i></a>
                                                @if($reponse->telegramme_id)
                                                    <a class="btn btn-outline-primary btn-sm" href="{{ route('reponses.create', ['telegramme_id' => $reponse->telegramme_id]) }}" title="Répondre"><i class="fas fa-reply"></i></a>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger btn-sm ajax-delete-btn" data-url="{{ route('reponses.destroy', $reponse->id) }}" title="Supprimer"><i class="fas fa-trash"></i></button>

                                                @if($user->role === 'admin')
                                                <div class="dropdown">
                                                  <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Archiver</button>
                                                  <div class="dropdown-menu p-3" style="min-width:240px;">
                                                    <form class="archive-inline-form" method="POST" data-url="{{ route('archives.archiver', ['numero_enregistrement' => urlencode($reponse->numero_enregistrement)]) }}">
                                                      @csrf
                                                      <div class="mb-2">
                                                        <select name="category" class="form-select form-select-sm" required>
                                                          <option value="">Catégorie...</option>
                                                          <option value="expo">Expo / Exposition</option>
                                                          <option value="ministre_culture_arts">Ministère de la Culture et des Arts</option>
                                                          <option value="administration">Administration</option>
                                                          <option value="autre">Autre</option>
                                                        </select>
                                                      </div>
                                                      <div class="mb-2 d-none" data-role="archive-autre">
                                                        <input name="autre_detail" class="form-control form-control-sm" placeholder="Précisez">
                                                      </div>
                                                      <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-sm btn-primary">Archiver</button>
                                                      </div>
                                                    </form>
                                                  </div>
                                                </div>

                                                <div class="dropdown">
                                                  <button class="btn btn-outline-warning btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Déclarer</button>
                                                   <div class="dropdown-menu p-3" style="min-width:220px;">
                                                    <form class="declarer-inline-form" method="POST" data-url="{{ route('archives.declarer_clos', ['numero_enregistrement' => urlencode($reponse->numero_enregistrement)]) }}">
                                                      @csrf
                                                      <div class="mb-2">
                                                        <select name="etat" class="form-select form-select-sm" required>
                                                          <option value="">Sélectionner...</option>
                                                          <option value="clos">Clos</option>
                                                          <option value="encours">En cours</option>
                                                          <option value="classe">Classé</option>
                                                        </select>
                                                      </div>
                                                      <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-sm btn-warning">Valider</button>
                                                      </div>
                                                    </form>
                                                  </div>
                                                </div>
                                                @endif
                                            </div>
                                         </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        <div class="mt-4 d-flex justify-content-center pagination-section">
            {{ $reponses->links('pagination::bootstrap-5') }}
        </div>
        </div>

        @push('scripts')
        <script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

  // Helper pour POST avec token CSRF
  function postUrl(url, bodyParams = {}) {
    const headers = {
      'X-CSRF-TOKEN': csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
    };
    return fetch(url, {
      method: 'POST',
      headers,
      body: new URLSearchParams(bodyParams).toString()
    });
  }

  // Suppression via bouton .ajax-delete-btn
  document.body.addEventListener('click', function (e) {
    const btn = e.target.closest('.ajax-delete-btn');
    if (!btn) return;
    e.preventDefault();
    if (!confirm('Voulez-vous vraiment supprimer cet élément ?')) return;
    const url = btn.getAttribute('data-url');
    if (!url) return alert('URL manquante pour la suppression');

    postUrl(url, {'_method': 'DELETE'})
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {
          return response.text().then(txt => { throw new Error(txt || 'Suppression échouée'); });
        }
      })
      .catch(err => { console.error(err); alert('Erreur lors de la suppression : ' + (err.message || '')); });
  });

  // Gestion des formulaires inline (archiver / déclarer)
  document.body.addEventListener('submit', function (e) {
    const archiveForm = e.target.closest('.archive-inline-form');
    const declarerForm = e.target.closest('.declarer-inline-form');

    if (!archiveForm && !declarerForm) return; // pas notre formulaire
    e.preventDefault();

    const form = archiveForm || declarerForm;
    const url = form.getAttribute('data-url') || form.getAttribute('action');
    if (!url) return alert('URL manquante pour ce formulaire');

    // Récupérer les valeurs du formulaire
    const formData = new FormData(form);
    // Convertir en URLSearchParams (les formulaires ici n'envoient pas de fichiers)
    const params = {};
    formData.forEach((value, key) => { params[key] = value; });

    postUrl(url, params)
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {
          return response.text().then(txt => { throw new Error(txt || 'Opération échouée'); });
        }
      })
      .catch(err => { console.error(err); alert('Erreur lors de l\'opération : ' + (err.message || '')); });
  });

  // Afficher/cacher champ 'autre' dans l'archive inline forms
  document.body.addEventListener('change', function (e) {
    const sel = e.target;
    if (!sel) return;
    const form = sel.closest('.archive-inline-form');
    if (!form) return;
    if (sel.name === 'category') {
      const autre = form.querySelector('[data-role="archive-autre"]');
      if (autre) {
        autre.classList.toggle('d-none', sel.value !== 'autre');
      }
    }
  });

  // Recherche / filtres AJAX (optimisé)
  const filterForm = document.querySelector('form[action="{{ route('reponses.index') }}"]');
  if (filterForm) {
    let timeout = null;
    // resultsContainer wraps both the responses tables and the pagination
    const resultsContainer = document.querySelector('#reponsesResults');

    function showSkeleton() {
      if (!resultsContainer) return;
      resultsContainer.innerHTML = `\n        <div class="p-4">\n          <div class="placeholder-glow">\n            <span class="placeholder col-12"></span>\n            <span class="placeholder col-12 mt-2"></span>\n            <span class="placeholder col-12 mt-2"></span>\n          </div>\n        </div>`;
    }

    function fetchResults(params) {
      showSkeleton();
      const url = new URL("{{ route('reponses.index') }}", window.location.origin);
      Object.keys(params).forEach(k => { if (params[k]) url.searchParams.set(k, params[k]); else url.searchParams.delete(k); });

      fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          // Prefer replacing the full #reponsesResults wrapper (table(s) + pagination)
          const newResults = doc.querySelector('#reponsesResults');
          if (newResults && resultsContainer) {
            resultsContainer.innerHTML = newResults.innerHTML;
          } else {
            // Fallback: replace only the first table-responsive found
            const newTable = doc.querySelector('.table-responsive');
            if (newTable && resultsContainer) {
              // try to find the table area inside the wrapper
              const tableArea = resultsContainer.querySelector('.table-responsive');
              if (tableArea) tableArea.innerHTML = newTable.innerHTML;
            }
            // Replace pagination if present
            const newPagination = doc.querySelector('.pagination-section');
            const paginationContainer = document.querySelector('.pagination-section');
            if (newPagination && paginationContainer) {
              paginationContainer.innerHTML = newPagination.innerHTML;
            }
          }
        })
        .catch(err => { console.error('Recherche AJAX échouée', err); });
    }

    ['q','service','from','to'].forEach(name => {
      const el = filterForm.querySelector('[name="'+name+'"]');
      if (!el) return;
      el.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const params = {
            q: filterForm.querySelector('[name="q"]').value,
            service: filterForm.querySelector('[name="service"]').value,
            from: filterForm.querySelector('[name="from"]').value,
            to: filterForm.querySelector('[name="to"]').value,
          };
          fetchResults(params);
        }, 350);
      });
      el.addEventListener('change', () => { el.dispatchEvent(new Event('input')); });
    });
  }

});
        </script>
        @endpush

        {{-- TÉLÉGRAMMES EN ATTENTE --}}
        <h3 class="mt-5 mb-3"><i class="fas fa-paper-plane"></i> Télégrammes en Attente</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th># Enr.</th>
                        <th>Référence</th>
                        <th>Service</th>
                        <th>Date & Heure</th>
                        <th>Résumé</th>
                        <th>Expéditeur</th>
                        <th>Délai</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @forelse ($telegrammesEnAttente as $telegramme)
                            @php
                                $user = auth()->user();
                                $visible = false;

                                // Récupérer services utilisateur en JSON
                                $userServices = json_decode($user->service, true) ?? [];
                                $userServices = array_map('mb_strtolower', $userServices);

                                // Calcul visibilité simplifiée (déjà faite côté contrôleur mais double-check côté vue)
                                $telegrammeServices = json_decode($telegramme->service_concerne, true) ?? [];
                                $telegrammeServices = array_map('mb_strtolower', $telegrammeServices);

                                if ($user->role === 'admin') {
                                    $visible = true;
                                } else {
                                    $visible = count(array_intersect($userServices, $telegrammeServices)) > 0;
                                }
                            @endphp

                            @if($visible)
                                <tr class="{{ $telegramme->isLate ? 'table-danger' : ($telegramme->remainingHours > 168 ? 'table-success' : 'table-warning') }}">
                                    <td>{{ $telegramme->numero_enregistrement }}</td>
                                    <td>{{ $telegramme->numero_reference }}</td>
                                    <td>
                                        {{-- Afficher badges des services du télégramme --}}
                                        @foreach(json_decode($telegramme->service_concerne, true) ?? [] as $svc)
                                            <span class="badge bg-info text-dark me-1">{{ $svc }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($telegramme->created_at)->translatedFormat('d/m/Y H:i') }}</td>
                                    <td>{{ $telegramme->commentaires }}</td>
                                    <td>{{ $telegramme->observation }}</td>

                                    @php
                                        // Calcul du délai : 1 semaine depuis la date du télégramme
                                        $dateTelegramme = \Carbon\Carbon::parse($telegramme->created_at);
                                        $now = \Carbon\Carbon::now();
                                        $deadline = $dateTelegramme->copy()->addWeek();
                                        $isLateTelegramme = $now->greaterThan($deadline);
                                        // s'assurer d'avoir un entier (pas de décimales)
                                        $remainingHours = $isLateTelegramme ? 0 : (int) $now->diffInHours($deadline);

                                        // Formater limite: '11 fev 026' (mois en minuscule, sans accents, année 3 chiffres)
                                        $monthRaw = strtolower($deadline->translatedFormat('M')); // ex: 'févr.' ou 'févr'
                                        // remplacer accents et points
                                        $monthClean = str_replace(['.','é','è','ê','ë','à','â','î','ï','ô','û','ù','ç'], ['','e','e','e','e','a','a','i','i','o','u','u','c'], $monthRaw);
                                        // garder 3-chiffres pour l'année comme demandé (ex: 2026 -> '026')
                                        $yearFull = $deadline->format('Y');
                                        $yearThree = substr($yearFull, 1);
                                        $deadlineFormatted = $deadline->format('d') . ' ' . $monthClean . ' ' . $yearThree;
                                    @endphp

                                    <td>
                                        @if($isLateTelegramme)
                                            <span class="badge badge-late"><i class="fas fa-times-circle"></i> En retard (limite: {{ $deadlineFormatted }})</span>
                                        @else
                                            <span class="badge badge-on-time"><i class="fas fa-check-circle"></i> Dans les délais ({{ $remainingHours }}h — limite: {{ $deadlineFormatted }})</span>
                                        @endif
                                    </td>

                                    <td class="d-flex flex-wrap gap-2">
                                        <div class="d-flex gap-2 flex-wrap align-items-center">
                                            <a class="btn btn-outline-info btn-sm" href="{{ route('telegramme.show', ['id' => $telegramme->id]) }}" title="Voir"><i class="fas fa-eye"></i></a>
                                            <a class="btn btn-outline-primary btn-sm" href="{{ route('reponses.create', ['telegramme_id' => $telegramme->id]) }}" title="Répondre"><i class="fas fa-reply"></i></a>
                                            <button type="button" class="btn btn-outline-danger btn-sm ajax-delete-btn" data-url="{{ route('telegrammes.destroy', $telegramme->id) }}" title="Supprimer"><i class="fas fa-trash"></i></button>
                                        </div>
                                     </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun télégramme en attente.</td>
                            </tr>
                        @endforelse
                    </tbody>

            </table>
        </div>

    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <div class="mx-auto" style="max-width: 500px;">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <i class="fas fa-lock"></i> Code d'accès requis
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('code.verifier') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label"><i class="fas fa-key"></i> Saisissez votre code</label>
                            <input type="text" class="form-control" name="code" id="code" required>
                        </div>
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle"></i> Valider
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
  const csrfToken = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

  // Helper pour POST avec token CSRF
  function postUrl(url, bodyParams = {}) {
    const headers = {
      'X-CSRF-TOKEN': csrfToken,
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
    };
    return fetch(url, {
      method: 'POST',
      headers,
      body: new URLSearchParams(bodyParams).toString()
    });
  }

  // Suppression via bouton .ajax-delete-btn
  document.body.addEventListener('click', function (e) {
    const btn = e.target.closest('.ajax-delete-btn');
    if (!btn) return;
    e.preventDefault();
    if (!confirm('Voulez-vous vraiment supprimer cet élément ?')) return;
    const url = btn.getAttribute('data-url');
    if (!url) return alert('URL manquante pour la suppression');

    postUrl(url, {'_method': 'DELETE'})
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {
          return response.text().then(txt => { throw new Error(txt || 'Suppression échouée'); });
        }
      })
      .catch(err => { console.error(err); alert('Erreur lors de la suppression : ' + (err.message || '')); });
  });

  // Gestion des formulaires inline (archiver / déclarer)
  document.body.addEventListener('submit', function (e) {
    const archiveForm = e.target.closest('.archive-inline-form');
    const declarerForm = e.target.closest('.declarer-inline-form');

    if (!archiveForm && !declarerForm) return; // pas notre formulaire
    e.preventDefault();

    const form = archiveForm || declarerForm;
    const url = form.getAttribute('data-url') || form.getAttribute('action');
    if (!url) return alert('URL manquante pour ce formulaire');

    // Récupérer les valeurs du formulaire
    const formData = new FormData(form);
    // Convertir en URLSearchParams (les formulaires ici n'envoient pas de fichiers)
    const params = {};
    formData.forEach((value, key) => { params[key] = value; });

    postUrl(url, params)
      .then(response => {
        if (response.ok) {
          location.reload();
        } else {
          return response.text().then(txt => { throw new Error(txt || 'Opération échouée'); });
        }
      })
      .catch(err => { console.error(err); alert('Erreur lors de l\'opération : ' + (err.message || '')); });
  });

  // Afficher/cacher champ 'autre' dans l'archive inline forms
  document.body.addEventListener('change', function (e) {
    const sel = e.target;
    if (!sel) return;
    const form = sel.closest('.archive-inline-form');
    if (!form) return;
    if (sel.name === 'category') {
      const autre = form.querySelector('[data-role="archive-autre"]');
      if (autre) {
        autre.classList.toggle('d-none', sel.value !== 'autre');
      }
    }
  });

  // Recherche / filtres AJAX (optimisé)
  const filterForm = document.querySelector('form[action="{{ route('reponses.index') }}"]');
  if (filterForm) {
    let timeout = null;
    // resultsContainer wraps both the responses tables and the pagination
    const resultsContainer = document.querySelector('#reponsesResults');

    function showSkeleton() {
      if (!resultsContainer) return;
      resultsContainer.innerHTML = `\n        <div class="p-4">\n          <div class="placeholder-glow">\n            <span class="placeholder col-12"></span>\n            <span class="placeholder col-12 mt-2"></span>\n            <span class="placeholder col-12 mt-2"></span>\n          </div>\n        </div}`;
    }

    function fetchResults(params) {
      showSkeleton();
      const url = new URL("{{ route('reponses.index') }}", window.location.origin);
      Object.keys(params).forEach(k => { if (params[k]) url.searchParams.set(k, params[k]); else url.searchParams.delete(k); });

      fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.text())
        .then(html => {
          const parser = new DOMParser();
          const doc = parser.parseFromString(html, 'text/html');
          // Prefer replacing the full #reponsesResults wrapper (table(s) + pagination)
          const newResults = doc.querySelector('#reponsesResults');
          if (newResults && resultsContainer) {
            resultsContainer.innerHTML = newResults.innerHTML;
          } else {
            // Fallback: replace only the first table-responsive found
            const newTable = doc.querySelector('.table-responsive');
            if (newTable && resultsContainer) {
              // try to find the table area inside the wrapper
              const tableArea = resultsContainer.querySelector('.table-responsive');
              if (tableArea) tableArea.innerHTML = newTable.innerHTML;
            }
            // Replace pagination if present
            const newPagination = doc.querySelector('.pagination-section');
            const paginationContainer = document.querySelector('.pagination-section');
            if (newPagination && paginationContainer) {
              paginationContainer.innerHTML = newPagination.innerHTML;
            }
          }
        })
        .catch(err => { console.error('Recherche AJAX échouée', err); });
    }

    ['q','service','from','to'].forEach(name => {
      const el = filterForm.querySelector('[name="'+name+'"]');
      if (!el) return;
      el.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
          const params = {
            q: filterForm.querySelector('[name="q"]').value,
            service: filterForm.querySelector('[name="service"]').value,
            from: filterForm.querySelector('[name="from"]').value,
            to: filterForm.querySelector('[name="to"]').value,
          };
          fetchResults(params);
        }, 350);
      });
      el.addEventListener('change', () => { el.dispatchEvent(new Event('input')); });
    });
  }

});
</script>
@endpush
@endsection
