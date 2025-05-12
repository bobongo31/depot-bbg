<?php  
$curPageName = basename($_SERVER["SCRIPT_NAME"]);  
?>  

<body>
    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none d-lg-block" style="background-color: #707674; color:#000;">
        <div class="row gx-0">
            <!-- Partie pour les informations de contact -->
            <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <small class="me-3 text-light"><i class="fa fa-map-marker-alt me-2"></i>13, AV.Itaga, C.Barumbu, Kinshasa/RDC</small>
                    <small class="me-3 text-light"><i class="fa fa-phone-alt me-2"></i>+243 826 449 364</small>
                    <small class="text-light"><i class="fa fa-envelope-open me-2"></i>Info@eqauteurmag.com</small>
                </div>
            </div>

            <!-- Partie pour le profil et la connexion -->
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <!-- Bouton pour afficher le menu de profil -->
                    <button id="profile-button" class="btn btn-sm btn-outline-light btn-sm-square rounded-circle ms-2" onclick="toggleProfileMenu()">
                        <i class="fas fa-user-circle"></i> <!-- Icône de profil -->
                    </button>

                    <!-- Menu de profil caché par défaut -->
                    <div id="profile-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden">
                        <!-- Lien vers la page de modification du profil -->
                        <a class="block px-4 py-2 text-gray-800 transition duration-200 hover:bg-gray-200" href="{{ route('profile.edit') }}">
                            <i class="fas fa-edit"></i> Profil <!-- Icône d'édition -->
                        </a>
                        <!-- Lien de déconnexion -->
                        <a class="block px-4 py-2 text-gray-800 transition duration-200 hover:bg-gray-200" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i> Déconnexion <!-- Icône de déconnexion -->
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar & Carousel Start -->
    <div class="container-fluid position-relative p-0">
        <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
            <a href="index.php" class="navbar-brand p-0">
                <img src="img/logomags.png" style="width:80px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto py-0">
                    <a href="index.php" class="nav-item nav-link <?php if($curPageName === "index.php") { echo 'active'; } ?>">Accueil</a>
                    <a href="apropos.php" class="nav-item nav-link <?php if($curPageName === "apropos.php") { echo 'active'; } ?>">Actualité</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link <?php if($curPageName === "publications.php") { echo 'active'; } ?> dropdown-toggle" data-bs-toggle="dropdown">Publications</a>
                        <div class="dropdown-menu m-0">
                            <a href="articles.php" class="dropdown-item">Articles</a>
                            <a href="magazine.php" class="dropdown-item">Magazine</a>
                            <a href="livres.php" class="dropdown-item">Livres</a>
                        </div>
                    </div>
                    <a href="contact.php" class="nav-item nav-link <?php if($curPageName === "contact.php") { echo 'active'; } ?>">Contact</a>
                </div>
                <button id="myButton" class="btn btn-primary py-2 px-4 ms-3" style="background-color:#076329; border: none;">Contactez-nous sur Whatsapp</button>
            </div>
        </nav>
    </div>
    <!-- Navbar & Carousel End -->

    <!-- Script pour afficher le menu de profil -->
   
</body>
</html>
