<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique(); // Ex: AA123
            $table->string('airline'); // Nom de la compagnie
            $table->string('departure_airport', 10); // Code IATA: CDG
            $table->string('arrival_airport', 10); // Code IATA: JFK
            $table->string('departure_city')->nullable();
            $table->string('arrival_city')->nullable();
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->decimal('price', 10, 2); // Prix du vol
            $table->integer('total_seats')->default(150);
            $table->integer('available_seats')->default(150);
            $table->string('status')->default('scheduled'); // scheduled, delayed, cancelled
            $table->json('additional_info')->nullable(); // Infos supplémentaires de l'API
            
            // Index pour optimiser les recherches
            $table->index('flight_number');
            $table->index(['departure_airport', 'arrival_airport']);
            $table->index('departure_time');
            $table->index('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
