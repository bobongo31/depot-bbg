<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LogMob</title>
    <script src="js/inactivity.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
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

        .btn {
            border-radius: 20px;
            padding: 8px 16px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary {
            background-color: #3b93d2;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2a78b0;
            transform: scale(1.05);
        }

        .btn-outline-success:hover {
            color: white;
            background-color: #28a745;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .actions-container > div {
            margin-right: 10px;
        }

        .search-bar input {
            margin-right: 5px;
        }

        .table {
            margin-bottom: 0;
        }
    </style>
</head>
<body>

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
                <a href="{{ route('web.clients.create') }}" class="btn btn-primary" aria-label="Ajouter un Redevable">
                    <i class="fas fa-plus-circle"></i> Ajouter un Redevable
                </a>
            </div>
            <div class="search-container">
                <form method="GET" action="{{ route('web.clients.index') }}" class="form-inline search-bar">
                    <input type="text" name="search" class="form-control mr-sm-2" placeholder="Rechercher un redevable" aria-label="Recherche" value="{{ request()->get('search') }}">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit" aria-label="Rechercher">
                        <i class="fas fa-search"></i> 
                    </button>
                </form>
            </div>
        </div>

        <div class="text-left mb-3">
            <a href="{{ url('/') }}" class="btn btn-secondary" aria-label="Retourner vers le Tableau de Bord">
                <i class="fas fa-arrow-left"></i> 
            </a>
        </div>

        <div class="table-title mb-3 p-2">PV DE TAXATION</div>

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de Redevable</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Nom de Taxateur</th>
                    <th>Nom de Liquidateur</th>
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
                        <td>{{ $client->nom_redevable }}</td>
                        <td>{{ $client->telephone }}</td>
                        <td>{{ $client->adresse }}</td>
                        <td>{{ $client->nom_taxateur }}</td>
                        <td>{{ $client->nom_liquidateur }}</td>
                        <td>{{ $client->matiere_taxable }}</td>
                        <td>{{ $client->prix_matiere }} FC</td>
                        <td>{{ $client->prix_a_payer }} FC</td>
                        <td>
                            <a href="{{ route('web.clients.edit', $client->id) }}" class="btn btn-warning btn-sm" aria-label="Modifier">
                                <i class="fas fa-edit"></i> 
                            </a>
                            <form action="{{ route('web.clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" aria-label="Supprimer">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" class="text-right">Total</th>
                    <th>{{ $clients->sum('prix_matiere') }} FC</th>
                    <th>{{ $clients->sum('prix_a_payer') }} FC</th>
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
