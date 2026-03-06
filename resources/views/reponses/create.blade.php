@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h2><i class="fas fa-reply"></i> Enregistrer une Réponse</h2>

    {{-- Encarts récapitulatif du télégramme (lecture seule) --}}
    @if(isset($prefillTelegramme))
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Récapitulatif du télégramme</h5>
                <div class="row">
                    <div class="col-md-4"><strong>N° Télégramme:</strong> {{ $prefillTelegramme->numero_enregistrement ?? '—' }}</div>
                    <div class="col-md-4"><strong>Numéro de référence:</strong> {{ $prefillTelegramme->numero_reference ?? '—' }}</div>
                    <div class="col-md-4 text-end">
                        <small class="text-muted">Date: {{ $prefillTelegramme->date ?? ($prefillTelegramme->created_at ?? '—') }}</small>
                    </div>
                </div>
                <p class="mt-2"><strong>Objet:</strong> {{ $prefillTelegramme->objet ?? '—' }}</p>
                <p><strong>Direction émettrice:</strong> {{ $prefillTelegramme->direction_emettrice ?? '—' }}</p>
                <div class="d-flex gap-2">
                    <button type="button" id="adopt_reference_btn" class="btn btn-outline-primary btn-sm">Reprendre la référence du télégramme</button>
                    {{-- Placeholder pour SLA / indicateur (si le contrôleur fournit des infos) --}}
                    @if(isset($prefillTelegramme->deadline_status))
                        @if($prefillTelegramme->deadline_status === 'on_time')
                            <span class="badge bg-success align-self-center">🟢 Dans les délais</span>
                        @elseif($prefillTelegramme->deadline_status === 'late')
                            <span class="badge bg-danger align-self-center">🔴 En retard</span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Type de réponse (interne / officielle / finale) --}}
    <div class="mb-3">
        <label class="form-label"><i class="fas fa-file-signature"></i> Type de réponse</label>
        <div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type_reponse" id="type_interne" value="interne" checked>
                <label class="form-check-label" for="type_interne">Interne</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type_reponse" id="type_officielle" value="officielle">
                <label class="form-check-label" for="type_officielle">Officielle</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type_reponse" id="type_finale" value="finale">
                <label class="form-check-label" for="type_finale">Finale</label>
            </div>
        </div>
    </div>

    <form action="{{ route('reponses.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Numéro d'Enregistrement -->
        <div class="scroll-animated mb-3">
            <label class="form-label"><i class="fas fa-hashtag"></i> Numéro d'Enregistrement</label>
            <select id="select_numero_enregistrement" class="form-control" onchange="document.getElementById('manual_numero_enregistrement').value = this.value">
                <option value="">Sélectionner un numéro d'enregistrement</option>
                @foreach($telegrammes as $telegramme)
                    <option value="{{ $telegramme->numero_enregistrement }}" {{ (isset($prefillTelegramme) && $prefillTelegramme->numero_enregistrement == $telegramme->numero_enregistrement) ? 'selected' : '' }}>{{ $telegramme->numero_enregistrement }}</option>
                @endforeach
            </select>
            <input type="text" id="manual_numero_enregistrement" class="form-control mt-2" name="numero_enregistrement" placeholder="Ou saisissez manuellement" value="{{ old('numero_enregistrement', isset($prefillTelegramme) ? $prefillTelegramme->numero_enregistrement : '') }}">
        </div>

        <!-- Numéro de Référence -->
        <div class="scroll-animated mb-3">
            <label class="form-label"><i class="fas fa-bookmark"></i> Numéro de Référence</label>
            <select id="select_numero_reference" class="form-control" onchange="document.getElementById('manual_numero_reference').value = this.value">
                <option value="">Sélectionner un numéro de référence</option>
                @foreach($telegrammes as $telegramme)
                    <option value="{{ $telegramme->numero_reference }}" {{ (isset($prefillTelegramme) && $prefillTelegramme->numero_reference == $telegramme->numero_reference) ? 'selected' : '' }}>{{ $telegramme->numero_reference }}</option>
                @endforeach
            </select>
            <input type="text" id="manual_numero_reference" class="form-control mt-2" name="numero_reference" placeholder="Ou saisissez manuellement" value="{{ old('numero_reference', isset($prefillTelegramme) ? $prefillTelegramme->numero_reference : '') }}">
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
                    'Autres' => ['Informatique', 'Juridique et Contentieux', 'Secrétariat DG', 'Audit interne'],
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

            <div class="d-flex gap-2 mt-2 mb-3">
                <button type="button" id="quick_select_all" class="btn btn-sm btn-outline-secondary">Tous</button>
                <button type="button" id="quick_select_none" class="btn btn-sm btn-outline-secondary">Aucun</button>
                <small class="align-self-center text-muted">Affichage du responsable de service en info-bulle</small>
            </div>
        </div>

        <!-- Expéditeur -->
        <div class="scroll-animated mb-3">
            <label for="observation" class="form-label">
                <i class="fas fa-user"></i> Expéditeur
            </label>
            <textarea name="observation" class="form-control">{{ old('observation', isset($prefillTelegramme) ? $prefillTelegramme->observation : '') }}</textarea>
        </div>

        <!-- Résumé -->
        <div class="scroll-animated mb-3">
            <label for="commentaires" class="form-label">
                <i class="fas fa-align-left"></i> Résumé
            </label>
            <textarea name="commentaires" class="form-control">{{ old('commentaires', isset($prefillTelegramme) ? $prefillTelegramme->commentaires : '') }}</textarea>
        </div>

        <!-- Annexes -->
        <div class="scroll-animated mb-3">
            <label for="annexes" class="form-label">
                <i class="fas fa-paperclip"></i> Annexes (jpg, png, pdf, docx)
            </label>
            <input type="file" name="annexes[]" id="annexes_input" class="form-control" multiple>
            <small class="text-muted">Taille max fichier: 5MB — Nombre max: 5. Les documents joints ont valeur administrative.</small>
            <div id="annexes_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
        </div>

        @if(isset($telegramme_id))
            <input type="hidden" name="telegramme_id" value="{{ $telegramme_id }}">
        @elseif(isset($telegrammes) && $telegrammes->isNotEmpty())
            <div class="scroll-animated mb-3">
                <label for="telegramme_id" class="form-label">
                    <i class="fas fa-envelope"></i> Télégramme
                </label>
                <select name="telegramme_id" class="form-control" required>
                    @foreach($telegrammes as $telegramme)
                        <option value="{{ $telegramme->id }}" {{ (isset($prefillTelegramme) && $prefillTelegramme->id == $telegramme->id) ? 'selected' : '' }}>{{ $telegramme->numero_enregistrement }} - {{ $telegramme->numero_reference }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <button type="submit" class="scroll-animated btn btn-primary">
            <i class="fas fa-paper-plane"></i> Soumettre
        </button>

        <button type="button" id="save_draft" class="scroll-animated btn btn-outline-secondary ms-2">Enregistrer comme brouillon</button>
        <input type="hidden" name="is_draft" id="is_draft" value="0">

    </form>

    {{-- Post-submit actions — si le contrôleur met session('created_id') on affiche des boutons utiles --}}
    @if(session('success'))
        <div class="alert alert-success mt-3"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @if(session('created_id'))
            <div class="mt-2">
                <a href="{{ route('reponses.show', session('created_id')) }}" class="btn btn-sm btn-primary">Voir la réponse</a>
                <a href="{{ route('reponses.index') }}" class="btn btn-sm btn-outline-secondary">Retour aux réponses</a>
            </div>
        @endif
    @endif

    {{-- Historique des réponses liées (si fourni par le contrôleur) --}}
    @if(isset($relatedResponses) && count($relatedResponses) > 0)
        <div class="card mt-3">
            <div class="card-body">
                <h6>Historique des réponses liées</h6>
                <ul class="list-unstyled mb-0">
                    @foreach($relatedResponses as $r)
                        <li>
                            <strong>{{ $r->created_at->format('d/m/Y') ?? '—' }}</strong> — {{ Str::limit($r->commentaires ?? $r->titre ?? '-', 80) }}
                            <a href="{{ route('reponses.show', $r->id) }}" class="ms-2 small">Voir</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Scripts -->
    <script>
        // Directions et Services dynamiques
        const directionsData = @json($directions);
        // Optionnel: mapping responsable de service (affichage contextuel)
        const serviceManagers = {
            "Comptabilité": "Mme. A. Kouassi",
            "Trésorerie": "M. B. Traoré",
            "Ressources Humaines": "Mme. C. Diop",
            "Services Généraux": "M. D. N'Diaye",
            "Coordination": "M. E. Diallo",
            "Services de la Promotion Culturelle": "Mme. F. Touré",
            "Audit interne": "M. G. Keïta",
            "Taxation": "Mme. H. Sangaré",
            "Études": "M. I. Cissé",
            "Planification": "Mme. J. Coulibaly",
            "Formation": "M. K. Sy",
            "Informatique": "M. L. Ouattara",
            "Juridique et Contentieux": "Mme. M. Koné",
            "Secrétariat DG": "Mme. N. Sissoko"
        };

        function createServiceCheckboxes(direction) {
            const container = document.createElement('div');
            container.classList.add('services_group', 'mb-2');

            if (direction && directionsData[direction]) {
                // Direction mère en titre
                const dirTitle = document.createElement('div');
                dirTitle.innerHTML = `<strong>${direction}</strong>`;
                container.appendChild(dirTitle);

                directionsData[direction].forEach(service => {
                    const id = 'service_' + service.replace(/\s+/g, '_');
                    const div = document.createElement('div');
                    div.classList.add('form-check', 'd-flex', 'align-items-center', 'gap-2');

                    const manager = serviceManagers[service] ? ` <small class="text-muted">(resp: ${serviceManagers[service]})</small>` : '';

                    div.innerHTML = `
                        <input class="form-check-input" type="checkbox" name="service_concerne[]" value="${service}" id="${id}">
                        <label class="form-check-label" for="${id}">${service}${manager}</label>
                    `;
                    container.appendChild(div);
                });
            }

            return container;
        }

        // quick-select handlers
        document.getElementById('quick_select_all').addEventListener('click', function() {
            document.querySelectorAll('#directions_services_container input[type="checkbox"]').forEach(cb => cb.checked = true);
        });
        document.getElementById('quick_select_none').addEventListener('click', function() {
            document.querySelectorAll('#directions_services_container input[type="checkbox"]').forEach(cb => cb.checked = false);
        });

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

        // copy reference button
        const adoptBtn = document.getElementById('adopt_reference_btn');
        if (adoptBtn) {
            adoptBtn.addEventListener('click', function() {
                const ref = '{{ $prefillTelegramme->numero_reference ?? '' }}';
                if (ref) document.getElementById('manual_numero_reference').value = ref;
            });
        }

        // Annexes preview and simple tagging
        const annexesInput = document.getElementById('annexes_input');
        const annexesPreview = document.getElementById('annexes_preview');
        const MAX_FILES = 5;
        const MAX_SIZE = 5 * 1024 * 1024; // 5MB

        if (annexesInput) {
            annexesInput.addEventListener('change', function(e) {
                annexesPreview.innerHTML = '';
                const files = Array.from(e.target.files).slice(0, MAX_FILES);

                files.forEach((file, idx) => {
                    const card = document.createElement('div');
                    card.classList.add('border', 'p-2');
                    card.style.width = '160px';

                    const name = document.createElement('div');
                    name.innerHTML = `<strong>${file.name}</strong>`;

                    const size = document.createElement('div');
                    size.classList.add('text-muted', 'small');
                    size.textContent = Math.round(file.size/1024) + ' KB';

                    const tag = document.createElement('div');
                    tag.classList.add('small', 'mt-1');
                    tag.innerHTML = (idx === 0) ? '<span class="badge bg-primary">Annexe principale</span>' : '<span class="badge bg-secondary">Justificatif</span>';

                    const invalid = document.createElement('div');
                    invalid.classList.add('text-danger', 'small');
                    if (file.size > MAX_SIZE) invalid.textContent = 'Fichier trop volumineux';

                    // Preview thumbnail for images
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.style.maxWidth = '100%';
                        img.style.maxHeight = '80px';
                        const reader = new FileReader();
                        reader.onload = function(evt) {
                            img.src = evt.target.result;
                        }
                        reader.readAsDataURL(file);
                        card.appendChild(img);
                    } else {
                        const icon = document.createElement('div');
                        icon.innerHTML = '<i class="fas fa-file-pdf fa-2x"></i>';
                        card.appendChild(icon);
                    }

                    card.appendChild(name);
                    card.appendChild(size);
                    card.appendChild(tag);
                    card.appendChild(invalid);

                    annexesPreview.appendChild(card);
                });

                if (e.target.files.length > MAX_FILES) {
                    const warn = document.createElement('div');
                    warn.classList.add('text-warning', 'small', 'mt-2');
                    warn.textContent = 'Seuls les ' + MAX_FILES + ' premiers fichiers ont été pris en compte.';
                    annexesPreview.appendChild(warn);
                }
            });
        }

        // Enregistrer comme brouillon
        const saveDraftBtn = document.getElementById('save_draft');
        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function() {
                document.getElementById('is_draft').value = '1';
                // soumettre le formulaire
                saveDraftBtn.closest('form').submit();
            });
        }

        // If we have a prefill telegramme with services, apply them to the form
        @if(isset($prefillTelegramme) && !empty($prefillTelegramme->service_concerne))
            try {
                const services = {!! $prefillTelegramme->service_concerne !!};
                // services is expected to be a JSON array stored as string in DB
                const parsed = typeof services === 'string' ? JSON.parse(services) : services;
                // For simplicity, check the first service and select its direction
                if (Array.isArray(parsed) && parsed.length > 0) {
                    const first = parsed[0];
                    // Find the direction that contains this service
                    for (const dir in directionsData) {
                        if (directionsData[dir].includes(first)) {
                            // set first select and trigger change
                            const firstSelect = document.querySelector('.direction_service_group .select_direction');
                            if (firstSelect) {
                                firstSelect.value = dir;
                                handleDirectionChange(firstSelect);
                                // check the matching checkbox
                                setTimeout(() => {
                                    const chk = document.querySelector('input[name="service_concerne[]"][value="' + first + '"]');
                                    if (chk) chk.checked = true;
                                }, 50);
                            }
                            break;
                        }
                    }
                }
            } catch (e) { console.error('prefill services', e); }
        @endif
    </script>
</div>
@endsection
