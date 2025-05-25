@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h1><i class="fas fa-edit"></i> Modifier la Dépense</h1>
    
    <form action="{{ route('caisse.depenses.update', $depense->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Utilise PUT pour indiquer la mise à jour -->
        
        <div class="scroll-animated mb-3">
            <label for="rubrique" class="form-label">
                <i class="fas fa-tag"></i> Rubrique
            </label>
            <input type="text" class="form-control" id="rubrique" name="rubrique" value="{{ old('rubrique', $depense->rubrique) }}" required>
        </div>

        <div class="scroll-animated mb-3">
            <label for="montant" class="form-label">
                <i class="fas fa-euro-sign"></i> Montant (€)
            </label>
            <input type="number" class="form-control" id="montant" name="montant" value="{{ old('montant', $depense->montant) }}" required>
        </div>

        <div class="scroll-animated mb-3">
            <label for="date_depense" class="form-label">
                <i class="fas fa-calendar-day"></i> Date
            </label>
            <input type="date" class="form-control" id="date_depense" name="date_depense" value="{{ old('date_depense', $depense->date_depense->format('Y-m-d')) }}" required>
        </div>

        <div class="scroll-animated mb-3">
            <label for="description" class="form-label">
                <i class="fas fa-align-left"></i> Description
            </label>
            <textarea class="form-control" id="description" name="description" required>{{ old('description', $depense->description) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Mettre à jour
        </button>
    </form>
</div>
@endsection
