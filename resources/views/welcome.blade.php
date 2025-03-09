@extends('layouts.app') <!-- Utilise la vue de base app.blade.php -->

@section('content')
    <div class="container text-center">
        <h1>Bienvenue sur l'application !</h1>
        
        @if (Auth::check()) <!-- Vérifie si l'utilisateur est authentifié -->
            <p>Bonjour, {{ Auth::user()->name }} !</p> <!-- Affiche le nom de l'utilisateur -->
        @else
            <p>Nous sommes heureux de vous accueillir. Profitez de notre service de gestion de courriers.</p>
        @endif
    </div>
@endsection
