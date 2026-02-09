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


<form action="{{ route('recherche.globale') }}" method="GET" class="d-flex mb-3">
    <input type="text" name="q" class="form-control me-2" placeholder="Rechercher..." required>
    <button class="btn btn-outline-primary"><i class="fas fa-search"></i></button>
</form>

<!-- Notification bell + dropdown -->
<div class="d-inline-block align-middle ms-2">
  <div class="notification-wrapper position-relative">
    <button id="notification-icon" class="btn btn-light notification-icon" aria-label="Notifications" type="button">
      <i class="fas fa-bell"></i>
    </button>

    <div id="notification-dropdown" class="card shadow-sm" style="display:none; position:absolute; right:0; top:38px; z-index:2000; width:340px; max-height:420px; overflow:auto;">
      <div class="card-body p-2">
        <ul id="notification-list" class="list-unstyled mb-0 p-0"></ul>
      </div>
    </div>
  </div>
</div>

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

    $(document).ready(function() {
    // Assure que le menu est bien caché au chargement
    $('#notification-dropdown').hide();

    // Toggle menu au clic sur l'icône
    $('#notification-icon').click(function(e) {
        e.preventDefault();
        $('#notification-dropdown').toggle();
        if ($('#notification-dropdown').is(':visible')) {
            loadNotifications();
        }
    });

    // Fermer menu si clic en dehors
    $(document).click(function(event) {
        if (!$(event.target).closest('#notification-icon, #notification-dropdown').length) {
            $('#notification-dropdown').hide();
        }
    });

    // Ton reste de code...
});

