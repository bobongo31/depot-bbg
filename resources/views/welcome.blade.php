@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Début du Hero Section -->
<div class="scroll-animated container-fluid py-5" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            
            <!-- Texte à gauche -->
            <div class="scroll-animated col-lg-6 text-white">
                <h1 class="mb-4" style="font-weight: 700; font-size: 3rem;">Votre logiciel GIC,<br>accessible partout, tout le temps.</h1>
                <h4 class="mb-3 fst-italic">“Libérez-vous de la complexité documentaire”</h4>
                <p class="mb-4" style="font-size: 1.1rem;">
                    Vous êtes noyé(e) sous un flot de documents et de processus complexes ?<br><br>
                    Ne vous inquiétez pas, GIC est là pour vous simplifier la vie !<br><br>
                    GIC transforme la gestion documentaire en un jeu d'enfant. Simplifiez vos flux de travail, optimisez la collaboration et automatisez vos tâches répétitives. Libérez le potentiel de votre entreprise grâce à une gestion documentaire fluide et performante.
                </p>
                <div class="scroll-animated d-flex gap-3">
                    <a href="https://wa.me/243897604018" class="btn btn-light text-primary text-uppercase px-4 py-2">Demander une démo</a>
                    <a href="inscription" class="btn btn-outline-light text-uppercase px-4 py-2">Essai gratuit, sans carte bancaire</a>
                </div>
            </div>

            <!-- Image à droite avec effet de survol -->
            <div class="scroll-animated col-lg-6 text-center">
                <img src="image/Image_hero.JPG" alt="Illustration GedZilla" class="img-fluid hover-zoom" style="max-width: 100%;">
            </div>
            
        </div>
    </div>
</div>
<!-- Fin du Hero Section -->



<!-- Section Hero GED -->
<section class="scroll-animated py-5" style="background: linear-gradient(to bottom right, #e0f7fa, #ffffff); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); margin: 2rem auto; max-width: 1200px;">
  <div class="container px-4">
    <div class="row align-items-center">
      <div class="scroll-animated col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
        <h1 class="fw-bold mb-4" style="font-size: 2.8rem;">Votre Assistant Personnel de Gestion Documentaire</h1>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Fini les montagnes de paperasse à gérer ! Notre logiciel GIC a été conçu pour vous simplifier la vie.
          C’est un assistant numérique qui ne dort jamais (et ne boit pas de café), prêt à alléger votre charge de travail,
          vous faire gagner un temps précieux, et vous permettre de vous concentrer sur l’essentiel : <strong>faire grandir votre entreprise</strong>.
        </p>
      </div>
      <div class="scroll-animated col-lg-6 text-center">
        <img src="image/Assistant-Personnel.jpg" alt="Assistant GED" class="img-fluid rounded-4 shadow hover-scale" style="max-height: 350px;">
      </div>
    </div>
  </div>
</section>



