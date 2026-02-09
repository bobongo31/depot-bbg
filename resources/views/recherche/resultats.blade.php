@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Résultats pour : <em>{{ $query }}</em></h2>

    @if($telegrammes->count())
        <h4 class="mt-4 text-primary">📨 Télégrammes</h4>
        <ul class="list-group mb-3">
            @foreach($telegrammes as $t)
                <li class="list-group-item">
                    <a href="{{ route('telegramme.show', $t->id) }}">
                        {{ Str::limit($t->commentaires ?? $t->observation ?? '—', 60) }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if($accuses->count())
        <h4 class="mt-4 text-success">📥 Accusés de réception</h4>
        <ul class="list-group mb-3">
            @foreach($accuses as $a)
                <li class="list-group-item">
                    <a href="{{ route('courriers.show', $a->id) }}">
                        {{ $a->numero_enregistrement }} - {{ $a->numero_reference }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if($archives->count())
        <h4 class="mt-4 text-warning">🗄️ Archives</h4>
        <ul class="list-group mb-3">
            @foreach($archives as $ar)
                <li class="list-group-item">
                    <a href="{{ route('archives.show', $ar->id) }}">
                        {{ $ar->titre ?? ($ar->numero_enregistrement . ' - ' . $ar->numero_reference) }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if($reponses->count())
        <h4 class="mt-4 text-info">📝 Réponses</h4>
        <ul class="list-group mb-3">
            @foreach($reponses as $r)
                <li class="list-group-item">
                    <a href="{{ route('reponse.show', $r->id) }}">
                        {{ Str::limit($r->contenu ?? $r->commentaires ?? $r->observation ?? '—', 60) }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if($reponses_finales->count())
        <h4 class="mt-4 text-dark">✅ Réponses finales</h4>
        <ul class="list-group mb-3">
            @foreach($reponses_finales as $rf)
                <li class="list-group-item">
                    <a href="{{ route('reponses.showFinale', $rf->id) }}">
                        {{ Str::limit($rf->contenu_final ?? '—', 60) }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    @if(
        $telegrammes->isEmpty() &&
        $accuses->isEmpty() &&
        $archives->isEmpty() &&
        $reponses->isEmpty() &&
        $reponses_finales->isEmpty()
    )
        <div class="alert alert-warning mt-4">
            Aucun résultat trouvé pour "<strong>{{ $query }}</strong>".
        </div>
    @endif
</div>
@endsection
