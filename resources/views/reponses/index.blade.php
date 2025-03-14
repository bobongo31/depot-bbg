@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des Réponses</h2>

    @if(session('alert'))
        <div class="alert alert-warning">{{ session('alert') }}</div>
    @endif

    <!-- Table des Réponses -->
    <table class="table">
        <thead>
            <tr>
                <th>Numéro d'enregistrement</th>
                <th>Numéro de Référence</th>
                <th>Service Concerné</th>
                <th>Observation</th>
                <th>Commentaires</th>
                <th>Date de Réponse</th>
                <th>Statut</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reponses as $reponse)
            <tr class="{{ $reponse->statut == 'en retard' ? 'table-danger' : '' }}">
                <td>{{ $reponse->numero_enregistrement }}</td>
                <td>{{ $reponse->numero_reference }}</td>
                <td>{{ $reponse->service_concerne }}</td>
                <td>{{ $reponse->observation }}</td>
                <td>{{ $reponse->commentaires }}</td>
                <td>{{ $reponse->created_at }}</td>
                <td>
                    @if(now()->diffInHours($reponse->created_at) > 72)
                        <span class="text-danger">En retard</span>
                    @else
                        <span class="text-success">Dans le délai</span>
                    @endif
                </td>
                <td>
                    <!-- Bouton Voir -->
                    <a href="{{ route('reponses.show', $reponse->id) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> 
                    </a>

                    <!-- Bouton Répondre -->
                    @if ($reponse->telegramme_id)
                        <a href="{{ route('reponses.create', ['telegramme_id' => $reponse->id]) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-reply"></i>
                        </a>
                    @endif

                    <!-- Bouton Supprimer -->
                    <form action="{{ route('reponses.destroy', $reponse->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette réponse ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section des Télégrammes en Attente -->
<h3>📨 Télégrammes en Attente de Réponse</h3>
<table class="table">
    <thead>
        <tr>
            <th>Numéro d'enregistrement</th>
            <th>Numéro de Référence</th>
            <th>Service Concerné</th>
            <th>Contenu</th>
            <th>Annexes</th>
            <th>Délai de Réponse</th> <!-- Nouvelle colonne avec icône -->
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($telegrammes as $telegramme)
        @php
            $createdAt = \Carbon\Carbon::parse($telegramme->created_at);
            $deadline = $createdAt->addHours(72); // Délai de 72 heures
            $remainingHours = now()->diffInHours($deadline, false);
            $remainingHours = floor($remainingHours); // Arrondi à l'entier inférieur
            $isLate = $remainingHours < 0;
            $isWarning = $remainingHours > 0 && $remainingHours <= 12;
        @endphp
        <tr class="{{ $isLate ? 'table-danger' : ($isWarning ? 'table-warning' : 'table-success') }}">
            <td>{{ $telegramme->numero_enregistrement }}</td>
            <td>{{ $telegramme->numero_reference }}</td>
            <td>{{ $telegramme->service_concerne }}</td>
            <td>{{ $telegramme->commentaires }}</td>
            <td>
                @if($telegramme->annexes && $telegramme->annexes->isNotEmpty())
                    @foreach ($telegramme->annexes as $annexe)
                        <a href="{{ asset('storage/' . $annexe->file_path) }}" download>
                            <i class="fas fa-file-download"></i> 
                        </a><br>
                    @endforeach
                @else
                    <span>Aucune annexe</span>
                @endif
            </td>
            <td>
                @if ($isLate)
                    <span class="text-danger">
                        <i class="fas fa-times-circle"></i> Délai dépassé
                    </span>
                @elseif ($isWarning)
                    <span class="text-warning">
                        <i class="fas fa-exclamation-triangle"></i> {{ $remainingHours }}h restantes
                    </span>
                @else
                    <span class="text-success">
                        <i class="fas fa-check-circle"></i> {{ $remainingHours }}h restantes
                    </span>
                @endif
            </td>
            <td>
                <!-- Bouton Répondre -->
                <a href="{{ route('reponses.create', ['telegramme_id' => $telegramme->id]) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-reply"></i>
                </a>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">Aucun télégramme en attente.</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>
@endsection
