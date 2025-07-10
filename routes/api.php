<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use Carbon\Carbon;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route to get services for a specific spa
Route::get('/spas/{spaId}/services', function ($spaId) {
    $services = SpaService::where('spa_id', $spaId)
                ->where('is_active', true)
                ->get();
    return response()->json($services);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Weather API route
Route::get('/weather', 'App\Http\Controllers\WeatherController@getWeather');

// Payment route - direct implementation
Route::post('/create-spa-payment', function (Request $request) {
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
        $serverKey = config('services.midtrans.server_key');
        $isProduction = config('services.midtrans.is_production');
        
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

        // Prepare Midtrans parameter with correct date format
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
                'start_time' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 60
            ]
        ];

        // Determine Midtrans URL
        $midtransUrl = $isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        Log::info('Sending request to Midtrans', [
            'url' => $midtransUrl,
            'parameter' => $parameter
        ]);

        // Make request to Midtrans
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode($serverKey . ':')
        ])->post($midtransUrl, $parameter);

        $result = $response->json();
        Log::info('Midtrans response', [
            'status' => $response->status(),
            'result' => $result
        ]);

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
            
            Log::error('Midtrans Error', [
                'status' => $response->status(),
                'result' => $result
            ]);
            
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
});

// Webhook endpoint for Midtrans payment notifications
Route::post('/midtrans-webhook', function (Request $request) {
    try {
        $serverKey = config('services.midtrans.server_key');
        
        if (!$serverKey) {
            return response()->json(['message' => 'Server key not configured'], 500);
        }

        // Verify signature
        $orderId = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== $expectedSignature) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        // Find booking by order_id
        $booking = Booking::where('order_id', $orderId)->first();
        
        if (!$booking) {
            Log::warning('Booking not found for order_id: ' . $orderId);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Handle different transaction statuses
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status;

        Log::info('Midtrans webhook received', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'booking_id' => $booking->id
        ]);

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $booking->update(['payment_status' => 'pending', 'status' => 'pending']);
            } elseif ($fraudStatus === 'accept') {
                $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
            }
        } elseif ($transactionStatus === 'settlement') {
            $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $booking->update(['payment_status' => 'failed', 'status' => 'cancelled']);
        } elseif ($transactionStatus === 'pending') {
            $booking->update(['payment_status' => 'pending', 'status' => 'pending']);
        }

        return response()->json(['message' => 'OK']);
    } catch (\Exception $e) {
        Log::error('Webhook error', [
            'error' => $e->getMessage(),
            'request_data' => $request->all()
        ]);
        return response()->json(['message' => 'Internal server error'], 500);
    }
});