@extends('layouts.app')

@section('content')
<style>
    body {
        background-image: url('{{ asset('images/fpc.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
        background-attachment: fixed;
        color: white;
    }

    .card {
        background-color: rgba(0, 0, 255, 0.7);  /* Bleu */
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #1b7bdc;
        color: white;
    }

    .badge {
        font-size: 0.8rem;
    }

    .btn-modifier {
        font-size: 14px;
        padding: 5px 15px;
    }
</style>

<div class="container mt-5">
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <h1 class="display-4" style="color: #3498db;">Tableau de Bord</h1>
    <p class="lead" style="color: #2ecc71;">Visualisez et gérez vos paiements.</p>

    <div class="text-left mb-3">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Déconnexion</button>
        </form>
    </div>

    <div class="mb-3">
        <a href="{{ route('web.clients.index') }}" class="btn btn-primary btn-sm">Gérer les redevables</a>
        @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('payment_validator'))
            <a href="{{ route('web.paiements.index') }}" class="btn btn-success btn-sm">Gérer les paiements</a>
        @else
            <p class="text-danger">Vous n'avez pas les permissions nécessaires pour gérer les paiements.</p>
        @endif
    </div>

    <div class="mb-3">
        <!-- Bouton de tri -->
        <form method="GET" action="{{ route('web.paiements.index') }}" class="d-inline">
            <select name="sort" onchange="this.form.submit()" class="form-control form-control-sm d-inline" style="width: auto;">
                <option value="">Trier par</option>
                <option value="date_paiement" {{ request('sort') == 'date_paiement' ? 'selected' : '' }}>Date de Paiement</option>
                <option value="status" {{ request('sort') == 'status' ? 'selected' : '' }}>Statut</option>
            </select>
        </form>
    </div>

    @if (!auth()->user()->hasRole('read_write'))
        <div class="row">
            @foreach ($paiements as $paiement)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">{{ $paiement->client->nom_redevable }}</h5>
                            <a href="{{ route('web.paiements.edit', $paiement->id) }}" class="btn btn-warning btn-modifier">Modifier</a>
                        </div>
                        <div class="card-body">
                            <p><strong>Matière Taxable:</strong> {{ $paiement->matiere_taxable }}</p>
                            <p><strong>Prix à Payer:</strong> {{ number_format($paiement->prix_a_payer) }} €</p>
                            <p><strong>Date de Paiement:</strong> {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</p>
                            <p><strong>Statut:</strong> 
                                <span class="badge 
                                    @if ($paiement->status === 'validé') badge-success
                                    @elseif ($paiement->status === 'rejeté') badge-danger
                                    @elseif ($paiement->status === 'en entente') badge-warning
                                    @else badge-secondary
                                    @endif">
                                    {{ ucfirst($paiement->status) }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $paiements->links() }}
        </div>
    @else
        <p class="text-warning">Les détails des paiements ne sont pas accessibles pour votre rôle.</p>
    @endif
</div>
@endsection
