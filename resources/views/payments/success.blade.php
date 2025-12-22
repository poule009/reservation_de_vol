{{-- resources/views/payments/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Paiement réussi')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Animation de succès -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Paiement réussi !</h1>
        <p class="text-lg text-gray-600">Votre réservation a été confirmée</p>
    </div>
    
    <!-- Carte de confirmation -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <!-- Header avec dégradé -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-8 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-sm opacity-90">Numéro de réservation</p>
                    <p class="text-2xl font-bold">{{ $reservation->booking_reference }}</p>
                </div>
                <div class="bg-white bg-opacity-20 px-4 py-2 rounded-full">
                    <p class="text-sm font-medium">{{ $reservation->status }}</p>
                </div>
            </div>
            <p class="text-sm opacity-90">Un email de confirmation vous a été envoyé</p>
        </div>
        
        <!-- Détails de la réservation -->
        <div class="p-6">
            <!-- Vol -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Informations du vol</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ $reservation->flight->flight_number }}</p>
                            <p class="text-sm text-gray-600">{{ $reservation->flight->airline }}</p>
                        </div>
                        @if($reservation->flight->aircraft_type)
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Appareil</p>
                            <p class="text-sm font-medium">{{ $reservation->flight->aircraft_type }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Départ</p>
                            <p class="text-xl font-bold text-gray-900">{{ $reservation->flight->departure_airport }}</p>
                            <p class="text-sm text-gray-600">{{ $reservation->flight->departure_time->format('d/m/Y') }}</p>
                            <p class="text-lg font-semibold text-indigo-600">{{ $reservation->flight->departure_time->format('H:i') }}</p>
                        </div>
                        
                        <div class="flex flex-col items-center px-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ floor($reservation->flight->duration / 60) }}h {{ $reservation->flight->duration % 60 }}m
                            </p>
                        </div>
                        
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-1">Arrivée</p>
                            <p class="text-xl font-bold text-gray-900">{{ $reservation->flight->arrival_airport }}</p>
                            <p class="text-sm text-gray-600">{{ $reservation->flight->arrival_time->format('d/m/Y') }}</p>
                            <p class="text-lg font-semibold text-indigo-600">{{ $reservation->flight->arrival_time->format('H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Passager -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Informations du passager</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500">Nom complet</p>
                            <p class="font-medium">{{ $reservation->getFullPassengerName() }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Téléphone</p>
                            <p class="font-medium">{{ $reservation->passenger_phone }}</p>
                        </div>
                        @if($reservation->passenger_email)
                        <div>
                            <p class="text-xs text-gray-500">Email</p>
                            <p class="font-medium">{{ $reservation->passenger_email }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-xs text-gray-500">Date de réservation</p>
                            <p class="font-medium">{{ $reservation->booking_date->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sièges -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Sièges assignés</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($reservation->seats as $seat)
                    <div class="bg-indigo-50 border-2 border-indigo-200 rounded-lg px-4 py-3 text-center">
                        <p class="text-2xl font-bold text-indigo-600">{{ $seat->seat_number }}</p>
                        <p class="text-xs text-gray-600">{{ ucfirst($seat->seat_class) }}</p>
                        @if($seat->is_window)
                        <p class="text-xs text-indigo-600">Fenêtre</p>
                        @elseif($seat->is_aisle)
                        <p class="text-xs text-indigo-600">Couloir</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            
            <!-- Paiement -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Détails du paiement</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Prix du vol</span>
                        <span class="font-medium">{{ number_format($reservation->flight->price, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($reservation->seats->sum('extra_charge') > 0)
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600">Frais sièges</span>
                        <span class="font-medium">{{ number_format($reservation->seats->sum('extra_charge'), 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                    <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between items-center">
                        <span class="text-lg font-semibold">Total payé</span>
                        <span class="text-2xl font-bold text-indigo-600">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($reservation->payment)
                    <div class="mt-3 pt-3 border-t border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Transaction ID</span>
                            <span class="font-mono">{{ $reservation->payment->transaction_id }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600">Méthode de paiement</span>
                            <span class="capitalize">{{ str_replace('_', ' ', $reservation->payment->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-gray-600">Date du paiement</span>
                            <span>{{ $reservation->payment->paid_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('payments.ticket', $reservation) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Télécharger le ticket
            </a>
            
            <a href="{{ route('reservations.show', $reservation) }}" 
               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-md shadow-sm text-base font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Voir les détails
            </a>
        </div>
    </div>
    
    <!-- Instructions importantes -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <h3 class="flex items-center text-lg font-semibold text-yellow-900 mb-4">
            <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Instructions importantes
        </h3>
        <ul class="space-y-2 text-sm text-yellow-800">
            <li class="flex items-start">
                <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Présentez-vous à l'aéroport <strong>2 heures avant le départ</strong>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Munissez-vous de votre <strong>pièce d'identité valide</strong> et de votre <strong>ticket</strong>
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Vérifiez les <strong>restrictions de bagages</strong> auprès de la compagnie
            </li>
            <li class="flex items-start">
                <svg class="h-5 w-5 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                Vous pouvez annuler votre réservation <strong>jusqu'à 24h avant le départ</strong>
            </li>
        </ul>
    </div>
    
    <!-- Navigation -->
    <div class="text-center">
        <a href="{{ route('dashboard') }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
            <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au tableau de bord
        </a>
    </div>
</div>
@endsection