<!-- Début de la section Fonctionnalités -->
<section style="width: 100%; background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
  <div class="container text-center text-white">
    <h2 class="scroll-animated section-title mb-5" style="font-weight: bold; color: #00ccff;">Fonctionnalités Clés de GIC</h2>
    <p class="scroll-animated mb-5" style="font-size: 18px;">Des outils puissants pour optimiser votre gestion quotidienne.</p>

    <div class="row g-4">
      <!-- Carte Fonctionnalité 1 -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="sci-card h-100 bg-dark text-white shadow-lg p-4">
          <div class="scroll-animated card-body text-center">
            <h4 class="text-white">Gestion de Courriers</h4>
            <p>Enregistrez facilement vos courriers entrants et sortants, suivez leur statut en temps réel.</p>
            <img src="image/gestion_courrier.jpg" alt="Gestion de Courriers" class="img-fluid mt-3 mb-3" style="max-width: 80%;">
            <a href="{{ route('articles.show', 'gestion-courriers') }}" class="btn btn-primary">
              En savoir plus <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Carte Fonctionnalité 2 -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="sci-card h-100 bg-dark text-white shadow-lg p-4">
          <div class="scroll-animated card-body text-center">
            <h4 class="text-white">Archivage Intelligent</h4>
            <p>Indexation automatique, recherche rapide, accès cloud sécurisé à vos documents.</p>
            <img src="image/Archivage_Intelligent.jpg" alt="Archivage Intelligent" class="img-fluid mt-3 mb-3" style="max-width: 80%;">
            <a href="{{ route('articles.show', 'archivage-intelligent') }}" class="btn btn-primary">
              En savoir plus <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Carte Fonctionnalité 3 -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="sci-card h-100 bg-dark text-white shadow-lg p-4">
          <div class="scroll-animated card-body text-center">
            <h4 class="text-white">Messagerie Sécurisée</h4>
            <p>Communiquez en toute confidentialité grâce à une messagerie interne chiffrée.</p>
            <img src="image/Archivage_IntelligentOK.jpg" alt="Messagerie Sécurisée" class="img-fluid mt-3 mb-3" style="max-width: 80%;">
            <a href="{{ route('articles.show', 'messagerie-securisee') }}" class="btn btn-primary">
              En savoir plus <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Carte Fonctionnalité 4 -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="sci-card h-100 bg-dark text-white shadow-lg p-4">
          <div class="scroll-animated card-body text-center">
            <h4 class="text-white">Gestion des Utilisateurs</h4>
            <p>Contrôle des accès, rôles définis, audit des actions pour une gestion sécurisée.</p>
            <img src="image/Gestion-des-Utilisateurs.jpg" alt="Gestion des Utilisateurs" class="img-fluid mt-3 mb-3" style="max-width: 80%;">
            <a href="{{ route('articles.show', 'gestion-utilisateurs') }}" class="btn btn-primary">
              En savoir plus <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Carte Fonctionnalité 5 -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="sci-card h-100 bg-dark text-white shadow-lg p-4">
          <div class="scroll-animated card-body text-center">
            <h4 class="text-white">Signature Numérique</h4>
            <p>Signez vos documents en ligne avec une validité juridique et conforme RGPD.</p>
            <img src="image/Signature-Numerique.jpg" alt="Signature Numérique" class="img-fluid mt-3 mb-3" style="max-width: 80%;">
            <a href="{{ route('articles.show', 'signature-numerique') }}" class="btn btn-primary">
              En savoir plus <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Carte Fonctionnalité 6 -->
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="sci-card h-100 bg-dark text-white shadow-lg p-4">
          <div class="scroll-animated card-body text-center">
            <h4 class="text-white">Gestion de Stock & Vente</h4>
            <p>Suivi des stocks, gestion des ventes, facturation automatisée dans un seul outil.</p>
            <img src="image/Gestion_de_Stock_Vente.jpg" alt="Gestion de Stock et Vente" class="img-fluid mt-3 mb-3" style="max-width: 80%;">
            <a href="{{ route('articles.show', 'gestion-stock-vente') }}" class="btn btn-primary">
              En savoir plus <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Fin de la section Fonctionnalités -->





<!-- Début de la section Tarifs -->
<section class="scroll-animated py-5" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
  <div class="container">
      <div class="text-center section-title position-relative pb-3 mb-5 mx-auto" style="max-width: none;">
      <h1 class="fw-bold text-white" style="font-size: 2.5rem;">Nos Tarifs Simples et Adaptés</h1>
    </div>

    <div class="row g-4 justify-content-center">
      <!-- Carte Tarif - Basic -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="tarif-card hover-scale bg-white rounded-4 shadow p-4 h-100 d-flex flex-column text-center">
          <h4 class="mb-2 text-primary">Basic</h4>
          <h2 class="mb-3">19$<small class="text-muted">/mois</small></h2>
          <ul class="list-unstyled mb-4 flex-grow-1">
            <li>✓ Accès GIC</li>
            <li>✓ 5 Utilisateurs</li>
            <li>✓ 10 Go de stockage</li>
          </ul>
          <a href="#" class="btn btn-primary text-uppercase">Choisir Basic</a>
        </div>
      </div>

      <!-- Carte Tarif - Standard -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="tarif-card hover-scale bg-white rounded-4 shadow p-4 h-100 d-flex flex-column text-center">
          <h4 class="mb-2 text-success">Standard</h4>
          <h2 class="mb-3">49$<small class="text-muted">/mois</small></h2>
          <ul class="list-unstyled mb-4 flex-grow-1">
            <li>✓ Toutes fonctionnalités Basic</li>
            <li>✓ 20 Utilisateurs</li>
            <li>✓ 100 Go de stockage</li>
          </ul>
          <a href="#" class="btn btn-success text-uppercase">Choisir Standard</a>
        </div>
      </div>

      <!-- Carte Tarif - Premium -->
      <div class="col-12 col-sm-6 col-lg-4">
        <div class="tarif-card hover-scale bg-white rounded-4 shadow p-4 h-100 d-flex flex-column text-center">
          <h4 class="mb-2 text-danger">Premium</h4>
          <h2 class="mb-3">99$<small class="text-muted">/mois</small></h2>
          <ul class="list-unstyled mb-4 flex-grow-1">
            <li>✓ Toutes fonctionnalités Standard</li>
            <li>✓ Utilisateurs illimités</li>
            <li>✓ Stockage illimité</li>
            <li>✓ Assistance prioritaire</li>
          </ul>
          <a href="#" class="btn btn-danger text-uppercase">Choisir Premium</a>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- Fin de la section Tarifs -->

