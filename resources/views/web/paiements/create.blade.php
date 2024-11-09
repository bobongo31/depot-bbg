@extends('layouts.app')

@section('content')
    <div class="container mt-1">
        <h1 class="display-4 text-black">Établir une note de débit</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('web.paiements.store') }}" method="POST" class="bg-light p-4 rounded shadow">
            @csrf
        
            <!-- Sélection du Client -->
            <div class="form-group">
                <label for="client_id">Client</label>
                <select name="client_id" class="form-control" id="client_id" required>
                    <option value="">Sélectionnez un client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->nom_redevable }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Matière Taxable -->
            <div class="form-group">
                <label for="matiere_taxable">Matière Taxable</label>
                <input type="text" name="matiere_taxable" class="form-control" id="matiere_taxable" placeholder="Matière taxable">
                <div>
                    <input type="checkbox" id="edit_matiere_taxable"> <label for="edit_matiere_taxable">Modifier la matière taxable</label>
                </div>
            </div>

            <!-- Prix de la Matière -->
            <div class="form-group">
                <label for="prix_matiere">Prix de la Matière</label>
                <input type="number" step="0.01" name="prix_matiere" class="form-control" id="prix_matiere" placeholder="Prix de la matière">
                <div>
                    <input type="checkbox" id="edit_prix_matiere"> <label for="edit_prix_matiere">Modifier le prix de la matière</label>
                </div>
            </div>

            <!-- Date d'Ordonnancement -->
            <div class="form-group">
                <label for="date_ordonancement">Date d'Ordonnancement</label>
                <input type="date" name="date_ordonancement" class="form-control" id="date_ordonancement" required>
            </div>

            <!-- Date d'Accusé de Réception (Non requis) -->
            <div class="form-group">
                <label for="date_accuse_reception">Date d'Accusé de Réception</label>
                <input type="date" name="date_accuse_reception" class="form-control" id="date_accuse_reception">
            </div>

            <!-- Nom de l'Ordonnanceur -->
            <div class="form-group">
                <label for="nom_ordonanceur">Nom de l'Ordonnanceur</label>
                <input type="text" name="nom_ordonanceur" class="form-control" id="nom_ordonanceur" placeholder="Nom de l'ordonnanceur" required>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('web.paiements.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log("Script chargé");

            const matiereInput = document.getElementById('matiere_taxable');
            const prixMatiereInput = document.getElementById('prix_matiere');
            const prixAPayerInput = document.getElementById('prix_a_payer');
            const editMatiereTaxable = document.getElementById('edit_matiere_taxable');
            const editPrixMatiere = document.getElementById('edit_prix_matiere');

            // Fonction pour activer/désactiver les champs
            function toggleEditableFields() {
                matiereInput.readOnly = !editMatiereTaxable.checked;
                prixMatiereInput.readOnly = !editPrixMatiere.checked;

                if (prixMatiereInput.readOnly && prixMatiereInput.value) {
                    // Calculer le prix à payer lorsque l'utilisateur ne modifie pas le prix de la matière
                    const prixMatiere = parseFloat(prixMatiereInput.value);
                    if (!isNaN(prixMatiere)) {
                        const prixPayer = (prixMatiere * 0.05).toFixed(2);
                        prixAPayerInput.value = prixPayer;
                    }
                }
            }

            // Écouter les changements des cases à cocher
            editMatiereTaxable.addEventListener('change', toggleEditableFields);
            editPrixMatiere.addEventListener('change', toggleEditableFields);

            // Appel initial de la fonction pour activer/désactiver les champs
            toggleEditableFields();
        });
    </script>
@endsection
