@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>visualiser et enregistrer toutes les dépenses effectuées à partir de la caisse</strong>, assurant ainsi un suivi financier transparent et efficace.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

<div class="scroll-animated container">
    @if (session('code_acces_valide'))
        <h1 class="text-center text-primary mb-4"><i class="fas fa-wallet"></i> Dépenses de Caisse</h1>
        <a href="{{ route('caisse.depenses.create') }}" class="btn btn-success mb-3 scroll-animated">
            <i class="fas fa-plus-circle"></i> Nouvelle Dépense
        </a>
        @if($depenses->isEmpty())
            <p class="alert alert-info">Aucune dépense enregistrée.</p>
        @else
            <div class="scroll-animated table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="bg-info text-white">
                        <tr>
                            <th><i class="fas fa-tags"></i> Rubrique</th>
                            <th><i class="fas fa-money-bill-wave"></i> Montant</th>
                            <th><i class="fas fa-calendar-alt"></i> Date</th>
                            <th><i class="fas fa-align-left"></i> Description</th>
                            <th><i class="fas fa-file-alt"></i> Justificatifs</th>
                            <th><i class="fas fa-cogs"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($depenses as $depense)
                            <tr>
                                <td>{{ $depense->rubrique }}</td>
                                <td>{{ number_format($depense->montant, 2, ',', ' ') }} €</td>
                                <td>{{ \Carbon\Carbon::parse($depense->date_depense)->format('d/m/Y') }}</td>
                                <td>{{ $depense->description }}</td>
                                <td>
                                    @if (!empty($depense->justificatifs) && is_iterable($depense->justificatifs))
                                        @foreach($depense->justificatifs as $justif)
                                            <a href="{{ asset('storage/' . $justif->fichier) }}" target="_blank" class="d-block">
                                                <i class="fas fa-file-pdf text-danger"></i> Voir
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Aucun justificatif</span>
                                    @endif
                                </td>


                                <td>
                                    <a href="{{ route('caisse.depenses.edit', $depense->id) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i> 
                                    </a>
                                    <form action="{{ route('caisse.depenses.destroy', $depense->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce dépense ?');">
                                    <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @else
        {{-- FORMULAIRE DE CODE D'ACCÈS --}}
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
    @endif
</div>
@endsection