<!-- Section Hero : Fini les recherches interminables -->
<section class="scroll-animated py-5" style="background: linear-gradient(to bottom right, #e3f2fd, #ffffff); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); margin: 2rem auto; max-width: 1200px;">
  <div class="container px-4">
    <div class="row align-items-center">
      <div class="scroll-animated col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
        <h2 class="fw-bold mb-4" style="font-size: 2.5rem;">Fini les recherches interminables.</h2>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Oubliez les fichiers égarés et les dossiers perdus dans les recoins de votre bureau… ou de votre ordinateur.
          <br><br>
          Grâce à notre logiciel GIC, retrouvez vos documents en un seul clic. Il vous suffit de lui donner quelques instructions simples, et il s’occupe du reste.
          <br><br>
          Moins de stress, plus de temps pour votre métier (ou votre café), et une entreprise qui gagne en efficacité.
          <strong>Notre GIC est la solution qu’il vous faut.</strong>
        </p>
      </div>
      <div class="scroll-animated col-lg-6 text-center">
        <img src="image/recherches.jpg" alt="Recherche documentaire facile" class="img-fluid rounded-4 shadow hover-scale" style="max-height: 350px;">
      </div>
    </div>
  </div>
</section>

<!-- Début de la section avec dégradé -->
<section>
    <div class="scroll-animated container-fluid py-5" style="background: linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%);">
        <div class="container text-center">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 800px;">
                <h1 class="mb-0 text-dark" style="white-space: nowrap;">GIC, la GED qui change tout</h1>
            </div>
            <div class="row g-4">

                <!-- Messagerie interne -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-comment-alt fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Messagerie interne</h4>
                        <p>Communiquez facilement avec votre équipe grâce à notre messagerie intégrée.</p>
                    </div>
                </div>

                <!-- Gestion de finance et caisse -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-credit-card fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Gestion de finance et caisse</h4>
                        <p>Suivez vos finances en temps réel, avec des rapports détaillés et des outils de gestion de caisse.</p>
                    </div>
                </div>

                <!-- Gestion RH -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Gestion des Ressources Humaines</h4>
                        <p>Optimisez la gestion de votre personnel et de vos équipes avec des outils RH dédiés.</p>
                    </div>
                </div>

                <!-- Simplifiez votre gestion documentaire -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-file-alt fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Simplifiez votre gestion documentaire</h4>
                        <p>Accédez rapidement à vos documents en un seul clic.</p>
                    </div>
                </div>

                <!-- Accès rapide aux documents -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Accès à vos documents en 1 clic</h4>
                        <p>Retrouvez vos fichiers en toute simplicité grâce à une recherche rapide.</p>
                    </div>
                </div>

                <!-- Télétravail -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-laptop-house fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Facilitez le télétravail</h4>
                        <p>Accédez à vos documents et collaborez à distance, en toute sécurité.</p>
                    </div>
                </div>

                <!-- Sécurisation des documents -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Sécurisez vos documents</h4>
                        <p>Protégez la confidentialité de vos données avec une sécurité renforcée.</p>
                    </div>
                </div>

                <!-- Réduction des tâches -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Réduisez les tâches chronophages</h4>
                        <p>Automatisez la gestion documentaire et concentrez-vous sur l'essentiel.</p>
                    </div>
                </div>

                <!-- Réduction des coûts -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-4">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-hdd fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Réduisez vos coûts de stockage</h4>
                        <p>Libérez de l'espace et optimisez vos ressources de stockage.</p>
                    </div>
                </div>

                <!-- Demander une démo -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-6">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-video fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Demander une démo</h4>
                        <p>Essayez notre solution avec une démo personnalisée.</p>
                        <a href="https://wa.me/243897604018" class="btn btn-primary text-uppercase mt-3">Demander une démo</a>
                    </div>
                </div>

                <!-- Essai gratuit -->
                <div class="scroll-animated col-12 col-sm-6 col-lg-6">
                    <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Commencer un essai gratuit en ligne</h4>
                        <p>Essayez notre solution sans engagement ni carte bancaire.</p>
                        <a href="inscription" class="btn btn-success text-uppercase mt-3">Commencer un essai gratuit</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- Fin de la section -->

