@extends('layouts.app')

@section('content')
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

@if (session('code_acces_valide'))
    {{-- CONTENU DE LA MESSAGERIE --}}
    <div class="container">
        <div class="scroll-animated custom-box mb-4">
            <h2><i class="fa-solid fa-comments"></i> Messagerie</h2>
        </div>

        @if(!isset($users))
        <p>Erreur : La variable $users n'est pas définie.</p>
        @endif

        <!-- Bouton pour démarrer une nouvelle discussion -->
        <div class="scroll-animated mb-3">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newConversationModal">
                <i class="fa-solid fa-user-plus"></i> Nouvelle Discussion
            </button>
        </div>

        <div class="scroll-animated row">
            <!-- Liste des discussions -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="scroll-animated card-header bg-primary text-white">
                        <i class="fa-solid fa-users"></i> Discussions
                    </div>
                    <div class="scroll-animated list-group">
                        @foreach($conversations as $user)
                            @if($user->id !== auth()->id())
                                <a href="{{ route('messages.show', $user->id) }}" 
                                   class="list-group-item list-group-item-action {{ request('user_id') == $user->id ? 'active' : '' }}">
                                    <i class="fa-solid fa-user"></i> {{ $user->name }}
                                    @if($user->unreadMessagesCount > 0)
                                        <span class="badge bg-danger float-end">{{ $user->unreadMessagesCount }}</span>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Affichage de la conversation -->
            <div class="col-md-9">
                <div class="scroll-animated card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="fa-solid fa-comments"></i> Conversation avec {{ $selectedUser->name ?? '...' }}
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        @if(isset($messages) && count($messages) > 0)
                            @foreach($messages as $message)
                                <!-- MESSAGE BLOCK -->
                                <div class="d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                                    <div class="p-3 rounded position-relative" style="max-width: 70%; {{ $message->sender_id == auth()->id() ? 'background-color: #007bff; color: white;' : 'background-color: #f1f1f1; color: black;' }}">
                                        <strong><i class="fa-solid fa-user"></i> {{ $message->sender->name }}</strong>
                                        <p class="mb-1">{{ $message->content }}</p>

                                        @if($message->annexes->isNotEmpty())
                                            <div class="bg-warning p-2 rounded mb-2">
                                                @foreach ($message->annexes as $annexe)
                                                    <a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank">
                                                        <i class="fa-solid fa-paperclip"></i> Voir l'annexe
                                                    </a><br>
                                                @endforeach
                                            </div>
                                        @endif

                                        <small><i class="fa-solid fa-clock"></i> {{ $message->created_at->format('H:i') }}</small>
                                        <span class="position-absolute bottom-0 end-0 me-2 mb-1">
                                            @if($message->is_read)
                                                <i class="fa-solid fa-check-double text-success"></i>
                                            @else
                                                <i class="fa-solid fa-check text-muted"></i>
                                            @endif
                                        </span>

                                        <form action="{{ route('messages.delete', $message->id) }}" method="POST" class="d-inline-block mt-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">Aucun message dans cette conversation.</p>
                        @endif
                    </div>
                </div>

                @if(isset($selectedUser))
                    <div class="card shadow-sm mt-3">
                        <div class="card-body p-2">
                            <form action="{{ route('messages.store') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $selectedUser->id }}">
                                <textarea name="content" class="form-control me-2" placeholder="Écrire un message..." required></textarea>
                                <label for="file-upload" class="btn btn-outline-secondary me-2">
                                    <i class="fa-solid fa-paperclip"></i>
                                </label>
                                <input type="file" name="annexes[]" id="file-upload" class="d-none" multiple>
                                <button type="submit" class="btn btn-success">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal pour démarrer une nouvelle discussion -->
        <div class="modal fade" id="newConversationModal" tabindex="-1" aria-labelledby="newConversationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newConversationModalLabel">Choisir un destinataire</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('messages.start') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="receiver">Destinataire</label>
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
                            <button type="submit" class="btn btn-primary">Démarrer la conversation</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    {{-- FORMULAIRE DE CODE D'ACCÈS --}}
    <div class="scroll-animated container">
        <h2 class="text-center text-dark mb-4"><i class="fas fa-lock"></i> Bienvenue - Code d'accès requis</h2>

        <div class="card shadow-lg">
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
@endsection
