<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Définir la taille et l'orientation de la page pour l'impression */
        @page {
            size: A4 landscape;  /* Utiliser A4 paysage, remplacez par portrait pour l'orientation verticale */
            margin: 20mm;
        }

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
        <img src="{{ asset('images/icon.jpg') }}" alt="Logo de l'entreprise">
    </div>
    <div class="company-info">
        Nom de l'Entreprise
    </div>

    <!-- Détail de tous les clients -->
    <h2>Tableau des Clients</h2>
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
                <td>{{ $client->prix_a_payer }}</td>
                <td>{{ $client->created_at }}</td>
                <td>{{ $client->updated_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
