@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet d’<strong>accéder à votre boîte de réception</strong> pour traiter les courriers qui vous sont adressés en tant que chef de service.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container py-4">

    @if(session('code_acces_valide'))
        <h2 class="mb-4"><i class="fas fa-list-alt"></i> Liste des Réponses</h2>

        @foreach ($reponsesGrouped as $date => $reponsesDuJour)
            <div class="mb-5">
                <h5 class="text-primary fw-bold">
                    <i class="fas fa-calendar-day"></i>
                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
                </h5>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="scroll-animated table-light">
                            <tr>
                                <th># Enr.</th>
                                <th>Référence</th>
                                <th>Service</th>
                                <th>Expéditeur</th>
                                <th>Résumé</th>
                                <th>Heure</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reponsesDuJour as $reponse)
                                @if(auth()->user()->hasRole('admin') || $reponse->service_concerne == auth()->user()->service)
                                <tr class="{{ $reponse->statut == 'en retard' ? 'table-danger' : '' }}">
                                    <td>{{ $reponse->numero_enregistrement }}</td>
                                    <td>{{ $reponse->numero_reference }}</td>
                                    <td>{{ $reponse->service_concerne }}</td>
                                    <td>{{ $reponse->observation }}</td>
                                    <td>{{ $reponse->commentaires }}</td>
                                    <td>{{ $reponse->created_at->format('H:i') }}</td>
                                    <td>
                                        @if(now()->diffInHours($reponse->created_at) > 72)
                                            <span class="badge bg-danger"><i class="fas fa-times-circle"></i> En retard</span>
                                        @else
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Dans le délai</span>
                                        @endif
                                    </td>
                                    <td class="d-flex flex-wrap gap-2">
                                        <a href="{{ route('reponse.show', $reponse->id) }}" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if ($reponse->telegramme_id)
                                            <a href="{{ route('reponses.create', ['telegramme_id' => $reponse->id]) }}" class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-reply"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('reponses.destroy', $reponse->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette réponse ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                        @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('reponse.ajouter', ['reponseId' => $reponse->id]) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-check-circle"></i> Finale
                                            </a>

                                            <form action="{{ route('archives.archiver', $reponse->numero_enregistrement) }}" method="POST">
                                                @csrf
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="categorie" class="form-select form-select-sm">
                                                        <option value="" disabled selected>Catégorie</option>
                                                        <option value="Ministère de la Culture et des Arts">Culture</option>
                                                        <option value="Ministères">Ministères</option>
                                                        <option value="Expositions">Expositions</option>
                                                        <option value="Autre">Autre</option>
                                                    </select>
                                                    <button class="btn btn-outline-secondary btn-sm">
                                                        <i class="fas fa-archive"></i>
                                                    </button>
                                                </div>
                                            </form>

                                            <form action="{{ route('archives.declarer_clos', $reponse->numero_enregistrement) }}" method="POST">
                                                @csrf
                                                <div class="d-flex align-items-center gap-2">
                                                    <select name="status_archive" class="form-select form-select-sm">
                                                        <option value="" disabled selected>Statut</option>
                                                        <option value="clos">Clos</option>
                                                        <option value="en cours">En cours</option>
                                                        <option value="autre">Autre</option>
                                                    </select>
                                                    <button class="btn btn-outline-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        {{-- TÉLÉGRAMMES EN ATTENTE --}}
        <h3 class="mt-5 mb-3"><i class="fas fa-paper-plane"></i> Télégrammes en Attente</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th># Enr.</th>
                        <th>Référence</th>
                        <th>Service</th>
                        <th>Résumé</th>
                        <th>Expéditeur</th>
                        <!--<th>Annexes</th>-->
                        <th>Délai</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($telegrammesEnAttente as $telegramme)
                        <tr class="{{ $telegramme->isLate ? 'table-danger' : ($telegramme->isWarning ? 'table-warning' : 'table-success') }}">
                            <td>{{ $telegramme->numero_enregistrement }}</td>
                            <td>{{ $telegramme->numero_reference }}</td>
                            <td>{{ $telegramme->service_concerne }}</td>
                            <td>{{ $telegramme->commentaires }}</td>
                            <td>{{ $telegramme->observation }}</td>
                            <!--<td>
                                @if($telegramme->annexes && $telegramme->annexes->isNotEmpty())
                                    <ul class="list-unstyled">
                                        @foreach ($telegramme->annexes as $annexe)
                                            <li>
                                                <a href="{{ asset('storage/' . $annexe->file_path) }}" class="text-decoration-none" download>
                                                    <i class="fas fa-file-download"></i> {{ basename($annexe->file_path) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td> -->
                            <td>
                                @if ($telegramme->isLate)
                                    <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Délai dépassé</span>
                                @elseif ($telegramme->isWarning)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-exclamation-triangle"></i> {{ $telegramme->remainingHours }}h</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> {{ $telegramme->remainingHours }}h</span>
                                @endif
                            </td>
                            <td class="d-flex flex-wrap gap-2">
                                @if(auth()->user() && auth()->user()->isAdmin())
                                    <form action="{{ route('telegrammes.destroy', $telegramme->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce télégramme ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('telegramme.show', ['id' => $telegramme->id]) }}" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('reponses.create', ['telegramme_id' => $telegramme->id]) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-reply"></i> Répondre
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center">Aucun télégramme en attente.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
        <div class="mx-auto" style="max-width: 500px;">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <i class="fas fa-lock"></i> Code d'accès requis
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('code.verifier') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="code" class="form-label"><i class="fas fa-key"></i> Saisissez votre code</label>
                            <input type="text" class="form-control" name="code" id="code" required>
                        </div>
                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
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
@endsection
