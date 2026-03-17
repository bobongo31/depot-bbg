@extends('layouts.app')

@section('title', 'Maintenance en cours')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-warning">
        <div class="text-center p-6 bg-white rounded-lg shadow-lg max-w-lg w-full">
            <!-- Silhouette d'erreur avec Font Awesome (icône de maintenance) -->
            <div class="text-8xl text-yellow-600">
                <i class="fas fa-cogs"></i>
            </div>

            <h1 class="text-5xl font-bold text-gray-900 mt-4">503</h1>
            <p class="mt-4 text-lg text-gray-700">Le site est actuellement en maintenance. Merci de revenir plus tard.</p>

            <!-- Compteur élégant avec effet de chiffres animés (avec couleur bleu ciel) -->
            <div class="mt-6">
                <p class="text-2xl text-gray-600">Temps avant réouverture :</p>
                <div id="countdown" class="text-4xl font-bold text-info">01:00:00</div>
            </div>

            <!-- Lien de retour à l'accueil -->
            <a href="/" class="text-blue-500 underline mt-6 inline-block hover:text-blue-700">Retour à l'accueil</a>
        </div>
    </div>

    <!-- Script pour le compteur de 1 heure -->
    <script>
        let countdownElement = document.getElementById('countdown');
        let countdownValue = 3600; // 1 heure en secondes

        function formatTime(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const remainingSeconds = seconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(remainingSeconds).padStart(2, '0')}`;
        }

        function updateCountdown() {
            if (countdownValue > 0) {
                countdownValue--;
                countdownElement.textContent = formatTime(countdownValue);
            } else {
                window.location.href = '/';
            }
        }

        setInterval(updateCountdown, 1000);
    </script>
@endsection
