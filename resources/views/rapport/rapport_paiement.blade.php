<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        /* Bordure de page */
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
            color: #fff;
        }

        /* Style des titres */
        h2, h3 {
            color: #333;
            font-family: Arial, sans-serif;
        }

        /* Pied de page */
        @page {
            margin: 20px;
            @bottom-center {
                content: "Page " counter(page) " sur " counter(pages);
                font-size: 0.9em;
                color: #666;
            }
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
        FONDS DE PROMOTION CULTURELLE
        <p>ETABLISSEMENT PUBLIC</p>
    </div>

    <!-- Détail des paiements -->
    <h2>NOTE DE DEBITS</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Client</th>
                <th>Matière Taxable</th>
                <th>Prix de la Matière</th>
                <th>Date d'Ordonnancement</th>
                <th>Date Accusé Réception</th>
                <th>Coût Opportunité</th>
                <th>Date de Paiement</th>
                <th>Retard de Paiement</th>
                <th>Prix à Payer</th>
                <th>Nom de l'Ordonnanceur</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPrixMatiere = 0;
                $totalPrixAPayer = 0;
            @endphp
            @foreach($paiements as $paiement)
            <tr>
                <td>{{ $paiement->id }}</td>
                <td>{{ $paiement->client_id }}</td>
                <td>{{ $paiement->matiere_taxable }}</td>
                <td>{{ number_format($paiement->prix_matiere, 2, ',', ' ') }} $</td>
                <td>{{ $paiement->date_ordonancement }}</td>
                <td>{{ $paiement->date_accuse_reception }}</td>
                <td>{{ number_format($paiement->cout_opportunite, 2, ',', ' ') }} $</td>
                <td>{{ $paiement->date_paiement }}</td>
                <td>{{ $paiement->retard_de_paiement ? 'Oui' : 'Non' }}</td>
                <td>{{ number_format($paiement->prix_a_payer, 2, ',', ' ') }} $</td>
                <td>{{ $paiement->nom_ordonanceur }}</td>
                <td>{{ $paiement->status }}</td>
            </tr>
            @php
                $totalPrixMatiere += $paiement->prix_matiere;
                $totalPrixAPayer += $paiement->prix_a_payer;
            @endphp
            @endforeach
            <!-- Ligne de total -->
            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalPrixMatiere, 2, ',', ' ') }} $</strong></td>
                <td colspan="5"></td>
                <td><strong>{{ number_format($totalPrixAPayer, 2, ',', ' ') }} $</strong></td>
                <td colspan="2"></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Date d'impression et localisation -->
    <p style="text-align: right; margin-top: 40px;">
        Fait à Kinshasa, le {{ date('d/m/Y') }}
    </p>
</body>
</html>
