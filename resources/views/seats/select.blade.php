{{-- resources/views/seats/select.blade.php --}}
@extends('layouts.app')

@section('title', 'Sélection des sièges')

@push('styles')
<style>
    .seat {
        transition: all 0.2s ease;
    }
    .seat:hover:not(.occupied):not(.reserved) {
        transform: scale(1.1);
    }
    .seat.available {
        background-color: #10B981;
        cursor: pointer;
    }
    .seat.available:hover {
        background-color: #059669;
    }
    .seat.selected {
        background-color: #3B82F6;
    }
    .seat.occupied {
        background-color: #EF4444;
        cursor: not-allowed;
    }
    .seat.reserved {
        background-color: #F59E0B;
        cursor: not-allowed;
    }
    .seat.emergency {
        border: 2px solid #F59E0B;
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Sélection des sièges</h1>
        <p class="text-gray-600">Vol {{ $flight->flight_number }} - {{ $flight->departure_airport }} → {{ $flight->arrival_airport }}</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Plan des sièges -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <!-- Légende -->
                <div class="flex flex-wrap gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Disponible</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Sélectionné</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-red-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Occupé</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-yellow-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Réservé</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-white border-2 border-yellow-500 rounded mr-2"></div>
                        <span class="text-sm text-gray-700">Issue de secours</span>
                    </div>
                </div>
                
                <!-- Cockpit -->
                <div class="text-center mb-4">
                    <div class="inline-block bg-gray-200 px-6 py-2 rounded-t-full">
                        <svg class="h-6 w-6 text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
                        </svg>
                    </div>
                </div>
                
                <!-- Plan des sièges -->
                <form id="seatForm" action="{{ route('seats.store', $reservation) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-2" id="seatMap">
                        @foreach($seats as $rowNumber => $rowSeats)
                        <div class="flex items-center justify-center space-x-1">
                            <!-- Numéro de rangée (gauche) -->
                            <div class="w-8 text-center text-sm font-medium text-gray-500">
                                {{ $rowNumber }}
                            </div>
                            
                            <!-- Sièges A, B, C -->
                            @foreach(['A', 'B', 'C'] as $column)
                                @php
                                    $seat = $rowSeats->firstWhere('seat_number', $rowNumber . $column);
                                @endphp
                                
                                @if($seat)
                                    <button type="button"
                                            data-seat-id="{{ $seat->id }}"
                                            data-seat-number="{{ $seat->seat_number }}"
                                            data-extra-charge="{{ $seat->extra_charge }}"
                                            class="seat w-10 h-10 rounded text-white text-xs font-medium
                                                {{ $seat->status === 'available' ? 'available' : ($seat->status === 'occupied' ? 'occupied' : 'reserved') }}
                                                {{ $seat->is_emergency_exit ? 'emergency' : '' }}
                                                {{ in_array($seat->id, $selectedSeats) ? 'selected' : '' }}"
                                            {{ $seat->status !== 'available' ? 'disabled' : '' }}
                                            title="{{ $seat->seat_number }} - {{ $seat->seat_class }} 
                                                {{ $seat->extra_charge > 0 ? '(+' . number_format($seat->extra_charge, 0, ',', ' ') . ' FCFA)' : '' }}
                                                {{ $seat->is_window ? '- Fenêtre' : '' }}
                                                {{ $seat->is_aisle ? '- Couloir' : '' }}
                                                {{ $seat->is_emergency_exit ? '- Issue de secours' : '' }}">
                                        {{ $column }}
                                    </button>
                                @endif
                            @endforeach
                            
                            <!-- Allée -->
                            <div class="w-6"></div>
                            
                            <!-- Sièges D, E, F -->
                            @foreach(['D', 'E', 'F'] as $column)
                                @php
                                    $seat = $rowSeats->firstWhere('seat_number', $rowNumber . $column);
                                @endphp
                                
                                @if($seat)
                                    <button type="button"
                                            data-seat-id="{{ $seat->id }}"
                                            data-seat-number="{{ $seat->seat_number }}"
                                            data-extra-charge="{{ $seat->extra_charge }}"
                                            class="seat w-10 h-10 rounded text-white text-xs font-medium
                                                {{ $seat->status === 'available' ? 'available' : ($seat->status === 'occupied' ? 'occupied' : 'reserved') }}
                                                {{ $seat->is_emergency_exit ? 'emergency' : '' }}
                                                {{ in_array($seat->id, $selectedSeats) ? 'selected' : '' }}"
                                            {{ $seat->status !== 'available' ? 'disabled' : '' }}
                                            title="{{ $seat->seat_number }} - {{ $seat->seat_class }} 
                                                {{ $seat->extra_charge > 0 ? '(+' . number_format($seat->extra_charge, 0, ',', ' ') . ' FCFA)' : '' }}
                                                {{ $seat->is_window ? '- Fenêtre' : '' }}
                                                {{ $seat->is_aisle ? '- Couloir' : '' }}
                                                {{ $seat->is_emergency_exit ? '- Issue de secours' : '' }}">
                                        {{ $column }}
                                    </button>
                                @endif
                            @endforeach
                            
                            <!-- Numéro de rangée (droite) -->
                            <div class="w-8 text-center text-sm font-medium text-gray-500">
                                {{ $rowNumber }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <input type="hidden" name="seats[]" id="selectedSeatsInput">
                </form>
            </div>
        </div>
        
        <!-- Récapitulatif -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Récapitulatif</h2>
                
                <!-- Vol -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">Vol</p>
                    <p class="font-medium">{{ $flight->flight_number }}</p>
                    <p class="text-sm text-gray-600">{{ $flight->departure_airport }} → {{ $flight->arrival_airport }}</p>
                </div>
                
                <!-- Passager -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500">Passager</p>
                    <p class="font-medium">{{ $reservation->getFullPassengerName() }}</p>
                </div>
                
                <!-- Sièges sélectionnés -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <p class="text-sm text-gray-500 mb-2">Sièges sélectionnés</p>
                    <div id="selectedSeatsList" class="space-y-2">
                        <p class="text-sm text-gray-400 italic">Aucun siège sélectionné</p>
                    </div>
                </div>
                
                <!-- Prix -->
                <div class="mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Prix du vol</span>
                        <span class="font-medium">{{ number_format($flight->price, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-600">Frais sièges</span>
                        <span class="font-medium" id="extraCharges">0 FCFA</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span class="text-indigo-600" id="totalAmount">{{ number_format($flight->price, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="space-y-3">
                    <button type="button" 
                            id="submitButton"
                            onclick="submitSeats()"
                            disabled
                            class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md hover:bg-indigo-700 transition-colors disabled:bg-gray-300 disabled:cursor-not-allowed">
                        Continuer vers le paiement
                    </button>
                    <a href="{{ route('reservations.edit', $reservation) }}" 
                       class="block w-full text-center bg-white border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 transition-colors">
                        Modifier les informations
                    </a>
                </div>
                
                <!-- Note -->
                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-3">
                    <p class="text-xs text-yellow-800">
                        <strong>Note:</strong> Sélectionnez au moins un siège pour continuer
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let selectedSeats = [];
const basePrice = {{ $flight->price }};
let totalExtraCharge = 0;

// Rafraîchir les sièges disponibles toutes les 5 secondes
setInterval(refreshAvailableSeats, 5000);

function refreshAvailableSeats() {
    fetch('{{ route('seats.available', $reservation) }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateSeatMap(data.seats);
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function updateSeatMap(availableSeats) {
    const availableSeatIds = availableSeats.map(seat => seat.id);
    
    document.querySelectorAll('.seat').forEach(seatBtn => {
        const seatId = parseInt(seatBtn.dataset.seatId);
        
        if (!selectedSeats.includes(seatId) && !availableSeatIds.includes(seatId)) {
            seatBtn.classList.remove('available');
            seatBtn.classList.add('occupied');
            seatBtn.disabled = true;
        }
    });
}

// Gestion de la sélection de sièges
document.querySelectorAll('.seat.available').forEach(button => {
    button.addEventListener('click', function() {
        const seatId = parseInt(this.dataset.seatId);
        const seatNumber = this.dataset.seatNumber;
        const extraCharge = parseFloat(this.dataset.extraCharge);
        
        if (this.classList.contains('selected')) {
            // Désélectionner
            this.classList.remove('selected');
            selectedSeats = selectedSeats.filter(id => id !== seatId);
            totalExtraCharge -= extraCharge;
        } else {
            // Sélectionner
            this.classList.add('selected');
            selectedSeats.push(seatId);
            totalExtraCharge += extraCharge;
        }
        
        updateSummary();
    });
});

function updateSummary() {
    const submitButton = document.getElementById('submitButton');
    const selectedSeatsList = document.getElementById('selectedSeatsList');
    const extraChargesEl = document.getElementById('extraCharges');
    const totalAmountEl = document.getElementById('totalAmount');
    
    // Activer/désactiver le bouton
    submitButton.disabled = selectedSeats.length === 0;
    
    // Mettre à jour la liste des sièges
    if (selectedSeats.length > 0) {
        const seatNumbers = selectedSeats.map(id => {
            const button = document.querySelector(`[data-seat-id="${id}"]`);
            return button.dataset.seatNumber;
        });
        
        selectedSeatsList.innerHTML = seatNumbers.map(num => 
            `<span class="inline-block bg-indigo-100 text-indigo-800 px-2 py-1 rounded text-sm">${num}</span>`
        ).join(' ');
    } else {
        selectedSeatsList.innerHTML = '<p class="text-sm text-gray-400 italic">Aucun siège sélectionné</p>';
    }
    
    // Mettre à jour les montants
    extraChargesEl.textContent = totalExtraCharge.toLocaleString('fr-FR') + ' FCFA';
    const total = basePrice + totalExtraCharge;
    totalAmountEl.textContent = total.toLocaleString('fr-FR') + ' FCFA';
}

function submitSeats() {
    if (selectedSeats.length === 0) {
        alert('Veuillez sélectionner au moins un siège');
        return;
    }
    
    const form = document.getElementById('seatForm');
    const input = document.getElementById('selectedSeatsInput');
    
    // Supprimer les anciens inputs cachés
    form.querySelectorAll('input[name="seats[]"]').forEach(el => el.remove());
    
    // Créer un input pour chaque siège sélectionné
    selectedSeats.forEach(seatId => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'seats[]';
        hiddenInput.value = seatId;
        form.appendChild(hiddenInput);
    });
    
    form.submit();
}
</script>
@endpush
@endsection