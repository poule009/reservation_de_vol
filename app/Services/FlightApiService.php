<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Flight;

class FlightApiService
{
    private $apiKey;
    private $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.flight_api.key');
        $this->baseUrl = config('services.flight_api.url');
    }

    /**
     * Récupérer les vols depuis l'API
     * Cache de 30 minutes pour optimiser les performances
     */
    public function fetchFlights(array $params = [])
    {
        $cacheKey = 'flights_' . md5(json_encode($params));

        return Cache::remember($cacheKey, 1800, function () use ($params) {
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/flights", array_merge([
                    'access_key' => $this->apiKey,
                    'limit' => 100,
                ], $params));

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }

                Log::error('Flight API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                return [];

            } catch (\Exception $e) {
                Log::error('Flight API Exception', [
                    'message' => $e->getMessage()
                ]);
                return [];
            }
        });
    }

    /**
     * Synchroniser les vols de l'API avec la base de données
     */
    public function syncFlights()
    {
        $flightsData = $this->fetchFlights([
            'flight_status' => 'scheduled',
        ]);

        $syncedCount = 0;

        foreach ($flightsData as $flightData) {
            try {
                $flight = Flight::updateOrCreate(
                    ['flight_number' => $flightData['flight']['iata'] ?? 'UNKNOWN'],
                    [
                        'airline' => $flightData['airline']['name'] ?? 'Unknown Airline',
                        'departure_airport' => $flightData['departure']['iata'] ?? 'N/A',
                        'arrival_airport' => $flightData['arrival']['iata'] ?? 'N/A',
                        'departure_time' => $flightData['departure']['scheduled'] ?? now(),
                        'arrival_time' => $flightData['arrival']['scheduled'] ?? now()->addHours(2),
                        'price' => $this->calculatePrice($flightData),
                        'total_seats' => 180,
                        'available_seats' => 180,
                        'aircraft_type' => $flightData['aircraft']['iata'] ?? null,
                        'status' => 'scheduled',
                        'api_data' => $flightData,
                    ]
                );

                // Créer les sièges pour ce vol s'ils n'existent pas
                if ($flight->seats()->count() === 0) {
                    $this->createSeatsForFlight($flight);
                }

                $syncedCount++;

            } catch (\Exception $e) {
                Log::error('Flight sync error', [
                    'flight' => $flightData['flight']['iata'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $syncedCount;
    }

    /**
     * Calculer le prix du vol (logique personnalisée)
     */
    private function calculatePrice($flightData)
    {
        // Prix de base entre 50 000 et 500 000 FCFA
        $basePrice = rand(50000, 500000);

        // Ajuster selon la distance (si disponible)
        // Pour une vraie application, utilisez une API de calcul de distance

        return $basePrice;
    }

    /**
     * Créer les sièges pour un vol
     */
    private function createSeatsForFlight(Flight $flight)
    {
        $seats = [];

        // Configuration de l'avion : 30 rangées, 6 sièges par rangée (A-F)
        $rows = 30;
        $columns = ['A', 'B', 'C', 'D', 'E', 'F'];

        foreach (range(1, $rows) as $row) {
            foreach ($columns as $column) {
                $seatNumber = $row . $column;

                // Déterminer la classe
                $seatClass = $row <= 5 ? 'business' : 'economy';

                // Déterminer les caractéristiques
                $isWindow = in_array($column, ['A', 'F']);
                $isAisle = in_array($column, ['C', 'D']);
                $isEmergencyExit = in_array($row, [10, 20]);

                // Coût additionnel
                $extraCharge = 0;
                if ($seatClass === 'business') {
                    $extraCharge = 50000;
                } elseif ($isEmergencyExit) {
                    $extraCharge = 15000;
                } elseif ($isWindow) {
                    $extraCharge = 5000;
                }

                $seats[] = [
                    'flight_id' => $flight->id,
                    'seat_number' => $seatNumber,
                    'seat_class' => $seatClass,
                    'status' => 'available',
                    'extra_charge' => $extraCharge,
                    'is_window' => $isWindow,
                    'is_aisle' => $isAisle,
                    'is_emergency_exit' => $isEmergencyExit,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insertion en masse pour optimiser les performances
        \App\Models\Seat::insert($seats);
    }

    /**
     * Rechercher des vols
     */
    public function searchFlights($departure, $arrival, $date = null)
    {
        $query = Flight::available()
            ->where('departure_airport', 'LIKE', "%{$departure}%")
            ->where('arrival_airport', 'LIKE', "%{$arrival}%");

        if ($date) {
            $query->whereDate('departure_time', $date);
        }

        return $query->with('seats')->get();
    }
}
