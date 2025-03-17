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
                    <th>Expeditaire</th>
                    <th>Resumé</th>
                    <th>Date</th>
                    <th>Statut</th>
                    <th>Action</th>
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
                                <a href="{{ route('reponses.show', $reponse->id) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($reponse->telegramme_id)
                                    <a href="{{ route('reponses.create', ['telegramme_id' => $reponse->id]) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-reply"></i> Répondre
                                    </a>
                                @endif
                                <form action="{{ route('reponses.destroy', $reponse->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Voulez-vous vraiment supprimer cette réponse ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Pagination pour chaque groupe 
        <div class="d-flex justify-content-center">
            {{ $reponses->links() }}
        </div>
    @endforeach-->

    <!-- Section des Télégrammes en Attente -->
    <h3 class="mt-4">📨 Télégrammes en Attente de Réponse</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Numéro d'enregistrement</th>
                <th>Numéro de Référence</th>
                <th>Service Concerné</th>
                <th>Resume</th>
                <th>Expeditaire</th>
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
                    <a href="{{ route('reponses.create', ['telegramme_id' => $telegramme->id]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-reply"></i> Répondre
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Aucun télégramme en attente.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
@endsection
