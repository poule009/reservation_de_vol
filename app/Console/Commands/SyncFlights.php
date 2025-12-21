<?php
// app/Console/Commands/SyncFlights.php

namespace App\Console\Commands;

use App\Services\FlightApiService;
use Illuminate\Console\Command;

class SyncFlights extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'flights:sync';

    /**
     * The console command description.
     */
    protected $description = 'Synchroniser les vols depuis l\'API externe';

    /**
     * Execute the console command.
     */
    public function handle(FlightApiService $flightApiService)
    {
        $this->info('Synchronisation des vols en cours...');
        
        try {
            $syncedCount = $flightApiService->syncFlights();
            
            $this->info("✓ {$syncedCount} vols synchronisés avec succès!");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Erreur lors de la synchronisation: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}