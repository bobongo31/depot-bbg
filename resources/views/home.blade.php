@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-6">
        <!-- Barre de recherche -->
        <div class="flex justify-center mb-6">
            <form action="{{ route('search') }}" method="GET" class="w-full flex items-center bg-white shadow-md rounded-lg px-4 py-2 max-w-screen-lg">
                <input type="text" name="query" class="w-full px-3 py-2 border-none outline-none text-gray-700"
                    placeholder="Rechercher un courrier ou un accusé de réception..." required>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                    Rechercher 🔍
                </button>
            </form>
        </div>
    </div>
@endsection
