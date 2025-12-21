<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'flight_id',
        'passenger_first_name',
        'passenger_last_name',
        'passenger_phone',
        'passenger_email',
        'booking_reference',
        'status',
        'total_amount',
        'booking_date',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    /**
     * Boot method pour générer automatiquement le booking_reference
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            if (empty($reservation->booking_reference)) {
                $reservation->booking_reference = self::generateBookingReference();
            }
        });
    }

    /**
     * Relations
     */
    
    // Une réservation appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Une réservation appartient à un vol
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    // Une réservation peut avoir plusieurs sièges
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    // Une réservation peut avoir un paiement
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Scopes
     */
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Méthodes utilitaires
     */
    
    // Générer un numéro de réservation unique
    public static function generateBookingReference(): string
    {
        do {
            $reference = strtoupper(Str::random(6) . rand(100, 999));
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    // Obtenir le nom complet du passager
    public function getFullPassengerName(): string
    {
        return $this->passenger_first_name . ' ' . $this->passenger_last_name;
    }

    // Confirmer la réservation
    public function confirm(): void
    {
        $this->update(['status' => 'confirmed']);
    }

    // Annuler la réservation
    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
        
        // Libérer les sièges
        $this->seats()->update([
            'status' => 'available',
            'reservation_id' => null,
            'selected_at' => null,
            'selected_by' => null,
        ]);
        
        // Incrémenter les sièges disponibles du vol
        $this->flight->incrementSeats($this->seats()->count());
    }

    // Vérifier si la réservation peut être modifiée
    public function canBeModified(): bool
    {
        return in_array($this->status, ['pending']) && 
               $this->flight->departure_time->isFuture();
    }

    // Vérifier si la réservation peut être annulée
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']) && 
               $this->flight->departure_time->isFuture();
    }
}