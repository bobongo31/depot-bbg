@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="text-center text-dark mb-4">Éditer un Paiement</h3>

    <form action="{{ route('update', $paiement->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="matiere_taxable">Matière Taxable</label>
            <input type="text" name="matiere_taxable" class="form-control" value="{{ old('matiere_taxable', $paiement->matiere_taxable) }}" required>
        </div>

        <div class="form-group">
            <label for="prix_matiere">Prix de la Matière</label>
            <input type="number" name="prix_matiere" class="form-control" value="{{ old('prix_matiere', $paiement->prix_matiere) }}" required>
        </div>

        <div class="form-group">
            <label for="prix_a_payer">Prix à Payer</label>
            <input type="number" name="prix_a_payer" class="form-control" value="{{ old('prix_a_payer', $paiement->prix_a_payer) }}" required>
        </div>

        <div class="form-group">
            <label for="date_ordonancement">Date d'Ordonnancement</label>
            <input type="date" name="date_ordonancement" class="form-control" value="{{ old('date_ordonancement', $paiement->date_ordonancement) }}" required>
        </div>

        <div class="form-group">
            <label for="date_accuse_reception">Date d'Accusé de Réception</label>
            <input type="date" name="date_accuse_reception" class="form-control" value="{{ old('date_accuse_reception', $paiement->date_accuse_reception) }}" required>
        </div>

        <div class="form-group">
            <label for="nom_ordonanceur">Nom de l'Ordonnanceur</label>
            <input type="text" name="nom_ordonanceur" class="form-control" value="{{ old('nom_ordonanceur', $paiement->nom_ordonanceur) }}" required>
        </div>

        <div class="form-group">
            <label for="status">Statut</label>
            <select name="status" class="form-control" required>
                <option value="validé" {{ $paiement->status == 'validé' ? 'selected' : '' }}>Validé</option>
                <option value="en attente" {{ $paiement->status == 'en attente' ? 'selected' : '' }}>En Attente</option>
                <option value="rejeté" {{ $paiement->status == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('home') }}" class="btn btn-secondary">Retour</a>

    </form>
</div>
@endsection
