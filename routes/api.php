<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Models\SpaService;
use App\Http\Controllers\BookingController;
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

// Payment routes - use BookingController methods
Route::post('/create-spa-payment', [BookingController::class, 'createSpaPayment']);
Route::post('/create-yoga-payment', [BookingController::class, 'createYogaPayment']);

// Webhook endpoint for Midtrans payment notifications
Route::post('/midtrans-webhook', function (Request $request) {
    try {
        $serverKey = config('midtrans.server_key');

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

                // Send email notification on successful payment
                try {
                    if ($booking->service_type === 'spa') {
                        Mail::to($booking->user_email)->send(new \App\Mail\SpaBookingSuccessMail($booking));
                    } elseif ($booking->service_type === 'yoga') {
                        Mail::to($booking->user_email)->send(new \App\Mail\YogaBookingSuccessMail($booking));
                    } elseif ($booking->service_type === 'gym') {
                        Mail::to($booking->user_email)->send(new \App\Mail\GymBookingSuccessMail($booking));
                    }

                    Log::info('Email notification sent for successful payment', ['order_id' => $orderId]);
                } catch (\Exception $e) {
                    Log::error('Failed to send email notification', ['error' => $e->getMessage(), 'order_id' => $orderId]);
                }
            }
        } elseif ($transactionStatus === 'settlement') {
            $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);

            // Send email notification on successful payment
            try {
                if ($booking->service_type === 'spa') {
                    Mail::to($booking->user_email)->send(new \App\Mail\SpaBookingSuccessMail($booking));
                } elseif ($booking->service_type === 'yoga') {
                    Mail::to($booking->user_email)->send(new \App\Mail\YogaBookingSuccessMail($booking));
                } elseif ($booking->service_type === 'gym') {
                    Mail::to($booking->user_email)->send(new \App\Mail\GymBookingSuccessMail($booking));
                }

                Log::info('Email notification sent for successful payment', ['order_id' => $orderId]);
            } catch (\Exception $e) {
                Log::error('Failed to send email notification', ['error' => $e->getMessage(), 'order_id' => $orderId]);
            }
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
