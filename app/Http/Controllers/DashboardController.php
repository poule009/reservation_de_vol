<?php

namespace App\Http\Controllers;
// app/Http/Controllers/DashboardController



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Charger les réservations avec leurs relations
        $reservations = $user->reservations()
            ->with(['flight', 'seats', 'payment'])
            ->latest()
            ->paginate(10);

        // Statistiques de l'utilisateur
        $stats = [
            'total_reservations' => $user->totalReservations(),
            'completed_flights' => $user->completedFlights(),
            'total_spent' => $user->totalSpent(),
            'upcoming_flights' => $user->reservations()
                ->whereHas('flight', function ($query) {
                    $query->where('departure_time', '>', now());
                })
                ->where('status', 'confirmed')
                ->count(),
        ];

        // Prochains vols
        $upcomingReservations = $user->reservations()
            ->whereHas('flight', function ($query) {
                $query->where('departure_time', '>', now())
                    ->orderBy('departure_time', 'asc');
            })
            ->where('status', 'confirmed')
            ->with(['flight', 'seats'])
            ->limit(3)
            ->get();

        return view('dashboard', compact('reservations', 'stats', 'upcomingReservations'));
    }
}