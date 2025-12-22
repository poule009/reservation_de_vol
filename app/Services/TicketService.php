<?php

namespace App\Services;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

use App\Mail\TicketMail;

class TicketService
{
    public function generateTicket(Reservation $reservation)
    {
        $reservation->load(['flight', 'seats', 'user', 'payment', 'ticket']);

        return Pdf::loadView('tickets.pdf', [
            'reservation' => $reservation,
            'ticket' => $reservation->ticket,
        ]);
    }

    public function downloadTicket(Reservation $reservation)
    {
        $pdf = $this->generateTicket($reservation);
        return $pdf->download("ticket-{$reservation->booking_reference}.pdf");
    }



    public function emailTicket(Reservation $reservation)
    {
        if ($reservation->user && $reservation->user->email) {
            $pdf = $this->generateTicket($reservation);
            
            // On envoie le contenu brut du PDF (output)
            Mail::to($reservation->user->email)
                ->send(new TicketMail($reservation, $pdf->output()));
        }
    }

    public function verifyTicket($reservationNumber)
    {
        $reservation = Reservation::where('booking_reference', $reservationNumber)
            ->with(['flight', 'seats'])
            ->first();

        if (!$reservation) {
            return ['valid' => false, 'message' => 'Réservation introuvable'];
        }

        if ($reservation->status !== 'confirmed') {
            return ['valid' => false, 'message' => 'Réservation non confirmée'];
        }

        // Nécessite le cast 'datetime' dans le modèle Flight
        if ($reservation->flight->departure_time->isPast()) {
            return ['valid' => false, 'message' => 'Vol déjà parti'];
        }

        return ['valid' => true, 'reservation' => $reservation];
    }
}
