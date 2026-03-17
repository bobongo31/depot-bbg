@extends('layouts.app')

@section('title', 'Messagerie Sécurisée | GIC')

@section('meta')
    <meta name="description" content="Communiquez en toute sécurité grâce à la messagerie interne chiffrée de GIC. Protégez la confidentialité de vos données professionnelles.">
    <meta name="keywords" content="messagerie sécurisée, chiffrement, communication confidentielle, GIC, sécurité des données, communication interne, confidentialité">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="Messagerie Sécurisée | GIC">
    <meta property="og:description" content="Utilisez la messagerie sécurisée de GIC pour garantir la confidentialité de vos échanges internes. Chiffrement de bout en bout.">
    <meta property="og:image" content="https://172.233.244.133.nip.io/image/Messagerie_Sécurisée.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Messagerie Sécurisée avec GIC">
    <meta name="twitter:description" content="Communiquez en toute sécurité grâce à la messagerie interne chiffrée de GIC. Confidentialité garantie.">
    <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/Messagerie_Sécurisée.jpg">
@endsection

@section('content')
<section style="background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="scroll-animated container text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Messagerie Sécurisée</h2>
        <p class="mb-5" style="font-size: 18px;">Protégez vos échanges professionnels avec une messagerie interne sécurisée et chiffrée de bout en bout. Assurez la confidentialité de vos données.</p>

        <div class="scroll-animated row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="bg-dark text-white p-5 rounded shadow-lg">
                    <img src="{{ asset('image/Archivage_IntelligentOK.jpg') }}" alt="Messagerie Sécurisée" class="img-fluid mb-4 rounded">
                    <p><strong>Exemple concret :</strong> Imaginons que vous deviez discuter de sujets sensibles avec un collègue. Avec GIC, tous vos messages sont automatiquement chiffrés. Même si un message est intercepté, il reste illisible sans la clé de déchiffrement. Vous pouvez ainsi échanger des informations confidentielles en toute sérénité.</p>
                    <p>De plus, GIC vous offre une messagerie avec des fonctionnalités de sécurité avancées : vérification de l'identité, alertes de sécurité, et journalisation des connexions.</p>
                    <p><strong>Appel à l'action :</strong> Protégez vos échanges dès maintenant et optez pour une messagerie sécurisée avec GIC. Inscrivez-vous pour commencer !</p>
                    <a href="{{ url('inscription') }}" class="btn btn-primary mt-3">Commencer</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