$(document).ready(function() {
    // Mise à jour du badge
    function updateNotificationIcon(count) {
        var displayCount = count > 100 ? '100+' : count;
        var badgeHtml = '<span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">' + displayCount + '</span>';
        $('.notification-icon').find('.notification-badge').remove();
        if(count > 0) {
            $('.notification-icon').append(badgeHtml);
        }
    }

    // Charger le compteur de notifications au chargement
    function loadNotificationCount() {
        $.ajax({
            url: '/notifications/count',
            method: 'GET',
            success: function(data) {
                updateNotificationIcon(data.count);
            },
            error: function() {
                console.error('Erreur lors du chargement du compteur de notifications');
            }
        });
    }

    // Charger la liste des notifications au clic (déjà présent behaviour uses loadNotifications)

    // Nouvelle logique: mettre à jour le dropdown avec le endpoint unifié
    async function loadNotifications() {
        try {
            const res = await fetch('/notifications/list');
            if (!res.ok) throw new Error('Erreur réseau');
            const data = await res.json();
            $('#notification-list').empty();
            if (!data || data.length === 0) {
                $('#notification-list').append('<li class="text-gray-500 text-center p-4">Aucune notification</li>');
                return;
            }
            data.forEach(function(notif) {
                let dateStr = new Date(notif.created_at).toLocaleString();
                let title = notif.type === 'message' ? (notif.from ? notif.from + ' : ' : '') + notif.content : notif.content;
                let li = `<li class="notification-item mb-2 p-2 border-l-4 border-blue-500 bg-gray-50 rounded">
                              <div class="text-xs text-gray-400">${dateStr}</div>
                              <div class="text-sm font-medium text-gray-700"><a href="${notif.url}">${title}</a></div>
                          </li>`;
                $('#notification-list').append(li);
            });
        } catch (e) {
            $('#notification-list').html('<li class="text-red-500 text-center p-4">Erreur lors du chargement des notifications</li>');
        }
    }

    // Charger la liste lors de l'ouverture du dropdown
    $('#notification-icon').off('click').on('click', function(e) {
        e.preventDefault();
        $('#notification-dropdown').toggle();
        if ($('#notification-dropdown').is(':visible')) {
            loadNotificationCount();
            loadNotifications();
        }
    });

    // Charger le compteur au démarrage
    loadNotificationCount();

    // Rafraîchir le compteur toutes les minutes
    setInterval(loadNotificationCount, 60000);
});

    
    // Script pour les notifications & autres fonctionnalités (exemple)
    jQuery(document).ready(function($) {
  var notificationCount = 0;

  // Sur tous les inputs/selects/textareas dans toutes les tables
  $('table').find('input, select, textarea').on('change', function(){
    notificationCount++;
    updateNotificationIcon(notificationCount);
  });

  function updateNotificationIcon(count){
    var displayCount = (count >= 2) ? 2 : count;
    var badgeHtml = '<span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">' 
                    + displayCount + '</span>';
    $('.notification-icon').find('.notification-badge').remove();
    $('.notification-icon').append(badgeHtml);
  }



      // Gestion du menu profil
      const profileBtn = document.getElementById('profile-btn');
      const profileMenu = document.getElementById('profile-menu');
      if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function () {
          profileMenu.classList.toggle('hidden');
        });
      }

      // Changement de thème
      const themeToggleButton = document.getElementById('theme-toggle');
      const currentTheme = localStorage.getItem('theme') || 'light-theme';
      document.body.classList.add(currentTheme);
      if (themeToggleButton) {
        themeToggleButton.addEventListener('click', () => {
          let newTheme = document.body.classList.contains('light-theme') ? 'dark-theme' : 'light-theme';
          document.body.classList.remove('light-theme', 'dark-theme');
          document.body.classList.add(newTheme);
          localStorage.setItem('theme', newTheme);
        });
      }
    });


    // --- Animation d'apparition du footer au scroll ---
    document.addEventListener('DOMContentLoaded', function () {
      const footer = document.querySelector('.footer-animated');
      if (footer) {
        const observer = new IntersectionObserver(
          entries => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                footer.classList.add('visible');
                observer.unobserve(footer);
              }
            });
          },
          { threshold: 0.3 }
        );
        observer.observe(footer);
      }
    });

    // --- Animation d'apparition des éléments au scroll (autres que le footer) ---
    document.addEventListener('DOMContentLoaded', function () {
  const animatedItems = document.querySelectorAll('.scroll-animated');

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      entries => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.2 }
    );

    animatedItems.forEach(item => observer.observe(item));
  } else {
    // Fallback pour les anciens navigateurs (mobile ou IE)
    animatedItems.forEach(item => {
      item.classList.add('visible');
    });
  }
});

    // Ajouter une classe "zoom-in" à la page lors du chargement
  document.addEventListener("DOMContentLoaded", function () {
    // Sélectionner la balise body ou div qui contient l'ensemble du contenu de la page
    const pageContent = document.querySelector('body');
    if (pageContent) {
      pageContent.classList.add('zoom-in');

      // Lorsque la page est complètement chargée, ajouter la classe "zoom-in-active"
      setTimeout(() => {
        pageContent.classList.add('zoom-in-active');
      }, 100); // Donne un petit délai avant d'appliquer l'animation
    }
  });
    window.onload = function () {
        const overlay = document.getElementById('overlayMessage');
        if (!overlay) return;

        // Gestion des vues
        let viewCount = parseInt(localStorage.getItem('overlayMessageViewCount')) || 0;

        if (viewCount >= 8) {
          overlay.style.display = 'none';
          document.body.classList.remove('modal-open-scrollblock');
        } else {
          overlay.style.display = 'flex';
          document.body.classList.add('modal-open-scrollblock');
        }
      };

      function closeOverlay() {
        const overlay = document.getElementById('overlayMessage');
        if (overlay) {
          overlay.style.display = 'none';
          document.body.classList.remove('modal-open-scrollblock');
        }

        let viewCount = parseInt(localStorage.getItem('overlayMessageViewCount')) || 0;
        localStorage.setItem('overlayMessageViewCount', viewCount + 1);
      }

