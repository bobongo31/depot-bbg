@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gestion des Télégrammes et Réponses</h1>

    <!-- Section des Télégrammes en attente -->
    <h2>Télégrammes en attente</h2>
    @if($telegrammesEnAttente->isEmpty())
        <p>Aucun télégramme en attente.</p>
    @else
        <ul>
            @foreach ($telegrammesEnAttente as $telegramme)
                <li>
                    <strong>{{ $telegramme->numero_reference }}</strong> - {{ $telegramme->contenu }} 
                    <a href="{{ route('reponses.show', $telegramme->id) }}" class="btn btn-primary btn-sm">Voir détails</a>
                </li>
            @endforeach
        </ul>
    @endif

    
    <!-- Affichage des détails si un télégramme est sélectionné -->
    @if(isset($telegramme))
        <hr>
        <h2>Détails du Télégramme</h2>
        <table class="table">
            <tr><th>Numéro d'enregistrement :</th><td>{{ $telegramme->numero_enregistrement }}</td></tr>
            <tr><th>Numéro de référence :</th><td>{{ $telegramme->numero_reference }}</td></tr>
            <tr><th>Service Concerné :</th><td>{{ $telegramme->service_concerne }}</td></tr>
            <tr><th>Commentaires :</th><td>{{ $telegramme->commentaires }}</td></tr>
        </table>

        <h3>Annexes du Télégramme</h3>
        @if($telegramme->annexes->isNotEmpty())
            <ul>
                @foreach ($telegramme->annexes as $annexe)
                    <li><a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank">📄 Voir l'annexe</a></li>
                @endforeach
            </ul>
        @else
            <p>Aucune annexe</p>
        @endif

        <!-- Affichage de la réponse si elle existe -->
        @if(isset($reponse))
            <h2>Réponse Associée</h2>
            <table class="table">
                <tr><th>Numéro d'enregistrement :</th><td>{{ $reponse->numero_enregistrement }}</td></tr>
                <tr><th>Numéro de référence :</th><td>{{ $reponse->numero_reference }}</td></tr>
                <tr><th>Service concerné :</th><td>{{ $reponse->service_concerne }}</td></tr>
                <tr><th>Observation :</th><td>{{ $reponse->observation }}</td></tr>
                <tr><th>Commentaires :</th><td>{{ $reponse->commentaires }}</td></tr>
                <tr><th>Date de réponse :</th><td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td></tr>
            </table>

            <h3>Annexes de la Réponse</h3>
            <ul>
                @foreach ($reponse->annexes as $annexe)
                    <li>
                        <a href="{{ asset('storage/' . $annexe->file_path) }}" download>
                            📎 Télécharger {{ basename($annexe->file_path) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    @endif

    <a href="{{ route('reponses.index') }}" class="btn btn-secondary mt-3">Retour</a>
</div>
@endsection
