{{-- resources/views/flights/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Vols disponibles')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header avec recherche -->
    <div class="mb-12">
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2"></h1>
            <p class="text-lg text-gray-600">Trouvez votre vol idéal pour votre prochain voyage</p>
        </div>

        <!-- Formulaire de recherche -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-8 border border-blue-100">
            <form action="{{ route('flights.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="space-y-2">
                    <label for="departure" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Départ
                    </label>
                    <input type="text"
                           name="departure"
                           id="departure"
                           value="{{ request('departure') }}"
                           placeholder="Ex: DAK, CDG"
                           class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="arrival" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Arrivée
                    </label>
                    <input type="text"
                           name="arrival"
                           id="arrival"
                           value="{{ request('arrival') }}"
                           placeholder="Ex: JFK, LHR"
                           class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all duration-200 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="date" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Date
                    </label>
                    <input type="date"
                           name="date"
                           id="date"
                           value="{{ request('date') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 transition-all duration-200 bg-white">
                </div>

                <div class="space-y-2">
                    <label for="sort" class="flex items-center text-sm font-semibold text-gray-700">
                        <svg class="w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        Trier par
                    </label>
                    <select name="sort"
                            id="sort"
                            class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition-all duration-200 bg-white">
                        <option value="departure" {{ request('sort') === 'departure' ? 'selected' : '' }}>Date de départ</option>
                        <option value="price_asc" {{ request('sort') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                        <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                    </select>
                </div>

                <div class="flex items-end space-x-3">
                    <button type="submit"
                            class="flex-1 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-indigo-700 transform hover:scale-105 transition-all duration-200 shadow-lg font-semibold">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Rechercher
                    </button>
                    @if(request()->hasAny(['departure', 'arrival', 'date', 'sort']))
                    <a href="{{ route('flights.index') }}"
                       class="px-4 py-3 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 hover:border-gray-400 transition-all duration-200 font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
    
    <!-- Bouton synchronisation -->
    <div class="mb-6 flex justify-end">
        <form action="{{ route('flights.sync') }}" method="POST">
            @csrf
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Synchroniser les vols
            </button>
        </form>
    </div>
    
    <!-- Liste des vols -->
    @if($flights->count() > 0)
    <div class="grid grid-cols-1 gap-8">
        @foreach($flights as $flight)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 overflow-hidden border border-gray-100">
            <!-- Header avec dégradé -->
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm font-medium">{{ $flight->airline }}</p>
                            <p class="text-2xl font-bold">{{ $flight->flight_number }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                            @if($flight->available_seats > 50) bg-green-500 text-white
                            @elseif($flight->available_seats > 20) bg-yellow-500 text-white
                            @else bg-red-500 text-white
                            @endif">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $flight->available_seats }} places
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Départ -->
                    <div class="text-center lg:text-left">
                        <div class="flex items-center justify-center lg:justify-start mb-3">
                            <div class="bg-blue-100 rounded-full p-2 mr-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </div>
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Départ</span>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $flight->departure_airport }}</p>
                        <p class="text-sm text-gray-500 mb-2">{{ $flight->departure_time->format('d/m/Y') }}</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $flight->departure_time->format('H:i') }}</p>
                    </div>

                    <!-- Durée et avion -->
                    <div class="text-center flex flex-col justify-center">
                        <div class="relative mb-4">
                            <div class="flex items-center justify-center">
                                <div class="bg-gray-100 rounded-full p-4">
                                    <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="bg-white rounded-full px-3 py-1 shadow-md border">
                                    <span class="text-sm font-semibold text-gray-700">{{ floor($flight->duration / 60) }}h {{ $flight->duration % 60 }}m</span>
                                </div>
                            </div>
                        </div>
                        @if($flight->aircraft_type)
                        <div class="flex items-center justify-center text-sm text-gray-500">
                            <svg class="h-4 w-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                            </svg>
                            <span class="font-medium">{{ $flight->aircraft_type }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Arrivée -->
                    <div class="text-center lg:text-right">
                        <div class="flex items-center justify-center lg:justify-end mb-3">
                            <span class="text-sm font-semibold text-gray-600 uppercase tracking-wide mr-3">Arrivée</span>
                            <div class="bg-green-100 rounded-full p-2">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-bold text-gray-900 mb-1">{{ $flight->arrival_airport }}</p>
                        <p class="text-sm text-gray-500 mb-2">{{ $flight->arrival_time->format('d/m/Y') }}</p>
                        <p class="text-2xl font-bold text-green-600">{{ $flight->arrival_time->format('H:i') }}</p>
                    </div>
                </div>

                <!-- Prix et actions -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="mb-6 lg:mb-0">
                            <p class="text-sm text-gray-500 mb-1">Prix par personne</p>
                            <div class="flex items-baseline">
                                <span class="text-4xl font-bold text-gray-900">{{ number_format($flight->price, 0, ',', ' ') }}</span>
                                <span class="text-lg text-gray-500 ml-2">FCFA</span>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('flights.show', $flight) }}"
                               class="inline-flex justify-center items-center px-6 py-3 border-2 border-gray-300 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 rounded-xl transition-all duration-200 font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Voir détails
                            </a>
                            <a href="{{ route('reservations.create', $flight) }}"
                               class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white hover:from-green-600 hover:to-emerald-700 rounded-xl transform hover:scale-105 transition-all duration-200 font-semibold shadow-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Choisir ce vol
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($flights->hasPages())
    <div class="mt-8">
        {{ $flights->links() }}
    </div>
    @endif
    
    @else
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun vol disponible</h3>
        <p class="text-gray-500 mb-6">
            @if(request()->hasAny(['departure', 'arrival', 'date']))
                Aucun vol ne correspond à vos critères de recherche
            @else
                Veuillez synchroniser les vols ou modifier vos critères de recherche
            @endif
        </p>
        <div class="flex justify-center space-x-4">
            @if(request()->hasAny(['departure', 'arrival', 'date']))
            <a href="{{ route('flights.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Réinitialiser la recherche
            </a>
            @endif
            <form action="{{ route('flights.sync') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Synchroniser les vols
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection