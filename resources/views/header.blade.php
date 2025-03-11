<header class="bg-white shadow-lg">
  <!-- Top Row: Notifications et Profil / Connexion aligné à droite -->
  <div class="flex justify-between items-center py-4 px-8 bg-gradient-to-r from-blue-100 via-blue-50 to-blue-200 shadow-md">
    <div class="ml-auto flex items-center space-x-6">
    <!-- Icône Notifications -->
    <a href="#" class="notification-icon relative text-gray-600 hover:text-gray-900 transition duration-300 ease-in-out transform hover:scale-110">
        <i class="fas fa-bell text-xl"></i>
    </a>


      <!-- Profil / Connexion -->
      <div class="relative">
        @auth
          <!-- Bouton Profil pour utilisateur connecté -->
          <button class="text-gray-700 hover:text-blue-600 transition duration-300 ease-in-out transform hover:scale-110">
            <i class="fas fa-user"></i> {{ Auth::user()->name }} <!-- Affichage du nom de l'utilisateur -->
          </button>
          <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
            <a class="block px-4 py-2 text-gray-800 transition duration-200 hover:bg-gray-200"
               href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt"></i> Déconnexion <!-- Icône de déconnexion -->
            </a>
          </div>
        @else
          <!-- Lien Connexion pour les invités -->
          <a href="{{ route('login') }}" class="px-4 py-2 rounded-md text-green-500 hover:text-green-700 transition duration-300 ease-in-out font-medium hover:underline transform hover:scale-110">
            <i class="fas fa-sign-in-alt"></i> Connexion <!-- Icône connexion -->
          </a>
        @endauth
      </div>
    </div>
  </div>

  <!-- Bottom Row: Menu de Navigation Horizontal -->
  <nav class="bg-gray-50 border-t border-b border-gray-200">
    <div class="container mx-auto overflow-x-auto">
      <ul class="flex justify-center items-center space-x-8 py-4 whitespace-nowrap">
    @if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'chef_service'))
      <li>
          <a href="{{ route('home') }}" class="px-4 py-2 rounded-md text-blue-500 hover:text-blue-700 transition duration-200 hover:bg-blue-100 font-medium hover:shadow-md transform hover:scale-105">
              <i class="fas fa-home"></i> Accueil
          </a>
      </li>
      <li>
          <a href="{{ route('accuse.form') }}" class="px-4 py-2 rounded-md text-blue-500 hover:text-blue-700 transition duration-200 hover:bg-blue-100 font-medium hover:shadow-md transform hover:scale-105">
              <i class="fas fa-file-alt"></i> Accuser Réception
          </a>
      </li>
      <li>
          <a href="{{ route('courriers.index') }}" class="px-4 py-2 rounded-md text-blue-500 hover:text-blue-700 transition duration-200 hover:bg-blue-100 font-medium hover:shadow-md transform hover:scale-105">
              <i class="fas fa-envelope-open-text"></i> Tous les Courriers
          </a>
      </li>
      <li>
          <a href="{{ route('courriers.create') }}" class="px-4 py-2 rounded-md text-blue-500 hover:text-blue-700 transition duration-200 hover:bg-blue-100 font-medium hover:shadow-md transform hover:scale-105">
              <i class="fas fa-plus-circle"></i> Enregistrer un Courrier
          </a>
      </li>
      <li>
          <a href="{{ route('accuses.index') }}" class="px-4 py-2 rounded-md text-blue-500 hover:text-blue-700 transition duration-200 hover:bg-blue-100 font-medium hover:shadow-md transform hover:scale-105">
              <i class="fas fa-list-ul"></i> Liste des accusés réception
          </a>
      </li>
      <li>
    @endif
  
    @if(Auth::user() && Auth::user()->role === 'directeur_general')
          <a href="{{ route('courriers.traites') }}" class="px-4 py-2 rounded-md text-green-500 hover:text-green-700 transition duration-200 hover:bg-green-100 font-medium hover:shadow-md transform hover:scale-105">
              <i class="fas fa-check-circle"></i> Courriers Traités
          </a>
    @endif
</li>
    </div>
  </nav>
</header>

@push('styles')
    <style>
        /* Style pour le header */
        header {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        /* Top Row : Notifications et Profil / Connexion */
        .header-top {
            background: linear-gradient(to right, #e4f0f7, #f3f7fc);
        }

        /* Lien de connexion et profil */
        .header-top button, .header-top a {
            font-size: 16px;
            color: #4a5568;
            font-weight: 500;
            padding: 8px 12px;
            transition: background-color 0.3s ease;
        }

        .header-top a:hover {
            text-decoration: underline;
            color: #2b6cb0;
        }

        /* Menu de navigation */
        nav {
            background-color: #f8f9fa;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        nav a {
            padding: 12px 24px;
            color: #3182ce;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        nav a:hover {
            color: #2b6cb0;
            background-color: #ebf8ff;
            transform: translateY(-2px);
        }

        nav a.active {
            background-color: #ebf8ff;
            color: #2b6cb0;
        }

        /* Espacement et alignement */
        .flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .space-x-4 {
            margin-right: 16px;
        }

        .space-x-8 {
            margin-right: 32px;
        }

        .py-2, .py-3 {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .px-6, .px-4 {
            padding-left: 24px;
            padding-right: 24px;
        }
    </style>
@endpush
