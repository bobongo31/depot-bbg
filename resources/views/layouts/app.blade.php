<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google-site-verification" content="F4V5-90SAdqL2N6PFPeoscwMqvFKIMHWT5mF3fssm1A" />

  {{-- Canonical URL pour le référencement --}}
  <link rel="canonical" href="{{ url()->current() }}">

  @hasSection('meta')
      @yield('meta')
  @else
      <!-- Balises SEO par défaut -->
      <meta name="description" content="GIC est une solution tout-en-un pour PME : gestion des ventes, stocks, facturation, bons de commande, livraisons, courriers, et archivage électronique sécurisé. Simple, rapide et efficace.">
      <meta name="keywords" content="GIC, gestion PME, facturation, gestion de stock, vente, archivage électronique, bon de commande, gestion électronique de courrier, archivage numérique, application entreprise, ERP, solution PME">
      <meta name="author" content="Équipe GIC">
      <meta name="openingHours" content="Mo-Su 00:00-23:59">
      <meta name="telephone" content="+243897604018">
      <meta name="website" content="https://keynsoft.tech">
      <meta name="address" content="15, AV. Lutendele, Mont-Ngafula, Kinshasa, RDC">

      <!-- Open Graph -->
      <meta property="og:type" content="website">
      <meta property="og:title" content="GIC – Solution de gestion intelligente pour PME">
      <meta property="og:description" content="Simplifiez la gestion de votre entreprise avec GIC : ventes, stocks, factures, courriers, archivage numérique et plus.">
      <meta property="og:url" content="https://172.233.244.133.nip.io">
      <meta property="og:image" content="https://172.233.244.133.nip.io/image/SEO.jpg">

      <!-- Twitter -->
      <meta name="twitter:card" content="summary_large_image">
      <meta name="twitter:title" content="GIC – ERP pour PME : gestion et archivage intelligents">
      <meta name="twitter:description" content="Gérez votre PME avec efficacité : GIC regroupe vente, stock, facturation, gestion de courriers et archivage numérique.">
      <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/SEO.jpg">
  @endif

  <!-- Sécurité -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Titre -->
  <title>@yield('title', 'GIC – Gestion intelligente pour PME')</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('image/favicon.png') }}">

  <!-- Feuilles de style -->
  {{-- Using Vite/@env block below to load compiled app CSS/JS. Removed hardcoded hashed filenames that caused 404. --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <!-- Fonts & bibliothèques CSS -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

  <!-- JSON-LD Schema.org -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Keynsoft",
    "url": "https://keynsoft.tech",
    "telephone": "+243897604018",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "15, AV. Lutendele",
      "addressLocality": "Mont-Ngafula",
      "addressRegion": "Kinshasa",
      "addressCountry": "CD"
    },
    "openingHours": "Mo-Su 00:00-23:59"
  }
  </script>

  <!-- JavaScript Libraries -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.counterup/2.1.0/jquery.counterup.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/wow.js/1.1.2/wow.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Scripts compilés et personnalisés -->
  {{-- App JS is loaded via Vite in local env or from build/assets in production (see @env block below). --}}
  <script src="{{ asset('lib/wow/wow.min.js') }}"></script>
  <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
  <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
  <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
  <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('js/main.js') }}"></script>
