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

        /* Style du tableau */
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

        /* Style pour le titre de la section */
        h2 {
            text-align: center;
            margin-top: 40px;
        }

    </style>
</head>
<body>
    <!-- Logo et nom de l'entreprise -->
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo de l'entreprise">
    </div>
    <div class="company-info">
        Fonds de promotion culturelle
    </div>

    <!-- Détail des paiements -->
    <h2>Détails des Paiements</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Matière Taxable</th>
                <th>Prix de la Matière</th>
                <th>Date d'Ordonnancement</th>
                <th>Date Accusé Réception</th>
                <th>Coût Opportunité</th>
                <th>Date de Paiement</th>
                <th>Retard de Paiement</th>
                <th>Status</th>
                <th>Prix à Payer</th>
                <th>Nom de l'Ordonnanceur</th>
                <th>ID Client</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paiements as $paiement)
            <tr>
                <td>{{ $paiement->id }}</td>
                <td>{{ $paiement->matiere_taxable }}</td>
                <td>{{ $paiement->prix_matiere }}</td>
                <td>{{ $paiement->date_ordonancement }}</td>
                <td>{{ $paiement->date_accuse_reception }}</td>
                <td>{{ $paiement->cout_opportunite }}</td>
                <td>{{ $paiement->date_paiement }}</td>
                <td>{{ $paiement->retard_de_paiement ? 'Oui' : 'Non' }}</td>
                <td>{{ $paiement->status }}</td>
                <td>{{ $paiement->prix_a_payer }}</td>
                <td>{{ $paiement->nom_ordonanceur }}</td>
                <td>{{ $paiement->client_id }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
