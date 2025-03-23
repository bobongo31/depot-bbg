@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des Réponses</h2>

    @foreach ($reponsesGrouped as $date => $reponsesDuJour)
        <h3 class="mt-4">{{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Numéro d'enregistrement</th>
                    <th>Numéro de Référence</th>
                    <th>Service Concerné</th>
                    <th>Expéditeur</th>
                    <th>Résumé</th>
                    <th>Date</th>
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
                                    <span class="text-danger">En retard</span>
                                @else
                                    <span class="text-success">Dans le délai</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('reponse.show', $reponse->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($reponse->telegramme_id)
                                    <a href="{{ route('reponses.create', ['telegramme_id' => $reponse->id]) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-reply"></i> 
                                    </a>
                                @endif
                                <form action="{{ route('reponses.destroy', $reponse->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette réponse ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                                @if(auth()->user()->hasRole('admin')) <!-- Vérification du rôle admin -->
                                    <!-- Bouton pour Réponse Finale -->
                                    <a href="{{ route('reponse.ajouter', ['reponseId' => $reponse->id]) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-check-circle"></i> Réponse Finale
                                    </a>

                                    <!-- Bouton pour archiver le dossier -->
                                    <form action="{{ route('archives.archiver', $reponse->numero_enregistrement) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="categorie" class="form-select d-inline" style="width: auto;" required>
                                            <option value="" disabled selected>Catégorie</option>
                                            <option value="Ministère de la Culture et des Arts">Ministère de la Culture et des Arts</option>
                                            <option value="Ministères">Ministères</option>
                                            <option value="Expositions">Expositions</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                        <button type="submit" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-archive"></i> Archiver
                                        </button>
                                    </form>

                                    <!-- Bouton pour déclarer le dossier clos ou autre statut -->
                                    <form action="{{ route('archives.declarer_clos', $reponse->numero_enregistrement) }}" method="POST" class="d-inline">
                                        @csrf
                                        <select name="status_archive" class="form-select d-inline" style="width: auto;" required>
                                            <option value="" disabled selected>Statut</option>
                                            <option value="clos">Clos</option>
                                            <option value="en cours">En cours</option>
                                            <option value="autre">Autre</option>
                                        </select>
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i> Déclarer
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endforeach

    <!-- Section des Télégrammes en Attente -->
    <h3 class="mt-4">📨 Télégrammes en Attente de Réponse</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Numéro d'enregistrement</th>
                <th>Numéro de Référence</th>
                <th>Service Concerné</th>
                <th>Résumé</th>
                <th>Expéditeur</th>
                <th>Annexes</th>
                <th>Délai de Réponse</th>
                <th>Action</th>
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
                <td>
                    @if($telegramme->annexes && $telegramme->annexes->isNotEmpty())
                        @foreach ($telegramme->annexes as $annexe)
                        <a href="{{ asset('storage/' . $annexe->file_path) }}" download>
                                <i class="fas fa-file-download"></i>
                            </a><br>
                        @endforeach
                    @endif
                </td>
                <td>
                    @if ($telegramme->isLate)
                        <span class="text-danger"><i class="fas fa-times-circle"></i> Délai dépassé</span>
                    @elseif ($telegramme->isWarning)
                        <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> {{ $telegramme->remainingHours }}h restantes</span>
                    @else
                        <span class="text-success"><i class="fas fa-check-circle"></i> {{ $telegramme->remainingHours }}h restantes</span>
                    @endif
                </td>
                <td>
                @if(auth()->user() && auth()->user()->isAdmin())
                    <form action="{{ route('telegrammes.destroy', $telegramme->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer ce télégramme ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @endif

                <!-- Boutons avec alignement horizontal -->
                <!-- Bouton "Afficher Détails" -->
                <a href="{{ route('telegramme.show', ['id' => $telegramme->id]) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                    </a>

                <div class="d-flex">
                    <!-- Bouton "Répondre" -->
                    <a href="{{ route('reponses.create', ['telegramme_id' => $telegramme->id]) }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-reply"></i> Répondre
                    </a>
                </div>
            </td>

        @empty
            <tr>
                <td colspan="8">Aucun télégramme en attente.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
