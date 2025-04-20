<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Gestion_Courrier') }}</title>

  <!-- Fonts & CDN -->
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
  <!-- Font Awesome via CDN -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />


  <!-- jQuery, Popper.js & Bootstrap JS (CDN) -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 <!-- Garder seulement Bootstrap 5.3.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>




  <!-- Import Tailwind CSS et JS via Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

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
    
    
    .scroll-animated {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
  }

  .scroll-animated.visible {
    opacity: 1;
    transform: translateY(0);
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
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    backdrop-filter: blur(4px); /* flou de fond optionnel */
    padding: 1rem;
  }

  /* Contenu de la boîte */
  .overlay-message .message-box {
    background: white;
    color: #333;
    border-radius: 1rem;
    padding: 2rem;
    max-width: 500px;
    width: 100%;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    animation: fadeInScale 0.4s ease;
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

  
    
  </style>
</head>
<body class="bg-gray-100 text-gray-900 {{ session('theme', 'light-theme') }}">
  <div id="app" class="flex min-h-screen flex-col">
    <!-- Inclusion du header contenant le menu -->
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
    
    
    // Script pour les notifications & autres fonctionnalités (exemple)
    jQuery(document).ready(function($) {
      var notificationCount = 0;

      // Détecter les changements dans les inputs, selects et textareas de la table
      $('#courriersTable').find('input, select, textarea').on('change', function(){
        notificationCount++;
        updateNotificationIcon(notificationCount);
      });

      // Fonction qui met à jour l'icône de notification avec un badge
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
    const messageBox = overlay.querySelector('.message-box');

    // Appliquer flexbox pour centrer
    overlay.style.display = 'flex';
    overlay.style.justifyContent = 'center';
    overlay.style.alignItems = 'center';

    // Compteur de vues
    let viewCount = localStorage.getItem('overlayMessageViewCount');
    if (!viewCount) {
      viewCount = 0;
    } else {
      viewCount = parseInt(viewCount);
    }

    if (viewCount >= 8) {
      overlay.style.display = 'none';
    } else {
      overlay.style.display = 'flex';
    }
  };

  function closeOverlay() {
    const overlay = document.getElementById('overlayMessage');
    overlay.style.display = 'none';

    let viewCount = localStorage.getItem('overlayMessageViewCount');
    if (!viewCount) {
      viewCount = 0;
    } else {
      viewCount = parseInt(viewCount);
    }

    viewCount++;
    localStorage.setItem('overlayMessageViewCount', viewCount);
}
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
  </body>
</html>
