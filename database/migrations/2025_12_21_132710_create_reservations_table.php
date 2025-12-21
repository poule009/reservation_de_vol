<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table pour gérer les réservations des utilisateurs
     */
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            
            // Informations du passager
            $table->string('passenger_first_name');
            $table->string('passenger_last_name');
            $table->string('passenger_phone');
            $table->string('passenger_email')->nullable();
            
            // Numéro de réservation unique
            $table->string('booking_reference', 10)->unique(); // Ex: XYZ123ABC
            
            // Statut de la réservation
            $table->enum('status', [
                'pending',      // En attente de paiement
                'confirmed',    // Confirmée
                'cancelled',    // Annulée
                'completed'     // Vol effectué
            ])->default('pending');
            
            // Prix total payé
            $table->decimal('total_amount', 10, 2);
            
            $table->timestamp('booking_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index pour optimisation
            $table->index('booking_reference');
            $table->index(['user_id', 'status']);
            $table->index('flight_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};