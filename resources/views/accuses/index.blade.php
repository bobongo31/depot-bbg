@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Liste des accusés de réception</h1>

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
                                    @if($accuse->statut == 'reçu') bg-green-100 text-green-600 
                                    @elseif($accuse->statut == 'en attente') bg-yellow-100 text-yellow-600 
                                    @else bg-blue-100 text-blue-600 @endif">
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

@if(session('download_url'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(() => {
                var link = document.createElement('a');
                link.href = "{{ session('download_url') }}";
                link.download = "accuse_reception.pdf";
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }, 1000);
        });
    </script>
@endif
