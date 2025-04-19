@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h2>Modifier Statut de la Demande</h2>
    <form action="{{ route('demandes_conges.update', $demandeConge->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="scroll-animated mb-3">
        <label for="statut" class="form-label">Statut</label>
        <select name="statut" class="form-control" required>
            <option value="en_attente" {{ $demandeConge->statut == 'en_attente' ? 'selected' : '' }}>En Attente</option>
            <option value="acceptee" {{ $demandeConge->statut == 'acceptee' ? 'selected' : '' }}>Acceptée</option>
            <option value="refusee" {{ $demandeConge->statut == 'refusee' ? 'selected' : '' }}>Refusée</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>

</div>
@endsection