<!-- Chargement conditionnel des assets Laravel Vite -->
  @env('local')
      {{-- 🔥 En développement : Vite avec rechargement à chaud --}}
      @vite(['resources/css/app.css', 'resources/js/app.js'])
  @else
      {{-- ✅ En production : Vite compilé --}}
      <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
      <script type="module" src="{{ asset('build/assets/app.js') }}"></script>
  @endenv

  <!-- Styles globaux et animations extraites -->
  <style>
    /* Thèmes clair et sombre */
    .light-theme {
      background-color: #ffffff;
      color: #333;
    }
    .dark-theme {
      background-color: #333;
      color: #ffffff;
    }

    .hover-scale {
        transition: transform 0.3s ease-in-out;
    }

    .hover-scale:hover {
        transform: scale(1.03);
      }

      .hover-scale {
      transition: transform 0.3s ease-in-out;
  }

  .hover-scale:hover {
      transform: scale(1.03);
  }

 header {
    display: block;
    overflow: hidden;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100px; /* Ajuste à ta hauteur réelle */
    background-color: #fff; /* ou autre couleur souhaitée */
    z-index: 999;
}



  body {
    padding-top: 10px; /* Ajustez selon la hauteur réelle */
}
main {
    padding-top: 30px;
}


 @media (max-width: 768px) {
        .card h3 {
            font-size: 1.2em;
        }
        .card p {
            font-size: 0.95em;
        }
    }

    @media (max-width: 576px) {
        .card {
            border-radius: 10px;
        }
        .icon-feature {
            font-size: 1.5em !important;
        }
    }

    .sci-card {
        background: linear-gradient(145deg, rgba(0, 204, 255, 0.1), rgba(0, 102, 204, 0.2));
        border: 1px solid rgba(0, 204, 255, 0.4);
        border-radius: 20px;
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .sci-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 0 20px rgba(0, 204, 255, 0.6);
    }

    .sci-icon {
        font-size: 2.5rem;
        color: #00ccff;
        text-shadow: 0 0 8px rgba(0, 204, 255, 0.6);
    }

    .sci-card p {
        color: #e0f7ff;
    }

    @media (max-width: 576px) {
        .sci-card {
            border-radius: 15px;
        }

        .sci-icon {
            font-size: 2rem;
        }
    }

    .team-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 16px;
    }

    .team-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 25px rgba(0, 204, 255, 0.2);
    }

    .team-card img {
        border: 3px solid #00ccff;
        transition: border-color 0.3s;
    }

    .team-card:hover img {
        border-color: #00ffff;
    }

    .social-icons a {
        font-size: 18px;
        color: #cccccc;
        transition: color 0.3s ease;
    }

    .social-icons a:hover {
        color: #00ccff;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .team-card {
            padding: 20px;
        }
    }

    .sci-fi-card {
    background: #111827;
    border: 2px solid #00ccff;
    border-radius: 15px;
    box-shadow: 0 0 15px #00ccff88;
    transition: transform 0.3s, box-shadow 0.3s;
    max-width: 350px;
}
.sci-fi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0 25px #00ccff;
}
.social-icons a {
    color: #00ccff;
    font-size: 20px;
    transition: color 0.3s;
}
.social-icons a:hover {
    color: #ffffff;
}

