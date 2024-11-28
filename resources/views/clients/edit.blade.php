<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer le Client</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</head>
<body>

    <!-- En-tête -->
    @include('partials.header')

    <!-- Contenu Principal -->
    <div class="container mt-5">
        <h2>Éditer le Client : {{ $client->nom_redevable }}</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('web.clients.update', $client->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nom_redevable">Nom du Redevable</label>
                <input type="text" class="form-control" id="nom_redevable" name="nom_redevable" value="{{ old('nom_redevable', $client->nom_redevable) }}" required>
            </div>

            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $client->telephone) }}">
            </div>

            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" class="form-control" id="adresse" name="adresse" value="{{ old('adresse', $client->adresse) }}">
            </div>

            <div class="form-group">
                <label for="prix_matiere">Prix de la Matière</label>
                <input type="number" class="form-control" id="prix_matiere" name="prix_matiere" value="{{ old('prix_matiere', $client->prix_matiere) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Mettre à jour</button>
            <a href="{{ route('web.clients.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>

    <!-- Pied de page -->
    @include('partials.footer')
</body>
</html>
