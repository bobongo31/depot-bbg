@extends('layouts.app')

@section('content')

<h1 class="scroll-animated mb-4">
    <i class="fa-solid fa-paper-plane"></i> Courriers expédiés
</h1>

{{-- Message succès --}}
@if(session('success'))
    <div class="alert alert-success scroll-animated">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

{{-- Bouton création --}}
@if(Auth::user() && in_array(Auth::user()->role, ['admin','agent']))
    <a href="{{ route('courrier_expedie.create') }}"
       class="btn btn-primary mb-3 scroll-animated">
        <i class="fa-solid fa-plus"></i> Nouveau courrier expédié
    </a>
@endif

<div class="card shadow-sm scroll-animated">
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
                @forelse($courriers as $courrier)

                    @php
                        $user = auth()->user();
                        $visible = false;

                        /* 🔹 Normalisation fiable */
                        $normalize = function ($v) {
                            $s = trim((string)$v);
                            $t = @iconv('UTF-8','ASCII//TRANSLIT',$s);
                            if ($t === false) $t = $s;
                            $t = mb_strtolower($t);
                            return preg_replace('/[^a-z0-9]+/u','',$t);
                        };

                        /* 1️⃣ Admin voit tout */
                        if ($user->role === 'admin') {
                            $visible = true;
                        }

                        /* 2️⃣ Auteur voit son courrier */
                        elseif (!empty($courrier->user_id) && $courrier->user_id === $user->id) {
                            $visible = true;
                        }

                        /* 3️⃣ Chef de service / Agent : par service concerné */
                        elseif (in_array($user->role, ['chef_service','agent'])) {

                            $userServiceNorm = $normalize($user->service);

                            $servicesCopies = [];
                            if ($courrier->copies && $courrier->copies->count()) {
                                foreach ($courrier->copies as $copy) {
                                    $servicesCopies[] = $normalize($copy->service);
                                }
                            }

                            $visible = in_array($userServiceNorm, $servicesCopies, true);
                        }
                    @endphp

                    @if($visible)
                    <tr>
                        <td>{{ $courrier->numero_ordre }}</td>

                        <td>{{ \Carbon\Carbon::parse($courrier->date_expedition)->format('d/m/Y') }}</td>

                        <td>{{ $courrier->numero_lettre }}</td>

                        {{-- Destinataire principal + copies --}}
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
                                            {{ $copy->service }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>

                        <td>{{ Str::limit($courrier->resume, 50) }}</td>
                        <td>{{ Str::limit($courrier->observation, 30) }}</td>

                        {{-- Annexes --}}
                        <td class="text-center">
                            @if(is_array($courrier->annexes) && count($courrier->annexes))
                                <span class="badge bg-success">{{ count($courrier->annexes) }}</span>
                            @else
                                <span class="badge bg-secondary">0</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="text-center">
                            <a href="{{ route('courrier_expedie.show', $courrier->id) }}"
                               class="btn btn-sm btn-info">
                                <i class="fa-solid fa-eye"></i>
                            </a>

                            @if(in_array($user->role,['admin','agent']))
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
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endif

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
