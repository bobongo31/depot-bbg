@extends('layouts.app')

@section('title', 'Gestion de Courriers | GIC')

@section('meta')
    <meta name="description" content="Découvrez comment gérer vos courriers entrants et sortants avec GIC. Suivi, archivage et traitement simplifiés.">
    <meta name="keywords" content="gestion courriers, GIC, courrier, archivage, traitement, suivi, gestion des courriers entrants, gestion des courriers sortants">

    <!-- Open Graph pour les réseaux sociaux -->
    <meta property="og:title" content="Gestion de Courriers | GIC">
    <meta property="og:description" content="Gérez vos courriers entrants et sortants avec un système intuitif et sécurisé pour une meilleure organisation.">
    <meta property="og:image" content="https://172.233.244.133.nip.io/image/gestion_courrier.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Gestion de Courriers | GIC">
    <meta name="twitter:description" content="Découvrez comment gérer vos courriers entrants et sortants avec GIC. Suivi, archivage et traitement simplifiés.">
    <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/gestion_courrier.jpg">
@endsection

@section('content')
<section style="background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="scroll-animated container text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Gestion de Courriers</h2>
        <p class="mb-5" style="font-size: 18px;">Gérez vos courriers entrants et sortants de manière efficace et transparente avec GIC. Automatisez l'enregistrement, le suivi et l'archivage de tous vos documents.</p>

        <div class="scroll-animated row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="bg-dark text-white p-5 rounded shadow-lg">
                    <img src="{{ asset('image/gestion_courrier.jpg') }}" alt="Gestion de Courriers" class="img-fluid mb-4 rounded">
                    <p><strong>Exemple concret :</strong> Imaginons que vous receviez un paquet important. Grâce à GIC, vous pouvez enregistrer ce courrier instantanément avec une référence unique. Vous pouvez ensuite suivre l'état du traitement en temps réel, de la réception à la distribution. Si le courrier nécessite une action urgente, GIC vous alertera automatiquement.</p>
                    <p>Le processus est simple : enregistrez chaque document entrant ou sortant dans le système, ajoutez une description et une date, et associez-le à un destinataire ou un expéditeur. En quelques clics, vous avez un suivi complet et des archives accessibles à tout moment.</p>
                    <p><strong>Appel à l'action :</strong> Commencez dès maintenant à centraliser vos courriers et à améliorer votre organisation en vous inscrivant sur GIC.</p>
                    <a href="{{ url('inscription') }}" class="btn btn-primary mt-3">S'inscrire</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
