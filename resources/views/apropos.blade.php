@extends('layouts.app')

@section('meta')
    <title>Entreprise Informatique à Kinshasa - Solutions Numériques Depuis 2017</title>
    <meta name="description" content="Notre entreprise fournit des solutions technologiques innovantes à Kinshasa depuis 2017. Découvrez nos services en transformation digitale, gestion documentaire, communication et plus.">
    <meta name="keywords" content="Entreprise informatique, Kinshasa, transformation digitale, services IT, innovation numérique, gestion documentaire, automatisation, équipe IT, solutions sur mesure">
    <meta name="author" content="Votre Entreprise">
    <meta name="robots" content="index, follow">

    <!-- Open Graph pour Facebook -->
    <meta property="og:title" content="Entreprise Informatique à Kinshasa - Depuis 2017">
    <meta property="og:description" content="Des solutions numériques pour votre succès. Découvrez comment notre expertise peut transformer votre entreprise.">
    <meta property="og:image" content="{{ asset('image/Image_hero.JPG') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Entreprise Informatique à Kinshasa">
    <meta name="twitter:description" content="Depuis 2017, nous fournissons des solutions numériques innovantes à Kinshasa.">
    <meta name="twitter:image" content="{{ asset('image/Image_hero.JPG') }}">

    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- GraphQL Endpoint (optionnel si utilisé) -->
    @if(config('app.graphql'))
        <meta name="graphql-endpoint" content="{{ url('/graphql') }}">
    @endif
@endsection

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<!-- Début du Hero Section -->
<div class="scroll-animated container-fluid py-5 position-relative" style="background: linear-gradient(135deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.4)), url('src=image/Image_hero.JPG') no-repeat center center; background-size: cover;">
    <div class="container position-relative">
        <div class="row align-items-center g-5">
            
            <!-- Texte à gauche -->
            <div class="scroll-animated col-lg-6 text-white">
                <h1 class="mb-4" style="font-weight: 700; font-size: 3rem;">Notre entreprise, <br>Au service de votre succès.</h1>
                <h4 class="mb-3 fst-italic">“Des solutions sur mesure pour chaque défi technologique”</h4>
                <p class="mb-4" style="font-size: 1.1rem;">
                    Depuis 2017, nous sommes engagés à fournir des solutions qui aident nos clients à naviguer dans l'univers numérique avec succès. Notre expertise repose sur l'innovation et la qualité, pour des résultats tangibles.<br><br>
                    Découvrez comment nous avons transformé la gestion documentaire, simplifié les processus et offert des solutions de communication et de productivité adaptées à vos besoins.
                </p>
                <div class="scroll-animated d-flex gap-3">
                    <a href="https://wa.me/243897604018" class="btn btn-light text-primary text-uppercase px-4 py-2">Demander une démo</a>
                    <a href="inscription" class="btn btn-outline-light text-uppercase px-4 py-2">Essai gratuit, sans carte bancaire</a>
                </div>
            </div>

            <!-- Image à droite avec effet de survol -->
            <div class="scroll-animated col-lg-6 text-center">
                <img src="image/Image_hero.JPG" alt="Illustration Entreprise" class="img-fluid hover-zoom" style="max-width: 100%; height: auto; border-radius: 8px;">
            </div>
            
        </div>
    </div>
</div>
<!-- Fin du Hero Section -->

<!-- Section À propos -->
<section style="width: 100%; background: linear-gradient(135deg, rgba(0, 51, 102, 0.9), rgba(0, 102, 204, 0.8), rgba(0, 0, 0, 0.9)), url('path/to/your/background-image.jpg'); background-size: cover; background-position: center; padding: 60px 15px;">
    <div class="scroll-animated container-fluid py-5 container position-relative">
       <h2 class="text-center section-title text-white mb-4"><strong>Qui sommes-nous ?</strong></h2>
        <p class="lead text-center text-white mb-3"><strong>Depuis 2017</strong>, notre entreprise informatique basée à Kinshasa s'est consacrée à fournir des solutions sur mesure aux défis technologiques auxquels font face les entreprises et organisations de toutes tailles.</p>
        <p class="text-center text-white mb-5"><strong>Notre mission est double :</strong></p>

        <div class="row justify-content-center">
            <div class="scroll-animated col-lg-5 col-md-6 mb-4 px-3">
                <div class="card h-100" style="background-color: rgba(0, 0, 0, 0.75); border: 1px solid rgba(0, 204, 255, 0.6); border-radius: 15px;">
                    <div class="card-body text-white">
                        <i class="fas fa-user-shield icon-feature mb-3" style="font-size: 2em; color: #00ccff;"></i>
                        <h3>1. Améliorer votre image de marque</h3>
                        <p>Renforcez la confiance et l'engagement de vos clients, fournisseurs et investisseurs grâce à une présence numérique professionnelle et efficace. Bénéficiez d'une communication claire et transparente qui met en valeur vos valeurs et vos réalisations.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 col-md-6 mb-4 px-3">
                <div class="card h-100" style="background-color: rgba(0, 0, 0, 0.75); border: 1px solid rgba(0, 204, 255, 0.6); border-radius: 15px;">
                    <div class="scroll-animated card-body text-white">
                        <i class="fas fa-cogs icon-feature mb-3" style="font-size: 2em; color: #00ccff;"></i>
                        <h3>2. Optimiser vos opérations</h3>
                        <p>Automatisez vos processus pour gagner en productivité, réduire les coûts et améliorer la satisfaction des employés. Profitez de solutions numériques innovantes pour simplifier vos tâches quotidiennes et optimiser le fonctionnement de votre organisation.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Fin Section À propos -->

 <!-- Section Pourquoi nous choisir -->
