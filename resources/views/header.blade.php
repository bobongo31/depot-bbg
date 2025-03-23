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
                <!-- Lien vers la page de modification du profil -->
                <a class="block px-4 py-2 text-gray-800 transition duration-200 hover:bg-gray-200" href="{{ route('profile.edit') }}">
                    <i class="fas fa-edit"></i> Profil <!-- Icône d'édition -->
                </a>
                <!-- Lien de déconnexion -->
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
  <ul class="d-flex justify-content-between align-items-center list-unstyled flex-wrap w-100">


    @auth
        <li>
            <a href="{{ route('home') }}" class="btn btn-outline-primary px-4 py-2" data-bs-toggle="tooltip" title="Accueil">
                <i class="fas fa-home"></i>
            </a>
        </li>
    @endauth
    @if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin'))
        <li>
            <a href="{{ route('messages.index') }}" class="btn btn-outline-primary px-4 py-2" data-bs-toggle="tooltip" title="Messages">
                <i class="fa-solid fa-envelope"></i> 
            </a>
        </li>
    @endif

    @if(Auth::user() && Auth::user()->role === 'agent') 
        <li>
            <a href="{{ route('accuse.form') }}" class="btn btn-outline-info px-4 py-2" data-bs-toggle="tooltip" title="Accuser Réception">
                <i class="fas fa-file-alt"></i>
            </a>
        </li>

        <li>
            <a href="{{ route('courriers.create') }}" class="btn btn-outline-success px-4 py-2" data-bs-toggle="tooltip" title="Enregistrer un Courrier">
                <i class="fas fa-plus-circle"></i>
            </a>
        </li>

        <li>
            <a href="{{ route('accuses.index') }}" class="btn btn-outline-warning px-4 py-2" data-bs-toggle="tooltip" title="Liste des accusés réception">
                <i class="fas fa-list-ul"></i>
            </a>
        </li>
    @endif

    @if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin'))
        <li>
            <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary px-4 py-2" data-bs-toggle="tooltip" title="Tous les Courriers">
                <i class="fas fa-envelope-open-text"></i>
            </a>
        </li>
    @endif

    @if(Auth::user() && (Auth::user()->role === 'chef_service' || Auth::user()->role === 'admin'))
        <li>
            <a href="{{ route('reponses.index') }}" class="btn btn-outline-dark px-4 py-2" data-bs-toggle="tooltip" title="Boîte de réception">
                <i class="fas fa-inbox"></i>
            </a>
        </li>

        <li>
            <a href="{{ route('telegramme.create') }}" class="btn btn-outline-primary px-4 py-2" data-bs-toggle="tooltip" title="Envoyer un Télégramme">
                <i class="fas fa-paper-plane"></i>
            </a>
        </li>
    @endif

    @if(Auth::user() && Auth::user()->role === 'admin')
        <li>
            <a href="{{ route('archives.index') }}" class="btn btn-outline-success px-4 py-2" data-bs-toggle="tooltip" title="Archives">
                <i class="fas fa-archive"></i>
            </a>
        </li>
    @endif

    @if(Auth::user() && Auth::user()->role === 'directeur_general')
        <li>
            <a href="{{ route('courriers.traites') }}" class="btn btn-outline-danger px-4 py-2" data-bs-toggle="tooltip" title="Courriers Traités">
                <i class="fas fa-check-circle"></i>
            </a>
        </li>
    @endif
</ul>

</div>


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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 100, hide: 100 } // Affichage plus rapide (100ms au lieu de la valeur par défaut ~500ms)
            });
        });
    });
</script>


@endpush