/* Effet de survol pour les cartes */
.hover-effect {
    transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.hover-effect:hover {
    transform: scale(1.05); /* Légère mise à l'échelle */
    box-shadow: 0 10px 15px rgba(0, 204, 255, 0.7); /* Ombre douce */
}

/* Assurez-vous que les cartes soient visibles même avec des fonds sombres */
.card {
    background-color: rgba(0, 0, 0, 0.85); /* Assurez-vous que la carte est suffisamment opaque */
    border: 1px solid rgba(0, 204, 255, 0.6); /* Bordure bleue claire */
    border-radius: 15px;
}

.card-body {
    color: white; /* Assurez-vous que le texte est visible */
}
    .feature-card {
        background: linear-gradient(135deg, #4A90E2, #5D9BFB);
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .feature-card .card-body {
        background: white;
        border-radius: 12px 12px 0 0;
        padding: 20px;
        color: #333;
    }

    .feature-card .card-body h3 {
        font-size: 1.5rem;
        font-weight: bold;
    }

    .feature-card .card-body p {
        font-size: 1rem;
        color: #555;
    }

    .feature-card img {
        border-radius: 0 0 12px 12px;
        max-width: 100%;
    }


    .blog-item {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease;
        position: relative;
    }

    .blog-item:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .hover-effect {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.2);
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .blog-item:hover .hover-effect {
        opacity: 1;
    }

     /* Optionnel : ajout d'une animation ou d'un effet supplémentaire */
     #contact .bg-white {
        transition: transform 0.3s ease-in-out, background-color 0.3s ease;
    }

    #contact .bg-white:hover {
        background-color: #f3f4f6;
        transform: scale(1.05);
    }
    
    
    
    .scroll-animated {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.scroll-animated.visible {
  opacity: 1;
  transform: translateY(0);
}


/* Effet de zoom sur l'image */
.hover-zoom {
  transition: transform 0.3s ease-in-out;
}

.hover-zoom:hover {
  transform: scale(1.1); /* Zoom léger lors du survol */
}
    /* --- Animation du Footer --- */
    /* Animation d'apparition */
    .footer-animated {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.8s ease-out;
    }
    .footer-animated.visible {
      opacity: 1;
      transform: translateY(0);
    }
    /* Animation au survol des liens du footer */
    .footer-link {
      position: relative;
      transition: color 0.3s ease;
    }
    .footer-link::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      display: block;
      margin-top: 4px;
      right: 0;
      background: #fff;
      transition: width 0.3s ease;
    }
    .footer-link:hover::after {
      width: 100%;
      left: 0;
    }
    .footer-link:hover {
      color: #f8f9fa;
    }
    /* Animation Zoom pour la transition de page */
  .zoom-in {
    transform: scale(0.95);
    opacity: 0;
    transition: all 0.6s ease-in-out;
  }

  .zoom-in-active {
    transform: scale(1);
    opacity: 1;
  }
  .custom-box {
    background-color: #f8f9fa;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
}
.chart-container {
    max-width: 100%;
    height: auto;
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 0.75rem;
}

canvas {
    min-height: 300px !important;
}


  
.menu-icon {
    transition: 
        transform 0.2s ease, 
        background-color 0.2s ease, 
        color 0.2s ease, 
        border-color 0.2s ease;
    border: 2px solid transparent;
    position: relative;
}

.menu-icon:hover {
    transform: scale(1.1);
    background-color: #0d6efd;
    color: #fff !important;
    border-color: #0d6efd;
}

/* État actif */
.menu-icon.active {
    background-color: #0d6efd;
    color: #fff !important;
    border-color: #0d6efd;
    transform: scale(1.1) translateX(5px); /* Zoom + décalage à droite */
}

 /* Superposition */
 .overlay-message {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(10, 30, 60, 0.7); /* Couleur de superposition personnalisée */
    display: flex; /* centre le message-box */
    align-items: center;
    justify-content: center;
    z-index: 100050; /* au-dessus de la plupart des éléments (whatsapp, header, etc.) */
    backdrop-filter: blur(4px); /* flou de fond optionnel */
    padding: 0; /* éviter bande noire créée par padding */
  }

  /* Contenu de la boîte */
  .overlay-message .message-box {
    background: white;
    color: #333;
    border-radius: 1rem;
    padding: 1.25rem 1.5rem; /* plus compact qu'avant */
    max-width: 520px;
    width: calc(100% - 2rem);
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    animation: fadeInScale 0.4s ease;
    position: relative;
    z-index: 100060; /* au-dessus de l'overlay */
  }

  /* ID overlay used ailleurs : assurer un comportement cohérent */
  #overlayMessage {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    display: none; /* contrôlé par JS */
    background-color: rgba(0, 0, 0, 0.6);
    z-index: 100050;
    justify-content: center;
    align-items: center;
    overflow-y: auto;
    margin: 0;
    padding: 0;
    -webkit-overflow-scrolling: touch;
}

/* Pour empêcher le scroll de la page */
body.modal-open-scrollblock {
  overflow: hidden !important;
}


  
  /* Animation d'apparition */
  @keyframes fadeInScale {
    from {
      opacity: 0;
      transform: scale(0.95);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

#whatsapp-btn {
  position: fixed !important; /* reste fixe peu importe le scroll */
  bottom: 30px;
  right: 30px;
  width: 60px;
  height: 60px;
  background-color: #25D366;
  border-radius: 50%;
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);
  cursor: pointer;
  animation: pulse 2s infinite;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999; /* au-dessus de presque tout */
}
#whatsapp-btn:hover {
  animation-play-state: paused;
}
@keyframes pulse {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.2); }
}
#whatsapp-btn svg {
  width: 32px;
  height: 32px;
  fill: white;
}

