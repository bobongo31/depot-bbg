<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Bordure de page */
        body {
            border: 10px solid transparent;
            padding: 10px;
            background-color: #fff; /* Couleur de fond */
            position: relative;
        }

        /* Filigrane d'image */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1; /* Place le filigrane en arrière-plan */
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

        /* Style du logo */
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            max-width: 150px;
        }

        /* Style de l'en-tête */
        .company-info {
            text-align: center;
            font-size: 1.5em;
            font-weight: bold;
            margin-top: 10px;
        }

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

        /* Style des titres */
        h2, h3 {
            color: #333;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <!-- Filigrane d'image -->
    <div class="watermark">
        <img src="{{ public_path('images/filigrame.png') }}" alt="Filigrane">
    </div>
    
    <!-- Filigrane textuel -->
    <div class="watermark-text">
        CONFIDENTIEL
    </div>

    <!-- Logo et nom de l'entreprise -->
    <div class="logo">
        <img src="{{ public_path('images/icone.png') }}" alt="Logo de l'entreprise">
    </div>
    <div class="company-info">
        REPUBLIQUE DEMOCRATIQUE DU CONGO
    </div>
    <div class="company-info">
        FONDS DE PROMOTION CULTURELE
    </div>

    <!-- Détail du client -->
    <h2>PV DE TAXATION</h2>
    <p><strong>Nom de redevable :</strong> {{ $client->nom_redevable }}</p>
    <p><strong>Adresse :</strong> {{ $client->adresse }}</p>
    <p><strong>Téléphone :</strong> {{ $client->telephone }}</p>
    <p><strong>Nom de Taxateur :</strong> {{ $client->nom_taxateur }}</p>
    <p><strong>Nom de Liquidateur :</strong> {{ $client->nom_liquidateur }}</p>
    <p><strong>Matière Taxable :</strong> {{ $client->matiere_taxable }}</p>
    <p><strong>Prix à Payer :</strong> {{ $client->prix_a_payer }}</p>

    <h3>EXTRAIT DE NOTE DE DEBIT</h3>
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
            @foreach($client->paiements as $paiement)
            <tr>
                <td>{{ $paiement->id }}</td>
                <td>{{ $paiement->matiere_taxable }}</td>
                <td>{{ $paiement->prix_a_payer }}</td>
                <td>{{ $paiement->date_paiement }}</td>
                <td>{{ $paiement->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
