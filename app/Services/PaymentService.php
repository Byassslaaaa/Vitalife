<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        $this->configureMidtrans();
    }

    /**
     * Configure Midtrans settings
     */
    protected function configureMidtrans(): void
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        Log::info('Midtrans configured', [
            'is_production' => Config::$isProduction,
            'server_key_exists' => !empty(Config::$serverKey)
        ]);
    }

    /**
     * Generate Midtrans Snap token for booking
     *
     * @param object $booking The booking model instance (SpaBooking|YogaBooking|GymBooking)
     * @param string $type Booking type (spa|yoga|gym)
     * @return string Snap token
     * @throws \Exception
     */
    public function generateToken($booking, string $type): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $booking->booking_code,
                'gross_amount' => (int) $booking->service_price,
            ],
            'customer_details' => [
                'first_name' => $booking->customer_name,
                'email' => $booking->customer_email,
                'phone' => $booking->customer_phone,
            ],
            'item_details' => [[
                'id' => $booking->service_id ?? $booking->id,
                'price' => (int) $booking->service_price,
                'quantity' => 1,
                'name' => $booking->service_name ?? ucfirst($type) . ' Booking',
            ]],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Update booking dengan payment token
            $booking->update(['payment_token' => $snapToken]);

            Log::info('Snap token generated successfully', [
                'booking_code' => $booking->booking_code,
                'type' => $type,
                'amount' => $booking->service_price
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap token generation failed', [
                'booking_code' => $booking->booking_code,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Failed to generate payment token: ' . $e->getMessage());
        }
    }

    /**
     * Generate Midtrans token dengan custom item details (untuk multiple items)
     *
     * @param string $bookingCode
     * @param int $totalAmount
     * @param string $customerName
     * @param string $customerEmail
     * @param string $customerPhone
     * @param array $itemDetails
     * @return string
     * @throws \Exception
     */
    public function generateTokenWithItems(
        string $bookingCode,
        int $totalAmount,
        string $customerName,
        string $customerEmail,
        string $customerPhone,
        array $itemDetails
    ): string {
        // Ensure item_details is array of arrays (multiple items case)
        if (isset($itemDetails['id'])) {
            $itemDetails = [$itemDetails];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $bookingCode,
                'gross_amount' => (int) $totalAmount,
            ],
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            Log::info('Snap token generated with custom items', [
                'booking_code' => $bookingCode,
                'amount' => $totalAmount,
                'items_count' => count($itemDetails)
            ]);

            return $snapToken;

        } catch (\Exception $e) {
            Log::error('Midtrans Snap token generation failed (custom items)', [
                'booking_code' => $bookingCode,
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Failed to generate payment token: ' . $e->getMessage());
        }
    }

    /**
     * Handle Midtrans webhook/callback notification
     *
     * @param array $notification Notification from Midtrans
     * @return array Processed payment status
     */
    public function handleCallback(array $notification): array
    {
        $orderId = $notification['order_id'] ?? null;
        $transactionStatus = $notification['transaction_status'] ?? 'pending';
        $fraudStatus = $notification['fraud_status'] ?? 'accept';
        $paymentType = $notification['payment_type'] ?? 'unknown';

        Log::info('Processing Midtrans callback', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType
        ]);

        $paymentStatus = $this->mapTransactionStatus($transactionStatus, $fraudStatus);
        $bookingStatus = $this->mapBookingStatus($paymentStatus);

        return [
            'order_id' => $orderId,
            'payment_status' => $paymentStatus,
            'booking_status' => $bookingStatus,
            'transaction_status' => $transactionStatus,
            'payment_type' => $paymentType,
        ];
    }

    /**
     * Map Midtrans transaction status to internal payment status
     *
     * @param string $status Transaction status from Midtrans
     * @param string $fraudStatus Fraud status from Midtrans
     * @return string Internal payment status
     */
    protected function mapTransactionStatus(string $status, string $fraudStatus): string
    {
        // Handle capture with fraud check
        if ($status == 'capture') {
            return $fraudStatus == 'accept' ? 'paid' : 'failed';
        }

        // Map other statuses
        return match($status) {
            'settlement' => 'paid',
            'pending' => 'pending',
            'deny', 'expire', 'cancel' => 'failed',
            default => 'pending',
        };
    }

    /**
     * Map payment status to booking status
     *
     * @param string $paymentStatus Internal payment status
     * @return string Booking status
     */
    protected function mapBookingStatus(string $paymentStatus): string
    {
        return match($paymentStatus) {
            'paid' => 'confirmed',
            'failed' => 'cancelled',
            default => 'pending',
        };
    }

    /**
     * Verify Midtrans signature untuk keamanan webhook
     *
     * @param array $notification
     * @return bool
     */
    public function verifySignature(array $notification): bool
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $notification['order_id'] ?? '';
        $statusCode = $notification['status_code'] ?? '';
        $grossAmount = $notification['gross_amount'] ?? '';
        $signatureKey = $notification['signature_key'] ?? '';

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        $isValid = $expectedSignature === $signatureKey;

        if (!$isValid) {
            Log::warning('Invalid Midtrans signature detected', [
                'order_id' => $orderId,
                'expected' => substr($expectedSignature, 0, 20) . '...',
                'received' => substr($signatureKey, 0, 20) . '...'
            ]);
        }

        return $isValid;
    }

    /**
     * Get transaction status from Midtrans
     *
     * @param string $orderId
     * @return array|null
     */
    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            $status = \Midtrans\Transaction::status($orderId);

            Log::info('Transaction status retrieved', [
                'order_id' => $orderId,
                'status' => $status->transaction_status ?? 'unknown'
            ]);

            return (array) $status;

        } catch (\Exception $e) {
            Log::error('Failed to get transaction status', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Cancel a transaction
     *
     * @param string $orderId
     * @return bool
     */
    public function cancelTransaction(string $orderId): bool
    {
        try {
            \Midtrans\Transaction::cancel($orderId);

            Log::info('Transaction cancelled', [
                'order_id' => $orderId
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to cancel transaction', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
