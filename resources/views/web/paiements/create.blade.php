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
                        <option value="{{ $client->id }}">{{ $client->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Matière Taxable -->
            <div class="form-group">
                <label for="matieres_taxables">Matière Taxable</label>
                <input type="text" name="matieres_taxables" class="form-control" id="matieres_taxables" placeholder="Entrez la matière taxable" required>
            </div>

            <!-- Prix de la Matière -->
            <div class="form-group">
                <label for="prix_matiere">Prix de la Matière</label>
                <input type="number" step="0.01" name="prix_matiere" class="form-control" id="prix_matiere" placeholder="Entrez le prix" required>
            </div>

            <!-- Date d'Ordonnancement -->
            <div class="form-group">
                <label for="date_ordonancement">Date d'Ordonnancement</label>
                <input type="date" name="date_ordonancement" class="form-control" id="date_ordonancement" required>
            </div>

            <!-- Date d'Accusé de Réception -->
            <div class="form-group">
                <label for="date_accuse_reception">Date d'Accusé de Réception</label>
                <input type="date" name="date_accuse_reception" class="form-control" id="date_accuse_reception" required>
            </div>

            <!-- Nom de l'Ordonnanceur -->
            <div class="form-group">
                <label for="nom_ordonanceur">Nom de l'Ordonnanceur</label>
                <input type="text" name="nom_ordonanceur" class="form-control" id="nom_ordonanceur" placeholder="Entrez le nom de l'ordonnanceur" required>
            </div>

            <button type="submit" class="btn btn-success">Enregistrer</button>
            <a href="{{ route('web.paiements.index') }}" class="btn btn-secondary">Retour</a>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const clientSelect = document.getElementById('client_id');
            const matiereInput = document.getElementById('matieres_taxables');
            const prixMatiereInput = document.getElementById('prix_matiere');

            clientSelect.addEventListener('change', function () {
                const clientId = this.value;
                if (clientId) {
                    // Appel AJAX pour récupérer les données du client
                    fetch(`/clients/${clientId}/data`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.matiere_taxable && data.prix_matiere) {
                                matiereInput.value = data.matiere_taxable;
                                prixMatiereInput.value = data.prix_matiere;
                            }
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération des données du client:', error);
                        });
                } else {
                    // Réinitialiser les champs si aucun client n'est sélectionné
                    matiereInput.value = '';
                    prixMatiereInput.value = '';
                }
            });
        });
    </script>
@endsection
