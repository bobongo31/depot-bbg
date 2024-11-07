<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fonds de promotion culturelle')</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @livewireStyles

    <style>
        /* CSS pour la couleur de fond de la navbar */
        .navbar-custom {
            background-color: #bc8d0d; /* Changez cette couleur selon vos préférences */
        }
        .navbar-brand img {
            width: 30px; /* Ajustez la taille de l'icône selon vos besoins */
            margin-right: 8px; /* Espacement entre l'icône et le texte */
            vertical-align: middle; /* Aligne verticalement l'icône avec le texte */
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light navbar-custom shadow-sm">
    <div class="container">
        <a class="navbar-brand font-weight-bold" href="#">
            <img src="{{ asset('images/icone.jpg') }}" alt="Icône"> LogMob
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Gestion des redevables</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('web.paiements.index') }}">Contact</a>
                </li>
                <!-- Ajoutez d'autres liens si nécessaire -->
            </ul>
        </div>
    </div>
</nav>

    <div class="container mt-4">
        <div id="app">
            @yield('content') <!-- Contenu spécifique à chaque page -->
        </div>
    </div>

    <footer style="background-color: #3f7db6; color: #f1f1f1;" class="text-center text-lg-start mt-4">
    <div class="text-center p-3">
        <p>&copy; 2024 LogMob - Tous droits réservés.</p>
        <p>Design par Keynsoft | Contact : +243 970 712 280</p>
    </div>
    </footer>
    @livewireScripts
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/js/app.js'])
</body>
</html>
