{{-- resources/views/flights/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Détails du vol ' . $flight->flight_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white 900">{{ $flight->flight_number }}</h1>
                <p class="text-lg text-white-600">{{ $flight->airline }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($flight->status === 'scheduled') bg-green-100 text-green-800
                    @elseif($flight->status === 'delayed') bg-yellow-100 text-yellow-800
                    @elseif($flight->status === 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($flight->status) }}
                </span>
                <a href="{{ route('flights.index') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    ← Retour aux vols
                </a>
            </div>
        </div>
    </div>

    <!-- Flight Details -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Departure -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-2">Départ</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $flight->departure_airport }}</p>
                    <p class="text-sm text-gray-600">{{ $flight->departure_city }}</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ $flight->departure_time->format('H:i') }}</p>
                    <p class="text-sm text-gray-500">{{ $flight->departure_time->format('d/m/Y') }}</p>
                </div>

                <!-- Duration -->
                <div class="flex flex-col items-center justify-center">
                    <svg class="h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                    <p class="text-sm text-gray-500">Durée estimée</p>
                    <p class="text-lg font-semibold">{{ floor($flight->duration / 60) }}h {{ $flight->duration % 60 }}m</p>
                </div>

                <!-- Arrival -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-2">Arrivée</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $flight->arrival_airport }}</p>
                    <p class="text-sm text-gray-600">{{ $flight->arrival_city }}</p>
                    <p class="text-lg font-semibold text-indigo-600">{{ $flight->arrival_time->format('H:i') }}</p>
                    <p class="text-sm text-gray-500">{{ $flight->arrival_time->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Seat Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Sièges disponibles</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seatStats['available'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Sièges réservés</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seatStats['reserved'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Sièges occupés</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $seatStats['occupied'] }}</p>
                </div>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du vol</h3>
            <dl class="space-y-3">
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Prix</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ number_format($flight->price, 0, ',', ' ') }} FCFA</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Total sièges</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ $flight->total_seats }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Sièges disponibles</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ $flight->available_seats }}</dd>
                </div>
                @if($flight->aircraft_type)
                <div class="flex justify-between">
                    <dt class="text-sm font-medium text-gray-500">Type d'appareil</dt>
                    <dd class="text-sm font-semibold text-gray-900">{{ $flight->aircraft_type }}</dd>
                </div>
                @endif
            </dl>
        </div>


    </div>
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8 w-full">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan des sièges</h3>

        <!-- Group seats by row -->
        @php
            $seatsByRow = $flight->seats->groupBy(function($seat) {
                return intval($seat->seat_number);
            })->sortKeys();
        @endphp

        <div class="space-y-2 max-w-2xl mx-auto">
            @foreach($seatsByRow as $rowNumber => $rowSeats)
            <div class="flex items-center justify-center space-x-1">
                <!-- Row number (left) -->
                <div class="w-8 text-center text-sm font-medium text-gray-500">
                    {{ $rowNumber }}
                </div>

                <!-- Seats A, B, C -->
                @foreach(['A', 'B', 'C'] as $column)
                    @php
                        $seat = $rowSeats->firstWhere('seat_number', $rowNumber . $column);
                    @endphp
                    @if($seat)
                    <div class="relative">
                        <button class="w-10 h-10 rounded border-2 text-xs font-medium flex items-center justify-center transition-colors
                            @if($seat->status === 'available') border-green-200 bg-green-50 text-green-700 hover:bg-green-100
                            @elseif($seat->status === 'reserved') border-yellow-200 bg-yellow-50 text-yellow-700
                            @else border-red-200 bg-red-50 text-red-700
                            @endif"
                            disabled>
                            {{ $column }}
                        </button>

                        {{-- Window indicator --}}
                        @if($seat->is_window)
                        <div class="absolute -top-1 -left-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white" title="Fenêtre"></div>
                        @endif

                        {{-- Aisle indicator --}}
                        @if($seat->is_aisle)
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-purple-500 rounded-full border-2 border-white" title="Couloir"></div>
                        @endif
                    </div>
                    @endif
                @endforeach

                <!-- Aisle -->
                <div class="w-6"></div>

                <!-- Seats D, E, F -->
                @foreach(['D', 'E', 'F'] as $column)
                    @php
                        $seat = $rowSeats->firstWhere('seat_number', $rowNumber . $column);
                    @endphp
                    @if($seat)
                    <div class="relative">
                        <button class="w-10 h-10 rounded border-2 text-xs font-medium flex items-center justify-center transition-colors
                            @if($seat->status === 'available') border-green-200 bg-green-50 text-green-700 hover:bg-green-100
                            @elseif($seat->status === 'reserved') border-yellow-200 bg-yellow-50 text-yellow-700
                            @else border-red-200 bg-red-50 text-red-700
                            @endif"
                            disabled>
                            {{ $column }}
                        </button>

                        {{-- Aisle indicator --}}
                        @if($seat->is_aisle)
                        <div class="absolute -top-1 -left-1 w-3 h-3 bg-purple-500 rounded-full border-2 border-white" title="Couloir"></div>
                        @endif

                        {{-- Window indicator --}}
                        @if($seat->is_window)
                        <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-white" title="Fenêtre"></div>
                        @endif
                    </div>
                    @endif
                @endforeach

                <!-- Row number (right) -->
                <div class="w-8 text-center text-sm font-medium text-gray-500">
                    {{ $rowNumber }}
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 pt-6 border-t border-gray-100 flex flex-wrap gap-6 text-sm">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-50 border border-green-200 rounded mr-2"></div> Libre
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-50 border border-red-200 rounded mr-2"></div> Occupé
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div> Fenêtre
            </div>
            <div class="flex items-center">
                <div class="w-3 h-3 bg-purple-500 rounded-full mr-2"></div> Couloir
            </div>
        </div>
    </div>



    @if($flight->available_seats > 0)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="text-center">
            <a href="{{ route('reservations.create', $flight) }}"
                class="inline-flex items-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                Réserver ce vol
            </a>
        </div>
    </div>
    @endif
</div>
@endsection