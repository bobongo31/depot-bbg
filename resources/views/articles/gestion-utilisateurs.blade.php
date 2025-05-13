@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs | GIC')

@section('meta')
    <meta name="description" content="Gérez les utilisateurs de manière flexible avec GIC : rôles, autorisations et audits intégrés pour une gestion optimisée.">
    <meta name="keywords" content="gestion utilisateurs, GIC, rôles, autorisations, audits, administration des utilisateurs">
    <meta property="og:title" content="Gestion des Utilisateurs | GIC">
    <meta property="og:description" content="Créez, gérez et suivez les rôles des utilisateurs avec un système flexible et sécurisé, intégré à GIC.">
    <meta property="og:image" img src="https://172.233.244.133.nip.io/image/Gestion-des-Utilisateurs.jpg">
    <!-- Open Graph pour Facebook -->
    <meta property="og:title" content="Gestion des Utilisateurs avec GIC">
    <meta property="og:description" content="Gérez les utilisateurs de manière flexible avec GIC : rôles, autorisations et audits intégrés pour une gestion optimisée.">
    <meta property="og:image" img src="https://172.233.244.133.nip.io/image/Gestion-des-Utilisateurs.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Gestion des Utilisateurs avec GIC">
    <meta name="twitter:description" content="Gérez les utilisateurs de manière flexible avec GIC : rôles, autorisations et audits intégrés pour une gestion optimisée.">
    <meta name="twitter:image" img src="https://172.233.244.133.nip.io/image/Gestion-des-Utilisateurs.jpg">
@endsection

@section('content')
<section style="background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="container text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Gestion des Utilisateurs</h2>
        <p class="mb-5" style="font-size: 18px;">Attribuez des rôles et des autorisations à vos utilisateurs, suivez leur activité, et optimisez la gestion des accès avec GIC.</p>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="bg-dark text-white p-5 rounded shadow-lg">
                    <img src="{{ asset('image/Gestion-des-Utilisateurs.jpg') }}" alt="Gestion des Utilisateurs" class="img-fluid mb-4 rounded">
                    <p><strong>Exemple concret :</strong> Imaginez que vous souhaitiez restreindre l'accès à certaines informations sensibles au sein de votre équipe. Avec GIC, vous pouvez créer des rôles personnalisés et attribuer des permissions d'accès selon les besoins de chaque membre de votre organisation.</p>
                    <p>Chaque action effectuée par un utilisateur est enregistrée pour garantir une traçabilité complète. Vous pourrez facilement consulter l'historique des activités de chaque utilisateur grâce aux rapports d'audit intégrés.</p>
                    <p><strong>Appel à l'action :</strong> Commencez à gérer les utilisateurs et à sécuriser vos données dès aujourd'hui. Inscrivez-vous sur GIC !</p>
                    <a href="inscription" class="btn btn-primary mt-3">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