<section style="width: 100%; background: linear-gradient(135deg, #001F3F, #001C33); padding: 60px 20px;">
    <div class="scroll-animated container text-center text-white">
        <h2 class="section-title mb-5" style="font-weight: bold; color: #00ccff;">Pourquoi nous choisir ?</h2>
        <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="sci-card h-100">
                    <div class="scroll-animated card-body text-center">
                        <i class="fas fa-handshake sci-icon mb-3"></i>
                        <h4 class="text-white">Tarif Équitable</h4>
                        <p>Nous offrons un rapport qualité-prix exceptionnel.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="sci-card h-100">
                    <div class="scroll-animated card-body text-center">
                        <i class="fas fa-users sci-icon mb-3"></i>
                        <h4 class="text-white">Équipe Expérimentée</h4>
                        <p>Des experts avec des années d'expérience dans leur domaine.</p>
                    </div>
                </div>
            </div>
            <div class="scroll-animated col-lg-4 col-md-6 mb-4">
                <div class="sci-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-trophy sci-icon mb-3"></i>
                        <h4 class="text-white">Service & Qualité</h4>
                        <p>Nous nous engageons à fournir des solutions de haute qualité.</p>
                    </div>
                </div>
            </div>
        </div>
        <ul class="list-inline text-white mt-4">
            <li class="list-inline-item mx-3"><strong>+7 ans d'expérience</strong></li>
            <li class="list-inline-item mx-3"><strong>+50 clients fidèles</strong></li>
            <li class="list-inline-item mx-3"><strong>+1000 projets réalisés</strong></li>
        </ul>
    </div>
</section>


<!-- Section Notre Équipe (Version Moderne Sci-Fi) -->
<section style="width: 100%; background: linear-gradient(135deg, #0d0f1c, #1a1d35); padding: 60px 20px;">
    <div class="scroll-animated container text-center text-white">
        <h2 class="section-title position-relative pb-3 mb-5 mx-auto" style="font-weight: bold; color: #00ccff;">Notre Équipe</h2>
        <p class="mb-5" style="color: #cccccc;">Une équipe jeune, agile, dynamique et innovante, des profils complémentaires et variés pour répondre à vos besoins.</p>

        <div class="row">
            <!-- Membre d'équipe -->
            <div class="scroll-animated col-md-4 mb-4">
                <div class="team-card p-4 rounded-4 shadow" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                <!-- Icône silhouette -->
                        <div class="mb-3">
                            <i class="fas fa-user-circle" style="font-size: 100px; color: #00ccff;"></i>
                        </div>
                    <h5 class="text-white">Rodrique Matumona</h5>
                    <p style="color: #cccccc;">Marketeur</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <!-- Répétez pour chaque membre -->
            <div class="scroll-animated col-md-4 mb-4">
                <div class="team-card p-4 rounded-4 shadow" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                <!-- Icône silhouette -->
                        <div class="mb-3">
                            <i class="fas fa-user-circle" style="font-size: 100px; color: #00ccff;"></i>
                        </div>
                        <h5 class="text-white">Joel Mpibi</h5>
                    <p style="color: #cccccc;">Marketeur</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>
            </div>

            <!-- Autres membres -->
            <div class="scroll-animated col-md-4 mb-4">
                <div class="team-card p-4 rounded-4 shadow" style="background: rgba(255,255,255,0.05); backdrop-filter: blur(10px);">
                <!-- Icône silhouette -->
                        <div class="mb-3">
                            <i class="fas fa-user-circle" style="font-size: 100px; color: #00ccff;"></i>
                        </div>                    
                        <h5 class="text-white">Exauce Bolumbe</h5>
                    <p style="color: #cccccc;">Développeur Web</p>
                    <div class="social-icons mt-3">
                        <a href="#" class="text-white mx-2"><i class="fab fa-github"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <!-- Ajoutez d'autres membres selon le même modèle -->
        </div>
    </div>
</section>


<!-- Section centrée -->
<div class="d-flex justify-content-center my-5">
    <div class="team-card sci-fi-card p-4 text-white text-center position-relative">
        <!-- Icône téléphone fixe -->
        <div class="mb-3">
            <i class="fas fa-phone-volume" style="font-size: 100px; color: #00ccff;"></i>
        </div>
        <h5>Service Client</h5>
        <p class="fs-5" style="color: #00ccff;"><strong>0897 60 40 18</strong></p>
        <div class="d-flex align-items-center justify-content-center">
            <i class="fas fa-headset me-2" style="font-size: 20px; color: #00ccff;"></i>
            <p class="mb-0" style="color: #cccccc;">Disponible 24h/24 et 7j/7</p>
        </div>
        <!-- Réseaux sociaux -->
        <div class="social-icons mt-3">
            <a href="#"><i class="fab fa-facebook-f mx-2"></i></a>
            <a href="#"><i class="fab fa-linkedin-in mx-2"></i></a>
            <a href="#"><i class="fab fa-twitter mx-2"></i></a>
        </div>
    </div>
</div>

<!-- Style Science-Fiction -->

@endsection
