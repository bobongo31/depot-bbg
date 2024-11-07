<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogMob</title>
    <script src="js/inactivity.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('images/fpc.jpg') no-repeat center center fixed;
            background-size: cover;
            opacity: 0.9;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.80);
            padding: 30px;
            border-radius: 40px;
        }

        .table thead th, .table-title {
            background-color: #3b93d2;
            color: white;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding: 3px;
        }

        .table tbody tr {
            font-size: 12px;
        }

        .table tbody tr:hover {
            background-color: #3697cf;
        }

        .table tfoot th {
            background-color: #66798d;
            font-weight: bold;
            font-size: 11px;
        }

        .btn-primary {
            background-color: #3b93d2;
            border: none;
        }

        .table {
            margin-bottom: 0;
        }

        .btn-sm {
            font-size: 12px;
            padding: 5px 10px;
        }

        .search-bar {
            max-width: 400px;
            margin-bottom: 20px;
        }

        /* Aligne la barre de recherche à droite */
        .search-container {
            display: flex;
            justify-content: flex-end;
        }

        /* Ajouter un espace entre la barre de recherche et le bouton */
        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Barre de recherche alignée à droite et bouton aligné à gauche -->
        <div class="actions-container mb-3">
            <div>
                <a href="{{ route('web.clients.create') }}" class="btn btn-primary">Ajouter un Redevable</a>
            </div>
            <div class="search-container">
                <form method="GET" action="{{ route('web.clients.index') }}" class="form-inline search-bar">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Rechercher un redevable" aria-label="Recherche" value="{{ request()->get('search') }}">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Rechercher</button>
                </form>
            </div>
        </div>

        <div class="text-left mb-3">
            <a href="{{ url('/') }}" class="btn btn-secondary">Retourner vers le Tableau de Bord</a>
        </div>

        <div class="table-title mb-3 p-2">PV DE TAXATION</div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Nom Redevable</th>
                    <th>Nom Taxateur</th>
                    <th>Nom Liquidateur</th>
                    <th>Matière Taxable</th>
                    <th>Prix de la Matière</th>
                    <th>Prix à Payer</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->telephone }}</td>
                        <td>{{ $client->adresse }}</td>
                        <td>{{ $client->nom_redevable }}</td>
                        <td>{{ $client->nom_taxateur }}</td>
                        <td>{{ $client->nom_liquidateur }}</td>
                        <td>{{ $client->matiere_taxable }}</td>
                        <td>{{ $client->prix_matiere }} €</td>
                        <td>{{ $client->prix_a_payer }} €</td>
                        <td>
                            <a href="{{ route('web.clients.edit', $client->id) }}" class="btn btn-warning btn-sm">Modifier</a>
                            <form action="{{ route('web.clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-right">Total</th>
                    <th>{{ $clients->sum('prix_matiere') }} €</th>
                    <th>{{ $clients->sum('prix_a_payer') }} €</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>

    @include('partials.footer')

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
