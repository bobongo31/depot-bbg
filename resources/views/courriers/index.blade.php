@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>consulter tous les courriers enregistrés</strong>, triés par date ou par service émetteur, pour un accès rapide et organisé.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

@auth
    @if(session('code_acces_valide'))
    <div class="container my-4">
            <div class="scroll-animated custom-box text-center mb-4">
                <h1><i class="fas fa-envelope-open-text"></i> Liste des courriers reçus</h1>
            </div>

            <div class="scroll-animated table-responsive">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="small text-muted">
                                Affichage {{ $courriers->firstItem() ?? 0 }}–{{ $courriers->lastItem() ?? 0 }} sur {{ $courriers->total() ?? 0 }}
                            </div>
                            <div class="small text-muted">Trié par date (plus récent d'abord)</div>
                        </div>

                        <table id="courriersTable" class="table table-bordered table-hover table-sm align-middle">
                    <thead class="table-light">
                <tr>
                            <th class="text-start">Date</th>
                            <th class="text-start">N° enreg.</th>
                            <th class="text-start">N° réf.</th>
                            <th class="text-start">Expéditeur</th>
                            <th class="text-start">Object</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                        @foreach($courriers as $courrier)
    <tr data-href="{{ route('courriers.show', $courrier->id) }}" class="clickable-row" tabindex="0" role="link" style="cursor:pointer;">
                                <td class="text-start">{{ \Carbon\Carbon::parse($courrier->date_reception)->format('d/m/Y') }}</td>
                                <td class="text-start"><span class="fw-medium">{{ $courrier->numero_enregistrement }}</span></td>
                                <td class="text-start">{{ $courrier->numero_reference ?? '-' }}</td>
                                <td class="text-start">{{ $courrier->nom_expediteur }}</td>
                                <td class="text-start" style="max-width:320px;">
                                    <div class="text-truncate" style="max-width:320px;">{!! nl2br(e(\Illuminate\Support\Str::limit($courrier->resume, 160))) !!}</div>
                                </td>
                                

                        <td>
                                    @php
                                        $badgeClass = match($courrier->statut) {
                                            'reçu' => 'bg-success text-white',
                                            'en attente' => 'bg-danger text-white',
                                            'traité' => 'bg-info text-white',
                                            default => 'bg-secondary text-white',
                                        };
                                    @endphp
                                    <span class="badge px-2 py-1 {{ $badgeClass }} rounded-pill">{{ ucfirst($courrier->statut) }}</span>
                                    @if(Auth::user()->role === 'admin')
                                        <div class="mt-2">
                                            <select class="form-select form-select-sm status-select" data-id="{{ $courrier->id }}">
                                                <option value="reçu" {{ $courrier->statut == 'reçu' ? 'selected' : '' }}>Reçu</option>
                                                <option value="en attente" {{ $courrier->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
                                                <option value="traité" {{ $courrier->statut == 'traité' ? 'selected' : '' }}>Traité</option>
                                            </select>
                                        </div>
                                    @endif
                        </td>
                        <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-sm btn-outline-warning" title="Éditer">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-3">
                    {!! $courriers->links('pagination::bootstrap-5') !!}
                </div>
    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <div class="scroll-animated container">
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
        </div>
    @endif
@endauth
@endsection

@push('styles')
<style>
    .clickable-row { cursor: pointer; }
    .clickable-row:focus { outline: 2px solid rgba(13,110,253,0.6); outline-offset: 2px; }

    /* Overlay styling: visible and hidden states */
    .overlay-message {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        z-index: 1050;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .overlay-message.hidden {
        display: none;
        pointer-events: none;
    }

    /* Compact the table: remove internal scrollbars and use ellipsis */
    #courriersTable {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
        font-size: .9rem;
    }

    #courriersTable th,
    #courriersTable td {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: .35rem .5rem; /* tighter cell padding */
        vertical-align: middle;
    }

    /* Column width hints (adjustable) */
    #courriersTable th:nth-child(1), #courriersTable td:nth-child(1) { width: 8%; }
    #courriersTable th:nth-child(2), #courriersTable td:nth-child(2) { width: 8%; }
    #courriersTable th:nth-child(3), #courriersTable td:nth-child(3) { width: 8%; }
    #courriersTable th:nth-child(4), #courriersTable td:nth-child(4) { width: 15%; }
    #courriersTable th:nth-child(5), #courriersTable td:nth-child(5) { width: 40%; max-width: 320px; }
    #courriersTable th:nth-child(6), #courriersTable td:nth-child(6) { width: 6%; text-align: center; }
    #courriersTable th:nth-child(7), #courriersTable td:nth-child(7) { width: 10%; text-align: center; }

    /* Ensure inner truncation works inside cells */
    #courriersTable .text-truncate { display:inline-block; width:100%; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

    /* Hide the horizontal scrollbar from the responsive wrapper by default */
    .table-responsive { overflow-x: hidden; }

    /* On very small screens allow wrapping and scrolling if necessary */
    @media (max-width: 720px) {
        #courriersTable, #courriersTable th, #courriersTable td { table-layout: auto; white-space: normal; }
        .table-responsive { overflow-x: auto; }
    }
</style>
@endpush

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
// Close overlay in a way that it won't block interactions
function closeOverlay() {
    const overlay = document.getElementById('overlayMessage');
    if (overlay) {
        overlay.classList.add('hidden');
        overlay.setAttribute('aria-hidden', 'true');
    }
}

    $(document).ready(function() {
        // Ajouter un commentaire via AJAX
        $('.comment-form').submit(function(e) {
            e.preventDefault();
            const form = $(this);
            const commentaire = form.find('input[name="commentaire"]').val();
            const courrier_id = form.data('id');

            if(commentaire) {
                $.ajax({
                    url: '/courriers/' + courrier_id + '/commentaire',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        commentaire: commentaire
                    },
                    success: function(response) {
                        if(response.success) {
                            let section = form.closest('td').find('.commentaires-section');
                            if (section.length === 0) {
                                section = $('<div class="commentaires-section mt-2"></div>');
                                form.before(section);
                            }
                            section.append('<p>' + response.commentaire + '</p>');
                            form.find('input[name="commentaire"]').val('');
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert('Erreur lors de l\'ajout du commentaire.');
                    }
                });
            }
        });
    });

// Make table rows clickable, keyboard accessible, ignore clicks on controls
document.addEventListener('DOMContentLoaded', function () {
    const ignoredSelector = 'a,button,form,input,select,textarea,label';

    document.querySelectorAll('table tbody tr[data-href]').forEach(function (row) {

        // CLICK souris
        row.addEventListener('click', function (e) {
            if (e.target.closest(ignoredSelector)) return;

            const href = row.dataset.href;
            if (href) {
                window.location.href = href;
            }
        });

        // CLAVIER (accessibilité)
        row.addEventListener('keydown', function (e) {
            if (e.target.closest(ignoredSelector)) return;

            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const href = row.dataset.href;
                if (href) {
                    window.location.href = href;
                }
            }
        });
    });
});
</script>
@endpush
