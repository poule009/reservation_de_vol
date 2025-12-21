<?php

namespace App\Http\Controllers;


use App\Models\Reservation;
use App\Services\PaymentService;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $ticketService;

    public function __construct(PaymentService $paymentService, TicketService $ticketService)
    {
        $this->paymentService = $paymentService;
        $this->ticketService = $ticketService;
    }

    /**
     * Page de paiement
     */
    public function create(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
abort(403);
} 
if ($reservation->status !== 'pending') {
        return redirect()->route('reservations.show', $reservation)
            ->with('error', 'Cette réservation a déjà été payée');
    }

    if ($reservation->seats()->count() === 0) {
        return redirect()->route('seats.select', $reservation)
            ->with('error', 'Veuillez d\'abord sélectionner vos sièges');
    }

    $reservation->load(['flight', 'seats']);

    return view('payments.create', compact('reservation'));
}

/**
 * Traiter le paiement
 */
public function store(Request $request, Reservation $reservation)
{
    if ($reservation->user_id !== Auth::id()) {
        abort(403);
    }

    $request->validate([
        'payment_method' => 'required|in:card,mobile_money,bank_transfer',
    ]);

    $result = $this->paymentService->processPayment($reservation, $request->all());

    if ($result['success']) {
        return redirect()->route('payments.success', $reservation);
    }

    return back()->with('error', $result['message']);
}

/**
 * Page de succès du paiement
 */
public function success(Reservation $reservation)
{
    if ($reservation->user_id !== Auth::id()) {
        abort(403);
    }

    if ($reservation->status !== 'confirmed') {
        return redirect()->route('reservations.show', $reservation);
    }

    $reservation->load(['flight', 'seats', 'payment']);

    return view('payments.success', compact('reservation'));
}

/**
 * Télécharger le ticket
 */
public function downloadTicket(Reservation $reservation)
{
    if ($reservation->user_id !== Auth::id()) {
        abort(403);
    }

    if ($reservation->status !== 'confirmed') {
        return redirect()->route('reservations.show', $reservation)
            ->with('error', 'Le ticket n\'est pas disponible');
    }

    return $this->ticketService->downloadTicket($reservation);
}
}
