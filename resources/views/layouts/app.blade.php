<!-- resources/views/layout.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Gestion_Courrier') }}</title>
  <link rel="dns-prefetch" href="//fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
  <!-- Ajout de Font Awesome via CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

  <!-- Import Tailwind CSS et JS via Vite -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900">
  <div id="app" class="flex min-h-screen flex-col">
    <!-- Inclusion du header contenant le menu -->
    @include('header')

    <!-- Zone de contenu principal -->
    <main class="flex-grow p-6">
      @yield('content')
    </main>
  </div>

    @auth
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
    </form>
    @endauth

  <!-- JavaScript pour le dropdown du profil -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
  jQuery(document).ready(function($) {
    var notificationCount = 0;

    // Détecter les changements dans les inputs, selects et textareas de la table
    $('#courriersTable').find('input, select, textarea').on('change', function(){
        notificationCount++;
        updateNotificationIcon(notificationCount);
    });

    // Fonction qui met à jour l'icône de notification avec un badge
    function updateNotificationIcon(count){
        var displayCount = (count >= 2) ? 2 : count;
        var badgeHtml = '<span class="notification-badge absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">' 
                        + displayCount + '</span>';
        $('.notification-icon').find('.notification-badge').remove();
        $('.notification-icon').append(badgeHtml);
    }

    // Gestion du menu profil en vanilla JS
    const profileBtn = document.getElementById('profile-btn');
    const profileMenu = document.getElementById('profile-menu');
    if (profileBtn && profileMenu) {
        profileBtn.addEventListener('click', function () {
            profileMenu.classList.toggle('hidden');
        });
    }
});
</script>
</body>
</html>
