<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function createSpaPayment(Request $request)
    {
        try {
            // Log the incoming request for debugging
            Log::info('Payment request received', $request->all());

            // Validate request data
            $validator = Validator::make($request->all(), [
                'order_id' => 'required|string',
                'spa_id' => 'required|integer',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email',
                'customer_phone' => 'required|string',
                'service_type' => 'required|string',
                'service_price' => 'required|integer|min:0',
                'admin_fee' => 'required|integer|min:0',
                'total_amount' => 'required|integer|min:0',
                'booking_date' => 'required|date',
                'booking_time' => 'required|string',
                'service_address' => 'required|string',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $validated = $validator->validated();

            // Get Midtrans configuration
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production');

            if (!$serverKey) {
                Log::error('Midtrans server key not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi Midtrans tidak lengkap'
                ], 500);
            }

            // Create booking record first
            Log::info('Creating booking record', $validated);

            $booking = Booking::create([
                'order_id' => $validated['order_id'],
                'spa_id' => $validated['spa_id'],
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'service_type' => $validated['service_type'],
                'service_price' => $validated['service_price'],
                'admin_fee' => $validated['admin_fee'],
                'total_amount' => $validated['total_amount'],
                'booking_date' => $validated['booking_date'],
                'booking_time' => $validated['booking_time'],
                'service_address' => $validated['service_address'],
                'notes' => $validated['notes'],
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            Log::info('Booking created successfully', ['booking_id' => $booking->id]);

            // Prepare Midtrans parameter
            $parameter = [
                'transaction_details' => [
                    'order_id' => $validated['order_id'],
                    'gross_amount' => $validated['total_amount']
                ],
                'credit_card' => [
                    'secure' => true
                ],
                'customer_details' => [
                    'first_name' => $validated['customer_name'],
                    'email' => $validated['customer_email'],
                    'phone' => $validated['customer_phone']
                ],
                'item_details' => [
                    [
                        'id' => 'spa-service',
                        'price' => $validated['service_price'],
                        'quantity' => 1,
                        'name' => 'Layanan Spa - ' . $validated['service_type'],
                        'category' => 'Spa Service'
                    ],
                    [
                        'id' => 'admin-fee',
                        'price' => $validated['admin_fee'],
                        'quantity' => 1,
                        'name' => 'Biaya Admin',
                        'category' => 'Admin Fee'
                    ]
                ],
                'callbacks' => [
                    'finish' => url('/spa/' . $validated['spa_id'])
                ],
                'expiry' => [
                    'start_time' => now()->toISOString(),
                    'unit' => 'minutes',
                    'duration' => 60
                ]
            ];

            // Determine Midtrans URL
            $midtransUrl = $isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            Log::info('Sending request to Midtrans', ['url' => $midtransUrl]);

            // Make request to Midtrans
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
            ])->post($midtransUrl, $parameter);

            $result = $response->json();
            Log::info('Midtrans response', $result);

            if ($response->successful() && isset($result['token'])) {
                // Update booking with Midtrans transaction ID
                $booking->update([
                    'midtrans_transaction_id' => $result['token']
                ]);

                // Log successful payment creation
                Log::info('Payment token created successfully', [
                    'order_id' => $validated['order_id'],
                    'total_amount' => $validated['total_amount'],
                    'customer_name' => $validated['customer_name'],
                    'booking_id' => $booking->id,
                    'token' => $result['token']
                ]);

                return response()->json([
                    'success' => true,
                    'payment_token' => $result['token'],
                    'order_id' => $validated['order_id'],
                    'total_amount' => $validated['total_amount'],
                    'booking_id' => $booking->id,
                    'redirect_url' => $result['redirect_url'] ?? null
                ]);
            } else {
                // Delete the booking if payment creation failed
                $booking->delete();

                Log::error('Midtrans Error', $result);
                return response()->json([
                    'success' => false,
                    'message' => isset($result['error_messages'])
                        ? implode(', ', $result['error_messages'])
                        : 'Gagal membuat token pembayaran'
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error creating payment', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan internal server: ' . $e->getMessage()
            ], 500);
        }
    }
}
