@extends('layouts.app')

@section('content')
    <div class="scroll-animated container">
    @auth
    @if(session('code_acces_valide') !== true)
            {{-- Formulaire de code d'accès --}}
            <h2 class="scroll-animated text-dark mb-4">
                <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
            </h2>

            <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header text-white bg-primary">
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
            {{-- Formulaire d'ajout de courrier si l'utilisateur a validé le code --}}
            @if(Auth::user()->role !== 'agent')
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> 
                    Accès refusé. Vous devez être un agent pour ajouter un courrier.
                </div>
                <a href="{{ route('courriers.index') }}" class="btn btn-primary">
                    <i class="fa-solid fa-arrow-left"></i> Retour à la liste des courriers
                </a>
            @else
                <h2><i class="fa-solid fa-envelope-open-text"></i> Ajouter un courrier reçu</h2>

                <form action="{{ route('courriers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Affichage des erreurs de validation -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($errors->has('database'))
                        <div class="alert alert-warning">
                            <strong>Erreur base de données :</strong> {{ $errors->first('database') }}
                        </div>
                    @endif

                    <div class="scroll-animated mb-3">
                        <label for="date_reception" class="form-label">
                            <i class="fa-solid fa-calendar"></i> Date de réception
                        </label>
                        <input type="date" class="form-control" id="date_reception" name="date_reception" required value="{{ old('date_reception', $draft->date_reception ?? '') }}">
                        @error('date_reception') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="scroll-animated mb-3">
                        <label for="numero_enregistrement" class="form-label">
                            <i class="fa-solid fa-hashtag"></i> Numéro d'enregistrement
                        </label>

                        <input list="numero_list" name="numero_enregistrement" id="numero_enregistrement" class="form-control" placeholder="Sélectionner ou saisir un numéro d'enregistrement" value="{{ old('numero_enregistrement', $draft->numero_enregistrement ?? '') }}">

                        <datalist id="numero_list">
                            @foreach($numEnregistrements as $id => $numero)
                                <option value="{{ $numero }}">{{ $numero }}</option>
                            @endforeach
                        </datalist>

                        @error('numero_enregistrement') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="scroll-animated mb-3">
                        <label for="nom_expediteur" class="form-label">
                            <i class="fa-solid fa-user"></i> Nom de l'expéditeur
                        </label>
                        <input type="text" class="form-control" id="nom_expediteur" name="nom_expediteur" required value="{{ old('nom_expediteur', $draft->nom_expediteur ?? '') }}">
                        @error('nom_expediteur') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="scroll-animated mb-3">
                        <label for="numero_reference" class="form-label">
                            <i class="fa-solid fa-bookmark"></i> Numéro de référence
                        </label>
                        <input type="text" class="form-control" id="numero_reference" name="numero_reference" value="{{ old('numero_reference', $draft->numero_reference ?? '') }}">
                        @error('numero_reference') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="scroll-animated mb-3">
                        <label for="resume" class="form-label">
                            <i class="fa-solid fa-align-left"></i> Résumé
                        </label>
                        <textarea class="form-control" id="resume" name="resume" required>{{ old('resume', $draft->resume ?? '') }}</textarea>
                        @error('resume') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="scroll-animated mb-3">
                        <label for="observation" class="form-label">
                            <i class="fa-solid fa-eye"></i> Observation
                        </label>
                        <textarea class="form-control" id="observation" name="observation">{{ old('observation', $draft->observation ?? '') }}</textarea>
                        @error('observation') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    

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
             @endif
         @endif
     @else
        {{-- Si l'utilisateur n'est pas authentifié --}}
        <div class="alert alert-danger">
            <i class="fa-solid fa-lock"></i> Veuillez vous connecter pour accéder à cette page.
        </div>
    @endauth
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // define chunk size (200 MB)
    const CHUNK_SIZE = 200 * 1024 * 1024;

    const fileInput = document.getElementById('annexes');
    const progressWrap = document.getElementById('chunkProgress');
    const bar = document.getElementById('chunkBar');
    const status = document.getElementById('chunkStatus');
    const uploadedPathsInput = document.getElementById('uploaded_paths');
    const draftIdInput = document.getElementById('draft_id');
    const saveDraftBtn = document.getElementById('saveDraftBtn');
    const draftStatus = document.getElementById('draftStatus');

    let currentDraftId = draftIdInput?.value || null;

    function setProgress(p){ bar.style.width = p + '%'; bar.textContent = p + '%'; }

    async function uploadFile(file){
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        const fileId = Date.now().toString() + '-' + Math.random().toString(36).slice(2,8);
        for(let i=0;i<totalChunks;i++){
            const start = i*CHUNK_SIZE; const end = Math.min(file.size, start+CHUNK_SIZE);
            const chunk = file.slice(start, end);
            const fd = new FormData();
            fd.append('chunk', chunk);
            fd.append('file_id', fileId);
            fd.append('chunk_index', i);
            fd.append('total_chunks', totalChunks);
            fd.append('filename', file.name);

            try{
                const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                const res = await fetch('{{ route("upload.chunk") }}', { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': token } });
                const json = await res.json();
                const percent = Math.round(((i+1)/totalChunks)*100);
                progressWrap.classList.remove('d-none'); setProgress(percent);
                if(json.status === 'merged'){
                    const cur = uploadedPathsInput.value ? JSON.parse(uploadedPathsInput.value) : [];
                    cur.push(json.path);
                    uploadedPathsInput.value = JSON.stringify(cur);
                    status.innerHTML = '<span class="text-success>'+file.name+' uploaded</span>';
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

    function collectFormData(){
        const d = {};
        d.date_reception = document.getElementById('date_reception')?.value || '';
        d.numero_enregistrement = document.getElementById('numero_enregistrement')?.value || '';
        d.nom_expediteur = document.getElementById('nom_expediteur')?.value || '';
        d.numero_reference = document.getElementById('numero_reference')?.value || '';
        d.resume = document.getElementById('resume')?.value || '';
        d.uploaded_paths = uploadedPathsInput.value || '';
        return d;
    }

    async function postDraft(){
        const data = collectFormData();
        const fd = new FormData();
        for(const k in data) fd.append(k, data[k]);
        if(currentDraftId) fd.append('draft_id', currentDraftId);
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try{
            const res = await fetch('{{ route("courriers.draft") }}',{ method: 'POST', body: fd, headers: { 'X-CSRF-TOKEN': token }});
            const json = await res.json();
            if(json.status === 'ok'){
                if(json.id){ currentDraftId = json.id; if(draftIdInput) draftIdInput.value = json.id; }
                draftStatus.textContent = 'Brouillon sauvegardé';
                draftStatus.classList.remove('text-danger'); draftStatus.classList.add('text-success');
            } else {
                draftStatus.textContent = 'Échec sauvegarde'; draftStatus.classList.remove('text-success'); draftStatus.classList.add('text-danger');
            }
        }catch(e){ draftStatus.textContent = 'Erreur réseau lors de la sauvegarde'; draftStatus.classList.remove('text-success'); draftStatus.classList.add('text-danger'); }
    }

    if(saveDraftBtn){ saveDraftBtn.addEventListener('click', postDraft); }

    // After chunk merged, attach to draft immediately
    async function onChunkMerged(){ await postDraft(); }

    // Autosave logic similar to accuse_de_reception
    let formChanged = false;
    ['date_reception','numero_enregistrement','nom_expediteur','resume'].forEach(id=>{ const el = document.getElementById(id); if(el){ el.addEventListener('input', ()=>{ formChanged = true; }); } });
    setInterval(()=>{ if(formChanged){ formChanged = false; postDraft(); } }, 30000);

    // beacon on unload
    function sendDraftBeacon(){ try{ const data = collectFormData(); if(currentDraftId) data.draft_id = currentDraftId; const params = new URLSearchParams(); params.append('_token','{{ csrf_token() }}'); for(const k in data) params.append(k,data[k]); const blob = new Blob([params.toString()], { type: 'application/x-www-form-urlencoded' }); navigator.sendBeacon('{{ route("courriers.draft") }}', blob);}catch(e){}
    }
    window.addEventListener('beforeunload', sendDraftBeacon); document.addEventListener('visibilitychange', ()=>{ if(document.hidden) sendDraftBeacon(); });

    // integrate with existing uploadFile to call onChunkMerged when merged
    const originalUploadFile = uploadFile;
    uploadFile = async function(file){
        const result = await originalUploadFile(file);
        if(result !== false){
            await onChunkMerged();
        }
        return result;
    }
});
</script>
@endpush
