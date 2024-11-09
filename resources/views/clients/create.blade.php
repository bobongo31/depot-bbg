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

        <!-- Formulaire d'enregistrement pour tous les utilisateurs -->
        <form action="{{ route('web.clients.store') }}" method="POST" class="bg-light p-4 rounded shadow">
            @csrf

            <!-- Nom Redevable -->
            <div class="form-group">
                <label for="nom_redevable">Nom de Redevable</label>
                <input type="text" name="nom_redevable" class="form-control" id="nom_redevable" placeholder="Entrez le nom du redevable" required>
            </div>

            <!-- Adresse -->
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" class="form-control" id="adresse" placeholder="Entrez l'adresse" required>
            </div>

            <!-- Téléphone -->
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" name="telephone" class="form-control" id="telephone" placeholder="Entrez le numéro de téléphone" required>
            </div>

            <!-- Nom Liquidateur -->
            <div class="form-group">
                <label for="nom_liquidateur">Nom de Liquidateur</label>
                <input type="text" name="nom_liquidateur" class="form-control" id="nom_liquidateur" placeholder="Entrez le nom du liquidateur" required>
            </div>

            <!-- Matière Taxable -->
            <div class="form-group">
                <label for="matiere_taxable">Matière Taxable</label>
                <input type="text" name="matiere_taxable" class="form-control" id="matiere_taxable" placeholder="Entrez la matière taxable" required>
            </div>

            <!-- Prix de la Matière -->
            <div class="form-group">
                <label for="prix_matiere">Prix de la Matière</label>
                <input type="number" name="prix_matiere" class="form-control" id="prix_matiere" placeholder="Entrez le prix de la matière" required step="0.01" oninput="calculatePrice()">
            </div>

            <!-- Prix à Payer -->
            <div class="form-group">
                <label for="prix_a_payer">Prix à Payer (5% du prix de la matière)</label>
                <input type="text" name="prix_a_payer" class="form-control" id="prix_a_payer" placeholder="Prix à payer" readonly>
            </div>

            <!-- Nom Taxateur -->
            <div class="form-group">
                <label for="nom_taxateur">Nom de Taxateur</label>
                <input type="text" name="nom_taxateur" class="form-control" id="nom_taxateur" placeholder="Entrez le nom du taxateur" required>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('web.clients.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>

    <script>
        function calculatePrice() {
            const prixMatiere = parseFloat(document.getElementById('prix_matiere').value);
            const prixAPayer = prixMatiere ? (prixMatiere * 0.05).toFixed(2) : 0;
            document.getElementById('prix_a_payer').value = prixAPayer;
        }
    </script>
@endsection
