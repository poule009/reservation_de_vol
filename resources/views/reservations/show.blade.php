{{-- resources/views/reservations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails de la réservation')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li>
                <a href="{{ route('reservations.index') }}" class="bg-white text-gray-500 hover:text-gray-700 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium p-700 ">Mes réservations</a>
            </li>
            <li>
                <span class="text-gray-400 mx-2">/</span>
            </li>
            <li class="text-gray-900 font-medium">{{ $reservation->passenger_first_name }} {{ $reservation->passenger_last_name }}</li>
        </ol>
    </nav>

    <h1 class="text-3xl font-bold text-white mb-900 mb-8">Détails de la réservation</h1>

    <!-- Statut de la réservation -->
    <div class="mb-6">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            @if($reservation->status === 'confirmed') bg-green-100 text-green-800
            @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
            @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($reservation->status) }}
        </span>
    </div>

    <!-- Récapitulatif du vol -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Vol réservé</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Vol</p>
                <p class="text-xl font-bold text-gray-900">{{ $reservation->flight->flight_number }}</p>
                <p class="text-sm text-gray-600">{{ $reservation->flight->airline }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Itinéraire</p>
                <div class="flex items-center mt-2">
                    <div>
                        <p class="text-lg font-semibold">{{ $reservation->flight->departure_airport }}</p>
                        <p class="text-xs text-gray-500">{{ $reservation->flight->departure_time->format('d/m/Y H:i') }}</p>
                    </div>
                    <svg class="mx-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <div>
                        <p class="text-lg font-semibold">{{ $reservation->flight->arrival_airport }}</p>
                        <p class="text-xs text-gray-500">{{ $reservation->flight->arrival_time->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Prix</p>
                <p class="text-2xl font-bold text-indigo-600">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </div>

    <!-- Informations du passager -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Informations du passager</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Prénom</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->passenger_details['first_name'] ?? 'Non spécifié' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nom</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->passenger_details['last_name'] ?? 'Non spécifié' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Numéro de téléphone</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->passenger_details['phone'] ?? 'Non spécifié' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->passenger_details['email'] ?? 'Non spécifié' }}</p>
            </div>
        </div>
    </div>

    <!-- Sièges réservés -->
    @if($reservation->seats->count() > 0)
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Sièges réservés</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($reservation->seats as $seat)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Siège</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $seat->seat_number }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Classe</p>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst($seat->class) }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Informations de paiement -->
    @if($reservation->payment)
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Paiement</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-500">Statut</p>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($reservation->payment->status === 'completed') bg-green-100 text-green-800
                    @elseif($reservation->payment->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($reservation->payment->status === 'refunded') bg-blue-100 text-blue-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($reservation->payment->status) }}
                </span>
            </div>

            <div>
                <p class="text-sm text-gray-500">Méthode</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->payment->payment_method ?: 'Non spécifié' }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Date de paiement</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->payment->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Informations supplémentaires -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">Informations supplémentaires</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Date de réservation</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->created_at->format('d/m/Y H:i') }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Dernière mise à jour</p>
                <p class="text-lg font-medium text-gray-900">{{ $reservation->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex justify-between">
        <a href="{{ route('reservations.index') }}"
           class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour aux réservations
        </a>

        <div class="flex space-x-3">
            @if($reservation->status === 'pending')
            <a href="{{ route('reservations.edit', $reservation) }}"
               class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Modifier
            </a>

            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')"
                        class="inline-flex items-center px-6 py-3 border border-red-300 rounded-md text-sm font-medium text-red-700 bg-white hover:bg-red-50 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Annuler
                </button>
            </form>
            @endif

            @if($reservation->status === 'confirmed' && !$reservation->payment)
            <a href="{{ route('payments.create', $reservation) }}"
               class="inline-flex items-center px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                Payer maintenant
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
