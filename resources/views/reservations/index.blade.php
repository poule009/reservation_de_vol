{{-- resources/views/reservations/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Mes réservations')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mes réservations</h1>
        <p class="mt-2 text-gray-600">Consultez et gérez vos réservations de vol</p>
    </div>

    <!-- Liste des réservations -->
    @if($reservations->count() > 0)
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Vos réservations récentes</h3>
        </div>

        <div class="divide-y divide-gray-200">
            @foreach($reservations as $reservation)
            <div class="px-6 py-4 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-4">
                            <!-- Informations du vol -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $reservation->flight->flight_number }}</span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($reservation->status === 'confirmed') bg-green-100 text-green-800
                                        @elseif($reservation->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($reservation->status === 'cancelled') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                                    <div>
                                        <p class="font-medium">{{ $reservation->flight->departure_airport }} → {{ $reservation->flight->arrival_airport }}</p>
                                        <p>{{ $reservation->flight->departure_time->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $reservation->passenger_first_name }} {{ $reservation->passenger_last_name }}</p>
                                        <p>{{ $reservation->passenger_email }}</p>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ number_format($reservation->total_amount, 0, ',', ' ') }} FCFA</p>
                                        <p>Réservé le {{ $reservation->booking_date->format('d/m/Y') }}</p>
                                    </div>
                                </div>

                                @if($reservation->seats->count() > 0)
                                <div class="mt-2 text-sm text-gray-500">
                                    Sièges: {{ $reservation->seats->pluck('seat_number')->join(', ') }}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('reservations.show', $reservation) }}"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Voir détails
                        </a>

                        @if($reservation->status === 'pending')
                        <a href="{{ route('reservations.edit', $reservation) }}"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Modifier
                        </a>

                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')"
                                    class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Annuler
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination -->
    @if($reservations->hasPages())
    <div class="mt-8">
        {{ $reservations->links() }}
    </div>
    @endif

    @else
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune réservation trouvée</h3>
        <p class="text-gray-500 mb-6">Vous n'avez encore effectué aucune réservation.</p>
        <a href="{{ route('flights.index') }}"
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
            Réserver un vol
        </a>
    </div>
    @endif
</div>
@endsection
