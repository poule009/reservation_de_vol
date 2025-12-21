<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Flight extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'flight_number',
        'airline',
        'departure_airport',
        'arrival_airport',
        'departure_city',
        'arrival_city',
        'departure_time',
        'arrival_time',
        'price',
        'total_seats',
        'available_seats',
        'status',
        'additional_info',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'price' => 'decimal:2',
        'additional_info' => 'array',
    ];

    /**
     * Relations
     */
    
    // Un vol peut avoir plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Un vol peut avoir plusieurs sièges
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Scopes pour les requêtes
     */
    
    // Vols disponibles (avec des sièges disponibles et dans le futur)
    public function scopeAvailable($query)
    {
        return $query->where('available_seats', '>', 0)
                    ->where('departure_time', '>', now())
                    ->where('status', 'scheduled');
    }

    // Recherche par route
    public function scopeRoute($query, $departure, $arrival)
    {
        return $query->where('departure_airport', $departure)
                    ->where('arrival_airport', $arrival);
    }

    // Recherche par date
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('departure_time', $date);
    }

    /**
     * Méthodes utilitaires
     */
    
    // Calculer la durée du vol en minutes
    public function getDurationAttribute()
    {
        $departure = Carbon::parse($this->departure_time);
        $arrival = Carbon::parse($this->arrival_time);

        return $departure->diffInMinutes($arrival);
    }

    // Vérifier si le vol est complet
    public function isFull(): bool
    {
        return $this->available_seats <= 0;
    }

    // Vérifier si le vol est disponible pour réservation
    public function isAvailable(): bool
    {
        return $this->available_seats > 0
            && $this->departure_time > now()
            && $this->status === 'scheduled';
    }

    // Obtenir le pourcentage de sièges réservés
    public function getOccupancyPercentage(): float
    {
        if ($this->total_seats == 0) return 0;
        
        return round((($this->total_seats - $this->available_seats) / $this->total_seats) * 100, 2);
    }

    // Décrémenter les sièges disponibles
    public function decrementSeats(int $count = 1): bool
    {
        if ($this->available_seats >= $count) {
            $this->decrement('available_seats', $count);
            return true;
        }
        return false;
    }

    // Incrémenter les sièges disponibles (en cas d'annulation)
    public function incrementSeats(int $count = 1): void
    {
        if ($this->available_seats < $this->total_seats) {
            $this->increment('available_seats', $count);
        }
    }
}