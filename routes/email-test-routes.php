<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Services\EmailNotificationService;
use App\Models\User;
use Illuminate\Support\Facades\Log;

// Email Testing Routes - Add these to your existing web.php
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Email Testing Routes
    Route::prefix('email-test')->name('email.test.')->group(function () {

        Route::get('/', function () {
            return view('admin.email-test');
        })->name('index');

        Route::post('/send-welcome', function (Request $request) {
            try {
                $email = $request->input('email', env('MAIL_FROM_ADDRESS'));

                // Create test user data
                $testUser = (object) [
                    'id' => 999,
                    'name' => 'Test User - ' . now()->format('H:i:s'),
                    'email' => $email,
                    'phone' => '+62812345678'
                ];

                $emailService = new EmailNotificationService();
                $result = $emailService->sendWelcomeEmail($testUser);

                return response()->json([
                    'success' => $result,
                    'message' => $result ? "✅ Welcome email berhasil dikirim ke {$email}" : "❌ Gagal mengirim welcome email",
                    'email_config' => $emailService->getEmailStatus()
                ]);

            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => "❌ Error: " . $e->getMessage()
                ], 500);
            }
        })->name('welcome');

        Route::post('/send-booking', function (Request $request) {
            try {
                $email = $request->input('email', env('MAIL_FROM_ADDRESS'));
                $type = $request->input('type', 'spa');

                // Create test booking data
                $testBooking = [
                    'booking_code' => strtoupper($type) . '-TEST-' . time(),
                    'customer_name' => 'Test Customer',
                    'customer_email' => $email,
                    'booking_date' => now()->addDays(1)->format('Y-m-d'),
                    'booking_time' => '14:00',
                    'duration' => $type === 'spa' ? 90 : ($type === 'gym' ? 2 : 60),
                    'total_amount' => $type === 'spa' ? 350000 : ($type === 'gym' ? 75000 : 100000),
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'payment_method' => 'Bank Transfer',
                    'notes' => 'Test booking via email testing',
                    'supportEmail' => env('MAIL_FROM_ADDRESS')
                ];

                // Add type-specific fields
                if ($type === 'spa') {
                    $testBooking['therapistPreference'] = 'Female';
                } elseif ($type === 'yoga') {
                    $testBooking['classType'] = 'Hatha Yoga';
                    $testBooking['participants'] = 1;
                    $testBooking['specialRequests'] = 'Test special request';
                }

                $emailService = new EmailNotificationService();
                $result = $emailService->sendBookingConfirmation($testBooking, $type);

                return response()->json([
                    'success' => $result,
                    'message' => $result ? "✅ {$type} booking email berhasil dikirim ke {$email}" : "❌ Gagal mengirim {$type} booking email",
                    'booking_data' => $testBooking
                ]);

            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => "❌ Error: " . $e->getMessage()
                ], 500);
            }
        })->name('booking');

        Route::get('/status', function () {
            try {
                $emailService = new EmailNotificationService();
                $status = $emailService->getEmailStatus();

                // Test connection
                $connectionTest = $emailService->testEmailConnection();

                return response()->json([
                    'config' => $status,
                    'connection_test' => $connectionTest,
                    'timestamp' => now()->toDateTimeString()
                ]);

            } catch (Exception $e) {
                return response()->json([
                    'error' => $e->getMessage(),
                    'timestamp' => now()->toDateTimeString()
                ], 500);
            }
        })->name('status');
    });
});
