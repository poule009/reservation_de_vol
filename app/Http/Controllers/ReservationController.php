<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateReservationRequest;
use App\Models\Flight;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReservationRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des réservations de l'utilisateur
     */
    public function index()
    {
        $reservations = Auth::user()->reservations()
            ->with(['flight', 'seats', 'payment'])
            ->latest()
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Formulaire de création de réservation
     */
    public function create(Flight $flight)
    {
        if (!$flight->isAvailable()) {
            return redirect()->route('flights.index')
                ->with('error', 'Ce vol n\'est plus disponible');
        }

        return view('reservations.create', compact('flight'));
    }

    /**
     * Enregistrer une réservation
     */
    public function store(StoreReservationRequest $request, Flight $flight)
    {
        if (!$flight->isAvailable()) {
            return redirect()->route('flights.index')
                ->with('error', 'Ce vol n\'est plus disponible');
        }

        try {
            $reservation = DB::transaction(function () use ($request, $flight) {
                return Reservation::create([
                    'user_id' => Auth::id(),
                    'flight_id' => $flight->id,
                    'booking_reference' => $this->generateBookingReference(),
                    'passenger_first_name' => $request->passenger_first_name,
                    'passenger_last_name' => $request->passenger_last_name,
                    'passenger_phone' => $request->passenger_phone,
                    'passenger_email' => $request->passenger_email,
                    'status' => 'pending',
                    'total_amount' => $flight->price,
                    'booking_date' => now(),
                ]);
            });

            return redirect()->route('seats.select', $reservation)
                ->with('success', 'Informations enregistrées. Veuillez choisir vos sièges.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Une erreur est survenue lors de la réservation');
        }
    }

    /**
     * Afficher les détails d'une réservation
     */
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);

        $reservation->load(['flight', 'seats', 'payment']);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Formulaire de modification
     */
    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        return view('reservations.edit', compact('reservation'));
    }

    /**
     * Mettre à jour une réservation
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $reservation->update($request->only([
            'passenger_first_name',
            'passenger_last_name',
            'passenger_phone',
            'passenger_email',
        ]));

        return redirect()->route('reservations.show', $reservation)
            ->with('success', 'Réservation mise à jour avec succès');
    }

    /**
     * Annuler une réservation
     */
    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        DB::transaction(function () use ($reservation) {
            // Libérer les sièges
            $reservation->seats()->update([
                'status' => 'available',
                'reservation_id' => null,
            ]);

            // Incrémenter les sièges disponibles
            $reservation->flight->increment('available_seats', $reservation->seats()->count());

            // Annuler la réservation
            $reservation->update(['status' => 'cancelled']);

            // Si paiement effectué, initier le remboursement
            if ($reservation->payment && $reservation->payment->isCompleted()) {
                $reservation->payment->update(['status' => 'refunded']);
            }
        });

        return redirect()->route('reservations.index')
            ->with('success', 'Réservation annulée avec succès');
    }

    /**
     * Générer une référence de réservation unique
     */
    private function generateBookingReference(): string
    {
        do {
            $reference = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        } while (Reservation::where('booking_reference', $reference)->exists());

        return $reference;
    }
}
