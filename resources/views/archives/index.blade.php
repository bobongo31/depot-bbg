@extends('layouts.app')

@section('content')
<div class="container">
    <h2>📁 Dossiers Archivés</h2>

    <!-- Formulaire de filtrage par catégorie et recherche -->
    <form action="{{ route('archives.index') }}" method="GET">
        <div class="form-group">
            <label for="categorie">Filtrer par catégorie :</label>
            <select name="categorie" id="categorie" class="form-control">
                <option value="" disabled {{ request('categorie') ? '' : 'selected' }}>Sélectionnez une catégorie</option>
                <option value="Ministère de la Culture et des Arts" {{ request('categorie') == 'Ministère de la Culture et des Arts' ? 'selected' : '' }}>Ministère de la Culture et des Arts</option>
                <option value="Ministères" {{ request('categorie') == 'Ministères' ? 'selected' : '' }}>Ministères</option>
                <option value="Expositions" {{ request('categorie') == 'Expositions' ? 'selected' : '' }}>Expositions</option>
                <option value="Autre" {{ request('categorie') == 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
        </div>

        <!-- Champ de recherche -->
        <div class="form-group">
            <label for="search">Rechercher par numéro d'enregistrement ou référence :</label>
            <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Numéro d'enregistrement, Référence...">
        </div>

        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    @php
        $sections = [
            'Accusés de réception' => $accuses,
            'Télégrammes' => $telegrammes,
            'Réponses' => $reponses
        ];
    @endphp

    @foreach($sections as $titre => $data)
        <h3>{{ $titre }}</h3>
        @if($data->isEmpty())
            <p class="text-muted">Aucune donnée disponible.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    @if ($titre == 'Accusés de réception')
                        <tr>
                            <th>Numéro d'enregistrement</th>
                            <th>Date Réception</th>
                            <th>Réceptionné par</th>
                            <th>Numéro de référence</th>
                            <th>Nom de l'expéditeur</th>
                            <th>Résumé</th>
                            <th>Observation</th>
                            <th>Commentaires</th>
                            <th>Statut</th>
                            <th>Archive</th>
                            <th>Status Archive</th>
                            <th>Annexes</th>
                        </tr>
                    @else
                        <tr>
                            <th>Numéro d'enregistrement</th>
                            <th>Numéro de référence</th>
                            <th>Service concerné</th>
                            <th>Expéditeur</th>
                            <th>Resume</th>
                            <th>Archive</th>
                            <th>Status Archive</th>
                            <th>Annexes</th>
                        </tr>
                    @endif
                </thead>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            @if ($titre == 'Accusés de réception')
                                <td>{{ $item->numero_enregistrement }}</td>
                                <td>{{ $item->date_reception ?? 'N/A' }}</td>
                                <td>{{ $item->receptionne_par ?? 'N/A' }}</td>
                                <td>{{ $item->numero_reference ?? 'N/A' }}</td>
                                <td>{{ $item->nom_expediteur ?? 'N/A' }}</td>
                                <td>{{ $item->resume ?? 'N/A' }}</td>
                                <td>{{ $item->observation ?? 'N/A' }}</td>
                                <td>{{ $item->commentaires ?? 'N/A' }}</td>
                                <td>{{ $item->statut ?? 'N/A' }}</td>
                                <td>{{ $item->archive }}</td>
                                <td>{{ $item->status_archive }}</td>
                            @else
                                <td>{{ $item->numero_enregistrement }}</td>
                                <td>{{ $item->numero_reference ?? 'N/A' }}</td>
                                <td>{{ $item->service_concerne ?? 'N/A' }}</td>
                                <td>{{ $item->observation ?? 'N/A' }}</td>
                                <td>{{ $item->commentaires ?? 'N/A' }}</td>
                                <td>{{ $item->archive }}</td>
                                <td>{{ $item->status_archive }}</td>
                            @endif
                            <td>
                                @if($item->annexes && $item->annexes->isNotEmpty())                                    
                                    <ul>
                                        @foreach($item->annexes as $annexe)
                                            <li>{{ $annexe->nom_fichier }} 
                                            <a href="{{ asset('storage/' . $annexe->file_path) }}" target="_blank" class="awesome-button">
                                                <i class="fas fa-file-alt"></i> Voir l'annexe
                                            </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-muted">Aucune annexe</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <a href="{{ route('annexes.print') }}" class="btn btn-success">Imprimer les Annexes</a>
</div>
@endsection
