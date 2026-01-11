<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Seat extends Model
{
    use HasFactory;

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_SELECTED = 'selected';
    public const STATUS_RESERVED = 'reserved';
    public const STATUS_OCCUPIED = 'occupied';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'flight_id',
        'reservation_id',
        'seat_number',
        'seat_class',
        'status',
        'selected_at',
        'selected_by',
        'extra_charge',
        'is_window',
        'is_aisle',
        'is_emergency_exit',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'selected_at' => 'datetime',
        'extra_charge' => 'decimal:2',
        'is_window' => 'boolean',
        'is_aisle' => 'boolean',
        'is_emergency_exit' => 'boolean',
    ];

    /**
     * Accessors
     */
    public function getColumnAttribute()
    {
        return substr($this->seat_number, -1);
    }

    /**
     * Relations
     */
    
    // Un siège appartient à un vol
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    // Un siège peut appartenir à une réservation
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    // Un siège peut être sélectionné par un utilisateur
    public function selectedBy()
    {
        return $this->belongsTo(User::class, 'selected_by');
    }

    /**
     * Scopes
     */
    
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
                    ->orWhere(function($q) {
                        // Libérer les sièges sélectionnés depuis plus de 10 minutes
                        $q->where('status', self::STATUS_SELECTED)
                          ->where('selected_at', '<', now()->subMinutes(10));
                    });
    }

    public function scopeForFlight($query, $flightId)
    {
        return $query->where('flight_id', $flightId);
    }

    public function scopeByClass($query, $class)
    {
        return $query->where('seat_class', $class);
    }

    /**
     * Méthodes utilitaires
     */
    
    // Vérifier si le siège est disponible
    public function isAvailable(): bool
    {
        if ($this->status === self::STATUS_AVAILABLE) {
            return true;
        }
        
        // Si le siège est sélectionné depuis plus de 10 minutes, il redevient disponible
        if ($this->status === self::STATUS_SELECTED && $this->selected_at) {
            return $this->selected_at->addMinutes(10)->isPast();
        }
        
        return false;
    }

    // Sélectionner temporairement le siège
    public function selectTemporarily(int $userId): bool
    {
        if ($this->isAvailable()) {
            $this->update([
                'status' => self::STATUS_SELECTED,
                'selected_at' => now(),
                'selected_by' => $userId,
            ]);
            return true;
        }
        return false;
    }

    // Réserver définitivement le siège
    public function reserve(int $reservationId): void
    {
        $this->update([
            'status' => self::STATUS_RESERVED,
            'reservation_id' => $reservationId,
            'selected_at' => null,
            'selected_by' => null,
        ]);
    }

    // Libérer le siège
    public function release(): void
    {
        $this->update([
            'status' => self::STATUS_AVAILABLE,
            'reservation_id' => null,
            'selected_at' => null,
            'selected_by' => null,
        ]);
    }

    // Obtenir le temps restant avant expiration de la sélection
    public function getTimeRemaining(): ?int
    {
        if ($this->status === self::STATUS_SELECTED && $this->selected_at) {
            $expiresAt = $this->selected_at->addMinutes(10);
            $now = now();
            
            if ($expiresAt->isFuture()) {
                return $now->diffInSeconds($expiresAt);
            }
        }
        return null;
    }

    /**
     * Méthode statique pour libérer les sièges expirés
     */
    public static function releaseExpiredSeats(): int
    {
        return self::where('status', self::STATUS_SELECTED)
                   ->where('selected_at', '<', now()->subMinutes(10))
                   ->update([
                       'status' => self::STATUS_AVAILABLE,
                       'selected_at' => null,
                       'selected_by' => null,
                   ]);
    }
}