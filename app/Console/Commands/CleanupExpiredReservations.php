<?php


namespace App\Console\Commands;

use App\Models\Reservation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredReservations extends Command
{
    
    protected $signature = 'reservations:cleanup';

    
    
     
    protected $description = 'Nettoyer les réservations en attente expirées (plus de 30 minutes)';

    
   
     
    public function handle()
    {
        $this->info('Nettoyage des réservations expirées...');
        
        try {
            DB::beginTransaction();
            
            
            $expiredReservations = Reservation::with('seats', 'flight')
                ->where('status', 'pending')
                ->where('created_at', '<', now()->subMinutes(30))
                ->get();
            
            $count = 0;
            
            foreach ($expiredReservations as $reservation) {
                $seatsCount = $reservation->seats->count();

                if ($seatsCount > 0) {
                    $seatIds = $reservation->seats->pluck('id');

                    DB::table('seats')->whereIn('id', $seatIds)->update([
                        'status' => 'available',
                        'reservation_id' => null,
                    ]);

                    if ($reservation->flight) {
                        $reservation->flight->increment('available_seats', $seatsCount);
                    }
                }
                
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