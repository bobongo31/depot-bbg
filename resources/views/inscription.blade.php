@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<div class="container">
    <h2 class="form-title text-center mb-5">Demandez un Essai Gratuit</h2>
    
    <form action="{{ route('utilisateur.store') }}" method="POST" class="form-container">
        @csrf <!-- Protection contre les attaques CSRF -->
        
        <div class="mb-3">
            <label for="first_name" class="form-label"><i class="fas fa-user"></i> Prénom</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        
        <div class="mb-3">
            <label for="last_name" class="form-label"><i class="fas fa-user-alt"></i> Nom</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        
        <div class="mb-3">
            <label for="company_name" class="form-label"><i class="fas fa-building"></i> Nom de l'entreprise</label>
            <input type="text" class="form-control" id="company_name" name="company_name" required>
        </div>
        
        <div class="mb-3">
            <label for="country" class="form-label"><i class="fas fa-globe"></i> Pays</label>
            <input type="text" class="form-control" id="country" name="country" required>
        </div>
        
        <div class="mb-3">
            <label for="city" class="form-label"><i class="fas fa-city"></i> Ville</label>
            <input type="text" class="form-control" id="city" name="city" required>
        </div>
        
        <div class="mb-3">
            <label for="province" class="form-label"><i class="fas fa-map-marked-alt"></i> Province</label>
            <input type="text" class="form-control" id="province" name="province" required>
        </div>
        
        <div class="mb-3">
            <label for="postal_code" class="form-label"><i class="fas fa-mail-bulk"></i> Code postal</label>
            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        
        <div class="mb-3">
            <label for="phone" class="form-label"><i class="fas fa-phone"></i> Numéro de téléphone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
        </div>

        <button type="submit" class="btn btn-primary w-100 btn-hover-effect">Commencer l'essai gratuit</button>
    </form>
</div>

<!-- Animation de défilement -->
<script>
    window.addEventListener('scroll', function() {
        let scroll = window.scrollY;
        let elements = document.querySelectorAll('.form-container');
        elements.forEach(function(element) {
            let position = element.getBoundingClientRect().top;
            if (position < window.innerHeight - 150) {
                element.classList.add('animate');
            }
        });
    });
</script>

@endsection

@section('styles')
<style>
    /* Styles personnalisés */
    .form-container {
        max-width: 500px;
        margin: 0 auto;
        padding: 30px;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    .form-container.animate {
        opacity: 1;
    }

    .form-label {
        font-weight: bold;
    }

    /* Effet survol du bouton */
    .btn-hover-effect {
        position: relative;
        overflow: hidden;
        background-color: #007bff;
        color: #fff;
        border: none;
        transition: background-color 0.3s;
    }

    .btn-hover-effect:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background-color: rgba(255, 255, 255, 0.2);
        transition: width 0.5s, height 0.5s, top 0.5s, left 0.5s;
        border-radius: 50%;
        z-index: 0;
        transform: translate(-50%, -50%);
    }

    .btn-hover-effect:hover {
        background-color: #0056b3;
    }

    .btn-hover-effect:hover:before {
        width: 0;
        height: 0;
        top: 50%;
        left: 50%;
    }

    /* Icônes Font Awesome */
    .form-label i {
        margin-right: 8px;
        color: #007bff;
    }

    /* Formulaire responsive */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
    }
</style>
@endsection
