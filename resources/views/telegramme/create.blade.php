@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>rédiger et envoyer un télégramme officiel</strong> via le système de transmission rapide de l’organisation.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container">
    @if(session('code_acces_valide'))
        <h2><i class="fas fa-paper-plane"></i> Envoyer un Télégramme</h2>

        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('telegramme.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Numéro d'Enregistrement -->
            <div class="scroll-animated mb-3">
                <label class="form-label"><i class="fas fa-hashtag"></i> Numéro d'Enregistrement</label>
                <select id="select_numero_enregistrement" class="form-control">
                    <option value="">Sélectionner un numéro d'enregistrement</option>
                    @foreach($accuse_receptions as $accuse)
                        <option value="{{ $accuse->numero_enregistrement }}">{{ $accuse->numero_enregistrement }}</option>
                    @endforeach
                </select>
                <input type="text" id="manual_numero_enregistrement" class="form-control mt-2" name="numero_enregistrement" placeholder="Ou saisissez manuellement">
            </div>

            <!-- Numéro de Référence -->
            <div class="scroll-animated mb-3">
                <label class="form-label"><i class="fas fa-bookmark"></i> Numéro de Référence</label>
                <select id="select_numero_reference" class="form-control">
                    <option value="">Sélectionner un numéro de référence</option>
                    @foreach($accuse_receptions as $accuse)
                        <option value="{{ $accuse->numero_reference }}">{{ $accuse->numero_reference }}</option>
                    @endforeach
                </select>
                <input type="text" id="manual_numero_reference" class="form-control mt-2" name="numero_reference" placeholder="Ou saisissez manuellement">
            </div>

            <!-- Directions et Services Concernés -->
            <div class="scroll-animated mb-3">
                <label class="form-label"><i class="fas fa-building"></i> Direction et Services Concernés :</label>

                @php
                    $directions = [
                        'Direction Financière' => ['Comptabilité', 'Trésorerie'],
                        'Ressources Humaines et Services Généraux' => ['Ressources Humaines', 'Services Généraux'],
                        'Coordination des Provinces' => ['Coordination'],
                        'Promotion Culturelle' => ['Services de la Promotion Culturelle'],
                        'Contrôle et Inspection' => ['Contrôle et Inspection'],
                        'Mobilisation de la Redevance' => ['Taxation'],
                        'Études, Planification et de la Formation' => ['Études', 'Planification', 'Formation'],
                        'Direction du Recouvrement' => ['Recouvrement'],
                        'Autres' => [
                            'Informatique',
                            'Juridique et Contentieux',
                            'Secrétariat DG',
                            'Assistant DG',
                            'Assistant DGA',
                            "Communication",
                            'Audit interne'
                        ],
                    ];
                @endphp


                <div id="directions_services_container">
                    <div class="direction_service_group mb-2">
                        <select class="form-control select_direction mb-2">
                            <option value="">Sélectionner une direction</option>
                            @foreach($directions as $direction => $services)
                                <option value="{{ $direction }}">{{ $direction }}</option>
                            @endforeach
                        </select>

                        <div class="services_container"></div>
                    </div>
                </div>

                <button type="button" id="add_direction_service" class="btn btn-secondary btn-sm mt-2">
                    <i class="fas fa-plus"></i> Ajouter une autre direction
                </button>
            </div>

            <!-- Expéditeur -->
            <div class="scroll-animated mb-3">
                <label class="form-label"><i class="fas fa-user"></i> Expéditeur</label>
                <textarea class="form-control" name="observation" rows="3" required></textarea>
            </div>

            <!-- Résumé -->
            <div class="scroll-animated mb-3">
                <label class="form-label"><i class="fas fa-align-left"></i> Résumé</label>
                <textarea class="form-control" name="commentaires" rows="4" required></textarea>
            </div>

            <!-- Annexes -->
            <div class="mb-3">
                <label class="form-label"><i class="fas fa-paperclip"></i> Ajouter des Annexes</label>
                <input type="file" class="form-control" name="annexes[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Envoyer</button>
            <a href="{{ route('reponses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
        </form>

        <!-- Scripts -->
        <script>
            // Synchronisation des numéros
            document.getElementById('select_numero_enregistrement').addEventListener('change', function () {
                document.getElementById('manual_numero_enregistrement').value = this.value;
            });
            document.getElementById('select_numero_reference').addEventListener('change', function () {
                document.getElementById('manual_numero_reference').value = this.value;
            });

            // Directions et Services dynamiques
            const directionsData = @json($directions);

            function createServiceCheckboxes(direction) {
                const container = document.createElement('div');
                container.classList.add('services_group', 'mb-2');

                if (direction && directionsData[direction]) {
                    directionsData[direction].forEach(service => {
                        const id = 'service_' + service.replace(/\s+/g, '_');
                        const div = document.createElement('div');
                        div.classList.add('form-check');

                        div.innerHTML = `
                            <input class="form-check-input" type="checkbox" name="service_concerne[]" value="${service}" id="${id}">
                            <label class="form-check-label" for="${id}">${service}</label>
                        `;
                        container.appendChild(div);
                    });
                }

                return container;
            }

            function handleDirectionChange(select) {
                const group = select.closest('.direction_service_group');
                const container = group.querySelector('.services_container');
                container.innerHTML = '';
                const checkboxes = createServiceCheckboxes(select.value);
                container.appendChild(checkboxes);
            }

            document.querySelector('#directions_services_container').addEventListener('change', function(e) {
                if (e.target.classList.contains('select_direction')) {
                    handleDirectionChange(e.target);
                }
            });

            document.getElementById('add_direction_service').addEventListener('click', function() {
                const newGroup = document.querySelector('.direction_service_group').cloneNode(true);
                newGroup.querySelector('.select_direction').value = '';
                newGroup.querySelector('.services_container').innerHTML = '';
                document.getElementById('directions_services_container').appendChild(newGroup);
            });
        </script>
    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <h2 class="scroll-animated text-center text-dark mb-4">
            <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
        </h2>

        <div class="scroll-animated card shadow-lg">
            <div class="card-header text-white bg-primary text-center">
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
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle"></i> Valider
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection
