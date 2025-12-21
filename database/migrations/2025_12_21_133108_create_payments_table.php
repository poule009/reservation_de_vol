<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Table pour suivre les paiements
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Détails du paiement
            $table->string('transaction_id')->unique()->nullable(); // ID de la transaction externe
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('XOF'); // Devise
            $table->string('payment_method')->nullable(); // card, mobile_money, etc.
            
            // Statut du paiement
            $table->enum('status', [
                'pending',
                'processing',
                'completed',
                'failed',
                'refunded'
            ])->default('pending');
            
            // Informations supplémentaires
            $table->text('payment_details')->nullable(); // JSON des détails du paiement
            $table->timestamp('paid_at')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index('transaction_id');
            $table->index(['reservation_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};