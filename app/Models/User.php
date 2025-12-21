<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relations
     */
    
    // Un utilisateur peut avoir plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Un utilisateur peut avoir plusieurs paiements
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Méthodes utilitaires
     */
    
    // Obtenir les statistiques de l'utilisateur
    public function getStatistics()
    {
        return [
            'total_bookings' => $this->reservations()->count(),
            'confirmed_bookings' => $this->reservations()->where('status', 'confirmed')->count(),
            'pending_bookings' => $this->reservations()->where('status', 'pending')->count(),
            'completed_bookings' => $this->reservations()->where('status', 'completed')->count(),
            'total_spent' => $this->payments()->where('status', 'completed')->sum('amount'),
        ];
    }

    // Vérifier si l'utilisateur a des réservations en attente
    public function hasPendingReservations(): bool
    {
        return $this->reservations()->where('status', 'pending')->exists();
    }

    // Obtenir le nombre total de réservations
    public function totalReservations()
    {
        return $this->reservations()->count();
    }

    // Obtenir le nombre de vols complétés (réservations confirmées avec vol passé)
    public function completedFlights()
    {
        return $this->reservations()
            ->where('status', 'confirmed')
            ->whereHas('flight', function ($query) {
                $query->where('departure_time', '<', now());
            })
            ->count();
    }

    // Obtenir le total dépensé (somme des paiements complétés)
    public function totalSpent()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }
}