/* =========================
   NOTIFICATION BADGE (SAFE)
========================= */
.notification-icon {
    position: relative; /* ancrage du badge */
}

.notification-icon .notif-count-badge {
    position: absolute;
    top: -4px;
    right: -4px;

    min-width: 18px;
    height: 18px;
    padding: 0 5px;

    background-color: #dc3545; /* rouge Bootstrap */
    color: #ffffff;

    border-radius: 9999px;
    font-size: 11px;
    font-weight: 700;
    line-height: 18px;
    text-align: center;

    box-shadow: 0 0 0 2px #ffffff; /* lisible sur fond clair/sombre */
    pointer-events: none;
    z-index: 5;
}

/* =========================
   NOTIFICATION DROPDOWN FIX
========================= */
.notification-wrapper {
    position: relative;
}

.notification-dropdown {
    position: absolute;
    top: 38px;
    right: 0;

    width: 340px;
    max-width: calc(100vw - 20px); /* 👈 empêche le débordement */
    max-height: 420px;
    overflow-y: auto;

    transform-origin: top right;
}

/* Si l'écran est petit, on centre */
@media (max-width: 480px) {
    .notification-dropdown {
        right: auto;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100vw - 20px);
    }
}


  </style>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-13LEHFNS9X"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-13LEHFNS9X');
</script>
</head>


<body class="bg-gray-100 text-gray-900 {{ session('theme', 'light-theme') }}">

