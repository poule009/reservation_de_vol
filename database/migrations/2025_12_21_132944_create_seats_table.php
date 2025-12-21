<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table pour gérer les sièges et leur disponibilité
     */
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_id')->constrained()->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->constrained()->onDelete('set null');
            
            // Identification du siège
            $table->string('seat_number', 5); // Ex: 12A, 23B
            $table->string('seat_class')->default('economy'); // Renommé pour éviter le mot-clé réservé 'class'
            
            // Statut du siège
            $table->enum('status', [
                'available',    // Disponible
                'selected',     // Sélectionné par un utilisateur (verrouillage temporaire)
                'reserved',     // Fait partie d'une réservation en attente de paiement
                'occupied'      // Payé et occupé
            ])->default('available');
            
            // Caractéristiques et coût additionnel
            $table->decimal('extra_charge', 10, 2)->default(0);
            $table->boolean('is_window')->default(false);
            $table->boolean('is_aisle')->default(false);
            $table->boolean('is_emergency_exit')->default(false);

            // Timestamp pour gérer l'expiration des sélections temporaires
            $table->timestamp('selected_at')->nullable();
            $table->foreignId('selected_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
            
            // Contrainte d'unicité: un siège par vol
            $table->unique(['flight_id', 'seat_number']);
            
            // Index pour optimisation
            $table->index(['flight_id', 'status']);
            $table->index('reservation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};