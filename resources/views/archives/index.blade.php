@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet d’<strong>accéder aux archives contenant tous les courriers classés</strong>, à des fins de consultation ou d’audit interne.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container">
    @auth
        @if(session('code_acces_valide'))
        <h2 class="scroll-animated text-center mb-5"><i class="fas fa-archive me-2"></i>Dossiers Archivés</h2>

        <!-- Filtrage -->
        <form action="{{ route('archives.index') }}" method="GET" class="mb-4 bg-light p-4 rounded shadow-sm">
            <div class="scroll-animated row g-3">
                <div class="scroll-animated col-md-6">
                    <label for="categorie" class="form-label"><i class="fas fa-tags me-2"></i>Catégorie</label>
                    <select name="categorie" id="categorie" class="form-select">
                        <option value="" disabled {{ request('categorie') ? '' : 'selected' }}>-- Choisir une catégorie --</option>
                        <option value="Ministère de la Culture et des Arts" {{ request('categorie') == 'Ministère de la Culture et des Arts' ? 'selected' : '' }}>Ministère de la Culture et des Arts</option>
                        <option value="Ministères" {{ request('categorie') == 'Ministères' ? 'selected' : '' }}>Ministères</option>
                        <option value="Expositions" {{ request('categorie') == 'Expositions' ? 'selected' : '' }}>Expositions</option>
                        <option value="Autre" {{ request('categorie') == 'Autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>

                <div class="scroll-animated col-md-6">
                    <label for="search" class="form-label"><i class="fas fa-search me-2"></i>Recherche</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Référence ou numéro...">
                </div>
            </div>

            <div class="scroll-animated mt-3 text-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-2"></i>Filtrer</button>
            </div>
        </form>

        @php
            $sections = [
                'Accusés de réception' => $accuses,
                'Télégrammes' => $telegrammes,
                'Réponses' => $reponses
            ];
        @endphp

        @foreach($sections as $titre => $documents)
            <div class="scroll-animated mb-5">
                <h4 class="mb-3"><i class="fas fa-folder-open me-2"></i>{{ $titre }}</h4>

                @if($documents->isEmpty())
                    <p class="text-muted fst-italic">Aucun enregistrement disponible.</p>
                @else
                    <div class="scroll-animated table-responsive">
                        <table class="scroll-animated table table-striped align-middle">
                            <thead class="scroll-animated table-dark">
                                @if ($titre == 'Accusés de réception')
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i></th>
                                        <th><i class="fas fa-calendar-alt"></i> Réception</th>
                                        <th><i class="fas fa-user-check"></i> Par</th>
                                        <th><i class="fas fa-file-alt"></i> Réf.</th>
                                        <th><i class="fas fa-user"></i> Expéditeur</th>
                                        <th><i class="fas fa-sticky-note"></i> Résumé</th>
                                        <th><i class="fas fa-eye"></i> Obs.</th>
                                        <th><i class="fas fa-comment"></i> Com.</th>
                                        <th><i class="fas fa-info-circle"></i> Statut</th>
                                        <th><i class="fas fa-archive"></i> Archive</th>
                                        <th><i class="fas fa-check-circle"></i> Statut Archive</th>
                                        <th><i class="fas fa-paperclip"></i> Annexes</th>
                                    </tr>
                                @else
                                    <tr>
                                        <th><i class="fas fa-hashtag"></i></th>
                                        <th><i class="fas fa-file-alt"></i> Réf.</th>
                                        <th><i class="fas fa-building"></i> Service</th>
                                        <th><i class="fas fa-user"></i> Expéditeur</th>
                                        <th><i class="fas fa-sticky-note"></i> Résumé</th>
                                        <th><i class="fas fa-archive"></i> Archive</th>
                                        <th><i class="fas fa-check-circle"></i> Statut Archive</th>
                                        <th><i class="fas fa-paperclip"></i> Annexes</th>
                                    </tr>
                                @endif
                            </thead>
                            <tbody>
                            @foreach($documents as $item)
                            <tr>
                                <td>{{ $item->numero_enregistrement }}</td>

                                @if ($titre == 'Accusés de réception')
                                    <td>{{ $item->date_reception ?? 'N/A' }}</td>
                                    <td>{{ $item->receptionne_par ?? 'N/A' }}</td>
                                    <td>{{ $item->numero_reference ?? 'N/A' }}</td>
                                    <td>{{ $item->nom_expediteur ?? 'N/A' }}</td>
                                    <td>{{ $item->resume ?? 'N/A' }}</td>
                                    <td>{{ $item->observation ?? 'N/A' }}</td>
                                    <td>{{ $item->commentaires ?? 'N/A' }}</td>
                                    <td>{{ $item->statut ?? 'N/A' }}</td>
                                    <td>{{ $item->archive }}</td>
                                    <td>{{ $item->status_archive }}</td>
                                @else
                                    <td>{{ $item->numero_reference ?? 'N/A' }}</td>
                                    <td>{{ $item->service_concerne ?? 'N/A' }}</td>
                                    <td>{{ $item->nom_expediteur ?? 'N/A' }}</td>
                                    <td>{{ $item->resume ?? 'N/A' }}</td>
                                    <td>{{ $item->archive }}</td>
                                    <td>{{ $item->status_archive }}</td>
                                @endif

                                <td>
                                    @if($titre == 'Télégrammes' && $item->accuseReception && $item->accuseReception->annexes && $item->accuseReception->annexes->isNotEmpty())
                                        <div class="scroll-animated d-flex flex-wrap gap-2">
                                            @foreach($item->accuseReception->annexes as $annexe)
                                                <div class="scroll-animated d-flex align-items-center gap-2 border rounded px-2 py-1 bg-light">
                                                    <i class="fas fa-file-pdf text-danger"></i>
                                                    <span class="text-truncate" style="max-width: 100px;">{{ basename($annexe->file_path) }}</span>
                                                    <a href="{{ asset('storage/' . $annexe->file_path) }}" class="text-success" target="_blank" title="Voir"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ asset('storage/' . $annexe->file_path) }}" download class="text-secondary" title="Télécharger"><i class="fas fa-download"></i></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($item->annexes && $item->annexes->isNotEmpty())
                                        <div class="scroll-animated d-flex flex-wrap gap-2">
                                            @foreach($item->annexes as $annexe)
                                                <div class="scroll-animated d-flex align-items-center gap-2 border rounded px-2 py-1 bg-light">
                                                    <i class="fas fa-file-alt text-primary"></i>
                                                    <span class=" scroll-animatedtext-truncate" style="max-width: 100px;">{{ $annexe->nom_fichier }}</span>
                                                    <a href="{{ asset('storage/' . $annexe->file_path) }}" class="text-success" target="_blank" title="Voir"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ asset('storage/' . $annexe->file_path) }}" download class="text-secondary" title="Télécharger"><i class="fas fa-download"></i></a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="scroll-animated text-muted fst-italic">Aucune</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endforeach

        <div class="scroll-animated text-end">
            <a href="{{ route('annexes.print') }}" class="btn btn-success">
                <i class="fas fa-print me-2"></i>Imprimer les annexes
            </a>
        </div>
        @else
            {{-- FORMULAIRE DE CODE D'ACCÈS --}}
            <div class="scroll-animated container mt-5">
                <h2 class="scroll-animated text-center text-dark mb-4">
                    <i class="fas fa-lock"></i> Bienvenue - Code d'accès requis
                </h2>

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
    @else
        <p class="text-center mt-5">Vous devez être connecté pour accéder aux archives.</p>
    @endauth
</div>
@endsection
