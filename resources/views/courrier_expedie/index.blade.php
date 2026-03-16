@extends('layouts.app')

@section('content')

<h1 class="mb-4">
    <i class="fa-solid fa-paper-plane"></i> Courriers expédiés
</h1>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

@if(auth()->check() && in_array(auth()->user()->role, ['admin','agent']))
    <a href="{{ route('courrier_expedie.create') }}"
       class="btn btn-primary mb-3">
        <i class="fa-solid fa-plus"></i> Nouveau courrier expédié
    </a>
@endif

<div class="alert alert-info">
    Total courriers visibles : {{ $courriers->total() }}
</div>

<div class="card shadow-sm">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>N°</th>
                        <th>Date</th>
                        <th>N° Lettre</th>
                        <th>Destinataires</th>
                        <th>Résumé</th>
                        <th>Observation</th>
                        <th class="text-center">Annexes</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                @php $user = auth()->user(); @endphp

                @forelse($courriers as $courrier)
                    <tr>
                        <td>{{ $courrier->numero_ordre }}</td>

                        <td>{{ optional($courrier->date_expedition)->format('d/m/Y') }}</td>

                        <td>{{ $courrier->numero_lettre }}</td>

                        <td>
                            <span class="badge bg-primary mb-1 d-inline-block">
                                <i class="fa-solid fa-user"></i>
                                {{ $courrier->destinataire }}
                            </span>

                            @if($courrier->copies && $courrier->copies->count())
                                <div class="mt-1">
                                    @foreach($courrier->copies as $copy)
                                        <span class="badge bg-warning text-dark me-1">
                                            <i class="fa-solid fa-building"></i>
                                            {{ $copy->service }} / {{ $copy->direction }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td>{{ \Illuminate\Support\Str::limit($courrier->resume, 50) }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($courrier->observation, 30) }}</td>

                        <td class="text-center">
                            @if(is_array($courrier->annexes) && count($courrier->annexes))
                                <span class="badge bg-success">{{ count($courrier->annexes) }}</span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </td>

                        <td class="text-center">
                            <a href="{{ route('courrier_expedie.show', $courrier->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if(in_array($user->role, ['admin','agent']))
                                <a href="{{ route('courrier_expedie.edit', $courrier->id) }}"
                                   class="btn btn-sm btn-warning">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="{{ route('courrier_expedie.destroy', $courrier->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Supprimer ce courrier ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            <i class="fa-solid fa-inbox"></i>
                            Aucun courrier expédié enregistré
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $courriers->links() }}
        </div>

    </div>
</div>

@endsection