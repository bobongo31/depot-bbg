@extends('layouts.app')

@section('content')
<div id="overlayMessage" class="overlay-message">
  <div class="message-box">
    <h5 class="mb-3">
      <i class="fas fa-info-circle text-primary"></i> Bienvenue 👋
    </h5>
    <p class="mb-4">
      Cette interface vous permet de <strong>consulter tous les courriers traités par la direction générale</strong> et de vérifier leur statut final.
    </p>
    <button class="btn btn-success btn-sm px-4" onclick="closeOverlay()">
      <i class="fas fa-check-circle me-1"></i> J'ai compris
    </button>
  </div>
</div>

@if(auth()->user() && auth()->user()->role === 'directeur_general')
<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Liste des courriers traités</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
            <thead class="bg-blue-100 text-left text-gray-800">
                <tr>
                    <th class="py-4 px-6 text-sm font-semibold text-gray-700">Date d'accusé réception</th>
                    <th class="py-4 px-6 text-sm font-semibold text-gray-700">Numéro d'enregistrement</th>
                    <th class="py-4 px-6 text-sm font-semibold text-gray-700">Réceptionné par</th>
                    <th class="py-4 px-6 text-sm font-semibold text-gray-700">Objet</th>
                    <th class="py-4 px-6 text-sm font-semibold text-gray-700">Annexes</th>
                    <th class="py-4 px-6 text-sm font-semibold text-gray-700">Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courriersTraites as $courrier)
                    <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                        <td class="py-4 px-6 text-sm text-gray-600">{{ $courrier->date_reception }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600">{{ $courrier->numero_enregistrement }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600">{{ $courrier->receptionne_par }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600">{{ $courrier->objet }}</td>
                        <td class="py-4 px-6 text-sm text-gray-600">
                            @if($courrier->annexes->isNotEmpty())
                                @foreach($courrier->annexes as $annexe)
                                    <a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank" class="text-blue-500 hover:underline block">
                                        Télécharger {{ basename($annexe->file_path) }}
                                    </a>
                                @endforeach
                            @else
                                Aucun
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $courrier->statut == 'reçu' ? 'bg-green-100 text-green-600' : ($courrier->statut == 'en attente' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }} ">
                                {{ ucfirst($courrier->statut) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg text-center">
        <h1 class="text-2xl font-semibold text-red-600">Accès refusé</h1>
        <p class="text-gray-600 mt-2">Vous n'avez pas l'autorisation de voir cette page.</p>
    </div>
@endif
@endsection


@push('styles')
    <style>
        /* Container */
        .container {
            width: 85%;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        /* Titre */
        h1 {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        /* Table headers */
        th {
            padding: 12px;
            background-color: #4B9DE9;
            text-align: left;
            font-size: 1rem;
            color: white;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }
        /* Table rows */
        td {
            padding: 12px;
            font-size: 0.875rem;
            color: #718096;
            border-bottom: 1px solid #e2e8f0;
        }
        /* Hover effect for rows */
        tr:hover {
            background-color: #f4f4f4;
        }
        /* Responsive design */
        @media (max-width: 768px) {
            table {
                width: 100%;
                overflow-x: auto;
                display: block;
            }
            th, td {
                padding: 8px;
                font-size: 0.75rem;
            }
        }
    </style>
@endpush