{{-- Messages de succès ou d'erreur --}}
@if (session('success'))
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
        </div>
    </div>
@endif

@if ($errors->any())
  <div class="container mt-4">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
    </div>
  </div>
@endif




<!-- Notification bell + dropdown -->
@auth
<div class="d-inline-block align-middle ms-2">
  <div class="notification-wrapper position-relative">

    <button id="notification-icon"
            class="btn btn-light notification-icon position-relative"
            type="button"
            aria-label="Notifications">
      <i class="fas fa-bell"></i>
      <!-- badge injecté en JS -->
    </button>

    <div id="notification-dropdown"
         class="card shadow-sm notification-dropdown"
         style="display:none; position:absolute; right:0; top:38px; z-index:2000; width:340px; max-height:420px; overflow:auto;">
      <div class="card-body p-2">
        <ul id="notification-list" class="list-unstyled mb-0 p-0"></ul>
      </div>
    </div>

  </div>
</div>
@endauth

@auth
<form action="{{ route('recherche.globale') }}" method="GET" class="d-flex mb-3">
    <input type="text" name="q" class="form-control me-2" placeholder="Rechercher..." required>
    <button class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
</form>
@endauth

  <!--<div id="app" class="flex min-h-screen flex-col">
     Inclusion du header contenant le menu 
    @include('header')

    <!-- Zone de contenu principal -->
    <main class="flex-grow p-6">
      @yield('content')
    </main>

    <!-- Inclusion du footer (le markup n'inclut plus le style et le script spécifiques) -->
    @include('footer')
  </div>

  @auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
  @endauth

  <!-- Scripts globaux -->
 <script>
/* ======================
   NOTIFICATIONS (FINAL – SAFE)
====================== */
(function ($) {
    'use strict';

    /* ----------------------
       CONFIG
    ---------------------- */
    const COUNT_DELAY    = 20000; // 20s après login
    const COUNT_INTERVAL = 60000;
    const LOGIN_TS_KEY   = 'login_ts';
    const csrfToken      = document.querySelector('meta[name="csrf-token"]')?.content || '';

    let dropdownOpen = false;

    /* ----------------------
       LOGIN TIMESTAMP
    ---------------------- */
    if (!localStorage.getItem(LOGIN_TS_KEY)) {
        localStorage.setItem(LOGIN_TS_KEY, Date.now());
    }

    function canFetchCount() {
        const ts = parseInt(localStorage.getItem(LOGIN_TS_KEY), 10);
        return ts && (Date.now() - ts >= COUNT_DELAY);
    }

    /* ----------------------
       BADGE (ANTI-CONFLIT)
    ---------------------- */
    function updateNotificationIcon(count) {
        const $icon = $('.notification-icon');

        // supprimer ancien badge
        $icon.find('.notif-count-badge').remove();

        if (!count || count <= 0) return;

        const display = count > 99 ? '99+' : count;

        $icon.append(
            `<span class="notif-count-badge">${display}</span>`
        );
    }

    /* ----------------------
       FETCH COUNT
    ---------------------- */
    async function fetchCount() {
        if (!canFetchCount()) return;
        if (dropdownOpen) return;

        try {
            const res = await fetch('/notifications/count', {
                credentials: 'same-origin'
            });
            if (!res.ok) return;

            const data = await res.json();
            updateNotificationIcon(data.count || 0);
        } catch {}
    }

    /* ----------------------
       FETCH LIST
    ---------------------- */
    async function fetchList() {
        try {
            const res = await fetch('/notifications/list', {
                credentials: 'same-origin'
            });
            if (!res.ok) throw new Error();

            const data = await res.json();
            const $list = $('#notification-list').empty();

            if (!data || data.length === 0) {
                $list.append(
                    '<li class="text-muted text-center p-3">Aucune notification</li>'
                );
                return;
            }

            data.forEach(n => {
                const title = n.type === 'message'
                    ? `${n.from ? n.from + ' : ' : ''}${n.content}`
                    : n.content;

                $list.append(`
                    <li class="notification-item mb-2 p-2 border-start border-3 border-primary bg-light rounded">
                        <div class="small text-muted">
                            ${new Date(n.created_at).toLocaleString()}
                        </div>
                        <div class="fw-semibold">
                            <a href="#" class="notif-link text-decoration-none text-dark"
                               data-id="${n.id}"
                               data-type="${n.type}"
                               data-url="${n.url}">
                                ${$('<div/>').text(title).html()}
                            </a>
                        </div>
                    </li>
                `);
            });
        } catch {
            $('#notification-list').html(
                '<li class="text-danger text-center p-3">Erreur de chargement</li>'
            );
        }
    }

    /* ----------------------
       MARK READ + REDIRECT
    ---------------------- */
    $(document).on('click', '.notif-link', async function (e) {
        e.preventDefault();
        const $a = $(this);

        try {
            await fetch('/notifications/read', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    id: $a.data('id'),
                    type: $a.data('type')
                })
            });
        } catch {}

        window.location.href = $a.data('url');
    });

    /* ----------------------
       DROPDOWN
    ---------------------- */
    $('#notification-dropdown').hide();

    $('#notification-icon').on('click', function (e) {
    e.preventDefault();

    const $dropdown = $('#notification-dropdown');
    $dropdown.toggle();

    dropdownOpen = $dropdown.is(':visible');

    if (dropdownOpen) {
        const rect = $dropdown[0].getBoundingClientRect();

        if (rect.left < 10) {
            $dropdown.css({
                left: '10px',
                right: 'auto'
            });
        }

        if (rect.right > window.innerWidth - 10) {
            $dropdown.css({
                right: '10px',
                left: 'auto'
            });
        }

        fetchList();
    }
});


    /* ----------------------
       CLICK OUTSIDE
    ---------------------- */
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#notification-icon, #notification-dropdown').length) {
            $('#notification-dropdown').hide();
            dropdownOpen = false;
        }
    });

    /* ----------------------
       POLLING SAFE
    ---------------------- */
    setInterval(fetchCount, COUNT_INTERVAL);
    window.addEventListener('focus', fetchCount);

})(jQuery);
</script>



<script>
/* ======================
   UI / THEME / MENU
====================== */
document.addEventListener('DOMContentLoaded', function () {
    const profileBtn = document.getElementById('profile-btn');
    const profileMenu = document.getElementById('profile-menu');
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', () => profileMenu.classList.toggle('hidden'));
    }

    const themeToggle = document.getElementById('theme-toggle');
    const theme = localStorage.getItem('theme') || 'light-theme';
    document.body.classList.add(theme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const newTheme = document.body.classList.contains('light-theme') ? 'dark-theme' : 'light-theme';
            document.body.className = newTheme;
            localStorage.setItem('theme', newTheme);
        });
    }
});
</script>

