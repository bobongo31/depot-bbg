@extends('layouts.app')

@php
use Carbon\Carbon;
@endphp

@section('content')
<div class="container py-4">
    <h2 class="scroll-animated text-center mb-5"><i class="fas fa-users"></i> Liste des Inscrits</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
@if($utilisateurs->isEmpty()) 
    <p class="text-center">Aucun inscrit pour le moment.</p>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        @foreach($utilisateurs as $utilisateur)
            <div class="col">
                <div class="card shadow-sm h-100 border-0 rounded-4 scroll-animated hover-zoom"
                     data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                    <div class="card-body text-white" style="background: linear-gradient(135deg, #4e54c8, #8f94fb); border-radius: 1rem;">
                        <h5 class="card-title">
                            <i class="fas fa-user"></i>
                            {{ $utilisateur->first_name }} {{ $utilisateur->last_name }} <br>
                            <small class="text-white-50">ID: {{ $utilisateur->id }}</small>
                        </h5>
                        <p class="card-text mb-2">
                            <i class="fas fa-building"></i>
                            <strong>Entreprise :</strong> {{ $utilisateur->company_name }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $utilisateur->city }}, {{ $utilisateur->province }}, {{ $utilisateur->country }} - {{ $utilisateur->postal_code }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-envelope"></i>
                            {{ $utilisateur->email }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-phone"></i>
                            {{ $utilisateur->phone }}
                        </p>
                    </div>
                    <div class="card-footer bg-light text-end">
                        <small class="text-muted">
                            <i class="fas fa-calendar-alt"></i>
                            Inscrit le {{ \Carbon\Carbon::parse($utilisateur->created_at)->format('d/m/Y H:i') }}
                        </small>

                        <!-- Bouton de validation -->
                        <form method="POST" action="{{ route('utilisateur.valider', $utilisateur->id) }}" class="mt-3" onsubmit="return confirmValidation()">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle"></i> Valider l'inscription
                            </button>
                        </form>

                        <!-- Bouton de suppression -->
                        <form method="POST" action="{{ route('utilisateur.supprimer', $utilisateur->id) }}" class="mt-3" onsubmit="return confirmDelete()">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
{{-- SECTION DES UTILISATEURS VALIDÉS --}}
<hr class="my-5">
<h2 class="scroll-animated text-center mb-5"><i class="fas fa-user-check"></i> Utilisateurs Validés</h2>

@if($users->isEmpty())
    <p class="text-center">Aucun utilisateur validé pour le moment.</p>
@else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
        @foreach($users as $user)
            <div class="col">
                <div class="card shadow-sm h-100 border-0 rounded-4 scroll-animated hover-zoom"
                     data-aos="fade-up" data-aos-duration="1000" data-aos-once="true">
                    <div class="card-body text-white" style="background: linear-gradient(135deg, #38ef7d, #11998e); border-radius: 1rem;">
                        <h5 class="card-title">
                            <i class="fas fa-user-check"></i>
                            {{ $user->name }} <br>
                            <small class="text-white-50">ID: {{ $user->id }}</small>
                        </h5>
                        <p class="card-text mb-2">
                            <i class="fas fa-envelope"></i>
                            {{ $user->email }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-phone"></i>
                            {{ $user->phone }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-building"></i>
                            <strong>Entreprise :</strong> {{ $user->entreprise }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-calendar-alt"></i>
                            Créé le {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}
                        </p>

                        @php
                            $isExpired = $user->abonnement_expires_at ? \Carbon\Carbon::parse($user->abonnement_expires_at)->lt(now()) : false;
                        @endphp
                        <p class="card-text mb-2">
                            <i class="fas fa-clock"></i>
                            <strong>Abonnement jusqu'au :</strong>
                            {{ $user->abonnement_expires_at ? \Carbon\Carbon::parse($user->abonnement_expires_at)->format('d/m/Y H:i') : 'Non défini' }}
                        </p>
                        <p class="card-text mb-2">
                            <i class="fas fa-bell"></i>
                            <strong>Statut de l’abonnement :</strong>
                            @if(is_null($user->abonnement_expires_at))
                                <span class="badge bg-primary">À vie</span>
                            @elseif($isExpired)
                                <span class="badge bg-danger">Expiré</span>
                            @else
                                <span class="badge bg-success">Valide</span>
                            @endif
                        </p>
                    </div>
                    <div class="card-footer bg-light text-end">
                        <form method="POST" action="{{ route('user.delete', $user->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection

@section('scripts')
<script>
    function confirmDelete() {
        return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');
    }

    function confirmValidation() {
        return confirm('Êtes-vous sûr de vouloir valider cette inscription ?');
    }
</script>
@endsection