document.addEventListener('DOMContentLoaded', function () {
  const counters = document.querySelectorAll('.counter');

  // Fonction pour animer les compteurs
  function animateCounters() {
    counters.forEach(counter => {
      if (!counter.classList.contains('animated')) {
        const target = +counter.getAttribute('data-count');
        let current = 0; // On commence à 0
        const increment = target / 200; // Increment de l'animation

        const updateCount = () => {
          if (current < target) {
            current += increment;
            counter.innerText = Math.ceil(current); // On met à jour la valeur affichée
            setTimeout(updateCount, 20); // Vitesse de l'animation
          } else {
            counter.innerText = target; // On s'assure que la valeur finale est bien atteinte
            counter.classList.add('animated'); // On marque comme animé pour éviter les répétitions
          }
        };

        updateCount();
      }
    });
  }

  // Observer la section pour savoir quand elle devient visible
  const observer = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          console.log('Section visible, animation des compteurs lancée!');
          animateCounters();
          observer.unobserve(entry.target); // On désactive l'observation une fois l'animation lancée
        }
      });
    },
    { threshold: 0.2 } // L'animation démarre lorsque 20% de la section est visible
  );

  const counterSection = document.querySelector('.scroll-animated');
  
  if (counterSection) {
    observer.observe(counterSection); // On observe la section contenant les compteurs
  } else {
    console.error('La section avec la classe .scroll-animated n\'a pas été trouvée.');
  }
});

    // Live notification poller + mark-as-read handler
    (function(){
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        function updateNotificationIcon(count){
            // remove existing
            $('.notification-icon').find('.notification-badge').remove();
            if(!count || parseInt(count) === 0){
                return;
            }
            const badgeHtml = '<span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-danger rounded-full">'+count+'</span>';
            $('.notification-icon').append(badgeHtml);
        }

        async function fetchCount(){
            try{
                const res = await fetch('/notifications/count', { credentials: 'same-origin' });
                if(!res.ok) return;
                const json = await res.json();
                const c = parseInt(json.unread_count || 0, 10);
                updateNotificationIcon(c);
            }catch(e){ console.warn('notif count error', e); }
        }

        async function fetchListAndBind(){
            try{
                const res = await fetch('/notifications/list', { credentials: 'same-origin' });
                if(!res.ok) throw new Error('network');
                const data = await res.json();
                $('#notification-list').empty();
                if(!data || data.length === 0){
                    $('#notification-list').append('<li class="text-gray-500 text-center p-4">Aucune notification</li>');
                    return;
                }

                data.forEach(function(notif){
                    let dateStr = new Date(notif.created_at).toLocaleString();
                    let title = notif.type === 'message' ? (notif.from ? notif.from + ' : ' : '') + notif.content : notif.content;
                    let li = $(`<li class="notification-item mb-2 p-2 border-l-4 border-blue-500 bg-gray-50 rounded">\
                                  <div class="text-xs text-gray-400">${dateStr}</div>\
                                  <div class="text-sm font-medium text-gray-700"><a href="#" class="notif-link" data-type="${notif.type}" data-id="${notif.id}" data-url="${notif.url}">${$('<div/>').text(title).html()}</a></div>\
                              </li>`);
                    $('#notification-list').append(li);
                });

                // bind click to mark as read then navigate
                $('.notif-link').off('click').on('click', async function(e){
                    e.preventDefault();
                    const $a = $(this);
                    const type = $a.data('type');
                    const id = $a.data('id');
                    const url = $a.data('url');

                    try{
                        await fetch('/notifications/read', {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ type: type, id: id })
                        });
                    } catch(err) {
                        console.warn('mark read failed', err);
                    }

                    // Small delay to allow server to update count (optional)
                    setTimeout(function(){ window.location.href = url; }, 120);
                });

            }catch(e){
                $('#notification-list').html('<li class="text-red-500 text-center p-4">Erreur lors du chargement des notifications</li>');
            }
        }

        // initial fetch
        fetchCount();

        // fetch list when dropdown opens: keep existing binding for icon click which calls loadNotifications
        $('#notification-icon').off('click.poll').on('click.poll', function(e){
            e.preventDefault();
            $('#notification-dropdown').toggle();
            if($('#notification-dropdown').is(':visible')){
                fetchListAndBind();
            }
        });

        // poll every 10 seconds
        setInterval(fetchCount, 10000);

        // refresh immediately when window gains focus
        window.addEventListener('focus', function(){ fetchCount(); });

    })();

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