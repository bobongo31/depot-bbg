@extends('layouts.app')

@section('content')
<style>
    :root{
        --msg-primary:#0d6efd;
        --msg-primary-dark:#0b5ed7;
        --msg-bg:#f5f7fb;
        --msg-panel:#ffffff;
        --msg-border:#e9edf5;
        --msg-muted:#6c757d;
        --msg-success:#198754;
        --msg-warning:#ffc107;
    }

    .messagerie-page{
        background: linear-gradient(180deg, #f8fbff 0%, #f4f7fb 100%);
        min-height: 100vh;
        padding-bottom: 40px;
    }

    .messagerie-header{
        background: linear-gradient(135deg, var(--msg-primary), #3f8cff);
        color:#fff;
        border-radius:24px;
        padding:22px 24px;
        box-shadow:0 12px 30px rgba(13, 110, 253, .18);
        margin-bottom:20px;
    }

    .messagerie-header h2{
        margin:0;
        font-weight:700;
        font-size:1.5rem;
    }

    .messagerie-header p{
        margin:6px 0 0;
        opacity:.95;
    }

    .side-card,
    .chat-card,
    .compose-card{
        border:1px solid var(--msg-border);
        border-radius:22px;
        background:var(--msg-panel);
        box-shadow:0 10px 26px rgba(31, 45, 61, .05);
        overflow:hidden;
    }

    .side-card .card-header,
    .chat-card .card-header{
        background:linear-gradient(135deg, var(--msg-primary), var(--msg-primary-dark));
        color:#fff;
        font-weight:600;
        border:none;
        padding:14px 18px;
    }

    .discussion-list{
        max-height: 620px;
        overflow-y:auto;
    }

    .discussion-item{
        border:none;
        border-bottom:1px solid #f0f2f6;
        padding:14px 16px;
        transition:all .15s ease-in-out;
    }

    .discussion-item:hover{
        background:#f7faff;
    }

    .discussion-item.active{
        background:#eaf3ff;
        color:#0a58ca;
        font-weight:600;
    }

    .discussion-avatar{
        width:38px;
        height:38px;
        border-radius:50%;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:#e8f1ff;
        color:#0d6efd;
        font-size:1rem;
        flex-shrink:0;
    }

    .chat-body{
        background:
            radial-gradient(circle at top right, rgba(13, 110, 253, .04), transparent 30%),
            radial-gradient(circle at bottom left, rgba(25, 135, 84, .04), transparent 28%),
            #fcfdff;
        max-height: 560px;
        overflow-y:auto;
        padding:18px;
    }

    .message-row{
        display:flex;
        margin-bottom:16px;
    }

    .message-row.mine{
        justify-content:flex-end;
    }

    .message-row.theirs{
        justify-content:flex-start;
    }

    .message-bubble{
        max-width:74%;
        border-radius:20px;
        padding:14px 14px 10px;
        position:relative;
        box-shadow:0 10px 20px rgba(0,0,0,.05);
        word-wrap:break-word;
    }

    .message-row.mine .message-bubble{
        background:linear-gradient(135deg, var(--msg-primary), #3b82f6);
        color:#fff;
        border-bottom-right-radius:8px;
    }

    .message-row.theirs .message-bubble{
        background:#fff;
        color:#1f2d3d;
        border:1px solid #edf1f5;
        border-bottom-left-radius:8px;
    }

    .message-author{
        font-size:.9rem;
        font-weight:700;
        margin-bottom:6px;
        display:flex;
        align-items:center;
        gap:8px;
    }

    .message-text{
        font-size:.95rem;
        line-height:1.5;
        white-space:pre-wrap;
    }

    .message-meta{
        margin-top:10px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        font-size:.78rem;
    }

    .message-row.mine .message-meta{
        color:rgba(255,255,255,.92);
    }

    .message-row.theirs .message-meta{
        color:#6c757d;
    }

    .attachment-grid{
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(170px, 1fr));
        gap:10px;
        margin-top:12px;
    }

    .attachment-card{
        border-radius:16px;
        border:1px solid rgba(0,0,0,.08);
        padding:10px;
        background:rgba(255,255,255,.75);
        backdrop-filter: blur(3px);
    }

    .message-row.mine .attachment-card{
        background:rgba(255,255,255,.16);
        border-color:rgba(255,255,255,.2);
    }

    .attachment-thumb{
        width:100%;
        height:110px;
        border-radius:12px;
        background:#f3f6fa;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        margin-bottom:8px;
    }

    .message-row.mine .attachment-thumb{
        background:rgba(255,255,255,.18);
    }

    .attachment-thumb img,
    .attachment-thumb video{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .attachment-name{
        font-size:.84rem;
        font-weight:600;
        line-height:1.3;
        word-break:break-word;
        margin-bottom:6px;
    }

    .attachment-meta{
        font-size:.74rem;
        opacity:.85;
        margin-bottom:8px;
    }

    .message-row.mine .attachment-meta{
        color:rgba(255,255,255,.92);
    }

    .attachment-actions{
        display:flex;
        flex-wrap:wrap;
        gap:6px;
    }

    .compose-card{
        margin-top:16px;
        padding:14px;
    }

    .message-input{
        border-radius:16px;
        resize:none;
        min-height:56px;
        border:1px solid var(--msg-border);
    }

    .compose-actions{
        display:flex;
        flex-wrap:wrap;
        gap:10px;
        align-items:center;
        justify-content:space-between;
        margin-top:12px;
    }

    .selected-files{
        display:flex;
        flex-wrap:wrap;
        gap:10px;
        margin-top:12px;
    }

    .selected-file-chip{
        display:flex;
        align-items:center;
        gap:10px;
        border:1px solid #dbe7ff;
        background:#f7fbff;
        color:#1f2d3d;
        border-radius:14px;
        padding:8px 10px;
        min-width:180px;
        max-width:280px;
    }

    .selected-thumb{
        width:42px;
        height:42px;
        border-radius:10px;
        background:#eaf3ff;
        display:flex;
        align-items:center;
        justify-content:center;
        overflow:hidden;
        flex-shrink:0;
    }

    .selected-thumb img{
        width:100%;
        height:100%;
        object-fit:cover;
    }

    .overlay-message{
        position:fixed;
        inset:0;
        background:rgba(15, 23, 42, .56);
        display:flex;
        align-items:center;
        justify-content:center;
        z-index:1055;
        padding:20px;
    }

    .overlay-message.d-none{
        display:none !important;
    }

    .message-box{
        width:min(560px, 100%);
        background:#fff;
        border-radius:24px;
        padding:28px;
        box-shadow:0 20px 50px rgba(0,0,0,.18);
        text-align:center;
    }

    .empty-chat{
        min-height:280px;
        display:flex;
        align-items:center;
        justify-content:center;
        text-align:center;
        color:#6c757d;
        padding:30px;
    }

    .preview-modal .modal-content{
        border:none;
        border-radius:20px;
        overflow:hidden;
    }

    .preview-modal .modal-header{
        background:linear-gradient(135deg, var(--msg-primary), var(--msg-primary-dark));
        color:#fff;
        border:none;
    }

    .preview-frame{
        width:100%;
        height:75vh;
        border:none;
        background:#f8f9fa;
    }

    .file-placeholder{
        font-size:2rem;
        opacity:.9;
    }

    .btn-soft{
        border-radius:12px;
    }

    @media (max-width: 991.98px){
        .message-bubble{ max-width: 92%; }
        .chat-body{ max-height: 450px; }
    }
</style>

<div id="overlayMessage" class="overlay-message">
    <div class="message-box">
        <h5 class="mb-3">
            <i class="fas fa-info-circle text-primary"></i> Messagerie Internet 📧
        </h5>
        <p class="mb-4">
            Cette fonctionnalité permet aux <strong>employés de l'entreprise</strong> de discuter et d'<strong>échanger des documents</strong> en toute sécurité, favorisant ainsi la collaboration et la communication fluide au sein de l'organisation.
        </p>
        <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
            <i class="fas fa-check-circle me-1"></i> J'ai compris
        </button>
    </div>
</div>

<div class="messagerie-page">
@if (session('code_acces_valide'))
    <div class="container py-4">
        <div class="messagerie-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2><i class="fa-solid fa-comments me-2"></i>Messagerie</h2>
                    <p>Espace sécurisé pour discuter et échanger des pièces jointes entre agents.</p>
                </div>
                <div>
                    <button class="btn btn-light btn-soft" data-bs-toggle="modal" data-bs-target="#newConversationModal">
                        <i class="fa-solid fa-user-plus me-1"></i> Nouvelle discussion
                    </button>
                </div>
            </div>
        </div>

        @if(!isset($users))
            <div class="alert alert-danger">Erreur : La variable <code>$users</code> n'est pas définie.</div>
        @endif

        <div class="row g-4">
            <div class="col-lg-4 col-xl-3">
                <div class="side-card">
                    <div class="card-header">
                        <i class="fa-solid fa-users me-2"></i>Discussions
                    </div>

                    <div class="discussion-list list-group list-group-flush">
                        @forelse($conversations as $user)
                            @if($user->id !== auth()->id())
                                <a href="{{ route('messages.show', $user->id) }}"
                                   class="discussion-item list-group-item list-group-item-action {{ request('user_id') == $user->id ? 'active' : '' }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="discussion-avatar">
                                            <i class="fa-solid fa-user"></i>
                                        </span>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold">{{ $user->name }}</div>
                                            <small class="text-muted">Discussion privée</small>
                                        </div>
                                        @if($user->unreadMessagesCount > 0)
                                            <span class="badge bg-danger rounded-pill">{{ $user->unreadMessagesCount }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endif
                        @empty
                            <div class="p-3 text-muted">Aucune discussion disponible.</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-xl-9">
                <div class="chat-card">
                    <div class="card-header d-flex justify-content-between align-items-center gap-2">
                        <div>
                            <i class="fa-solid fa-comments me-2"></i>
                            Conversation avec {{ $selectedUser->name ?? '...' }}
                        </div>
                        @if(isset($selectedUser))
                            <span class="badge bg-light text-dark">{{ $messages->count() ?? 0 }} message(s)</span>
                        @endif
                    </div>

                    <div class="chat-body" id="chatBody">
                        @if(isset($messages) && count($messages) > 0)
                            @foreach($messages as $message)
                                @php
                                    $isMine = $message->sender_id == auth()->id();
                                @endphp

                                <div class="message-row {{ $isMine ? 'mine' : 'theirs' }}">
                                    <div class="message-bubble">
                                        <div class="message-author">
                                            <i class="fa-solid fa-user"></i>
                                            {{ $message->sender->name ?? 'Utilisateur' }}
                                        </div>

                                        @if(!empty($message->content))
                                            <div class="message-text">{{ $message->content }}</div>
                                        @endif

                                        @if($message->annexes->isNotEmpty())
                                            <div class="attachment-grid">
                                                @foreach($message->annexes as $annexe)
                                                    @php
                                                        $path = $annexe->file_path ?? '';
                                                        $fileUrl = $path ? asset('storage/' . ltrim($path, '/')) : null;
                                                        $fileName = $annexe->original_name
                                                            ?? $annexe->file_name
                                                            ?? basename($path ?: 'fichier');
                                                        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                        $mime = $annexe->mime_type
                                                            ?? (function_exists('mime_content_type') && $path && file_exists(storage_path('app/public/' . ltrim($path, '/')))
                                                                ? mime_content_type(storage_path('app/public/' . ltrim($path, '/')))
                                                                : null);

                                                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
                                                        $isPdf   = $extension === 'pdf';
                                                        $isVideo = in_array($extension, ['mp4', 'webm', 'ogg', 'mov']);
                                                        $isAudio = in_array($extension, ['mp3', 'wav', 'ogg', 'm4a']);
                                                        $isText  = in_array($extension, ['txt', 'log', 'csv', 'json']);
                                                    @endphp

                                                    <div class="attachment-card">
                                                        <div class="attachment-thumb">
                                                            @if($isImage && $fileUrl)
                                                                <img src="{{ $fileUrl }}" alt="{{ $fileName }}">
                                                            @elseif($isVideo && $fileUrl)
                                                                <video muted>
                                                                    <source src="{{ $fileUrl }}">
                                                                </video>
                                                            @elseif($isPdf)
                                                                <div class="file-placeholder text-danger">
                                                                    <i class="fa-solid fa-file-pdf"></i>
                                                                </div>
                                                            @elseif($isAudio)
                                                                <div class="file-placeholder text-success">
                                                                    <i class="fa-solid fa-file-audio"></i>
                                                                </div>
                                                            @elseif($isText)
                                                                <div class="file-placeholder text-secondary">
                                                                    <i class="fa-solid fa-file-lines"></i>
                                                                </div>
                                                            @else
                                                                <div class="file-placeholder text-primary">
                                                                    <i class="fa-solid fa-file"></i>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="attachment-name">{{ $fileName }}</div>

                                                        <div class="attachment-meta">
                                                            {{ strtoupper($extension ?: 'FICHIER') }}
                                                            @if(!empty($annexe->file_size))
                                                                • {{ number_format($annexe->file_size / 1024, 1, ',', ' ') }} Ko
                                                            @endif
                                                        </div>

                                                        <div class="attachment-actions">
                                                            @if($fileUrl)
                                                                <button
                                                                    type="button"
                                                                    class="btn btn-light btn-sm btn-soft preview-file-btn"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#filePreviewModal"
                                                                    data-file-url="{{ $fileUrl }}"
                                                                    data-file-name="{{ $fileName }}"
                                                                    data-file-type="{{ $extension }}"
                                                                >
                                                                    <i class="fa-solid fa-eye me-1"></i>Prévisualiser
                                                                </button>

                                                                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-outline-secondary btn-sm btn-soft">
                                                                    <i class="fa-solid fa-up-right-from-square me-1"></i>Ouvrir
                                                                </a>

                                                                <a href="{{ $fileUrl }}" download class="btn btn-outline-primary btn-sm btn-soft">
                                                                    <i class="fa-solid fa-download me-1"></i>Télécharger
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="message-meta">
                                            <small>
                                                <i class="fa-solid fa-clock me-1"></i>
                                                {{ $message->created_at->format('d/m/Y H:i') }}
                                            </small>

                                            <div class="d-flex align-items-center gap-2">
                                                @if($isMine)
                                                    @if($message->is_read)
                                                        <span><i class="fa-solid fa-check-double text-success"></i></span>
                                                    @else
                                                        <span><i class="fa-solid fa-check text-light"></i></span>
                                                    @endif
                                                @endif

                                                <form action="{{ route('messages.delete', $message->id) }}" method="POST" class="d-inline-block"
                                                      onsubmit="return confirm('Supprimer ce message ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm btn-soft">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-chat">
                                <div>
                                    <i class="fa-solid fa-comments fs-1 text-primary mb-3"></i>
                                    <p class="mb-1 fw-semibold">Aucune conversation sélectionnée.</p>
                                    <small>Commencez par sélectionner une discussion puis écrire un message ou joindre un fichier.</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if(isset($selectedUser))
                    <div class="compose-card">
                        <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" id="messageForm">
                            @csrf
                            <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">

                            <textarea
                                name="content"
                                class="form-control message-input"
                                placeholder="Écrire un message..."
                                rows="3"
                            >{{ old('content') }}</textarea>

                            <div class="compose-actions">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <label for="file-upload" class="btn btn-outline-secondary btn-soft mb-0">
                                        <i class="fa-solid fa-paperclip me-1"></i>Joindre des fichiers
                                    </label>
                                    <input
                                        type="file"
                                        name="annexes[]"
                                        id="file-upload"
                                        class="d-none"
                                        multiple
                                        accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.bmp,.svg,.mp4,.webm,.ogg,.mov,.mp3,.wav,.m4a,.txt,.csv,.json,.doc,.docx,.xls,.xlsx,.ppt,.pptx"
                                    >
                                    <small class="text-muted">PDF, images, audio, vidéo et documents bureautiques.</small>
                                </div>

                                <button type="submit" class="btn btn-success btn-soft">
                                    <i class="fa-solid fa-paper-plane me-1"></i>Envoyer
                                </button>
                            </div>

                            <div id="selectedFilesPreview" class="selected-files"></div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="modal fade" id="newConversationModal" tabindex="-1" aria-labelledby="newConversationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content border-0 rounded-4 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="newConversationModalLabel">Choisir un destinataire</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('messages.start') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="receiver" class="form-label">Destinataire</label>
                                <select name="receiver_id" id="receiver" class="form-control" required>
                                    @foreach($users as $user)
                                        @if($user->id !== auth()->id() && !$conversations->contains($user))
                                            <option value="{{ $user->id }}">
                                                {{ $user->name ?? 'Nom non défini' }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-comments me-1"></i>Démarrer la conversation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade preview-modal" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filePreviewModalLabel">Prévisualisation du fichier</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body" id="filePreviewContent"></div>
                    <div class="modal-footer">
                        <a href="#" target="_blank" id="openPreviewFileBtn" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-up-right-from-square me-1"></i>Ouvrir dans un nouvel onglet
                        </a>
                        <a href="#" download id="downloadPreviewFileBtn" class="btn btn-outline-primary">
                            <i class="fa-solid fa-download me-1"></i>Télécharger
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="scroll-animated container py-5">
        <h2 class="text-center text-dark mb-4"><i class="fas fa-lock"></i> Bienvenue - Code d'accès requis</h2>

        <div class="card shadow-lg border-0 rounded-4">
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
    </div>
@endif
</div>

<script>
    function closeOverlay() {
        const overlay = document.getElementById('overlayMessage');
        if (overlay) {
            overlay.classList.add('d-none');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const chatBody = document.getElementById('chatBody');
        if (chatBody) {
            chatBody.scrollTop = chatBody.scrollHeight;
        }

        const fileInput = document.getElementById('file-upload');
        const selectedPreview = document.getElementById('selectedFilesPreview');

        function iconForExtension(ext) {
            ext = (ext || '').toLowerCase();
            if (['jpg','jpeg','png','gif','webp','bmp','svg'].includes(ext)) return 'fa-file-image text-primary';
            if (ext === 'pdf') return 'fa-file-pdf text-danger';
            if (['mp4','webm','ogg','mov'].includes(ext)) return 'fa-file-video text-info';
            if (['mp3','wav','m4a'].includes(ext)) return 'fa-file-audio text-success';
            if (['doc','docx'].includes(ext)) return 'fa-file-word text-primary';
            if (['xls','xlsx','csv'].includes(ext)) return 'fa-file-excel text-success';
            if (['ppt','pptx'].includes(ext)) return 'fa-file-powerpoint text-warning';
            if (['txt','json','log'].includes(ext)) return 'fa-file-lines text-secondary';
            return 'fa-file text-muted';
        }

        if (fileInput && selectedPreview) {
            fileInput.addEventListener('change', function () {
                selectedPreview.innerHTML = '';

                Array.from(this.files).forEach(file => {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const chip = document.createElement('div');
                    chip.className = 'selected-file-chip';

                    const thumb = document.createElement('div');
                    thumb.className = 'selected-thumb';

                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        thumb.appendChild(img);
                    } else {
                        thumb.innerHTML = `<i class="fa-solid ${iconForExtension(ext)}"></i>`;
                    }

                    const info = document.createElement('div');
                    info.className = 'flex-grow-1';

                    const title = document.createElement('div');
                    title.className = 'fw-semibold small';
                    title.textContent = file.name;

                    const meta = document.createElement('div');
                    meta.className = 'text-muted small';
                    meta.textContent = `${(file.size / 1024).toFixed(1)} Ko`;

                    info.appendChild(title);
                    info.appendChild(meta);

                    chip.appendChild(thumb);
                    chip.appendChild(info);
                    selectedPreview.appendChild(chip);
                });
            });
        }

        const previewContent = document.getElementById('filePreviewContent');
        const previewTitle = document.getElementById('filePreviewModalLabel');
        const openBtn = document.getElementById('openPreviewFileBtn');
        const downloadBtn = document.getElementById('downloadPreviewFileBtn');
        const previewModal = document.getElementById('filePreviewModal');

        document.querySelectorAll('.preview-file-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const url = this.dataset.fileUrl;
                const name = this.dataset.fileName || 'Fichier';
                const type = (this.dataset.fileType || '').toLowerCase();

                previewTitle.textContent = name;
                openBtn.href = url;
                downloadBtn.href = url;
                previewContent.innerHTML = '';

                if (['jpg','jpeg','png','gif','webp','bmp','svg'].includes(type)) {
                    previewContent.innerHTML = `<img src="${url}" alt="${name}" class="img-fluid rounded shadow-sm">`;
                } else if (type === 'pdf') {
                    previewContent.innerHTML = `<iframe src="${url}#toolbar=1&navpanes=0&scrollbar=1" class="preview-frame"></iframe>`;
                } else if (['mp4','webm','ogg','mov'].includes(type)) {
                    previewContent.innerHTML = `
                        <video controls class="w-100 rounded">
                            <source src="${url}">
                            Votre navigateur ne supporte pas la lecture vidéo.
                        </video>
                    `;
                } else if (['mp3','wav','m4a','ogg'].includes(type)) {
                    previewContent.innerHTML = `
                        <audio controls class="w-100">
                            <source src="${url}">
                            Votre navigateur ne supporte pas la lecture audio.
                        </audio>
                    `;
                } else if (['txt','log','csv','json'].includes(type)) {
                    previewContent.innerHTML = `<iframe src="${url}" class="preview-frame"></iframe>`;
                } else {
                    previewContent.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fa-solid fa-file fs-1 text-primary mb-3"></i>
                            <p class="mb-2">Prévisualisation directe non disponible pour ce type de fichier.</p>
                            <a href="${url}" target="_blank" class="btn btn-primary me-2">
                                <i class="fa-solid fa-up-right-from-square me-1"></i>Ouvrir
                            </a>
                            <a href="${url}" download class="btn btn-outline-primary">
                                <i class="fa-solid fa-download me-1"></i>Télécharger
                            </a>
                        </div>
                    `;
                }
            });
        });

        if (previewModal) {
            previewModal.addEventListener('hidden.bs.modal', function () {
                previewContent.innerHTML = '';
                openBtn.href = '#';
                downloadBtn.href = '#';
                previewTitle.textContent = 'Prévisualisation du fichier';
            });
        }
    });
</script>
@endsection