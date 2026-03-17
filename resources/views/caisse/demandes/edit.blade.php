<!-- resources/views/caisse/demandes/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h1><i class="fas fa-edit"></i> Modifier la demande de fonds</h1>

    <form action="{{ route('caisse.demandes.update', $demande->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="scroll-animated mb-3">
            <label for="montant" class="form-label">
                <i class="fas fa-euro-sign"></i> Montant (€)
            </label>
            <input type="number" class="form-control" id="montant" name="montant" value="{{ old('montant', $demande->montant) }}" required>
        </div>

        <div class="scroll-animated mb-3">
            <label for="motif" class="form-label">
                <i class="fas fa-file-signature"></i> Motif
            </label>
            <input type="text" class="form-control" id="motif" name="motif" value="{{ old('motif', $demande->motif) }}" required>
        </div>

        <button type="submit" class="scroll-animated btn btn-primary">
            <i class="fas fa-save"></i> Mettre à jour
        </button>
    </form>
</div>
@endsection