<script>
/* ======================
   ANIMATIONS & SCROLL
====================== */
document.addEventListener('DOMContentLoaded', function () {
    const footer = document.querySelector('.footer-animated');
    if (footer) {
        const obs = new IntersectionObserver(e => {
            if (e[0].isIntersecting) {
                footer.classList.add('visible');
                obs.disconnect();
            }
        }, { threshold: 0.3 });
        obs.observe(footer);
    }

    document.querySelectorAll('.scroll-animated').forEach(el => {
        const obs = new IntersectionObserver(e => {
            if (e[0].isIntersecting) {
                el.classList.add('visible');
                obs.disconnect();
            }
        }, { threshold: 0.2 });
        obs.observe(el);
    });

    const counters = document.querySelectorAll('.counter');
    if (counters.length) {
        const obs = new IntersectionObserver(e => {
            if (e[0].isIntersecting) {
                counters.forEach(c => {
                    let cur = 0, target = +c.dataset.count;
                    const inc = target / 200;
                    const step = () => {
                        if (cur < target) {
                            cur += inc;
                            c.innerText = Math.ceil(cur);
                            setTimeout(step, 20);
                        } else {
                            c.innerText = target;
                        }
                    };
                    step();
                });
                obs.disconnect();
            }
        }, { threshold: 0.2 });
        obs.observe(counters[0]);
    }
});
</script>

<script>
/* ======================
   OVERLAY & UX
====================== */
window.onload = function () {
    const overlay = document.getElementById('overlayMessage');
    if (!overlay) return;

    const count = parseInt(localStorage.getItem('overlayMessageViewCount')) || 0;
    if (count >= 8) {
        overlay.style.display = 'none';
        document.body.classList.remove('modal-open-scrollblock');
    } else {
        overlay.style.display = 'flex';
        document.body.classList.add('modal-open-scrollblock');
    }
};

function closeOverlay() {
    const overlay = document.getElementById('overlayMessage');
    if (!overlay) return;
    overlay.style.display = 'none';
    document.body.classList.remove('modal-open-scrollblock');
    localStorage.setItem(
        'overlayMessageViewCount',
        (parseInt(localStorage.getItem('overlayMessageViewCount')) || 0) + 1
    );
}
</script>

  <!-- jQuery (si nécessaire) -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


  @stack('scripts')
  <a id="whatsapp-btn" href="https://wa.me/243897604018" target="_blank" rel="noopener noreferrer" aria-label="Contact WhatsApp">
  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
    <path d="M20.52 3.48A11.91 11.91 0 0012 0C5.37 0 0 5.37 0 12a11.94 11.94 0 001.85 6L0 24l6-1.85A11.94 11.94 0 0012 24c6.63 0 12-5.37 12-12 0-3.2-1.24-6.2-3.48-8.52zM12 21.5c-1.92 0-3.75-.54-5.32-1.56l-.38-.23-3.56 1.08 1.08-3.48-.24-.38A9.44 9.44 0 012.5 12c0-5.25 4.25-9.5 9.5-9.5 2.54 0 4.93.99 6.72 2.79A9.44 9.44 0 0121.5 12c0 5.25-4.25 9.5-9.5 9.5zm5.32-7.72l-2.38-1.08a.71.71 0 00-.84.21l-1.22 1.49a9.04 9.04 0 01-4.15-4.15l1.48-1.22a.71.71 0 00.2-.84L9.22 6.18a.71.71 0 00-.92-.31l-2.5 1a3.51 3.51 0 00-1.52 4.1 9.27 9.27 0 007.97 7.98 3.49 3.49 0 004.1-1.53l1-2.5a.71.71 0 00-.32-.93z"/>
  </svg>
</a>
  </body>
</html>