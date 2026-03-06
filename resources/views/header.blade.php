<header class="scroll-animated bg-white shadow-lg">
  <!-- Top Row: Notifications et Profil / Connexion aligné à droite -->
  <div class="scroll-animated flex justify-between items-center py-4 px-8 bg-gradient-to-r from-blue-100 via-blue-50 to-blue-200 shadow-md">
    <div class="scroll-animated ml-auto flex items-center space-x-6">
    <!-- Icône notification -->












      <!-- Profil / Connexion -->
    <div class="scroll-animatedrelative">
        @auth
            <!-- Bouton Profil pour utilisateur connecté -->
            <!-- Icône utilisateur sans bouton -->
            <!-- Icône utilisateur en haut à droite avec taille augmentée -->
            <div class="absolute top-4 right-4 center items-center space-x-2 text-xl">
                <i class="fas fa-user-circle text-3xl"></i> <!-- Icône utilisateur avec taille augmentée -->
                <span class="font-semibold text-lg">{{ Auth::user()->name }}</span> <!-- Nom de l'utilisateur -->
            </div>


            <!-- Bouton d'aide, minuscule et placé en haut à droite -->
            <button class="btn btn-sm btn-info position-absolute top-0 end-0 m-3" data-bs-toggle="modal" data-bs-target="#iconHelpModal">
                <i class="fas fa-question-circle"></i>
            </button>




           
            <div id="profile-menu" class="scroll-animated absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
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
                <i class="scroll-animated fas fa-sign-in-alt"></i> Connexion <!-- Icône connexion -->
            </a>
        @endauth
    </div>

    </div>
    </div>
 <!-- Menu Navigation -->
  <nav class="bg-white py-2 px-4 shadow">
 <ul class="scroll-animated d-flex justify-content-between align-items-center list-unstyled flex-wrap w-100">
        @auth
            <li>
                <a href="{{ route('home') }}" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-home menu-icon {{ request()->routeIs('home') ? 'active' : '' }}" title="Accueil"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('messages.index') }}" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-envelope menu-icon {{ request()->routeIs('messages.index') ? 'active' : '' }}" title="Messages"></i>
                </a>
            </li>
        @endauth

        @if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin'))

            <li>
                <a href="{{ route('accuse.form') }}" class="btn btn-outline-info px-4 py-2">
                    <i class="fas fa-file-alt menu-icon {{ request()->routeIs('accuse.form') ? 'active' : '' }}" title="Accuser Réception"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('courriers.create') }}" class="btn btn-outline-success px-4 py-2">
                    <i class="fas fa-plus-circle menu-icon {{ request()->routeIs('courriers.create') ? 'active' : '' }}" title="Enregistrer un Courrier"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('accuses.index') }}" class="btn btn-outline-warning px-4 py-2">
                    <i class="fas fa-list-ul menu-icon {{ request()->routeIs('accuses.index') ? 'active' : '' }}" title="Liste des accusés réception"></i>
                </a>
            </li>
        @endif

        @if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin'))
            <li>
                <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fas fa-envelope-open-text menu-icon {{ request()->routeIs('courriers.index') ? 'active' : '' }}" title="Tous les Courriers"></i>
                </a>
            </li>
        @endif

        @if(Auth::user() && in_array(Auth::user()->role, ['chef_service', 'chef_direction','agent','DG', 'admin']))
            <li>
                <a href="{{ route('reponses.index') }}" class="btn btn-outline-dark px-4 py-2">
                    <i class="fas fa-inbox menu-icon {{ request()->routeIs('reponses.index') ? 'active' : '' }}" title="Boîte de réception"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('telegramme.create') }}" class="btn btn-outline-primary px-4 py-2">
                    <i class="fas fa-paper-plane menu-icon {{ request()->routeIs('telegramme.create') ? 'active' : '' }}" title="Envoyer un Télégramme"></i>
                </a>
            </li>

            <li>
                <a href="{{ route('courrier_expedie.index') }}"
                class="btn btn-outline-primary px-4 py-2">
                    <i class="fa-solid fa-arrow-up-from-bracket" 
                    {{ request()->routeIs('courrier_expedie.*') ? 'active' : '' }}"
                    title="Courriers expédiés">
                    </i>
                </a>
            </li>

        @endif

        @if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin'))
        <li>
                <a href="{{ route('archives.index') }}" class="btn btn-outline-success px-4 py-2">
                    <i class="fas fa-archive menu-icon {{ request()->routeIs('archives.index') ? 'active' : '' }}" title="Archives"></i>
                </a>
            </li>
        @endif

        @if(Auth::user() && Auth::user()->service === 'caisse')
            <li>
                <a href="{{ route('caisse.demandes.index') }}" class="btn btn-outline-info px-4 py-2">
                    <i class="fas fa-hand-holding-usd menu-icon {{ request()->routeIs('caisse.demandes.index') ? 'active' : '' }}" title="Demande de fonds"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('caisse.depenses.index') }}" class="btn btn-outline-success px-4 py-2">
                    <i class="fas fa-money-bill-wave menu-icon {{ request()->routeIs('caisse.depenses.index') ? 'active' : '' }}" title="Dépenses"></i>
                </a>
            </li>
            <li>
                <a href="{{ route('caisse.rapport.index') }}" class="btn btn-outline-warning px-4 py-2">
                    <i class="fas fa-chart-line menu-icon {{ request()->routeIs('caisse.rapport.index') ? 'active' : '' }}" title="Rapport de caisse"></i>
                </a>
            </li>
        @endif

        @if(Auth::user() && Auth::user()->service === 'RH')
            <li>
                <a href="{{ route('demandes_conges.index') }}" class="btn btn-outline-info px-4 py-2">
                    <i class="fas fa-calendar-check menu-icon {{ request()->routeIs('demandes_conges.index') ? 'active' : '' }}" title="Congés"></i>
                </a>
            </li>
            <li>
                <a href="{{ url('/dossiers_personnels') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="fas fa-folder-open menu-icon {{ request()->is('dossiers_personnels') ? 'active' : '' }}" title="Dossiers personnels"></i>
                </a>
            </li>
        @endif

        @if(Auth::user() && Auth::user()->role === 'directeur_general')
            <li>
                <a href="{{ route('courriers.traites') }}" class="btn btn-outline-danger px-4 py-2">
                    <i class="fas fa-check-circle menu-icon {{ request()->routeIs('courriers.traites') ? 'active' : '' }}" title="Courriers Traités"></i>
                </a>
            </li>
        @endif
    </ul>
