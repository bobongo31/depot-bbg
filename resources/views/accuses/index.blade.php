@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet d’<strong>afficher la liste complète de tous les courriers</strong> ayant reçu un accusé de réception dans l'organisation.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container py-5">
    @auth
        @if(session('code_acces_valide'))
            <div class="scroll-animated text-center mb-5">
                <h1 class="h3 fw-bold text-primary">
                    <i class="fas fa-file-alt me-2"></i> Accusés de Réception
                </h1>
                <p class="text-muted">Liste complète des documents réceptionnés</p>
            </div>

            <div class="scroll-animated table-responsive shadow-sm rounded bg-white p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><i class="fas fa-calendar-day text-secondary me-1"></i> Date</th>
                            <th><i class="fas fa-hashtag text-secondary me-1"></i> Numéro</th>
                            <th><i class="fas fa-user text-secondary me-1"></i> Réceptionné par</th>
                            <th><i class="fas fa-envelope-open-text text-secondary me-1"></i> Objet</th>
                            <th><i class="fas fa-info-circle text-secondary me-1"></i> Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($accuses as $accuse)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($accuse->date_reception)->format('d/m/Y') }}</td>
                                <td>{{ $accuse->numero_enregistrement }}</td>
                                <td>{{ $accuse->receptionne_par }}</td>
                                <td>{{ $accuse->objet }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($accuse->statut) {
                                            'reçu' => 'bg-success-subtle text-success',
                                            'en attente' => 'bg-warning-subtle text-warning',
                                            default => 'bg-info-subtle text-info',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill px-3 py-2">
                                        <i class="fas fa-circle me-1 small"></i> {{ ucfirst($accuse->statut) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Aucun accusé trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
        {{-- Formulaire de code d'accès --}}
            <h2 class="text-center text-dark mb-4">
                <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
            </h2>

            <div class="card shadow-lg border-0 rounded-4">
            <div class="card-header text-white bg-primary text-center">
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
                </div>
            </div>
        @endif
    @endauth
</div>
@endsection

@if(session('download_url'))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(() => {
            const link = document.createElement('a');
            link.href = "{{ session('download_url') }}";
            link.download = "accuse_reception.pdf";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }, 1000);
    });
</script>
@endif
