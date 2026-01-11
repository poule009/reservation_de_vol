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
        // Validate input data
        if (!isset($data['payment_method'])) {
            Log::warning("Payment method not provided for reservation {$reservation->id}");
            return [
                'success' => false,
                'message' => 'Payment method is required.',
            ];
        }

        DB::beginTransaction();
        $payment = null;
        try {
            // Validate reservation status
            if ($reservation->status !== 'pending') {
                Log::warning("Attempted payment for non-pending reservation {$reservation->id}, user {$reservation->user_id}");
                return [
                    'success' => false,
                    'message' => 'Reservation is not in a payable state.',
                ];
            }

            // Calculate total amount
            $totalAmount = $reservation->total_amount;

            if ($totalAmount <= 0) {
                Log::error("Invalid payment amount for reservation {$reservation->id}, user {$reservation->user_id}: {$totalAmount}");
                return [
                    'success' => false,
                    'message' => 'Invalid payment amount.',
                ];
            }

            Log::info("Processing payment for user {$reservation->user_id}, reservation {$reservation->id}, amount {$totalAmount}, method {$data['payment_method']}");

            // Create payment record
            $payment = Payment::create([
                'reservation_id' => $reservation->id,
                'user_id' => $reservation->user_id,
                'amount' => $totalAmount,
                'currency' => 'XOF',
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
                'transaction_id' => $this->generateTransactionId(),
            ]);

            Log::info("Payment record created: user {$payment->user_id}, reservation {$reservation->id}, amount {$payment->amount}, method {$payment->payment_method}, transaction_id {$payment->transaction_id}");

            // Process payment (in real implementation, integrate with payment gateway)
            $paymentResult = $this->processPaymentWithGateway($data, $totalAmount);

            Log::info("Gateway response for reservation {$reservation->id}: success={$paymentResult['success']}, message={$paymentResult['message']}, transaction_id=" . ($paymentResult['transaction_id'] ?? 'N/A'));

            if ($paymentResult['success']) {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'transaction_id' => $paymentResult['transaction_id'] ?? $payment->transaction_id,
                ]);
                $reservation->update(['status' => 'confirmed']);

                DB::commit();
                Log::info("Payment processed successfully for reservation {$reservation->id}, transaction_id {$payment->transaction_id}");

                return [
                    'success' => true,
                    'message' => 'Payment processed successfully.',
                    'transaction_id' => $payment->transaction_id,
                ];
            } else {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $paymentResult['message'] ?? 'Unknown error'
                ]);
                DB::rollBack();
                Log::warning("Payment failed for reservation {$reservation->id}: " . ($paymentResult['message'] ?? 'Unknown error'));

                return [
                    'success' => false,
                    'message' => $paymentResult['message'] ?? 'Payment processing failed.',
                ];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = 'An unexpected error occurred during payment processing. Please try again.';
            $failureReason = 'Exception: ' . $e->getMessage();
            if ($e instanceof \Illuminate\Database\QueryException) {
                $errorMessage = 'Database error during payment processing. Please try again.';
                $failureReason = 'Database error: ' . $e->getMessage();
            }
            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'failure_reason' => $failureReason
                ]);
            }
            Log::error("Payment processing error for reservation {$reservation->id}: {$failureReason}", [
                'exception' => $e,
                'data' => $data
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        }
    }

    private function generateTransactionId()
    {
        return 'txn_' . uniqid();
    }

    private function processPaymentWithGateway(array $data, float $amount)
    {
        // Simulate payment processing with proper error handling
        // In a real application, integrate with Stripe, PayPal, etc.

        // Simulate different outcomes for testing error handling
        $random = rand(1, 10);
        if ($random <= 7) {
            // 70% success
            return [
                'success' => true,
                'message' => 'Payment processed successfully.',
                'transaction_id' => 'txn_' . uniqid(),
            ];
        } elseif ($random == 8) {
            // Insufficient funds
            return [
                'success' => false,
                'message' => 'Insufficient funds.',
            ];
        } elseif ($random == 9) {
            // Network error
            return [
                'success' => false,
                'message' => 'Network error during payment processing.',
            ];
        } else {
            // Invalid card
            return [
                'success' => false,
                'message' => 'Invalid payment method.',
            ];
        }
    }
}
