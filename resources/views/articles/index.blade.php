@extends('layouts.app')

@section('title', 'Articles et nos fonctionnalités | GIC')

@section('meta')
    <meta name="description" content="Découvrez toutes les fonctionnalités avancées de GIC pour la gestion de vos courriers, utilisateurs, documents et ventes.">
    <meta name="keywords" content="GIC, fonctionnalités, gestion courrier, archivage, signature numérique, gestion utilisateurs, stock, vente">
    
    {{-- Open Graph (Facebook, LinkedIn) --}}
    <meta property="og:title" content="Articles et fonctionnalités | GIC">
    <meta property="og:description" content="Explorez les fonctionnalités puissantes de GIC pour optimiser votre gestion quotidienne.">
    <meta property="og:image" content="https://172.233.244.133.nip.io/image/HERO.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Articles et fonctionnalités | GIC">
    <meta name="twitter:description" content="Explorez les outils performants de GIC pour la gestion du courrier, des documents, utilisateurs, ventes et plus.">
    <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/HERO.jpg">
    <meta name="twitter:site" content="@GIC_Officiel">
@endsection

@section('content')

<!-- Début du Hero Section -->
<div class="scroll-animated container-fluid d-flex justify-content-center align-items-center text-center text-white" style="
    width: 100%;
    height: 500px;
    padding: 60px 20px;
    background: linear-gradient(135deg, rgba(0, 31, 63, 0.8), rgba(0, 51, 102, 0.8)), url('image/HERO.jpg') no-repeat center center;
    background-size: cover;
">
    <div class="container">
        <div class="mx-auto" style="max-width: 800px;">
            <h1 class="scroll-animated mb-4 fw-bold" style="font-size: 3rem;">Fonctionnalités GIC</h1>
            <h4 class="scroll-animated mb-3 fst-italic">“Optimisez vos processus, simplifiez votre gestion”</h4>
            <p class="scroll-animated mb-4" style="font-size: 1.1rem;">
                GIC propose des outils puissants pour centraliser, automatiser et sécuriser la gestion de vos activités.
                Découvrez nos modules pour la gestion du courrier, des documents, de la messagerie, de la signature numérique, et plus.
            </p>
            <div class="scroll-animated d-flex justify-content-center gap-3 flex-wrap">
                <a href="inscription" class="btn btn-outline-light text-uppercase px-4 py-2">Essai gratuit</a>
            </div>
        </div>
    </div>
</div>
<!-- Fin du Hero Section -->


<section style="width: 100%; background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="container text-center text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Articles et nos fonctionnalités</h2>
        <p class="mb-5" style="font-size: 18px;">Explorez les outils puissants de GIC pour votre entreprise</p>

        <div class="row g-4 justify-content-center">
            @php
                $articles = [
                    ['title' => 'Gestion de Courriers', 'slug' => 'gestion-courriers', 'image' => 'gestion_courrier.jpg', 'description' => 'Enregistrez facilement vos courriers entrants et sortants, suivez leur statut en temps réel. Cette fonctionnalité permet une gestion rapide et efficace des documents.'],
                    ['title' => 'Archivage Intelligent', 'slug' => 'archivage-intelligent', 'image' => 'Archivage_Intelligent.jpg', 'description' => 'Indexation automatique, recherche rapide, accès cloud sécurisé à vos documents. L’archivage intelligent facilite l’organisation et la récupération de vos fichiers en toute sécurité.'],
                    ['title' => 'Messagerie Sécurisée', 'slug' => 'messagerie-securisee', 'image' => 'Archivage_IntelligentOK.jpg', 'description' => 'Communiquez en toute confidentialité grâce à une messagerie interne chiffrée, garantissant la sécurité de vos échanges professionnels.'],
                    ['title' => 'Gestion des Utilisateurs', 'slug' => 'gestion-utilisateurs', 'image' => 'Gestion-des-Utilisateurs.jpg', 'description' => 'Contrôle des accès, rôles définis, audit des actions pour une gestion sécurisée des utilisateurs et de leurs autorisations au sein de la plateforme.'],
                    ['title' => 'Signature Numérique', 'slug' => 'signature-numerique', 'image' => 'Signature-Numerique.jpg', 'description' => 'Signez vos documents en ligne avec une validité juridique et conforme RGPD, garantissant l’authenticité et la sécurité des échanges numériques.'],
                    ['title' => 'Gestion de Stock & Vente', 'slug' => 'gestion-stock-vente', 'image' => 'Gestion_de_Stock_Vente.jpg', 'description' => 'Suivi des stocks, gestion des ventes, facturation automatisée dans un seul outil, permettant d’optimiser la gestion commerciale de votre entreprise.'],
                ];
            @endphp

            @foreach ($articles as $article)
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="sci-card h-100 bg-dark text-white shadow-lg d-flex flex-column align-items-center justify-content-between">
                        <div class="card-body text-center d-flex flex-column align-items-center justify-content-between p-4">
                            <h4 class="mb-3">{{ $article['title'] }}</h4>
                            <img src="{{ asset('image/' . $article['image']) }}" alt="{{ $article['title'] }}" class="img-fluid mb-3 rounded" style="max-width: 100%; height: auto;">
                            <p class="mb-3" style="font-size: 16px;">{{ \Str::limit($article['description'], 100) }}</p>
                            <a href="{{ route('articles.show', $article['slug']) }}" class="btn btn-primary mt-auto">Voir Détail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
