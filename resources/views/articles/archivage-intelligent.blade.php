@extends('layouts.app')

@section('title', 'Archivage Intelligent | GIC')

@section('meta')
    <meta name="description" content="Découvrez l’archivage intelligent avec GIC : indexation automatique, recherche rapide et accès sécurisé à vos documents.">
    <meta name="keywords" content="archivage, GIC, intelligent, indexation, documents, recherche, cloud, gestion documentaire, accès sécurisé">
    <meta property="og:title" content="Archivage Intelligent | GIC">
    <meta property="og:description" content="GIC offre un archivage intelligent avec des fonctions avancées d'indexation et une recherche ultra-rapide pour une gestion documentaire optimale.">
    <meta property="og:image" img src="https://172.233.244.133.nip.io/image/Archivage_Intelligent.jpg">
    <!-- Open Graph pour Facebook -->
    <meta property="og:title" content="Archivage Intelligent de GIC">
    <meta property="og:description" content="Découvrez l’archivage intelligent avec GIC : indexation automatique, recherche rapide et accès sécurisé à vos documents.">
    <meta property="og:image" img src="https://172.233.244.133.nip.io/image/Archivage_Intelligent.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Archivage Intelligent de GIC">
    <meta name="twitter:description" content="Découvrez l’archivage intelligent avec GIC : indexation automatique, recherche rapide et accès sécurisé à vos documents.">
    <meta name="twitter:image" img src="https://172.233.244.133.nip.io/image/Archivage_Intelligent.jpg">
@endsection

@section('content')
<section style="background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="container text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Archivage Intelligent</h2>
        <p class="mb-5" style="font-size: 18px;">Optimisez la gestion de vos documents avec un archivage intelligent, l’indexation automatique et un accès sécurisé grâce à GIC.</p>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="bg-dark text-white p-5 rounded shadow-lg">
                    <img src="{{ asset('image/Archivage_Intelligent.jpg') }}" alt="Archivage Intelligent" class="img-fluid mb-4 rounded">
                    <p><strong>Exemple concret :</strong> Vous gérez des centaines de documents internes chaque mois. Plutôt que de chercher un document en particulier, GIC indexe automatiquement tous les fichiers en fonction de leur contenu, de leur date, et de leur type. Cela vous permet de retrouver n'importe quel document en quelques secondes grâce à une recherche par mots-clés.</p>
                    <p>Plus besoin de passer des heures à fouiller dans des dossiers physiques ou numériques mal organisés. GIC permet de sécuriser vos archives en ligne, de les indexer intelligemment et de les retrouver en quelques clics, peu importe le volume de données.</p>
                    <p><strong>Appel à l'action :</strong> Prenez en main votre gestion documentaire aujourd'hui même et simplifiez votre travail grâce à GIC. Inscrivez-vous maintenant !</p>
                    <a href="inscription" class="btn btn-primary mt-3">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