</div>


<!-- Modal Bootstrap pour l'explication des icônes -->
<div id="iconHelpModal" class="modal fade" tabindex="-1" aria-labelledby="iconHelpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content shadow-lg">
      <div class="modal-header bg-blue-100">
        <h5 class="modal-title font-semibold text-gray-700" id="iconHelpModalLabel">Aide : Icônes de navigation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
      </div>
      <div class="modal-body text-gray-800 space-y-3">
        <p><i class="fas fa-home text-primary fade-in-icon"></i> <strong>Accueil</strong> – Revenir à la page d’accueil.</p>
        <p><i class="fas fa-envelope text-info fade-in-icon"></i> <strong>Messages</strong> – Afficher la messagerie interne.</p>
        <p><i class="fas fa-file-alt text-info fade-in-icon"></i> <strong>Accuser réception</strong> – Enregistrer un accusé de réception.</p>
        <p><i class="fas fa-plus-circle text-success fade-in-icon"></i> <strong>Créer courrier</strong> – Ajouter un nouveau courrier.</p>
        <p><i class="fas fa-list-ul text-warning fade-in-icon"></i> <strong>Liste des accusés</strong> – Voir les accusés réception enregistrés.</p>
        <p><i class="fas fa-envelope-open-text text-secondary fade-in-icon"></i> <strong>Courriers</strong> – Afficher tous les courriers reçus.</p>
        <p><i class="fas fa-inbox text-dark fade-in-icon"></i> <strong>Boîte de réception</strong> – Courriers à traiter.</p>
        <p><i class="fas fa-paper-plane text-primary fade-in-icon"></i> <strong>Télégramme</strong> – Envoyer un télégramme officiel.</p>
        <p><i class="fas fa-archive text-success fade-in-icon"></i> <strong>Archives</strong> – Voir les documents archivés.</p>
        <p><i class="fas fa-hand-holding-usd text-info fade-in-icon"></i> <strong>Demande de fonds</strong> – Envoyer une demande de fonds.</p>
        <p><i class="fas fa-money-bill-wave text-success fade-in-icon"></i> <strong>Dépenses</strong> – Gérer les dépenses.</p>
        <p><i class="fas fa-chart-line text-warning fade-in-icon"></i> <strong>Rapport de caisse</strong> – Suivi des finances.</p>
        <p><i class="fas fa-calendar-check text-info fade-in-icon"></i> <strong>Congés</strong> – Gestion des congés.</p>
        <p><i class="fas fa-folder-open text-secondary fade-in-icon"></i> <strong>Dossiers personnels</strong> – Accéder aux dossiers RH.</p>
        <p><i class="fas fa-check-circle text-danger fade-in-icon"></i> <strong>Courriers traités</strong> – Liste des courriers finalisés.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
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

                
                .custom-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 12px; /* Réduit la taille de l'icône */
            padding: 5px 8px; /* Petite taille pour le bouton */
            background-color: #dc3545; /* Couleur rouge pour le bouton */
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .custom-btn:hover {
            background-color: #c82333; /* Change la couleur au survol */
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

        /* Style pour le fade-in */
    .fade-in-icon {
      opacity: 0;
      animation: fadeIn 0.5s forwards;
    }

    .fade-in-icon:nth-child(1) {
      animation-delay: 0s;
    }
    .fade-in-icon:nth-child(2) {
      animation-delay: 0.2s;
    }
    .fade-in-icon:nth-child(3) {
      animation-delay: 0.4s;
    }
    .fade-in-icon:nth-child(4) {
      animation-delay: 0.6s;
    }
    .fade-in-icon:nth-child(5) {
      animation-delay: 0.8s;
    }
    .fade-in-icon:nth-child(6) {
      animation-delay: 1s;
    }
    .fade-in-icon:nth-child(7) {
      animation-delay: 1.2s;
    }
    .fade-in-icon:nth-child(8) {
      animation-delay: 1.4s;
    }
    .fade-in-icon:nth-child(9) {
      animation-delay: 1.6s;
    }
    .fade-in-icon:nth-child(10) {
      animation-delay: 1.8s;
    }
    .fade-in-icon:nth-child(11) {
      animation-delay: 2s;
    }
    .fade-in-icon:nth-child(12) {
      animation-delay: 2.2s;
    }
    .fade-in-icon:nth-child(13) {
      animation-delay: 2.4s;
    }

    @keyframes fadeIn {
      to {
        opacity: 1;
      }
    }
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialisation des tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                delay: { show: 100, hide: 100 } // Affichage plus rapide (100ms au lieu de la valeur par défaut ~500ms)
            });
        });

        // Affichage automatique du modal après 3 secondes
        setTimeout(function () {
            const modal = new bootstrap.Modal(document.getElementById('iconHelpModal'));
            modal.show();
        }, 3000);
    });

    window.onload = function () {
        const modal = document.getElementById('modalExplication');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    function fermerModal() {
        const modal = document.getElementById('modalExplication');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>


@endpush
