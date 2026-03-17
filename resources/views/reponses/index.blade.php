@extends('layouts.app')

@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

:root{
    --bg-page: #f4f6f9;
    --panel-bg: #ffffff;
    --muted: #eef2f7;
    --text-dark: #1f2d3d;
    --brand-blue: #1f4e79;
    --late-red: #c62828;
    --waiting-amber: #f9a825;
    --inprogress-blue: #1565c0;
    --done-green: #2e7d32;
}

body{ background-color: var(--bg-page); font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; color:var(--text-dark); }

.table-container{ background: var(--panel-bg); border-radius:6px; box-shadow:0 2px 6px rgba(0,0,0,0.05); padding:10px; border-top:3px solid var(--brand-blue); }

h2{ font-size:20px; font-weight:600; color:var(--text-dark); border-left:4px solid var(--brand-blue); padding-left:10px; }

/* Table refined */
table{ width:100%; border-collapse:collapse; background:var(--panel-bg); font-size:13px; }
thead{ background-color:var(--muted); }
thead th{ text-align:left; padding:10px; font-weight:600; color:var(--text-dark); border-bottom:1px solid #dcdfe6; font-size:13px; }
tbody td{ padding:10px; border-bottom:1px solid #e6e9ef; font-size:13px; vertical-align:middle; }
tbody tr:nth-child(even){ background-color:#fafbfc; }
tbody tr:hover{ background-color:#f1f6ff; }

/* Remove bootstrap danger/pink row usage — only badge red remains */
.late-row{ background: #fff !important; border-left:4px solid rgba(198,40,40,0.12); }
.on-time-row{ background: #fff; }

.date-header{ background:#e3eaf2; padding:8px 12px; font-weight:600; border-radius:4px; margin-top:20px; display:inline-block; }

.telegrames{ border-left:5px solid var(--brand-blue); padding-left:12px; margin-top:12px; }

.filter-bar{ display:flex; gap:10px; align-items:center; flex-wrap:wrap; }
.filter-bar .btn{ background:var(--brand-blue); color:#fff; border-color:transparent; }

.badge-status{ font-size:12px; padding:4px 10px; border-radius:4px; font-weight:500; color:#fff; display:inline-block; }
.badge-late{ background:var(--late-red) !important; }
.badge-waiting{ background:var(--waiting-amber) !important; color:#1f2d3d !important; }
.badge-inprogress{ background:var(--inprogress-blue) !important; }
.badge-on-time{ background:var(--done-green) !important; }

.actions-cell{ display:flex; gap:6px; justify-content:center; align-items:center; }
.actions-cell .btn{ padding:4px 8px; font-size:0.85rem; }

.resume{ max-width:250px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }

/* Column size suggestions */
thead th:nth-child(1), tbody td:nth-child(1){ width:70px; }
thead th:nth-child(2), tbody td:nth-child(2){ width:180px; }
thead th:nth-child(3), tbody td:nth-child(3){ width:140px; }
thead th:nth-child(4), tbody td:nth-child(4){ width:160px; }
thead th:nth-child(6), tbody td:nth-child(6){ width:80px; text-align:center; }
thead th:nth-child(7), tbody td:nth-child(7){ width:120px; text-align:center; }
thead th:nth-child(8), tbody td:nth-child(8){ width:120px; text-align:center; }

.hide-mobile{display:table-cell}
@media (max-width:768px){ .hide-mobile{display:none} }

.table .badge{ display:inline-block; max-width:100%; }
.dropdown-menu{ z-index:3000 }
</style>

<div class="container py-4">

@if(session('code_acces_valide'))

{{-- FILTRES --}}
<form method="GET" action="{{ route('reponses.index') }}" class="row mb-4 g-2 align-items-center">
    <div class="col-md-5">
        <input name="q" value="{{ request('q') }}" class="form-control" placeholder="Recherche...">
    </div>
    <div class="col-md-3">
        <select name="service" class="form-select">
            <option value="">Tous les services</option>
            @foreach($services as $svc)
                <option value="{{ $svc }}" @selected(request('service') == $svc)>{{ $svc }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3 d-flex gap-2">
        <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        <input type="date" name="to" value="{{ request('to') }}" class="form-control">
    </div>
    <div class="col-md-1 d-grid">
        <button class="btn btn-primary">OK</button>
    </div>
</form>

{{-- ===================== RÉPONSES ===================== --}}
<h2 class="mb-4"><i class="fas fa-list-alt"></i> Liste des réponses</h2>

@foreach($reponsesGrouped as $date => $reponsesDuJour)
<div class="mb-5">
    <div class="date-header">
        <i class="fas fa-calendar-day"></i>
        {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
    </div>

    <div class="table-container table-responsive">
    <table class="table table-hover align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>Référence</th>
            <th>Destinataire</th>
            <th class="hide-mobile">Expéditeur</th>
            <th class="hide-mobile">Résumé</th>
            <th>Heure</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($reponsesDuJour as $reponse)
        <tr class="{{ $reponse->isLate ? 'late-row' : '' }}">
            <td>{{ $reponse->numero_enregistrement }}</td>
            <td>{{ $reponse->numero_reference }}</td>

            <td class="wrap">
                @foreach($reponse->services_affiches as $svc)
                    <span class="badge bg-info text-dark me-1">{{ $svc }}</span>
                @endforeach
            </td>

            <td class="hide-mobile">{{ $reponse->observation }}</td>
            <td class="hide-mobile wrap resume">{{ $reponse->commentaires }}</td>
            <td>{{ $reponse->created_at->format('H:i') }}</td>

            <td class="status">
                @if($reponse->statutLabel === '—')
                    <span class="badge-status" style="background:#9aa6b2">—</span>
                @elseif($reponse->isLate)
                    <span class="badge-status badge-late">EN RETARD</span>
                @else
                    <span class="badge-status badge-on-time">DANS LE DÉLAI</span>
                @endif
            </td>

            <td class="actions-cell d-flex gap-2 flex-wrap">
                <a class="btn btn-outline-info btn-sm"
                   href="{{ route('reponse.show',$reponse->id) }}">
                    <i class="fas fa-eye"></i>
                </a>

                @if($reponse->telegramme_id)
                <a class="btn btn-outline-primary btn-sm"
                   href="{{ route('reponses.create',['telegramme_id'=>$reponse->telegramme_id]) }}">
                    <i class="fas fa-reply"></i>
                </a>
                @endif

                @if(auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->role === 'DG' || (method_exists(auth()->user(),'hasRole') && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('DG')))))
                <a class="btn btn-outline-success btn-sm"
                   href="{{ action([App\Http\Controllers\ReponseController::class, 'formAjouterReponseFinale'], ['reponseId' => $reponse->id]) }}"
                   title="Ajouter réponse finale">
                    <i class="fas fa-file-signature"></i>
                </a>
                @endif

                <button class="btn btn-outline-danger btn-sm ajax-delete-btn"
                        data-url="{{ route('reponses.destroy',$reponse->id) }}">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    </div>
</div>
@endforeach

{{-- ===================== TÉLÉGRAMMES ===================== --}}
<div class="telegrames">
    <h3 class="mt-4 mb-3">
        <i class="fas fa-paper-plane"></i> Télégrammes en attente ({{ $telegrammesEnAttente->total() ?? $telegrammesEnAttente->count() }})
    </h3>

    <div class="table-container table-responsive">
    <table class="table table-hover align-middle">
<thead>
<tr>
    <th>#</th>
    <th>Réf</th>
    <th>Service</th>
    <th class="hide-mobile">Date</th>
    <th class="hide-mobile">Résumé</th>
    <th class="hide-mobile">Expéditeur</th>
    <th>Délai</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@forelse($telegrammesEnAttente as $telegramme)
<tr class="{{ $telegramme->isLate ? 'table-danger' : 'table-success' }}">
    <td>{{ $telegramme->numero_enregistrement }}</td>
    <td>{{ $telegramme->numero_reference }}</td>

    <td class="wrap">
        @foreach($telegramme->services_affiches as $svc)
            <span class="badge bg-info text-dark me-1">{{ $svc }}</span>
        @endforeach
    </td>

    <td class="hide-mobile">
        {{ $telegramme->created_at->translatedFormat('d/m/Y H:i') }}
    </td>

    <td class="hide-mobile wrap resume">{{ $telegramme->commentaires }}</td>
    <td class="hide-mobile">{{ $telegramme->observation }}</td>

        <td class="time status">
        @if($telegramme->statutLabel === 'EN RETARD')
            <span class="badge badge-late">
                EN RETARD
                <br>
                <small>LIMITE {{ $telegramme->dateLimite }}</small>
            </span>

        @elseif($telegramme->statutLabel === 'EN ATTENTE')
            <span class="badge bg-warning text-dark">
                EN ATTENTE
                <br>
                <small>LIMITE {{ $telegramme->dateLimite }}</small>
            </span>

        @elseif($telegramme->statutLabel === 'EN COURS')
            <span class="badge bg-info">
                EN COURS
                <br>
                <small>LIMITE {{ $telegramme->dateLimite }}</small>
            </span>

        @else
            <span class="badge badge-on-time">
                DANS LE DÉLAI
                <br>
                <small>LIMITE {{ $telegramme->dateLimite }}</small>
            </span>
        @endif
    </td>

    <td class="actions-cell">
        <a class="btn btn-outline-info btn-sm"
           href="{{ route('telegramme.show',$telegramme->id) }}">
            <i class="fas fa-eye"></i>
        </a>
        <a class="btn btn-outline-primary btn-sm"
           href="{{ route('reponses.create',['telegramme_id'=>$telegramme->id]) }}">
            <i class="fas fa-reply"></i>
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center">Aucun télégramme</td>
</tr>
@endforelse
</tbody>
</table>
</div>

@else
{{-- CODE ACCÈS --}}
<div class="mx-auto" style="max-width:500px">
<div class="card">
<div class="card-header bg-primary text-white text-center">
    Code d’accès
</div>
<div class="card-body">
<form method="POST" action="{{ route('code.verifier') }}">
@csrf
<input class="form-control mb-3" name="code" required>
<button class="btn btn-primary w-100">Valider</button>
</form>
</div>
</div>
</div>
@endif

</div>
@endsection
