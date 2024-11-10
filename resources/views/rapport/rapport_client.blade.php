<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Styles de la page */
        body {
            border: 10px solid transparent;
            padding: 10px;
            background-color: #fff;
            position: relative;
        }

        /* Filigrane d'image */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
        }

        .watermark img {
            max-width: 300px;
        }

        /* Filigrane textuel */
        .watermark-text {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translateX(-50%) rotate(-45deg);
            font-size: 50px;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.1);
            z-index: -1;
        }

        /* Style du logo et des titres */
        .logo { text-align: center; margin-bottom: 20px; }
        .logo img { max-width: 150px; }
        .company-info { text-align: center; font-size: 1.5em; font-weight: bold; margin-top: 10px; }

        /* Style de la table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4086c2;
        }
    </style>
</head>
<body>
    <div class="watermark">
        <img src="{{ public_path('images/filigrame.png') }}" alt="Filigrane">
    </div>
    
    <div class="watermark-text">CONFIDENTIEL</div>

    <div class="logo">
        <img src="{{ public_path('images/icone.png') }}" alt="Logo de l'entreprise">
    </div>
    <div class="company-info">REPUBLIQUE DEMOCRATIQUE DU CONGO</div>
    <div class="company-info">FONDS DE PROMOTION CULTURELlE</div>
    <p><strong>Nom de redevable :</strong> {{ $client->nom_redevable }}</p>
    <p><strong>Adresse :</strong> {{ $client->adresse }}</p>
    <p><strong>Téléphone :</strong> {{ $client->telephone }}</p>
    <p><strong>Nom de Taxateur :</strong> {{ $client->nom_taxateur }}</p>
    <p><strong>Nom de Liquidateur :</strong> {{ $client->nom_liquidateur }}</p>
    <p><strong>Matière Taxable :</strong> {{ $client->matiere_taxable }}</p>
    <p><strong>Prix à Payer :</strong> {{ number_format($client->prix_a_payer, 2, ',', ' ') }} FC</p>

    <h3>EXTRAIT DES NOTES DES DEBITS</h3>
    <table>
        <thead>
            <tr>
                <th>ID Paiement</th>
                <th>Matière Taxable</th>
                <th>Prix à payer</th>
                <th>Date de Paiement</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $totalPrixAPayer = 0; @endphp
            @foreach($client->paiements as $paiement)
            <tr>
                <td>{{ $paiement->id }}</td>
                <td>{{ $paiement->matiere_taxable }}</td>
                <td>{{ number_format($paiement->prix_a_payer, 2, ',', ' ') }} FC</td>
                <td>{{ $paiement->date_paiement }}</td>
                <td>{{ $paiement->status }}</td>
            </tr>
            @php $totalPrixAPayer += $paiement->prix_a_payer; @endphp
            @endforeach
            <!-- Ligne de total -->
            <tr>
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalPrixAPayer, 2, ',', ' ') }} FC</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    
    <p style="text-align: right; margin-top: 40px;">
        Fait à Kinshasa, le {!! date('d/m/Y') !!}
    </p>
</body>
</html>
