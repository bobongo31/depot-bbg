@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fa-solid fa-comments"></i> Messagerie</h2>

    @if(!isset($users))
    <p>Erreur : La variable $users n'est pas définie.</p>
@endif

    <!-- Bouton pour démarrer une nouvelle discussion -->
    <div class="mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#newConversationModal">
            <i class="fa-solid fa-user-plus"></i> Nouvelle Discussion
        </button>
    </div>

    <div class="row">
        <!-- Liste des discussions -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fa-solid fa-users"></i> Discussions
                </div>
                <div class="list-group">
                    @foreach($conversations as $user)
                        @if($user->id !== auth()->id()) <!-- Exclure l'utilisateur courant -->
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

        <!-- Affichage de la conversation sélectionnée -->
        <div class="col-md-9">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fa-solid fa-comments"></i> Conversation avec {{ $selectedUser->name ?? '...' }}
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if(isset($messages) && count($messages) > 0)
                        @foreach($messages as $message)
                            <div class="d-flex {{ $message->sender_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                                <div class="p-3 rounded position-relative" style="max-width: 70%; {{ $message->sender_id == auth()->id() ? 'background-color: #007bff; color: white;' : 'background-color: #f1f1f1; color: black;' }}">
                                    <strong><i class="fa-solid fa-user"></i> {{ $message->sender->name }}</strong>
                                    <p class="mb-1">{{ $message->content }}</p>

                                    <!-- Affichage des annexes en jaune -->
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

                                    <!-- Formulaire de suppression du message -->
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

            <!-- Barre d'envoi du message -->
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

    <!-- Modal pour choisir un destinataire -->
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

@endsection
