@extends('layouts.app')

@section('title', 'Signature Électronique | GIC')

@section('meta')
    <meta name="description" content="Validez vos documents avec une signature électronique légale et sécurisée grâce à GIC.">
    <meta name="keywords" content="signature électronique, GIC, signature numérique, validation document, sécurité juridique, documents signés">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="Signature Électronique | GIC">
    <meta property="og:description" content="Utilisez la signature électronique de GIC pour certifier vos documents en toute légalité et simplicité.">
    <meta property="og:image" content="https://172.233.244.133.nip.io/image/Signature-Numerique.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="GIC Platform">
    <meta property="og:type" content="article">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Signature Électronique | GIC">
    <meta name="twitter:description" content="Signez vos documents en ligne avec GIC. Sécurité, légalité et rapidité.">
    <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/Signature-Numerique.jpg">
    <meta name="twitter:site" content="@GICPlatform">
    <meta name="twitter:creator" content="@GICPlatform">
@endsection

@section('content')
<section style="background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="scroll-animated container text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Signature Électronique</h2>
        <p class="mb-5" style="font-size: 18px;">Signez vos documents en ligne de façon sécurisée avec une signature électronique conforme aux normes légales. Fini les impressions inutiles !</p>

        <div class="scroll-animated row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="bg-dark text-white p-5 rounded shadow-lg">
                    <img src="{{ asset('image/Signature-Numerique.jpg') }}" alt="Signature Électronique" class="img-fluid mb-4 rounded">
                    <p><strong>Exemple concret :</strong> Vous devez envoyer un contrat à signer à un client à distance ? Téléversez le PDF dans GIC, sélectionnez les zones à signer, et envoyez le lien sécurisé. Le client reçoit une notification, signe électroniquement, et le document est automatiquement archivé avec un certificat de validité juridique.</p>
                    <p>La signature électronique sur GIC est conforme aux normes eIDAS et offre une traçabilité complète : adresse IP, date, heure et certificat numérique sont enregistrés.</p>
                    <p><strong>Appel à l'action :</strong> Passez à la signature numérique et réduisez vos délais de traitement documentaire. Activez la fonctionnalité dès maintenant dans votre espace GIC.</p>
                    <a href="{{ url('inscription') }}" class="btn btn-primary mt-3">Activer la signature</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
