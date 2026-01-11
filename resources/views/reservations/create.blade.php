
@extends('layouts.app')

@section('title', 'Nouvelle réservation')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <a href="{{ route('flights.index') }}" class="text-gray-500 hover:text-gray-700">Vols</a>
            </li>
            <li>
                <span class="text-gray-400 mx-2">/</span>
            </li>
            <li class="text-gray-900 font-medium">Réservation</li>
        </ol>
    </nav>
    
    <h1 class="text-3xl font-bold text-white-900 mb-8">Nouvelle réservation</h1>
    
    <!-- Récapitulatif du vol -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Vol sélectionné</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Vol</p>
                <p class="text-xl font-bold text-gray-900">{{ $flight->flight_number }}</p>
                <p class="text-sm text-gray-600">{{ $flight->airline }}</p>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Itinéraire</p>
                <div class="flex items-center mt-2">
                    <div>
                        <p class="text-lg font-semibold">{{ $flight->departure_airport }}</p>
                        <p class="text-xs text-gray-500">{{ $flight->departure_time->format('d/m/Y H:i') }}</p>
                    </div>
                    <svg class="mx-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <div>
                        <p class="text-lg font-semibold">{{ $flight->arrival_airport }}</p>
                        <p class="text-xs text-gray-500">{{ $flight->arrival_time->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
            
            <div>
                <p class="text-sm text-gray-500">Prix</p>
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($flight->price, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>
    
    <!-- Formulaire -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Informations du passager</h2>
        
        <form action="{{ route('reservations.store', $flight) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Prénom -->
                <div>
                    <label for="passenger_first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Prénom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="passenger_first_name" 
                           id="passenger_first_name" 
                           value="{{ old('passenger_first_name', Auth::user()->name) }}"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('passenger_first_name') border-red-500 @enderror">
                    @error('passenger_first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Nom -->
                <div>
                    <label for="passenger_last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nom <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="passenger_last_name" 
                           id="passenger_last_name" 
                           value="{{ old('passenger_last_name') }}"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('passenger_last_name') border-red-500 @enderror">
                    @error('passenger_last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Téléphone -->
                <div>
                    <label for="passenger_phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Numéro de téléphone <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           name="passenger_phone" 
                           id="passenger_phone" 
                           value="{{ old('passenger_phone', Auth::user()->phone) }}"
                           placeholder="+221 77 123 45 67"
                           required
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('passenger_phone') border-red-500 @enderror">
                    @error('passenger_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="passenger_email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email (optionnel)
                    </label>
                    <input type="email" 
                           name="passenger_email" 
                           id="passenger_email" 
                           value="{{ old('passenger_email', Auth::user()->email) }}"
                           placeholder="exemple@email.com"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('passenger_email') border-red-500 @enderror">
                    @error('passenger_email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Note informative -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Information importante</h3>
                        <p class="mt-1 text-sm text-blue-700">
                            Assurez-vous que les informations saisies correspondent exactement à votre pièce d'identité. 
                            Après validation, vous pourrez sélectionner vos sièges.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Boutons -->
            <div class="mt-8 flex justify-between">
                <a href="{{ route('flights.index') }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour aux vols
                </a>
                
                <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Continuer vers la sélection des sièges
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection