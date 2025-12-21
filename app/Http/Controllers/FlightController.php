<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Services\FlightApiService;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    protected $flightApiService;

    public function __construct(FlightApiService $flightApiService)
    {
        $this->flightApiService = $flightApiService;
    }

    /**
     * Liste des vols disponibles
     */
    public function index(Request $request)
    {
        $query = Flight::available()->with('seats');

        // Filtres de recherche
        if ($request->filled('departure')) {
            $query->where('departure_airport', 'LIKE', "%{$request->departure}%");
        }

        if ($request->filled('arrival')) {
            $query->where('arrival_airport', 'LIKE', "%{$request->arrival}%");
        }

        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'departure':
                    $query->orderBy('departure_time', 'asc');
                    break;
                default:
                    $query->orderBy('departure_time', 'asc');
            }
        } else {
            $query->orderBy('departure_time', 'asc');
        }

        $flights = $query->paginate(12);

        return view('flights.index', compact('flights'));
    }

    /**
     * Détails d'un vol
     */
    public function show(Flight $flight)
    {
        $flight->load(['seats' => function ($query) {
            $query->orderBy('seat_number');
        }]);

        // Statistiques des sièges
        $seatStats = [
            'available' => $flight->seats()->where('status', 'available')->count(),
            'reserved' => $flight->seats()->where('status', 'reserved')->count(),
            'occupied' => $flight->seats()->where('status', 'occupied')->count(),
        ];

        return view('flights.show', compact('flight', 'seatStats'));
    }

    /**
     * Synchroniser les vols depuis l'API
     */
    public function sync()
    {
        $syncedCount = $this->flightApiService->syncFlights();

        return redirect()->route('flights.index')
            ->with('success', "{$syncedCount} vols synchronisés avec succès");
    }

    /**
     * Rechercher des vols
     */
    public function search(Request $request)
    {
        $request->validate([
            'departure' => 'required|string|min:2',
            'arrival' => 'required|string|min:2',
            'date' => 'nullable|date|after_or_equal:today',
        ]);

        $flights = $this->flightApiService->searchFlights(
            $request->departure,
            $request->arrival,
            $request->date
        );

        return view('flights.search-results', compact('flights'));
    }
}