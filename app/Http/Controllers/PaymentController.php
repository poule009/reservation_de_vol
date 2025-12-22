<?php

namespace App\Http\Controllers;


use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Ticket;
use App\Services\PaymentService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    // Page de paiement pour une réservation
    public function create(Reservation $reservation)
    {
        $reservation->load(['flight', 'seats']);

        // On calcule le montant à payer
        $montant = $reservation->total_amount;

        return view('payments.create', compact('reservation', 'montant'));
    }

    // Traitement du paiement et génération du ticket
    public function process(Request $request, Reservation $reservation)
    {
        $request->validate([
            'payment_method' => ['required', 'in:card,mobile_money'],
        ]);

        $reservation->load(['flight', 'seats', 'user']);

        $paymentService = new PaymentService();
        $result = $paymentService->processPayment($reservation, $request->all());

        if ($result['success']) {
            // Générer le ticket si pas déjà créé
            if (!$reservation->ticket) {
                $ticket = Ticket::create([
                    'reservation_id' => $reservation->id,
                    'numero_billet'  => $reservation->booking_reference,
                    'pdf_path'       => null,
                ]);
            }

            return redirect()->route('payments.success', $reservation);
        } else {
            return back()->withErrors(['payment' => $result['message']]);
        }
    }

    // Page de succès après paiement
    public function success(Reservation $reservation)
    {
        // Vérifier que la réservation est confirmée et payée
        if ($reservation->status !== 'confirmed' || !$reservation->payment || $reservation->payment->status !== 'completed') {
            abort(403, 'Access denied');
        }

        $reservation->load(['flight', 'seats', 'user', 'payment']);

        return view('payments.success', compact('reservation'));
    }

    // Téléchargement du ticket
    public function ticket(Reservation $reservation)
    {
        // Vérifier que la réservation est confirmée et payée
        if ($reservation->status !== 'confirmed' || !$reservation->payment || $reservation->payment->status !== 'completed') {
            abort(403, 'Ticket not available');
        }

        $ticketService = new TicketService();
        return $ticketService->downloadTicket($reservation);
    }
}