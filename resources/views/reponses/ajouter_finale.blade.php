@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Ajouter une réponse finale</h3>

    @php
        $directions = [
            'Direction Financière' => ['Comptabilité', 'Trésorerie', 'Caisse'],
            'Ressources Humaines et Services Généraux' => ['Ressources Humaines', 'Services Généraux', 'Ressources Humaines et Services Généraux'],
            'Coordination des Provinces' => ['Coordination'],
            'Promotion Culturelle' => ['Services de la Promotion Culturelle', 'Production et Animation Culturelle'],
            'Contrôle et Inspection' => ['Audit interne', 'Contrôle et Inspection'],
            'Mobilisation de la Redevance' => ['Taxation', 'Mobilisation de la Redevance'],
            'Études, Planification et de la Formation' => ['Études', 'Planification', 'Formation'],
            'Autres' => ['Informatique', 'Juridique et Contentieux', 'Secrétariat DG', 'Assistant DG', 'Assistant DGA'],
        ];
    @endphp

    <form id="finale_form" action="{{ action([App\Http\Controllers\ReponseController::class, 'ajouterReponseFinale'], ['reponseId' => $reponse->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-hashtag"></i> Numéro d'Enregistrement</label>
            <select id="select_numero_enregistrement" class="form-control" onchange="document.getElementById('numero_enregistrement').value = this.value">
                <option value="">Sélectionner un numéro d'enregistrement</option>
                {{-- essayer de préremplir depuis la réponse liée --}}
                @if(!empty($reponse->numero_enregistrement))
                    <option value="{{ $reponse->numero_enregistrement }}" selected>{{ $reponse->numero_enregistrement }}</option>
                @endif
            </select>
            <input type="text" id="numero_enregistrement" name="numero_enregistrement" class="form-control mt-2" required value="{{ old('numero_enregistrement', $reponse->numero_enregistrement ?? '') }}">
            @error('numero_enregistrement') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-bookmark"></i> Numéro de Référence</label>
            <input type="text" id="numero_reference" name="numero_reference" class="form-control" value="{{ old('numero_reference', $reponse->numero_reference ?? '') }}">
            @error('numero_reference') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-building"></i> Direction et Services Concernés</label>
            <div id="directions_services_container">
                <div class="direction_service_group mb-2">
                    <select class="form-control select_direction mb-2">
                        <option value="">Sélectionner une direction</option>
                        @foreach($directions as $dir => $svcs)
                            <option value="{{ $dir }}">{{ $dir }}</option>
                        @endforeach
                    </select>
                    <div class="services_container"></div>
                </div>
            </div>
            <small class="text-muted">Sélectionnez le(s) service(s) concernés pour cette réponse finale.</small>
        </div>

        {{-- hidden field that will receive JSON array of selected services --}}
        <input type="hidden" name="service_concerne" id="service_concerne_input" value='{{ old('service_concerne', $reponse->service_concerne ?? '') }}'>
        @error('service_concerne') <small class="text-danger">{{ $message }}</small> @enderror

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-user"></i> Observation</label>
            <textarea name="observation" id="observation" class="form-control">{{ old('observation') }}</textarea>
            @error('observation') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label"><i class="fas fa-paperclip"></i> Annexe (pdf, jpg, png, docx)</label>
            <input type="file" name="file" id="file" class="form-control">
            @error('file') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
        <a href="{{ route('reponses.index') }}" class="btn btn-outline-secondary ms-2">Annuler</a>
    </form>

    <script>
        const directionsData = @json($directions);

        function createServiceCheckboxes(direction) {
            const container = document.createElement('div');
            container.classList.add('services_group','mb-2');
            if (direction && directionsData[direction]) {
                const title = document.createElement('div'); title.innerHTML = `<strong>${direction}</strong>`; container.appendChild(title);
                directionsData[direction].forEach(service => {
                    const id = 'svc_' + service.replace(/\s+/g,'_');
                    const div = document.createElement('div');
                    div.classList.add('form-check','mb-1');
                    div.innerHTML = `
                        <input class="form-check-input svc-checkbox" type="checkbox" id="${id}" value="${service}">
                        <label class="form-check-label" for="${id}">${service}</label>
                    `;
                    container.appendChild(div);
                });
            }
            return container;
        }

        document.getElementById('directions_services_container').addEventListener('change', function(e){
            if (e.target.classList.contains('select_direction')) {
                const group = e.target.closest('.direction_service_group');
                const container = group.querySelector('.services_container');
                container.innerHTML = '';
                container.appendChild(createServiceCheckboxes(e.target.value));
            }
        });

        function collectServices() {
            const checked = Array.from(document.querySelectorAll('.svc-checkbox:checked')).map(i=>i.value);
            return checked;
        }

        // Pre-check services if we have existing value (JSON or comma string)
        (function prefillServices(){
            const raw = document.getElementById('service_concerne_input').value || '';
            let arr = [];
            try { arr = JSON.parse(raw); } catch(e) { arr = raw ? raw.split(',').map(s=>s.trim()).filter(Boolean) : []; }
            if (!Array.isArray(arr) || arr.length===0) return;
            // find a direction containing first service and render its checkboxes
            const first = arr[0];
            for (const dir in directionsData) {
                if (directionsData[dir].includes(first)) {
                    const sel = document.querySelector('.direction_service_group .select_direction');
                    if (sel) { sel.value = dir; sel.dispatchEvent(new Event('change')); }
                    setTimeout(()=>{
                        arr.forEach(v=>{
                            const chk = document.querySelector('.svc-checkbox[value="'+v+'"]'); if (chk) chk.checked = true;
                        });
                    },50);
                    break;
                }
            }
        })();

        // Serialize services as JSON into hidden input before submit
        document.getElementById('finale_form').addEventListener('submit', function(e){
            const services = collectServices();
            document.getElementById('service_concerne_input').value = JSON.stringify(services);
        });
    </script>
</div>
@endsection
