@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Début du Hero Section -->
<div class="scroll-animated container-fluid py-5" style="background: linear-gradient(135deg, #756c02ff 0%, #ffd900ff 100%);">
    <div class="container">
        <div class="row align-items-center g-5">
            
            <!-- Message de bienvenue -->
            <div class="scroll-animated col-lg-6 text-white">
                <h1 class="mb-4" style="font-weight: 700; font-size: 3rem;">
                    Bienvenue à vous !
                </h1>
                <h4 class="mb-3 fst-italic">
                    “Ensemble, valorisons notre culture grâce à une gestion moderne et efficace.”
                </h4>
                <p class="mb-4" style="font-size: 1.1rem;">
                    Le Fonds de Promotion Culturelle innove avec GIC, votre nouvel outil numérique pensé pour vous accompagner dans la gestion quotidienne.<br><br>
                    Grâce à GIC, simplifiez vos tâches administratives, gagnez du temps, et concentrez-vous sur ce qui compte vraiment : la promotion et la valorisation de la culture congolaise.<br><br>
                    Ensemble, faisons de la transformation digitale un levier de performance et d'excellence pour notre institution.
                </p>
                <div class="scroll-animated d-flex gap-3">
                    <a href="login" class="btn btn-outline-light text-uppercase px-4 py-2">
                        Commencer maintenant
                    </a>
                </div>
            </div>

            <!-- Image illustrative -->
            <div class="scroll-animated col-lg-6 text-center">
                <img src="image/fpc.png" alt="Illustration FPC avec GIC" class="img-fluid hover-zoom" style="max-width: 100%;">
            </div>

        </div>
    </div>
</div>
<!-- Fin du Hero Section -->


@endsection
