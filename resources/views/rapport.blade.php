<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Bordure de page */
        body {
            border: 10px solid transparent;
            padding: 10px;
            background-image: linear-gradient(to right, yellow, red, blue);
            background-origin: border-box;
            background-clip: content-box, border-box;
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

    </style>
</head>
<body>
    <!-- Logo et nom de l'entreprise -->
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo de l'entreprise">
    </div>
    <div class="company-info">
        Nom de l'Entreprise
    </div>

    <!-- Détail de la table client et paiement -->
    <h2>Tableau des Paiements et Clients</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom du Client</th>
                <th>Montant</th>
                <th>Date de Paiement</th>
                <!-- Ajouter d'autres colonnes si nécessaire -->
            </tr>
        </thead>
        <tbody>
            @foreach($paiements as $paiement)
            <tr>
                <td>{{ $paiement->id }}</td>
                <td>{{ $paiement->client->nom }}</td>
                <td>{{ $paiement->montant }}</td>
                <td>{{ $paiement->date_paiement }}</td>
                <!-- Autres données -->
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Détail individuel pour chaque ID -->
    <h2>Détails par ID</h2>
    @foreach($paiements as $paiement)
    <h3>ID : {{ $paiement->id }}</h3>
    <p>Nom du Client : {{ $paiement->client->nom }}</p>
    <p>Montant : {{ $paiement->montant }}</p>
    <p>Date de Paiement : {{ $paiement->date_paiement }}</p>
    <!-- Autres détails -->
    @endforeach
</body>
</html>
