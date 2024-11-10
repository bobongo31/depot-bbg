<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Styles */
        @page {
            size: A4 landscape;
            margin: 20mm;
        }
        body { border: 10px solid transparent; padding: 10px; background-color: #fff; position: relative; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; z-index: -1; }
        .watermark img { max-width: 300px; }
        .watermark-text { position: absolute; top: 40%; left: 50%; transform: translateX(-50%) rotate(-45deg); font-size: 50px; font-weight: bold; color: rgba(0, 0, 0, 0.1); z-index: -1; }
        .logo { text-align: center; margin-bottom: 20px; }
        .logo img { max-width: 150px; }
        .company-info { text-align: center; font-size: 1.5em; font-weight: bold; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #4086c2; }
        h2, h3 { color: #333; font-family: Arial, sans-serif; }
    </style>
</head>
<body>
    <div class="watermark"><img src="{{ public_path('images/filigrame.png') }}" alt="Filigrane"></div>
    <div class="watermark-text">CONFIDENTIEL</div>
    <div class="logo"><img src="{{ public_path('images/icone.png') }}" alt="Logo de l'entreprise"></div>
    <div class="company-info">REPUBLIQUE DEMOCRATIQUE DU CONGO</div>
    <div class="company-info">FONDS DE PROMOTION CULTURELlE<p>ETABLISSEMENT PUBLIC</p></div>
    <h2>LISTE DES PV DE TAXATION</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom du Redevable</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Nom du Taxateur</th>
                <th>Nom du Liquidateur</th>
                <th>Matière Taxable</th>
                <th>Prix de la Matière</th>
                <th>Prix à Payer</th>
                <th>Date de Création</th>
                <th>Date de Mise à Jour</th>
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->nom_redevable }}</td>
                <td>{{ $client->adresse }}</td>
                <td>{{ $client->telephone }}</td>
                <td>{{ $client->nom_taxateur }}</td>
                <td>{{ $client->nom_liquidateur }}</td>
                <td>{{ $client->matiere_taxable }}</td>
                <td>{{ $client->prix_matiere }}</td>
                <td>{{ number_format($client->prix_a_payer, 2, ',', ' ') }} FC</td>
                <td>{{ $client->created_at }}</td>
                <td>{{ $client->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8" style="text-align: right; font-weight: bold;">Total</td>
                <td>{{ number_format($clients->sum('prix_a_payer'), 2, ',', ' ') }} FC</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>
    <p style="text-align: right; margin-top: 40px;">
        Fait à Kinshasa, le {!! date('d/m/Y') !!}
    </p>
</body>
</html>
