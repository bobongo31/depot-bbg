<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Annexes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Annexes des Archives</h1>
    
    <h2>Accusés de Réception</h2>
    <table>
        <thead>
            <tr>
                <th>Numéro d'enregistrement</th>
                <th>Annexe</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accuses as $accuse)
                @php
                    $annexes = $accuse->annexes ?? collect();  // Si la relation est null, utiliser une collection vide
                @endphp
                @if($annexes->isNotEmpty())
                    @foreach($annexes as $annexe)
                        <tr>
                            <td>{{ $accuse->numero_enregistrement }}</td>
                            <td>{{ $annexe->nom_fichier }}</td>
                            <td>{{ $annexe->status }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">Aucune annexe disponible</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <h2>Télégrammes</h2>
    <table>
        <thead>
            <tr>
                <th>Numéro d'enregistrement</th>
                <th>Annexe</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($telegrammes as $telegramme)
                @php
                    $annexes = $telegramme->annexes ?? collect();  // Si la relation est null, utiliser une collection vide
                @endphp
                @if($annexes->isNotEmpty())
                    @foreach($annexes as $annexe)
                        <tr>
                            <td>{{ $telegramme->numero_enregistrement }}</td>
                            <td>{{ $annexe->nom_fichier }}</td>
                            <td>{{ $annexe->status }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">Aucune annexe disponible</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <h2>Réponses</h2>
    <table>
        <thead>
            <tr>
                <th>Numéro d'enregistrement</th>
                <th>Annexe</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reponses as $reponse)
                @php
                    $annexes = $reponse->annexes ?? collect();  // Si la relation est null, utiliser une collection vide
                @endphp
                @if($annexes->isNotEmpty())
                    @foreach($annexes as $annexe)
                        <tr>
                            <td>{{ $reponse->numero_enregistrement }}</td>
                            <td>{{ $annexe->nom_fichier }}</td>
                            <td>{{ $annexe->status }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">Aucune annexe disponible</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>
</html>
