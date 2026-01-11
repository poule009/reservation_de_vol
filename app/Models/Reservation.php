<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'flight_id',
        'user_id',
        'booking_reference',
        'status',
        'total_amount',
        'passenger_details',
        'passenger_first_name',
        'passenger_last_name',
        'passenger_phone',
        'passenger_email',
        'booking_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'passenger_details' => 'array',
        'booking_date' => 'datetime',
    ];

    /**
     * Get the user that owns the reservation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the flight for the reservation.
     */
    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }

    /**
     * Get the seats for the reservation.
     */
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }

    /**
     * Get the payment associated with the reservation.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Get the ticket associated with the reservation.
     */
    public function ticket(): HasOne
    {
        return $this->hasOne(Ticket::class);
    }

    /**
     * Get the full name of the passenger.
     */
    public function getFullPassengerName(): string
    {
        return trim($this->passenger_first_name . ' ' . $this->passenger_last_name) ?: 'Passager Inconnu';
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

    // Confirmer la réservation
    public function confirm(): void
    {
        $this->update(['status' => self::STATUS_CONFIRMED]);
    }

    // Annuler la réservation
    public function cancel(): void
    {
        $this->update(['status' => self::STATUS_CANCELLED]);

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
