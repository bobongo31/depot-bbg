@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3>Ajouter une réponse finale</h3>

    <form action="{{ route('reponse.store', ['reponseId' => $reponse->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-3">
            <label for="numero_enregistrement">Numéro d'enregistrement</label>
            <input type="text" name="numero_enregistrement" id="numero_enregistrement" class="form-control" required
                   value="{{ old('numero_enregistrement', $reponse->numero_enregistrement) }}">
            @error('numero_enregistrement')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="numero_reference">Numéro de référence</label>
            <input type="text" name="numero_reference" id="numero_reference" class="form-control"
                   value="{{ old('numero_reference', $reponse->numero_reference) }}">
            @error('numero_reference')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="service_concerne">Service Concerné</label>
            <input type="text" name="service_concerne" id="service_concerne" class="form-control" required
                   value="{{ old('service_concerne', $reponse->service_concerne ?? '') }}">
            @error('service_concerne')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="observation">Observation</label>
            <textarea name="observation" id="observation" class="form-control">{{ old('observation') }}</textarea>
            @error('observation')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="file">Annexe (si nécessaire)</label>
            <input type="file" name="file" id="file" class="form-control">
            @error('file')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Soumettre</button>
    </form>
</div>
@endsection
