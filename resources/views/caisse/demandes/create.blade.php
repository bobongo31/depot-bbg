@extends('layouts.app')

@section('content')
<div class="scroll-animated container">
    <h1 class="scroll-animated text-center text-primary mb-4">
        <i class="fas fa-money-check-alt"></i> Nouvelle Demande de Fonds
    </h1>
    
    <div class="scroll-animated card shadow-lg">
        <div class="scroll-animated card-header bg-info text-white">
            <i class="fas fa-edit"></i> Formulaire de Demande de Fonds
        </div>
        <div class="card-body">
            <form action="{{ route('caisse.demandes.store') }}" method="POST">
                @csrf
                <div class="scroll-animated mb-3">
                    <label for="montant" class="form-label">
                        <i class="fas fa-euro-sign"></i> Montant (€)
                    </label>
                    <input type="number" class="form-control" id="montant" name="montant" value="{{ old('montant') }}" required>
                    @error('montant')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="scroll-animated mb-3">
                    <label for="motif" class="form-label">
                        <i class="fas fa-clipboard-list"></i> Motif
                    </label>
                    <input type="text" class="form-control" id="motif" name="motif" value="{{ old('motif') }}" required>
                    @error('motif')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-paper-plane"></i> Soumettre
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
