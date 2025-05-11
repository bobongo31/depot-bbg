@extends('layouts.app')

@section('title', 'Page non trouvée')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500">
        <div class="text-center p-6 bg-white rounded-lg shadow-lg max-w-lg w-full">
            <!-- Silhouette d'erreur avec Font Awesome -->
            <div class="text-8xl text-yellow-600">
                <i class="fas fa-search-minus"></i>
            </div>

            <h1 class="text-5xl font-bold text-gray-900 mt-4">404</h1>
            <p class="mt-4 text-lg text-gray-700">Désolé, la page que vous recherchez n'existe pas.</p>

            <!-- Compteur élégant avec effet de chiffres animés -->
            <div class="mt-6">
                <p class="text-2xl text-gray-600">Temps avant retour :</p>
                <div id="countdown" class="text-4xl font-bold text-purple-700">10</div>
            </div>

            <!-- Lien de retour à l'accueil -->
            <a href="/" class="text-blue-500 underline mt-6 inline-block hover:text-blue-700">Retour à l'accueil</a>
        </div>
    </div>

    <!-- Script pour le compteur -->
    <script>
        let countdownElement = document.getElementById('countdown');
        let countdownValue = 10;

        function updateCountdown() {
            if (countdownValue > 0) {
                countdownValue--;
                countdownElement.textContent = countdownValue;
            } else {
                window.location.href = '/';
            }
        }

        setInterval(updateCountdown, 1000);
    </script>
@endsection