<!-- Section Hero  -->
<section class="py-5" style="background: linear-gradient(to bottom right, #e0f7fa, #ffffff); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); margin: 2rem auto; max-width: 1200px;">
  <div class="container px-4">
    <div class="row align-items-center">
      <!-- Texte côté gauche -->
      <div class="scroll-animated col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
        <h1 class="fw-bold mb-4" style="font-size: 2.8rem;">Un logiciel GIC sans engagement accessible à tous</h1>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Nous savons que le monde de la gestion électronique de documents () peut être un vrai labyrinthe. Il y a tellement d'options, ça peut vous donner mal à la tête. Mais devinez quoi ? Nous sommes là pour transformer cette jungle numérique en un petit jardin zen !
        </p>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Imaginez notre logiciel GIC comme votre meilleur ami qui parle le langage de l'informatique pour vous. Pas besoin d'être un as de l'informatique pour le comprendre. On l'a conçu pour que même votre grand-mère puisse s'en servir.
        </p>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Mais attendez, il y a plus ! On ne veut pas vous ruiner avec des coûts astronomiques. On sait que l'argent est précieux, surtout en ce moment, alors on a rendu notre super GIC abordable. Parce que chaque entreprise, qu'elle soit grande ou petite, mérite de se simplifier la vie sans casser sa tirelire.
        </p>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Alors, si vous voulez dire adieu aux montagnes de papier, bonjour à l'efficacité, et faire des économies en prime, il est temps de tester notre solution.
        </p>
      </div>
      
      <!-- Image côté droit -->
      <div class="scroll-animated col-lg-6 text-center">
        <img src="image/Assistant-Personnel.jpg" alt="Assistant GED" class="img-fluid rounded-4 shadow hover-scale" style="max-height: 350px;">
      </div>
    </div>
  </div>
</section>


<div class="container-fluid wow fadeInUp" data-wow-delay="0.1s" style="background: linear-gradient(to right, #00bcd4, #3cc994);">
    <div class="scroll-animated container-fluid py-5">
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 800px;">
            <h1 class="mb-0 text-white">Vos données sont sécurisées et hébergées en France.</h1>
            <p class="text-dark-50">Expertise et technologie au service de la gestion documentaire et de la sécurité des données.</p>
        </div>
        <div class="row g-4 g-md-5 justify-content-center">

            <!-- Vos données sont sécurisées et hébergées en France -->
            <div class="scroll-animated col-12 col-md-4 wow slideInUp" data-wow-delay="0.3s">
                <div class="blog-item bg-light rounded overflow-hidden shadow-lg h-100">
                    <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Sécurisation des données</h4>
                        <p>Vos données sont hébergées en France, conformes aux normes de protection des données en vigueur en France et dans l’Union Européenne.</p>
                    </div>
                </div>
            </div>

            <!-- Développement et hébergement en France -->
            <div class="scroll-animated col-12 col-md-4 wow slideInUp" data-wow-delay="0.6s">
                <div class="blog-item bg-light rounded overflow-hidden shadow-lg h-100">
                    <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-location-arrow fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Hébergement en France</h4>
                        <p>
                        L'hébergement de notre solution de Gestion Intelligente de Courrier se fait entièrement en France, assurant ainsi une sécurité optimale et une conformité totale aux normes européennes de protection des données (RGPD). 
                        De plus, le développement et l'innovation continue sont réalisés en République Démocratique du Congo, garantissant une expertise locale et une grande proximité avec les besoins spécifiques de nos clients.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Accompagnement sur-mesure -->
            <div class="scroll-animated col-12 col-md-4 wow slideInUp" data-wow-delay="0.9s">
                <div class="blog-item bg-light rounded overflow-hidden shadow-lg h-100">
                    <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Accompagnement sur-mesure</h4>
                        <p>Nous vous offrons un accompagnement personnalisé pour garantir une utilisation optimale de notre solution GIC.</p>
                    </div>
                </div>
            </div>

            <!-- Équipe basée en France -->
            <div class="scroll-animated col-12 col-md-4 wow slideInUp" data-wow-delay="1.2s">
                <div class="blog-item bg-light rounded overflow-hidden shadow-lg h-100">
                    <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Une équipe internationale et locale</h4>
                        <p>
                        Notre équipe est répartie à travers le monde, avec une présence forte en République Démocratique du Congo, ainsi qu'en France et dans d'autres pays. 
                        Cette organisation nous permet de vous offrir une assistance locale réactive tout en bénéficiant d'une expertise internationale et d'une compréhension approfondie des besoins spécifiques de nos clients, où qu'ils se trouvent.
                        </p>

                    </div>
                </div>
            </div>

            <!-- Serveurs certifiés ISO/IEC et CSA STAR -->
            <div class="scroll-animated col-12 col-md-4 wow slideInUp" data-wow-delay="1.5s">
                <div class="blog-item bg-light rounded overflow-hidden shadow-lg h-100">
                    <div class="p-4 text-center d-flex flex-column justify-content-center align-items-center">
                        <i class="fas fa-cogs fa-3x text-primary mb-3"></i>
                        <h4 class="mb-3">Certification de nos serveurs</h4>
                        <p>Nos serveurs sont certifiés ISO/IEC et CSA STAR, garantissant une sécurité maximale pour vos données.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Section Compteurs avec animation et effet survol -->
