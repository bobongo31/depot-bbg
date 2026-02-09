@extends('layouts.app')

@section('content')


<div class="scroll-animated container">
    @auth
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                @if(session('download_url'))
                    <a href="{{ session('download_url') }}" class="btn btn-sm btn-outline-success ms-2">Télécharger l'accusé</a>
                @endif
            </div>
        @endif
        @if(session('code_acces_valide') !== true)
            <!-- Formulaire de code d'accès -->
            <h2 class="text-start text-dark mb-4 scroll-animated">
                <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
            </h2>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white bg-primary text-start">
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

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle"></i> Valider
                        </button>
                    </form>
                </div>
            </div>

        @else
            <!-- Formulaire d'ajout d'accusé de réception -->
            @if(Auth::user()->role !== 'agent')
                <div class="alert alert-danger">
                    <i class=" fa-solid fa-triangle-exclamation"></i> 
                    Accès refusé. Vous devez être un agent pour ajouter un accusé de réception.
                </div>
                <a href="{{ route('accuses.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left"></i> Retour à la liste des accusés
                </a>
            @else
                <div class="scroll-animated custom-box text-start mb-4">
                    <h1 class="text-start"><i class="fa-solid fa-file-signature"></i> Accusé de Réception</h1>
                    <form action="{{ route('accuse.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Champ Date de réception -->
                        <div class="mb-3 scroll-animated">
                            <label for="date_reception" class="form-label">
                                <i class="fa-solid fa-calendar"></i> Date de réception
                            </label>
                            <input type="date" name="date_reception" id="date_reception" class="form-control" required value="{{ isset($draft) ? $draft->date_reception : '' }}">
                        </div>

                        <!-- Champ Numéro d'enregistrement -->
                        <div class="mb-3 scroll-animated">
                            <label for="numero_enregistrement">
                                <i class="fa-solid fa-hashtag"></i> Numéro d'Enregistrement
                            </label>
                            <input type="text" name="numero_enregistrement" id="numero_enregistrement" class="form-control" required value="{{ isset($draft) ? $draft->numero_enregistrement : '' }}">
                        </div>

                        <!-- Champ Réceptionné par -->
                        <div class="mb-3 scroll-animated">
                            <label for="receptionne_par" class="form-label">
                                <i class="fa-solid fa-user"></i> Réceptionné par
                            </label>
                            <input type="text" name="receptionne_par" id="receptionne_par" class="form-control" required value="{{ isset($draft) ? $draft->receptionne_par : '' }}">
                        </div>

                        <!-- Champ Objet -->
                        <div class="mb-3 scroll-animated">
                            <label for="objet" class="form-label">
                                <i class="fa-solid fa-book"></i> Objet
                            </label>
                            <input type="text" name="objet" id="objet" class="form-control" required value="{{ isset($draft) ? $draft->objet : '' }}">
                        </div>

                        <!-- Champ Annexes -->
                        <div class="mb-3 scroll-animated">
                            <label for="annexes" class="form-label">
                                <i class="fa-solid fa-file-arrow-up"></i> Téléversez le courrier
                            </label>
                            <input type="file" name="annexes[]" id="annexes" class="form-control" multiple>

                            <div class="mt-2">
                                <div class="progress d-none" id="chunkProgress">
                                    <div class="progress-bar" role="progressbar" style="width:0%" id="chunkBar">0%</div>
                                </div>
                                <div id="chunkStatus" class="small mt-1"></div>
                            </div>
                            <input type="hidden" name="uploaded_paths" id="uploaded_paths" value='@json(isset($draft) && $draft->annexes ? $draft->annexes->pluck("file_path") : [])'>
                            <input type="hidden" name="draft_id" id="draft_id" value="{{ isset($draft) ? $draft->id : '' }}">
                        </div>

                        <!-- Bouton de soumission -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fa-solid fa-check"></i> Enregistrer
                            </button>
                            <button type="button" id="saveDraftBtn" class="btn btn-secondary">
                                <i class="fa-regular fa-floppy-disk"></i> Enregistrer comme brouillon
                            </button>
                            <div id="draftStatus" class="align-self-center small text-muted"></div>
                        </div>
                    </form>
                </div>
            @endif
        @endif
    @endauth

    @guest
        <h2 class="text-start text-dark mb-4">
            <i class="fas fa-lock"></i> Veuillez vous connecter pour accéder à cette page
        </h2>
    @endguest
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const fileInput = document.getElementById('annexes');
    const progressWrap = document.getElementById('chunkProgress');
    const bar = document.getElementById('chunkBar');
    const status = document.getElementById('chunkStatus');
    const uploadedPathsInput = document.getElementById('uploaded_paths');
    const draftIdInput = document.getElementById('draft_id');

    if(!fileInput) return;

    const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB

    function setProgress(p){ 
        bar.style.width = p + '%'; 
        bar.textContent = p + '%'; 
    }

    let currentDraftId = draftIdInput?.value || null;

    async function postDraft(){
        const data = collectFormData();
        const fd = new FormData();
        for(const k in data) fd.append(k, data[k]);
        fd.append('save_as_draft', '1');
        if(currentDraftId) fd.append('draft_id', currentDraftId);
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try{
            const res = await fetch('{{ route("accuse.draft") }}',{ 
                method: 'POST', 
                body: fd, 
                headers: { 'X-CSRF-TOKEN': token }
            });
            const json = await res.json();
            if(json.status === 'ok'){
                // persist draft id for subsequent saves
                if(json.id){
                    currentDraftId = json.id;
                    if(draftIdInput) draftIdInput.value = json.id;
                }
                draftStatus.textContent = 'Brouillon sauvegardé';
                draftStatus.classList.remove('text-danger');
                draftStatus.classList.add('text-success');
            } else {
                draftStatus.textContent = 'Échec sauvegarde';
                draftStatus.classList.remove('text-success');
                draftStatus.classList.add('text-danger');
            }
        }catch(e){
            draftStatus.textContent = 'Erreur réseau lors de la sauvegarde';
            draftStatus.classList.remove('text-success');
            draftStatus.classList.add('text-danger');
        }
    }

    async function uploadFile(file){
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        const fileId = Date.now().toString() + '-' + Math.random().toString(36).slice(2,8);
        for(let i=0;i<totalChunks;i++){
            const start = i*CHUNK_SIZE; 
            const end = Math.min(file.size, start+CHUNK_SIZE);
            const chunk = file.slice(start, end);
            const fd = new FormData();
            fd.append('chunk', chunk);
            fd.append('file_id', fileId);
            fd.append('chunk_index', i);
            fd.append('total_chunks', totalChunks);
            fd.append('filename', file.name);

            try{
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const res = await fetch('{{ route("upload.chunk") }}', { 
                    method: 'POST', 
                    body: fd, 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest', 
                        'X-CSRF-TOKEN': token 
                    } 
                });
                const json = await res.json();
                const percent = Math.round(((i+1)/totalChunks)*100);
                progressWrap.classList.remove('d-none'); 
                setProgress(percent);
                if(json.status === 'merged'){
                    // append final path
                    const cur = uploadedPathsInput.value ? JSON.parse(uploadedPathsInput.value) : [];
                    cur.push(json.path);
                    uploadedPathsInput.value = JSON.stringify(cur);
                    // attach uploaded annex immediately to draft (if any) so server creates Annexe records
                    // this will also create a draft if none exists
                    await postDraft();
                    status.innerHTML = '<span class="text-success">'+file.name+' uploaded</span>';
                }
            }catch(err){
                status.innerHTML = '<span class="text-danger">Échec upload: '+file.name+'</span>';
                progressWrap.classList.add('d-none');
                return;
            }
        }
    }

    fileInput.addEventListener('change', function(e){
        const files = Array.from(e.target.files || []);
        if(files.length === 0) return;
        uploadedPathsInput.value = JSON.stringify([]);
        (async ()=>{
            for(const f of files){
                await uploadFile(f);
            }
        })();
    });

    // --- Save as draft and autosave ---
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const draftStatus = document.getElementById('draftStatus');
    let autosaveTimer = null;

    function collectFormData(){
        const d = {};
        d.date_reception = document.getElementById('date_reception')?.value || '';
        d.numero_enregistrement = document.getElementById('numero_enregistrement')?.value || '';
        d.receptionne_par = document.getElementById('receptionne_par')?.value || '';
        d.objet = document.getElementById('objet')?.value || '';
        d.avis = document.getElementById('avis')?.value || '';
        d.uploaded_paths = uploadedPathsInput.value || '';
        return d;
    }

    if(saveDraftBtn){
        saveDraftBtn.addEventListener('click', function(){
            postDraft();
        });
    }

    // Autosave every 30 seconds when any form field changes
    let formChanged = false;
    ['date_reception','numero_enregistrement','receptionne_par','objet'].forEach(id=>{
        const el = document.getElementById(id);
        if(el){
            el.addEventListener('input', ()=>{ formChanged = true; });
        }
    });

    setInterval(()=>{
        if(formChanged){ formChanged = false; postDraft(); }
    }, 30000);

    // Save draft on page unload (attempt using navigator.sendBeacon for reliability)
    function sendDraftBeacon(){
        try{
            const data = collectFormData();
            data.save_as_draft = '1';
            if(currentDraftId) data.draft_id = currentDraftId;
            const params = new URLSearchParams();
            params.append('_token', '{{ csrf_token() }}');
            for(const k in data) params.append(k, data[k]);
            const blob = new Blob([params.toString()], { type: 'application/x-www-form-urlencoded' });
            navigator.sendBeacon('{{ route("accuse.draft") }}', blob);
        }catch(e){
            // swallow — best-effort
        }
    }

    window.addEventListener('beforeunload', function(e){
        // ensure we attempt to save even if user didn't click
        sendDraftBeacon();
    });

    document.addEventListener('visibilitychange', function(){
        if(document.hidden){
            // save when tab becomes hidden
            sendDraftBeacon();
        }
    });

});
</script>
@endpush
