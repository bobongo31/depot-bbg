@extends('layouts.app')

@section('content')
<!-- Contact Start -->
<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
        <!-- Titre -->
        <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
            <h1 class="fw-bold mb-0">
                Contactez-nous
            </h1>
            <p style="font-size: 1.15rem; line-height: 1.8;">
                Une assistance Opérationnel 24h/24, veuillez nous appeler, écrire ou pendre rendez-vous avec un de nos experts.
        </p>
            
        </div>

        <!-- Coordonnées -->
        <div class="row g-5 mb-5">
            <!-- Téléphone -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center justify-content-center rounded" style="background-color: #0756ea; width: 60px; height: 60px;">
                        <i class="fa fa-phone-alt text-white"></i>
                    </div>
                    <div class="ps-4">
                        <h5 class="mb-0" style="color:#0756ea;">+243 897 604 018</h5>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.4s">
                    <div class="d-flex align-items-center justify-content-center rounded" style="background-color: #0756ea; width: 60px; height: 60px;">
                        <i class="fa fa-envelope-open text-white"></i>
                    </div>
                    <div class="ps-4">
                        <h5 class="mb-0" style="color:#0756ea;">info@keynsoft.tech</h5>
                    </div>
                </div>
            </div>

            <!-- Adresse -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.8s">
                    <div class="d-flex align-items-center justify-content-center rounded" style="background-color: #0756ea; width: 60px; height: 60px;">
                        <i class="fa fa-map-marker-alt text-white"></i>
                    </div>
                    <div class="ps-4">
                        <h5 class="mb-0" style="color:#0756ea;">15, AV. Lutendele, C. Mont-Ngafula, Kinshasa/RDC</h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire + Carte -->
        <div class="row g-5">
            <!-- Formulaire -->
            <div class="col-lg-6 wow slideInUp" data-wow-delay="0.3s">
                <form method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="name" class="form-control border-0 bg-light px-4" placeholder="Votre Nom" style="height: 55px;" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="email" class="form-control border-0 bg-light px-4" placeholder="Votre Email" style="height: 55px;" required>
                        </div>
                        <div class="col-12">
                            <input type="text" name="subject" class="form-control border-0 bg-light px-4" placeholder="Sujet" style="height: 55px;">
                        </div>
                        <div class="col-12">
                            <textarea name="message" class="form-control border-0 bg-light px-4 py-3" rows="4" placeholder="Message" required></textarea>
                        </div>
                        <div class="col-12">
                            <button class="btn w-100 py-3" type="submit" style="background-color:#0756ea; color: #fff;">
                                Envoyer le message
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Carte Google Maps -->
            <div class="col-lg-6 wow slideInUp" data-wow-delay="0.6s">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1673.0771685217971!2d15.330542589672872!3d-4.394143300268091!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1a6a375c44f60de5%3A0x93d39660224f7997!2sStation%20Salongo!5e1!3m2!1sfr!2scd!4v1704891450145!5m2!1sfr!2scd" 
                    width="100%" 
                    height="350" 
                    style="border:0;" 
                    allowfullscreen 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </div>
</div>
<!-- Contact End -->
@endsection
