@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Liste des accusés de réception</h1>

        <!-- Table avec largeur 100% de l'écran -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-sm">
                <thead class="bg-blue-100 text-left text-gray-800">
                    <tr>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">Date d'accusé réception</th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">Numéro d'enregistrement</th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">Réceptionné par</th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">Objet</th>
                        <th class="py-4 px-6 text-sm font-semibold text-gray-700">Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accuses as $accuse)
                        <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-4 px-6 text-sm text-gray-600">{{ $accuse->date_reception }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ $accuse->numero_enregistrement }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ $accuse->receptionne_par }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ $accuse->objet }}</td>
                            <td class="py-4 px-6 text-sm text-gray-600">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                    {{ $accuse->statut == 'reçu' ? 'bg-green-100 text-green-600' :
                                       ($accuse->statut == 'en attente' ? 'bg-yellow-100 text-yellow-600' :
                                       'bg-blue-100 text-blue-600') }}">
                                    {{ ucfirst($accuse->statut) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
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

        /* En-têtes de table */
        th {
            padding: 12px;
            background-color: #4B9DE9;
            text-align: left;
            font-size: 1rem;
            color: white;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
        }

        /* Cellules de table */
        td {
            padding: 12px;
            font-size: 0.875rem;
            color: #718096;
            border-bottom: 1px solid #e2e8f0;
        }

        /* Effet hover sur les lignes */
        tr:hover {
            background-color: #f4f4f4;
        }

        /* Couleurs alternées pour les lignes */
        tbody tr:nth-child(odd) {
            background-color: #f9fafb;
        }

        tbody tr:nth-child(even) {
            background-color: #fff;
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
