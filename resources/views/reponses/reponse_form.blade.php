<form action="{{ route('reponse.ajouter', ['reponseId' => $reponse->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="numero_enregistrement">Numéro d'enregistrement</label>
        <input type="text" name="numero_enregistrement" id="numero_enregistrement" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="numero_reference">Numéro de référence</label>
        <input type="text" name="numero_reference" id="numero_reference" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="observation">Observation</label>
        <textarea name="observation" id="observation" class="form-control"></textarea>
    </div>
    <div class="form-group">
        <label for="file">Annexe (si nécessaire)</label>
        <input type="file" name="file" id="file" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Soumettre</button>
</form>
