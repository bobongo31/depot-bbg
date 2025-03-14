@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Debug temporaire --}}
    @php
        // Affichez le contenu des variables pour le débogage
        // dd($telegramme, $reponse);
    @endphp

    <h1>Détails du Télégramme et de la Réponse</h1>

    <!-- Détails du Télégramme -->
    <h2>Télégramme</h2>
    <table class="table">
        <tr>
            <th>Numéro d'enregistrement :</th>
            <td>{{ $telegramme->numero_enregistrement }}</td>
        </tr>
        <tr>
            <th>Numéro de référence :</th>
            <td>{{ $telegramme->numero_reference }}</td>
        </tr>
        <tr>
            <th>Service Concerné :</th>
            <td>{{ $telegramme->service_concerne }}</td>
        </tr>
        <tr>
            <th>Commentaires :</th>
            <td>{{ $telegramme->commentaires }}</td>
        </tr>
    </table>

    <h3>Annexes du Télégramme</h3>
    @if($telegramme->annexes && $telegramme->annexes->isNotEmpty())
        <ul>
            @foreach ($telegramme->annexes as $annexe)
                <li>
                    <a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank">Voir l'annexe</a>
                </li>
            @endforeach
        </ul>
    @else
        <p>Aucune annexe</p>
    @endif

    <!-- Détails de la Réponse -->
    <h2>Réponse</h2>
    <table class="table">
        <tr>
            <th>Numéro d'enregistrement :</th>
            <td>{{ $reponse->numero_enregistrement }}</td>
        </tr>
        <tr>
            <th>Numéro de référence :</th>
            <td>{{ $reponse->numero_reference }}</td>
        </tr>
        <tr>
            <th>Service concerné :</th>
            <td>{{ $reponse->service_concerne }}</td>
        </tr>
        <tr>
            <th>Observation :</th>
            <td>{{ $reponse->observation }}</td>
        </tr>
        <tr>
            <th>Commentaires :</th>
            <td>{{ $reponse->commentaires }}</td>
        </tr>
        <tr>
            <th>Date de réponse :</th>
            <td>{{ $reponse->created_at->format('d/m/Y H:i') }}</td>
        </tr>
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

    <a href="{{ route('reponses.index') }}" class="btn btn-secondary">Retour</a>
</div>
@endsection
