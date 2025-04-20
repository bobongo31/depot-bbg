<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de Recherche</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            margin-bottom: 20px;
        }

        .results-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

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

        .no-results {
            text-align: center;
            padding: 20px;
            font-size: 18px;
            color: #d9534f;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .details-container {
            display: none;
            margin-top: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
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
                        <a href="{{ route('courriers.show', $accuse->id) }}">Voir les détails</a>

                        <!-- Div où les détails du courrier s’afficheront -->
                        <div class="details-container" id="details-{{ $accuse->id }}"></div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <!-- Script AJAX pour charger les détails dynamiquement -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $(".voir-details").click(function(e) {
            e.preventDefault();
            var link = $(this);
            var courrierId = link.data("id");
            var detailsDiv = $("#details-" + courrierId);

            // Si déjà visible, on referme
            if (detailsDiv.is(":visible")) {
                detailsDiv.slideUp();
                link.text("Voir les détails");
                return;
            }

            // Sinon on charge dynamiquement via AJAX
            $.ajax({
                url: "/courriers/" + courrierId,  // Route qui appelle la méthode show
                type: "GET",
                success: function(data) {
                    detailsDiv.html(data); // Injecte les détails reçus dans le div
                    $(".details-container").not(detailsDiv).slideUp(); // Ferme les autres
                    detailsDiv.slideDown(); // Affiche celle-ci
                    $(".voir-details").not(link).text("Voir les détails"); // Reset texte des autres
                    link.text("Cacher les détails");
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement des détails : ", xhr.responseText);
                    detailsDiv.html("<p style='color:red;'>Erreur lors du chargement des détails.</p>").slideDown();
                }
            });
        });
    });
</script>

</body>
</html>
