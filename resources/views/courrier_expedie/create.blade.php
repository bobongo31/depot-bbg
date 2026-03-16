@extends('layouts.app')

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
        'Audit interne',
        'DG',
        'Communication',
        'Assistant DG',
        'DGA'
    ],
];
@endphp

@section('content')
<div class="container scroll-animated my-4">

    <h1 class="mb-4">
        <i class="fas fa-paper-plane"></i> Nouveau courrier expédié
    </h1>

    {{-- PROGRESS FORM --}}
    <div class="progress mb-4" style="height:25px">
        <div id="formProgress"
             class="progress-bar progress-bar-striped progress-bar-animated"
             style="width:25%">
            Étape 1 / 4
        </div>
    </div>

    <form method="POST" action="{{ route('courrier_expedie.store') }}">
        @csrf

        {{-- ================= ETAPE 1 ================= --}}
        <div class="wizard-step">
            <h5><i class="fas fa-list-ol"></i> Informations générales</h5>

            <div class="mb-3">
                <label>Numéro d’ordre *</label>
                <input type="text" name="numero_ordre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Date d’expédition *</label>
                <input type="date" name="date_expedition" class="form-control" required>
            </div>

            <button type="button" class="btn btn-primary nextStep">
                Suivant <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        {{-- ================= ETAPE 2 ================= --}}
        <div class="wizard-step d-none">
            <h5><i class="fas fa-envelope"></i> Destinataire principal</h5>

            <div class="mb-3">
                <label>Numéro de la lettre *</label>
                <input type="text" name="numero_lettre" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Destinataire principal *</label>
                <input type="text" name="destinataire" class="form-control" required>
            </div>

            <button type="button" class="btn btn-outline-secondary prevStep">
                <i class="fas fa-arrow-left"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary nextStep">
                Suivant <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        {{-- ================= ETAPE 3 ================= --}}
        <div class="wizard-step d-none">
            <h5><i class="fas fa-copy"></i> Copies (Directions & Services)</h5>

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

            <button type="button" id="add_direction_service"
                    class="btn btn-secondary btn-sm mt-2">
                <i class="fas fa-plus"></i> Ajouter une autre direction
            </button>

            <!-- Hidden input required: JS sets this before submit. Must have name="copies" and id="copies_input" -->
            <input type="hidden" name="copies" id="copies_input">

            <hr>

            <div class="mb-3">
                <label>Résumé *</label>
                <textarea name="resume" class="form-control" required></textarea>
            </div>

            <div class="mb-3">
                <label>Observation</label>
                <textarea name="observation" class="form-control"></textarea>
            </div>

            <button type="button" class="btn btn-outline-secondary prevStep">
                <i class="fas fa-arrow-left"></i> Précédent
            </button>
            <button type="button" class="btn btn-primary nextStep">
                Suivant <i class="fas fa-arrow-right"></i>
            </button>
        </div>

        {{-- ================= ETAPE 4 ================= --}}
        <div class="wizard-step d-none">
            <h5><i class="fas fa-paperclip"></i> Annexes</h5>

            <input type="file" id="annexes" class="form-control mb-2" multiple>

            <div class="progress d-none" id="chunkProgress">
                <div class="progress-bar" id="chunkBar">0%</div>
            </div>

            <small id="chunkStatus"></small>

            <input type="hidden" name="annexes_paths" id="annexes_paths">

            <div class="mt-3">
                <button type="button" class="btn btn-outline-secondary prevStep">
                    <i class="fas fa-arrow-left"></i> Précédent
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check"></i> Enregistrer
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ================= WIZARD ================= */
    const steps = document.querySelectorAll('.wizard-step');
    const formBar = document.getElementById('formProgress');
    let current = 0;

    function updateWizard() {
        steps.forEach((s, i) => s.classList.toggle('d-none', i !== current));
        const percent = ((current + 1) / steps.length) * 100;
        formBar.style.width = percent + '%';
        formBar.innerText = `Étape ${current + 1} / ${steps.length}`;
    }

    document.querySelectorAll('.nextStep').forEach(btn => {
        btn.addEventListener('click', () => {
            current++;
            updateWizard();
        });
    });

    document.querySelectorAll('.prevStep').forEach(btn => {
        btn.addEventListener('click', () => {
            current--;
            updateWizard();
        });
    });

    updateWizard();


    /* ================= COPIES ================= */
    const directionsData = @json($directions);
    const container = document.getElementById('directions_services_container');
    const copiesInput = document.getElementById('copies_input');
    const addBtn = document.getElementById('add_direction_service');

    /* ================== AJOUT DIRECTION ================== */
    addBtn.addEventListener('click', () => {
        const html = `
            <div class="direction_service_group mb-3">
                <select class="form-control select_direction mb-2">
                    <option value="">Sélectionner une direction</option>
                    ${Object.keys(directionsData).map(d =>
                        `<option value="${d}">${d}</option>`
                    ).join('')}
                </select>
                <div class="services_container"></div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    });

    /* ================== CHARGEMENT SERVICES ================== */
    container.addEventListener('change', e => {
        if (!e.target.classList.contains('select_direction')) return;

        const servicesBox = e.target
            .closest('.direction_service_group')
            .querySelector('.services_container');

        servicesBox.innerHTML = '';

        (directionsData[e.target.value] || []).forEach(service => {
            servicesBox.insertAdjacentHTML('beforeend', `
                <div class="form-check">
                    <input type="checkbox"
                           class="form-check-input service-checkbox"
                           data-direction="${e.target.value}"
                           value="${service}">
                    <label class="form-check-label">${service}</label>
                </div>
            `);
        });
    });

    /* ================== AVANT SUBMIT ================== */
    function updateCopiesInput() {
    let copies = [];

    document.querySelectorAll('.service-checkbox:checked').forEach(cb => {
        copies.push({
            direction: cb.dataset.direction,
            service: cb.value
        });
    });

    copiesInput.value = JSON.stringify(copies);
    console.log('COPIES LIVE 👉', copies);
}

/* déclenché à CHAQUE clic sur une checkbox */
container.addEventListener('change', e => {
    if (e.target.classList.contains('service-checkbox')) {
        updateCopiesInput();
    }
});



    /* ================= CHUNK UPLOAD ================= */
    const input = document.getElementById('annexes');
    const progress = document.getElementById('chunkProgress');
    const barChunk = document.getElementById('chunkBar');
    const status = document.getElementById('chunkStatus');
    const pathsInput = document.getElementById('annexes_paths');
    const CHUNK = 5 * 1024 * 1024;

    input.addEventListener('change', async function () {

        let paths = [];
        barChunk.style.width = '0%';
        barChunk.innerText = '0%';
        progress.classList.remove('d-none');

        for (const file of input.files) {

            const total = Math.ceil(file.size / CHUNK);
            const fileId = Date.now() + '_' + Math.random().toString(36).slice(2);

            for (let i = 0; i < total; i++) {

                let fd = new FormData();
                fd.append('chunk', file.slice(i * CHUNK, (i + 1) * CHUNK));
                fd.append('file_id', fileId);
                fd.append('chunk_index', i);
                fd.append('total_chunks', total);
                fd.append('filename', file.name);

                let res = await fetch('{{ route("courrier_expedie.upload_chunk") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: fd
                });

                let json = await res.json();

                const percent = Math.round(((i + 1) / total) * 100);
                barChunk.style.width = percent + '%';
                barChunk.innerText = percent + '%';

                if (json.status === 'merged') {
                    paths.push(json.path);
                    status.innerHTML = `<span class="text-success">${file.name} téléversé</span>`;
                }
            }
        }

        pathsInput.value = JSON.stringify(paths);
    });

});
</script>

@endpush
