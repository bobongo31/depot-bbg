@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Titre -->
    <div class="bg-blue-500 text-white text-center p-4 rounded-lg mb-4">
        <h1 class="text-2xl font-bold">Détails du Courrier</h1>
    </div>

    <!-- Informations du courrier -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <p><strong>Numéro d'enregistrement :</strong> {{ $courrier->numero_enregistrement }}</p>
        <p><strong>Date d'accuse réception :</strong> {{ $courrier->date_reception }}</p>
        <p><strong>Numéro de référence :</strong> {{ $courrier->numero_reference ?? 'N/A' }}</p>
        <p><strong>Expéditeur :</strong> {{ $courrier->nom_expediteur }}</p>
        <p><strong>Résumé :</strong> {{ $courrier->resume }}</p>
        <p><strong>Observation :</strong> {{ $courrier->observation ?? 'N/A' }}</p>
        <p><strong>Commentaires :</strong> {{ $courrier->commentaires ?? 'N/A' }}</p>
        <p><strong>Statut :</strong> 
            <span class="px-2 py-1 rounded-md 
                {{ $courrier->statut == 'reçu' ? 'bg-green-500' : ($courrier->statut == 'en attente' ? 'bg-yellow-500' : 'bg-red-500') }} text-white">
                {{ ucfirst($courrier->statut) }}
            </span>
        </p>
    </div>

    <!-- Liste des annexes -->
    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-3">Annexes</h2>
        @if($courrier->annexes->isEmpty())
            <p>Aucune annexe disponible.</p>
        @else
            <ul class="list-disc list-inside">
                @foreach ($courrier->annexes as $annexe)
                    <li>
                        <a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank" class="text-blue-500 hover:underline">
                            📄 Voir le fichier {{ basename($annexe->file_path) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Bouton retour -->
    <div class="mt-6">
        <a href="{{ route('courriers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition">
            ⬅ Retour à la liste
        </a>
    </div>
</div>
@endsection
