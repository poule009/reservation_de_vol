<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function processPayment(Reservation $reservation, array $data)
    {
        DB::beginTransaction();
        try {
            // Calcul total
            $totalAmount = $reservation->total_amount;

            // Create payment record
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'amount' => $totalAmount,
                'currency' => 'XOF',
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'transaction_id' => uniqid('txn_'),
            ]);

            // Simul payment
            $paymentSuccess = $this->simulatePaymentProcessing($data);

            if ($paymentSuccess) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now()
                ]);
                $reservation->update(['status' => 'confirmed']);

                DB::commit();
                Log::info("Payment processed successfully for reservation {$reservation->id}");

                return [
                    'success' => true,
                    'message' => 'Payment processed successfully.',
                ];
            } else {
                $payment->update(['status' => 'failed']);
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Payment processing failed.',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Payment processing error: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'An error occurred during payment processing.',
            ];
        }
    }

    private function simulatePaymentProcessing(array $data)
    {
        // Simulate payment success/failure
        // In a real application, integrate with Stripe, PayPal, etc.
        return rand(1, 1) === 1; // 50% success rate for simulation
    }
}
