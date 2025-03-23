@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Réponse Associée</h1>

    <!-- Affichage de la réponse associée, si elle existe -->
    @if(isset($reponse))
        <hr>
        <h2>Détails de la Réponse</h2>
        <table class="table">
            <tr><th>Numéro d'enregistrement :</th><td>{{ $reponse->numero_enregistrement }}</td></tr>
            <tr><th>Numéro de référence :</th><td>{{ $reponse->numero_reference }}</td></tr>
            <tr><th>Service concerné :</th><td>{{ $reponse->service_concerne }}</td></tr>
            <tr><th>Observation :</th><td>{{ $reponse->observation }}</td></tr>
            <tr><th>Commentaires :</th><td>{{ $reponse->commentaires }}</td></tr>
            <tr><th>Date de réponse :</th><td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>

        <h3>Annexes de la Réponse</h3>
        @if($reponse->annexes->isNotEmpty())
            <ul>
                @foreach ($reponse->annexes as $annexe)
                    <li>
                        <a href="{{ asset('storage/' . $annexe->file_path) }}" download>
                            📎 Télécharger {{ basename($annexe->file_path) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Aucune annexe pour cette réponse</p>
        @endif
    @else
        <p>Aucune réponse associée à ce télégramme.</p>
    @endif

    <a href="{{ route('reponses.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
