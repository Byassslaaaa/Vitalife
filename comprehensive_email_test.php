<?php

// Test email dengan tinker script
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VITALIFE EMAIL TEST - REGISTRATION & BOOKING ===\n\n";

try {
    echo "Testing Email Configuration:\n";
    echo "- MAIL_HOST: " . env('MAIL_HOST') . "\n";
    echo "- MAIL_PORT: " . env('MAIL_PORT') . "\n";
    echo "- MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n\n";

    // Test 1: Welcome Email
    echo "1. Testing Welcome Email...\n";
    $emailService = new App\Services\EmailNotificationService();

    $testUser = (object) [
        'id' => 999,
        'name' => 'Test User - ' . date('H:i:s'),
        'email' => 'ubayagung1122@gmail.com',
        'phone' => '+6281234567890'
    ];

    $welcomeResult = $emailService->sendWelcomeEmail($testUser);
    echo "   Welcome Email Result: " . ($welcomeResult ? '✅ SUCCESS' : '❌ FAILED') . "\n\n";

    // Test 2: Spa Booking Email
    echo "2. Testing Spa Booking Email...\n";
    $spaBooking = [
        'booking_code' => 'SPA-TEST-' . time(),
        'customer_name' => 'Test Customer',
        'customer_email' => 'ubayagung1122@gmail.com',
        'booking_date' => now()->addDays(1)->format('Y-m-d'),
        'booking_time' => '14:00',
        'duration' => 90,
        'therapist_preference' => 'Female',
        'total_amount' => 350000,
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'payment_method' => 'Bank Transfer',
        'notes' => 'Test booking from PHP script',
        'supportEmail' => env('MAIL_FROM_ADDRESS')
    ];

    $spaResult = $emailService->sendBookingConfirmation($spaBooking, 'spa');
    echo "   Spa Booking Email Result: " . ($spaResult ? '✅ SUCCESS' : '❌ FAILED') . "\n\n";

    // Test 3: Yoga Booking Email
    echo "3. Testing Yoga Booking Email...\n";
    $yogaBooking = [
        'booking_code' => 'YOGA-TEST-' . time(),
        'customer_name' => 'Test Customer',
        'customer_email' => 'ubayagung1122@gmail.com',
        'booking_date' => now()->addDays(1)->format('Y-m-d'),
        'booking_time' => '07:00',
        'class_type' => 'Hatha Yoga',
        'participants' => 1,
        'total_amount' => 100000,
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'payment_method' => 'Bank Transfer',
        'special_requests' => 'Test booking from PHP script',
        'supportEmail' => env('MAIL_FROM_ADDRESS')
    ];

    $yogaResult = $emailService->sendBookingConfirmation($yogaBooking, 'yoga');
    echo "   Yoga Booking Email Result: " . ($yogaResult ? '✅ SUCCESS' : '❌ FAILED') . "\n\n";

    echo "=== SUMMARY ===\n";
    echo "Welcome Email: " . ($welcomeResult ? '✅' : '❌') . "\n";
    echo "Spa Booking: " . ($spaResult ? '✅' : '❌') . "\n";
    echo "Yoga Booking: " . ($yogaResult ? '✅' : '❌') . "\n\n";

    if ($welcomeResult && $spaResult && $yogaResult) {
        echo "🎉 ALL TESTS PASSED! Email system is working correctly.\n";
        echo "Cek email ubayagung1122@gmail.com untuk konfirmasi.\n";
    } else {
        echo "⚠️  Some tests failed. Check Laravel logs for details.\n";
        echo "Run: php artisan log:tail atau cek storage/logs/laravel.log\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
