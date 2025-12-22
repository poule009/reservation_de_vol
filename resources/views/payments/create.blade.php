@extends('layouts.app')

@section('title', 'Paiement de la réservation #' . $reservation->id)

@section('content')
<div class="flex justify-center">
    <div class="max-w-4xl w-full">
        <div class="bg-white rounded-lg shadow-lg mb-4">
            <div class="bg-blue-600 text-white flex justify-between p-6">
                <div>
                    <h4 class="mb-0 text-xl font-bold">
                        <i class="fas fa-credit-card mr-2"></i>
                        Paiement de votre billet
                    </h4>
                    <small class="text-blue-100">
                        Vol {{ $reservation->flight->flight_number }} •
                        Siège {{ $reservation->seats->first()->numero_siege ?? 'Non défini' }}
                    </small>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold mb-0">
                        {{ number_format($montant, 0) }} XOF
                    </div>
                    <small class="text-blue-100">Montant total à payer</small>
                </div>
            </div>

            <div class="p-6">
                <form method="POST" action="{{ route('payments.process', $reservation) }}">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Mode de paiement
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="border rounded-lg p-4 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="card"
                                       class="mr-3"
                                       {{ old('payment_method') === 'card' ? 'checked' : '' }}>
                                <span>
                                    Carte bancaire<br>
                                    <small class="text-gray-500">Visa, MasterCard</small>
                                </span>
                            </label>
                            <label class="border rounded-lg p-4 flex items-center cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="mobile_money"
                                       class="mr-3"
                                       {{ old('payment_method') === 'mobile_money' ? 'checked' : '' }}>
                                <span>
                                    Mobile Money<br>
                                    <small class="text-gray-500">Orange Money, Wave, etc.</small>
                                </span>
                            </label>
                        </div>
                        @error('payment_method')
                            <div class="text-red-600 mt-2 text-sm">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <i class="fas fa-info-circle mr-2 text-blue-600"></i>
                        <span class="text-blue-800">
                            Dans cette version, le paiement est simulé comme réussi pour générer le ticket.
                            Tu pourras remplacer cette étape par Stripe ou un autre gateway plus tard.
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('seats.select', $reservation) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour au choix du siège
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md text-white hover:bg-green-700 transition-colors">
                            Confirmer et télécharger le ticket
                            <i class="fas fa-download ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-gray-50 px-6 py-4 text-gray-600 text-sm">
                <i class="fas fa-lock mr-1"></i>
                Paiement sécurisé (simulation) • Vous recevrez un ticket PDF téléchargeable.
            </div>
        </div>
    </div>
</div>
@endsection