<section class="scroll-animated py-5" style="background: linear-gradient(to bottom right, #fef3c7, #ffffff); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); margin: 2rem auto; max-width: 1000px;">
  <div class="container px-4">
    <div class="row g-4 text-center">

      <!-- Utilisateurs -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="bg-light rounded p-4 hover-scale shadow-sm">
          <i class="fas fa-users fa-3x text-primary mb-3"></i>
          <h2 class="fw-bold mb-0 counter" data-count="5000">0</h2>
          <p class="mt-2 mb-0">Utilisateurs</p>
        </div>
      </div>

      <!-- Entreprises -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="bg-light rounded p-4 hover-scale shadow-sm">
          <i class="fas fa-building fa-3x text-success mb-3"></i>
          <h2 class="fw-bold mb-0 counter" data-count="1200">0</h2>
          <p class="mt-2 mb-0">Entreprises</p>
        </div>
      </div>

      <!-- Pays -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="bg-light rounded p-4 hover-scale shadow-sm">
          <i class="fas fa-globe-africa fa-3x text-warning mb-3"></i>
          <h2 class="fw-bold mb-0 counter" data-count="18">0</h2>
          <p class="mt-2 mb-0">Pays</p>
        </div>
      </div>

      <!-- Documents -->
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="bg-light rounded p-4 hover-scale shadow-sm">
          <i class="fas fa-file-alt fa-3x text-danger mb-3"></i>
          <h2 class="fw-bold mb-0 counter" data-count="100000">0</h2>
          <p class="mt-2 mb-0">Documents gérés</p>
        </div>
      </div>

    </div>
  </div>
</section>


<!-- Section Contact avec style de bouton -->
<section id="contact" class="scroll-animated py-5" style="background: linear-gradient(to bottom right, #ffeb3b, #ffffff); border-radius: 20px; box-shadow: 0 8px 20px rgba(0,0,0,0.1); margin: 2rem auto; max-width: 1000px;">
  <div class="container px-4">
    <div class="row align-items-center">
      <!-- Texte côté gauche -->
      <div class="scroll-animated col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
        <h2 class="fw-bold mb-4" style="font-size: 2.8rem;">Besoin de plus d'informations ?</h2>
        <p style="font-size: 1.15rem; line-height: 1.8;">
          Contactez notre équipe commerciale pour une démo gratuite et découvrez comment notre solution GIC peut transformer votre gestion documentaire !
        </p>
      </div>

      <!-- Conteneur avec icône et bouton -->
      <div class="scroll-animated col-lg-6">
        <div class="blog-item bg-light rounded overflow-hidden h-100 d-flex flex-column justify-content-center text-center p-4 hover-scale">
          <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
          <h4 class="mb-3">Nous contacter pour plus d'infos</h4>
          <p>Envoyez-nous un message pour commencer une démo gratuite ou obtenir plus d'informations.</p>
          <a href="https://wa.me/243897604018" target="_blank" class="btn btn-success text-uppercase mt-3">
            Commencer un essai gratuit
          </a>
        </div>
      </div>
    </div>
  </div>
</section>



@endsection
