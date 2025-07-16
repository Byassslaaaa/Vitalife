<?php
// FINAL EMAIL TEST - SYNC MODE
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FINAL VITALIFE EMAIL TEST ===\n";
echo "Queue Mode: " . env('QUEUE_CONNECTION') . "\n";
echo "Mail Host: " . env('MAIL_HOST') . "\n\n";

try {
    // Test 1: Registration Email
    echo "1. Testing Registration Email...\n";
    $emailService = new App\Services\EmailNotificationService();
    $testUser = (object) [
        'id' => 999,
        'name' => 'Test Registration User',
        'email' => 'ubayagung1122@gmail.com',
        'phone' => '+6281234567890'
    ];

    $result1 = $emailService->sendWelcomeEmail($testUser);
    echo "   Registration Email: " . ($result1 ? '✅ SUCCESS' : '❌ FAILED') . "\n";

    // Test 2: Spa Booking Email
    echo "\n2. Testing Spa Booking Email...\n";
    $spaBooking = [
        'booking_code' => 'SPA-FINAL-' . time(),
        'customer_name' => 'Final Test Customer',
        'customer_email' => 'ubayagung1122@gmail.com',
        'booking_date' => now()->addDays(1)->format('Y-m-d'),
        'booking_time' => '14:00',
        'duration' => 90,
        'therapist_preference' => 'Female',
        'total_amount' => 350000,
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'payment_method' => 'Bank Transfer',
        'notes' => 'Final test spa booking',
        'supportEmail' => env('MAIL_FROM_ADDRESS')
    ];

    $result2 = $emailService->sendBookingConfirmation($spaBooking, 'spa');
    echo "   Spa Booking Email: " . ($result2 ? '✅ SUCCESS' : '❌ FAILED') . "\n";

    echo "\n=== FINAL RESULTS ===\n";
    echo "Registration Email: " . ($result1 ? '✅' : '❌') . "\n";
    echo "Spa Booking Email: " . ($result2 ? '✅' : '❌') . "\n";

    if ($result1 && $result2) {
        echo "\n🎉 SUCCESS! Email system is now working!\n";
        echo "📧 Check email: ubayagung1122@gmail.com\n";
        echo "📁 Also check spam folder\n";
    } else {
        echo "\n⚠️ Some emails failed. Check Laravel logs.\n";
    }

} catch (Exception $e) {
    echo "\n❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
