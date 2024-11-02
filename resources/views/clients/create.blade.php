@extends('layouts.app')

@section('content')
    <div class="container mt-1">
        <h1 class="display-4 text-black">Enregistrez un redevable</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('web.clients.store') }}" method="POST" class="bg-light p-4 rounded shadow">
            @csrf

            <!-- Nom Redevable -->
            <div class="form-group">
                <label for="nom_redevable">Nom Redevable</label>
                <input type="text" name="nom_redevable" class="form-control" id="nom_redevable" placeholder="Entrez le nom du redevable" required>
            </div>

            <!-- Téléphone -->
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" name="telephone" class="form-control" id="telephone" placeholder="Entrez le numéro de téléphone" required>
            </div>

            <!-- Adresse -->
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" class="form-control" id="adresse" placeholder="Entrez l'adresse" required>
            </div>

            <!-- Nom Liquidateur -->
            <div class="form-group">
                <label for="nom_liquidateur">Nom Liquidateur</label>
                <input type="text" name="nom_liquidateur" class="form-control" id="nom_liquidateur" placeholder="Entrez le nom du liquidateur" required>
            </div>

            <!-- Matière Taxable -->
            <div class="form-group">
                <label for="matieres_taxables">Matière Taxable</label>
                <input type="text" name="matieres_taxables" class="form-control" id="matieres_taxables" placeholder="Entrez la matière taxable" required>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('web.clients.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const matiereInput = document.getElementById('matieres_taxables');

            // Si nécessaire, vous pouvez ajouter des événements spécifiques ici pour d'autres champs
        });
    </script>
@endsection
