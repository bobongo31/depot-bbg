@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Détails du Télégramme</h1>

    @if(isset($telegramme))
        <hr>
        <table class="table">
            <tr><th>Numéro d'enregistrement :</th><td>{{ $telegramme->numero_enregistrement }}</td></tr>
            <tr><th>Numéro de référence :</th><td>{{ $telegramme->numero_reference }}</td></tr>
            <tr><th>Service Concerné :</th><td>{{ $telegramme->service_concerne }}</td></tr>
            <tr><th>Commentaires :</th><td>{{ $telegramme->commentaires }}</td></tr>
            <tr><th>Expéditeur :</th><td>{{ $telegramme->observation }}</td></tr>
        </table>

        <h3>Annexes du Télégramme</h3>
            @if($telegramme->annexes && $telegramme->annexes->isNotEmpty())
                <ul>
                    @foreach ($telegramme->annexes as $annexe)
                        <li>
                            <a href="{{ asset('storage/annexes/' . $annexe->file_path) }}" target="_blank">📄 Voir l'annexe</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p>Aucune annexe disponible pour ce télégramme.</p>
            @endif



        <a href="{{ route('reponses.index') }}" class="btn btn-secondary mt-3">Retour à la liste</a>
    @else
        <p>Télégramme non trouvé.</p>
    @endif
</div>
@endsection
