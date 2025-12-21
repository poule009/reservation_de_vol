<?php

namespace App\Http\Controllers;
use App\Events\SeatUpdated;
use App\Models\Reservation;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
{
    /**
     * Page de sélection des sièges
     */
    public function select(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.show', $reservation)
                ->with('error', 'Les sièges ont déjà été sélectionnés');
        }

        $flight = $reservation->flight;
        
        // Organiser les sièges par rangée
        $seats = $flight->seats()
            ->orderBy('seat_number')
            ->get()
            ->groupBy(function ($seat) {
                return intval($seat->seat_number); // Grouper par numéro de rangée
            });

        // Sièges déjà sélectionnés (s'il y en a)
        $selectedSeats = $reservation->seats()->pluck('id')->toArray();

        return view('seats.select', compact('reservation', 'flight', 'seats', 'selectedSeats'));
    }

    /**
     * Enregistrer la sélection de sièges
     */
    public function store(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'seats' => 'required|array|min:1',
            'seats.*' => 'exists:seats,id',
        ]);

        try {
            DB::transaction(function () use ($request, $reservation) {
                // Vérifier que tous les sièges sont disponibles
                $seats = Seat::whereIn('id', $request->seats)
                    ->where('flight_id', $reservation->flight_id)
                    ->where('status', 'available')
                    ->get();

                if ($seats->count() !== count($request->seats)) {
                    throw new \Exception('Certains sièges ne sont plus disponibles');
                }

                // Libérer les anciens sièges si modification
                if ($reservation->seats()->count() > 0) {
                    $reservation->seats()->update([
                        'status' => 'available',
                        'reservation_id' => null,
                    ]);
                }

                // Réserver les nouveaux sièges
                foreach ($seats as $seat) {
                    $seat->reserve($reservation->id);
                    // Broadcaster la mise à jour
                    broadcast(new SeatUpdated($seat))->toOthers();
                }

                // Calculer le montant total avec les frais additionnels
                $totalAmount = $reservation->flight->price + $seats->sum('extra_charge');
                
                $reservation->update([
                    'total_amount' => $totalAmount,
                ]);
            });

            return redirect()->route('payments.create', $reservation)
                ->with('success', 'Sièges sélectionnés avec succès');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Obtenir les sièges disponibles en temps réel (API)
     */
    public function available(Reservation $reservation)
    {
        $seats = $reservation->flight->seats()
            ->where('status', 'available')
            ->get();

        return response()->json([
            'success' => true,
            'seats' => $seats,
        ]);
    }

    /**
     * Réserver temporairement un siège (pour éviter double réservation)
     */
    public function hold(Request $request, Seat $seat)
    {
        if ($seat->status !== 'available') {
            return response()->json([
                'success' => false,
                'message' => 'Siège non disponible',
            ], 400);
        }

        // Verrouiller temporairement (5 minutes)
        // Dans une vraie app, utilisez Redis ou Cache
        $seat->update(['status' => 'reserved']);

        return response()->json([
            'success' => true,
            'message' => 'Siège réservé temporairement',
        ]);
    }

    /**
     * Libérer un siège temporairement réservé
     */
    public function release(Seat $seat)
    {
        if ($seat->status === 'reserved' && !$seat->reservation_id) {
            $seat->update(['status' => 'available']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Siège libéré',
        ]);
    }
}