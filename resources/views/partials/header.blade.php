<?php  
    $curPageName = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);  
?>  

<body>
    <!-- Topbar (visible en ≥992px) -->
<div class="topbar d-none d-lg-flex justify-content-between">
    <div class="d-flex align-items-center">
        <a href="#" class="text-white me-3"><i class="fas fa-bell"></i></a>
    </div>

    <!-- Alignement à droite -->
    <div class="d-flex align-items-center ms-auto">
        @auth
            <!-- Affichage du nom de l'utilisateur et des boutons Profil et Déconnexion -->
            <span class="text-white me-3"><i class="fas fa-user-circle"></i> {{ Auth::user()->name }}</span>
            
            <!-- Bouton Profil -->
            <a href="{{ route('profile.edit') }}" class="text-white me-3">
                <i class="fas fa-edit"></i>
            </a>
            
            <!-- Bouton Déconnexion -->
            <a href="{{ route('logout') }}" class="text-white"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        @else
            <!-- Si l'utilisateur n'est pas connecté, afficher le bouton Connexion -->
            <a href="{{ route('login') }}" class="btn btn-sm btn-success ms-3">
                <i class="fas fa-sign-in-alt"></i> Connexion
            </a>
        @endauth
    </div>
</div>

  <!-- Navbar sticky juste en dessous -->
<nav class="navbar navbar-expand-lg navbar-dark px-5 navbar-sticky">
  <a href="{{ route('welcome') }}" class="navbar-brand">
    <img src="image/LOGO-GIC.png" alt="Logo" style="width:80px;">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
    <span class="navbar-toggler-icon"><i class="fa fa-bars"></i></span>
  </button>
  <div class="collapse navbar-collapse" id="mainMenu">
    <ul class="navbar-nav ms-auto">
      <!-- Accueil -->
      <li class="nav-item">
        <a href="{{ route('home') }}" class="nav-link {{ $curPageName === 'home' ? 'active' : '' }}">
            <i class="fas fa-home menu-icon"></i> Accueil
        </a>
      </li>

      <!-- Liens spécifiques à l'utilisateur authentifié -->
      @auth
        <!-- Messages -->
        <li class="nav-item">
          <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.index') ? 'active' : '' }}">
            <i class="fas fa-envelope menu-icon"></i> Messagerie
          </a>
        </li>

        <!-- Rôles spécifiques -->
        @if(Auth::user()->role === 'agent')
          <li class="nav-item">
            <a href="{{ route('accuse.form') }}" class="nav-link {{ request()->routeIs('accuse.form') ? 'active' : '' }}">
              <i class="fas fa-file-alt menu-icon"></i> Accuser Réception
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('courriers.create') }}" class="nav-link {{ request()->routeIs('courriers.create') ? 'active' : '' }}">
              <i class="fas fa-plus-circle menu-icon"></i> Enregistrer un Courrier
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('accuses.index') }}" class="nav-link {{ request()->routeIs('accuses.index') ? 'active' : '' }}">
              <i class="fas fa-list-ul menu-icon"></i> Liste des accusés réception
            </a>
          </li>
        @endif

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'agent')
          <li class="nav-item">
            <a href="{{ route('courriers.index') }}" class="nav-link {{ request()->routeIs('courriers.index') ? 'active' : '' }}">
              <i class="fas fa-envelope-open-text menu-icon"></i> Tous les Courriers
            </a>
          </li>
        @endif

        @if(Auth::user()->role === 'chef_service' || Auth::user()->role === 'admin')
          <li class="nav-item">
            <a href="{{ route('reponses.index') }}" class="nav-link {{ request()->routeIs('reponses.index') ? 'active' : '' }}">
              <i class="fas fa-inbox menu-icon"></i> Boîte de réception
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('telegramme.create') }}" class="nav-link {{ request()->routeIs('telegramme.create') ? 'active' : '' }}">
              <i class="fas fa-paper-plane menu-icon"></i> Envoyer un Télégramme
            </a>
          </li>
        @endif

        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'agent')
          <li class="nav-item">
            <a href="{{ route('archives.index') }}" class="nav-link {{ request()->routeIs('archives.index') ? 'active' : '' }}">
              <i class="fas fa-archive menu-icon"></i> Archives
            </a>
          </li>
        @endif

        @if(Auth::user()->role === 'directeur_general')
          <li class="nav-item">
            <a href="{{ route('courriers.traites') }}" class="nav-link {{ request()->routeIs('courriers.traites') ? 'active' : '' }}">
              <i class="fas fa-check-circle menu-icon"></i> Courriers Traités
            </a>
          </li>
        @endif

        @if(Auth::user()->service === 'caisse')
          <li class="nav-item">
            <a href="{{ route('caisse.demandes.index') }}" class="nav-link {{ request()->routeIs('caisse.demandes.index') ? 'active' : '' }}">
              <i class="fas fa-hand-holding-usd menu-icon"></i> Demande de fonds
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('caisse.depenses.index') }}" class="nav-link {{ request()->routeIs('caisse.depenses.index') ? 'active' : '' }}">
              <i class="fas fa-money-bill-wave menu-icon"></i> Dépenses
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('caisse.rapport.index') }}" class="nav-link {{ request()->routeIs('caisse.rapport.index') ? 'active' : '' }}">
              <i class="fas fa-chart-line menu-icon"></i> Rapport de caisse
            </a>
          </li>
        @endif

        @if(Auth::user()->service === 'RH')
          <li class="nav-item">
            <a href="{{ route('demandes_conges.index') }}" class="nav-link {{ request()->routeIs('demandes_conges.index') ? 'active' : '' }}">
              <i class="fas fa-calendar-check menu-icon"></i> Congés
            </a>
          </li>
          <li class="nav-item">
            <a href="{{ route('dossiers_personnels.index') }}" class="nav-link {{ request()->routeIs('dossiers_personnels.index') ? 'active' : '' }}">
              <i class="fas fa-folder-open menu-icon"></i> Dossiers personnels
            </a>
          </li>
        @endif
      @endauth
    </ul>
  </div>
</nav>

    <!-- Navbar & Carousel End -->

</body>
</html>
