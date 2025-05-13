@extends('layouts.app')

@section('title', 'Gestion de Stock et Vente | GIC')

@section('meta')
    <!-- Meta Description et Keywords -->
    <meta name="description" content="Gérez vos produits, stocks et ventes en temps réel avec GIC. Contrôle des niveaux de stock et génération de factures.">
    <meta name="keywords" content="gestion de stock, vente, facturation, GIC, suivi inventaire, produits, gestion commerciale">

    <!-- Open Graph (pour Facebook, LinkedIn, etc.) -->
    <meta property="og:type" content="article">
    <meta property="og:title" content="Gestion de Stock et Vente | GIC">
    <meta property="og:description" content="Surveillez vos niveaux de stock, suivez vos ventes et émettez des factures automatiquement avec GIC.">
    <meta property="og:image" content="https://172.233.244.133.nip.io/image/Gestion_de_Stock_Vente.jpg">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="GIC Platform">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Gestion de Stock et Vente | GIC">
    <meta name="twitter:description" content="GIC vous aide à suivre vos ventes, gérer vos produits et automatiser votre facturation.">
    <meta name="twitter:image" content="https://172.233.244.133.nip.io/image/Gestion_de_Stock_Vente.jpg">
    <meta name="twitter:site" content="@GICPlatform">
    <meta name="twitter:creator" content="@GICPlatform">
@endsection

@section('content')
<section style="background: linear-gradient(135deg, rgb(19, 28, 37), rgb(10, 42, 68)); padding: 60px 20px;">
    <div class="container text-white">
        <h2 class="mb-4" style="font-weight: bold; color: #00ccff;">Gestion de Stock et Vente</h2>
        <p class="mb-5" style="font-size: 18px;">Pilotez vos opérations commerciales en temps réel : suivi de stock, gestion des ventes, alertes de rupture, génération automatique de factures et rapports financiers.</p>

        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-12">
                <div class="bg-dark text-white p-5 rounded shadow-lg">
                    <img src="{{ asset('image/Gestion_de_Stock_Vente.jpg') }}" alt="Gestion de Stock et Vente" class="img-fluid mb-4 rounded">
                    <p><strong>Exemple concret :</strong> Votre boutique reçoit une commande de 5 unités d’un produit. GIC déduit automatiquement le stock, envoie une facture au client, et vous alerte si le stock passe sous un seuil critique. Vous pouvez alors décider de réapprovisionner immédiatement.</p>
                    <p>Le module vous permet aussi de créer des bons de commande, de gérer les fournisseurs, d’avoir des rapports de vente détaillés, et de suivre votre chiffre d'affaires au quotidien.</p>
                    <p><strong>Appel à l'action :</strong> Optimisez votre activité commerciale avec une solution tout-en-un. Essayez gratuitement GIC et gérez vos stocks et ventes en toute simplicité.</p>
                    <a href="inscription" class="btn btn-primary mt-3">Essayer gratuitement</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
