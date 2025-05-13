@extends('layouts.app')

@section('meta')
    <title>Nos Services - Entreprise Informatique à Kinshasa</title>
    <meta name="description" content="Découvrez en détail nos services : transformation digitale, gestion documentaire, communication numérique, automatisation et bien plus.">
    <meta name="keywords" content="Services informatiques, transformation digitale, automatisation, gestion documentaire, entreprise Kinshasa, IT, communication, numérique">
    <meta name="author" content="Votre Entreprise">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Nos Services - Solutions Numériques à Kinshasa">
    <meta property="og:description" content="Des solutions informatiques sur mesure pour faire croître votre entreprise.">
    <meta property="og:image" content="https://172.233.244.133.nip.io/image/Image_hero.JPG">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Nos Services - Entreprise Informatique à Kinshasa">
    <meta name="twitter:description" content="Découvrez comment nos services numériques transforment votre entreprise.">
    <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/Image_hero.JPG">
    <link rel="canonical" href="{{ url()->current() }}">
@endsection

@section('content')

<!-- HERO -->
<section style="width: 100%; padding: 60px 20px; background: linear-gradient(135deg, rgba(0, 31, 63, 0.8), rgba(0, 51, 102, 0.8)), url('image/Image_hero.JPG'); background-size: cover; background-position: center;">
    <div class="container text-center text-white" data-aos="fade-down">
        <h1 class="display-4 mb-4 fw-bold">Nos Services</h1>
        <p class="lead mb-0">Des solutions numériques pour vous faire gagner en efficacité, visibilité et rentabilité.</p>
    </div>
</section>

<!-- Services -->
@php
    $services = [
        [
            'icon' => 'fas fa-digital-tachograph',
            'title' => 'Transformation Digitale',
            'desc' => 'Nous accompagnons votre entreprise dans sa transition numérique avec des outils modernes et adaptés.',
            'items' => [
                'Audit de maturité numérique',
                'Implémentation de solutions cloud',
                'Formation de votre personnel'
            ],
            'gradient' => 'linear-gradient(135deg, #0e2f44, #11384f)'
        ],
        [
            'icon' => 'fas fa-folder-open',
            'title' => 'Gestion Documentaire',
            'desc' => 'Digitalisation et gestion électronique des documents (GED) pour un accès rapide, structuré et sécurisé à vos données.',
            'items' => [
                'Numérisation des archives',
                'Accès sécurisé en ligne',
                'Conformité RGPD et ISO'
            ],
            'gradient' => 'linear-gradient(135deg, #142c40, #1e3e5a)'
        ],
        [
            'icon' => 'fas fa-bullhorn',
            'title' => 'Communication Digitale',
            'desc' => 'Valorisez votre marque via le branding, les réseaux sociaux, la stratégie de contenu et la publicité digitale.',
            'items' => [
                'Design graphique & branding',
                'Community Management',
                'Campagnes Facebook & Google'
            ],
            'gradient' => 'linear-gradient(135deg, #1d4057, #27506a)'
        ],
        [
            'icon' => 'fas fa-robot',
            'title' => 'Automatisation des Processus',
            'desc' => 'Nous développons des outils d’automatisation pour réduire les tâches répétitives et améliorer la performance.',
            'items' => [
                'Scripts & logiciels automatisés',
                'Workflows personnalisés',
                'Intégration aux outils existants'
            ],
            'gradient' => 'linear-gradient(135deg, #22364b, #2c4b64)'
        ],
        [
            'icon' => 'fas fa-user-cog',
            'title' => 'Assistance & Maintenance',
            'desc' => 'Support informatique réactif, maintenance préventive et curative pour garantir la continuité de vos services.',
            'items' => [
                'Support à distance & sur site',
                'Mises à jour & sécurité',
                'Contrats adaptés à vos besoins'
            ],
            'gradient' => 'linear-gradient(135deg, #294b63, #336178)'
        ],
        [
            'icon' => 'fas fa-cloud',
            'title' => 'Solutions Cloud',
            'desc' => 'Accédez à vos ressources à tout moment grâce à nos services d’hébergement, de sauvegarde et d’infrastructure cloud.',
            'items' => [
                'Hébergement Web & Emails Pro',
                'Sauvegardes en ligne',
                'Cloud privé ou public'
            ],
            'gradient' => 'linear-gradient(135deg, #315c76, #3c7490)'
        ]
    ];
@endphp

@foreach($services as $index => $service)
<section style="background: {{ $service['gradient'] }}; padding: 80px 20px; margin-bottom: 20px;">
    <div class="container text-white text-center" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
        <i class="{{ $service['icon'] }} mb-4" style="font-size: 80px; color: #00ccff;"></i>
        <h2 class="mb-3 fw-bold">{{ $service['title'] }}</h2>
        <p class="mb-4">{{ $service['desc'] }}</p>
        <ul class="list-unstyled">
            @foreach($service['items'] as $item)
                <li>✅ {{ $item }}</li>
            @endforeach
        </ul>
    </div>
</section>
@endforeach

<!-- Call to Action -->
<section class="text-white text-center py-5" style="background: linear-gradient(135deg, #003366, #001f3f);">
    <div class="container" data-aos="zoom-in">
        <h2 class="mb-4">Besoin d’une solution sur mesure ?</h2>
        <p class="lead mb-4">Contactez-nous dès aujourd’hui pour une consultation gratuite ou une démonstration personnalisée.</p>
        <a href="https://wa.me/243897604018" class="btn btn-light text-primary px-4 py-2">Nous Contacter sur WhatsApp</a>
    </div>
</section>

@endsection
