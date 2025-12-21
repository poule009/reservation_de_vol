{{-- resources/views/payments/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Paiement')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Paiement</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire de paiement -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Choisissez votre méthode de paiement</h2>
                
                <form action="{{ route('payments.store', $reservation) }}" method="POST" id="paymentForm">
                    @csrf
                    
                    <!-- Méthodes de paiement -->
                    <div class="space-y-4 mb-6">
                        <!-- Carte bancaire -->
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="card" 
                                   class="sr-only peer"
                                   checked>
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-12 h-12 bg-indigo-100 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Carte bancaire</p>
                                    <p class="text-sm text-gray-500">Visa, Mastercard</p>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-indigo-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                        </label>
                        
                        <!-- Mobile Money -->
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="mobile_money" 
                                   class="sr-only peer">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Mobile Money</p>
                                    <p class="text-sm text-gray-500">Orange Money, Wave, Free Money</p>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-indigo-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                        </label>
                        
                        <!-- Virement bancaire -->
                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer hover:border-indigo-500 transition-colors">
                            <input type="radio" 
                                   name="payment_method" 
                                   value="bank_transfer" 
                                   class="sr-only peer">
                            <div class="flex items-center w-full">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">Virement bancaire</p>
                                    <p class="text-sm text-gray-500">Toutes les banques</p>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-indigo-600 rounded-lg opacity-0 peer-checked:opacity-100"></div>
                        </label>
                    </div>
                    
                    <!-- Conditions -->
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" 
                                   name="accept_terms" 
                                   required
                                   class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-3 text-sm text-gray-700">
                                J'accepte les <a href="#" class="text-indigo-600 hover:text-indigo-800">conditions générales</a> 
                                et la <a href="#" class="text-indigo-600 hover:text-indigo-800">politique de confidentialité</a>
                            </span>
                        </label>
                    </div>
                    
                    <!-- Note sécurité -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="h-5 w-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-800">
                                    <strong>Paiement sécurisé</strong> - Vos informations sont protégées et cryptées
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Boutons -->
                    <div class="flex space-x-4">
                        <a href="{{ route('seats.select', $reservation) }}" 
                           class="flex-1 text-center px-6 py-3 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Retour
                        </a>
                        <button type="submit" 
                                class="flex-1 px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                            Payer {{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Récapitulatif de la réservation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Récapitulatif</h2>
                
                <!-- Vol -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Vol</p>
                    <p class="font-bold text-lg">{{ $reservation->flight->flight_number }}</p>
                    <p class="text-sm text-gray-600">{{ $reservation->flight->airline }}</p>
                </div>
                
                <!-- Itinéraire -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <p class="text-xs text-gray-500">Départ</p>
                            <p class="font-semibold">{{ $reservation->flight->departure_airport }}</p>
                            <p class="text-xs text-gray-600">{{ $reservation->flight->departure_time->format('d/m/Y H:i') }}</p>
                        </div>
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                        <div class="text-right">
                            <p class="text-xs text-gray-500">Arrivée</p>
                            <p class="font-semibold">{{ $reservation->flight->arrival_airport }}</p>
                            <p class="text-xs text-gray-600">{{ $reservation->flight->arrival_time->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Passager -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500 mb-1">Passager</p>
                    <p class="font-medium">{{ $reservation->getFullPassengerName() }}</p>
                    <p class="text-sm text-gray-600">{{ $reservation->passenger_phone }}</p>
                </div>
                
                <!-- Sièges -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Sièges</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($reservation->seats as $seat)
                        <span class="inline-block bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">
                            {{ $seat->seat_number }}
                        </span>
                        @endforeach
                    </div>
                </div>
                
                <!-- Détails du prix -->
                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Prix du vol</span>
                        <span>{{ number_format($reservation->flight->price, 0, ',', ' ') }} FCFA</span>
                    </div>
                    @if($reservation->seats->sum('extra_charge') > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Frais sièges</span>
                        <span>{{ number_format($reservation->seats->sum('extra_charge'), 0, ',', ' ') }} FCFA</span>
                    </div>
                    @endif
                </div>
                
                <!-- Total -->
                <div class="flex justify-between text-xl font-bold">
                    <span>Total</span>
                    <span class="text-indigo-600">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simuler un délai de traitement
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    
    submitButton.disabled = true;
    submitButton.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Traitement...';
    
    setTimeout(() => {
        this.submit();
    }, 2000);
});
</script>
@endpush
@endsection