@extends('layouts.app')

@section('content')
<div class="container">
    <h2><i class="fas fa-paper-plane"></i> Envoyer un Télégramme</h2>

    @if(session('success'))
        <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('telegramme.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Numéro d'Enregistrement -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-hashtag"></i> Numéro d'Enregistrement</label>
            <select id="select_numero_enregistrement" class="form-control" onchange="document.getElementById('manual_numero_enregistrement').value = this.value">
                <option value="">Sélectionner un numéro d'enregistrement</option>
                @foreach($accuse_receptions as $accuse)
                    <option value="{{ $accuse->numero_enregistrement }}">{{ $accuse->numero_enregistrement }}</option>
                @endforeach
            </select>
            <input type="text" id="manual_numero_enregistrement" class="form-control mt-2" name="numero_enregistrement" placeholder="Ou saisissez manuellement">
        </div>

        <!-- Numéro de Référence -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-bookmark"></i> Numéro de Référence</label>
            <select id="select_numero_reference" class="form-control" onchange="document.getElementById('manual_numero_reference').value = this.value">
                <option value="">Sélectionner un numéro de référence</option>
                @foreach($accuse_receptions as $accuse)
                    <option value="{{ $accuse->numero_reference }}">{{ $accuse->numero_reference }}</option>
                @endforeach
            </select>
            <input type="text" id="manual_numero_reference" class="form-control mt-2" name="numero_reference" placeholder="Ou saisissez manuellement">
        </div>

        <!-- Services Concernés -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-building"></i> Services Concernés :</label>
            @foreach(['RH' => 'Ressources Humaines', 'Comptabilité' => 'Comptabilité', 'Informatique' => 'Informatique', 'Logistique' => 'Logistique'] as $key => $service)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="service_concerne[]" value="{{ $key }}" id="service{{ $key }}">
                    <label class="form-check-label" for="service{{ $key }}">{{ $service }}</label>
                </div>
            @endforeach
        </div>

        <!-- Expéditeur -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-user"></i> Expéditeur</label>
            <textarea class="form-control" name="observation" rows="3" required></textarea>
        </div>

        <!-- Résumé -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-align-left"></i> Résumé</label>
            <textarea class="form-control" name="commentaires" rows="4" required></textarea>
        </div>

        <!-- Annexes -->
        <div class="mb-3">
            <label class="form-label"><i class="fas fa-paperclip"></i> Ajouter des Annexes</label>
            <input type="file" class="form-control" name="annexes[]" multiple accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Envoyer</button>
        <a href="{{ route('reponses.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
    </form>
</div>

<!-- Script pour gérer la sélection et la saisie manuelle -->
<script>
    document.getElementById('select_numero_enregistrement').addEventListener('change', function() {
        document.getElementById('manual_numero_enregistrement').value = this.value;
    });

    document.getElementById('select_numero_reference').addEventListener('change', function() {
        document.getElementById('manual_numero_reference').value = this.value;
    });
</script>

@endsection
