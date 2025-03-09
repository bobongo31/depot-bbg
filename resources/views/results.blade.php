{{-- resources/views/search/results.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de Recherche</title>

    <style>
        /* Mise en page générale */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* En-tête */
        h1 {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        /* Contenu de la recherche */
        .results-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        /* Liste des résultats */
        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            background-color: #fafafa;
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 8px;
        }

        li:hover {
            background-color: #f1f1f1;
        }

        strong {
            font-weight: bold;
        }

        /* Message d'absence de résultat */
        .no-results {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #d9534f;
        }

        /* Liens */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>Résultats pour "{{ $query }}"</h1>

    <div class="results-container">
        @if($accuses->isEmpty())
            <p class="no-results">Aucun résultat trouvé pour votre recherche.</p>
        @else
            <ul>
                @foreach($accuses as $accuse)
                    <li>
                        <strong>Enregistrement :</strong> {{ $accuse->numero_enregistrement }}<br>
                        <strong>Expéditeur :</strong> {{ $accuse->nom_expediteur }}<br>
                        <strong>Résumé :</strong> {{ $accuse->resume }}<br>
                        <a href="#" class="voir-details" data-id="{{ $accuse->id }}">Voir les détails</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Div où les détails du courrier seront affichés -->
    <div id="courrier-details" class="details-container" style="display: none; margin-top: 20px; border: 1px solid #ddd; padding: 15px;"></div>

    <!-- Ajout du script AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".voir-details").click(function(e) {
                e.preventDefault();
                var courrierId = $(this).data("id");

                $.ajax({
                    url: "/courriers/" + courrierId, // Route Laravel pour récupérer les détails
                    type: "GET",
                    success: function(response) {
                        $("#courrier-details").html(response).fadeIn(); // Afficher les détails sans recharger
                    },
                    error: function() {
                        alert("Erreur lors du chargement des détails.");
                    }
                });
            });
        });
    </script>
</body>
