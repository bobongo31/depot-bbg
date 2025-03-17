@auth
    <li>
        <a href="{{ route('home') }}" class="px-4 py-2 block text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-md">
            <i class="fas fa-home"></i> Accueil
        </a>
    </li>
@endauth

@if(Auth::user() && Auth::user()->role === 'agent')
    <li>
        <a href="{{ route('accuse.form') }}" class="px-4 py-2 block text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-md">
            <i class="fas fa-file-alt"></i> Accuser Réception
        </a>
    </li>
    <li>
        <a href="{{ route('courriers.create') }}" class="px-4 py-2 block text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-md">
            <i class="fas fa-plus-circle"></i> Enregistrer un Courrier
        </a>
    </li>
    <li>
        <a href="{{ route('accuses.index') }}" class="px-4 py-2 block text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-md">
            <i class="fas fa-list-ul"></i> Liste des accusés réception
        </a>
    </li>
@endif

@if(Auth::user() && (Auth::user()->role === 'agent' || Auth::user()->role === 'admin'))
    <li>
        <a href="{{ route('courriers.index') }}" class="px-4 py-2 block text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-md">
            <i class="fas fa-envelope-open-text"></i> Tous les Courriers
        </a>
    </li>
@endif

@if(Auth::user() && (Auth::user()->role === 'chef_service' || Auth::user()->role === 'admin'))
    <li>
        <a href="{{ route('reponses.index') }}" class="px-4 py-2 block text-green-500 hover:text-green-700 hover:bg-green-100 rounded-md">
            <i class="fas fa-inbox"></i> Boîte de réception
        </a>
    </li>
    <li>
        <a href="{{ route('telegramme.create') }}" class="px-4 py-2 block text-blue-500 hover:text-blue-700 hover:bg-blue-100 rounded-md">
            <i class="fas fa-paper-plane"></i> Envoyer un Télégramme
        </a>
    </li>
@endif

@if(Auth::user() && Auth::user()->role === 'directeur_general')
    <li>
        <a href="{{ route('courriers.traites') }}" class="px-4 py-2 block text-green-500 hover:text-green-700 hover:bg-green-100 rounded-md">
            <i class="fas fa-check-circle"></i> Courriers Traités
        </a>
    </li>
@endif
