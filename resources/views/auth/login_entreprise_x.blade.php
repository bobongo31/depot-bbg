@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Connexion - Entreprise X</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Erreur !</strong><br>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/login/entreprise-x') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" name="email" class="form-control" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Connexion</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
