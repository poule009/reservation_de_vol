<?php
// app/Console/Commands/CleanupExpiredReservations.php

namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredReservations extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reservations:cleanup';

    /**
     * The console command description.
     */
    protected $description = 'Nettoyer les réservations en attente expirées (plus de 30 minutes)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Nettoyage des réservations expirées...');
        
        try {
            DB::beginTransaction();
            
            // Récupérer les réservations en attente depuis plus de 30 minutes
            $expiredReservations = Reservation::where('status', 'pending')
                ->where('created_at', '<', now()->subMinutes(30))
                ->get();
            
            $count = 0;
            
            foreach ($expiredReservations as $reservation) {
                // Libérer les sièges
                $reservation->seats()->update([
                    'status' => 'available',
                    'reservation_id' => null,
                ]);
                
                // Incrémenter les sièges disponibles
                if ($reservation->seats()->count() > 0) {
                    $reservation->flight->increment('available_seats', $reservation->seats()->count());
                }
                
                // Annuler la réservation
                $reservation->update(['status' => 'cancelled']);
                
                $count++;
            }
            
            DB::commit();
            
            $this->info("✓ {$count} réservations expirées nettoyées!");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Erreur lors du nettoyage: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}