<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VITALIFE PRODUCTION EMAIL TEST ===\n";
echo "Testing Brevo SMTP Configuration for VPS Production\n\n";

echo "Current Configuration:\n";
echo "- Environment: " . env('APP_ENV') . "\n";
echo "- App URL: " . env('APP_URL') . "\n";
echo "- Mail Host: " . env('MAIL_HOST') . "\n";
echo "- Mail Port: " . env('MAIL_PORT') . "\n";
echo "- Mail Username: " . env('MAIL_USERNAME') . "\n";
echo "- From Address: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "- Queue Connection: " . env('QUEUE_CONNECTION') . "\n\n";

try {
    echo "Testing EmailNotificationService with Production Settings...\n";

    $emailService = new App\Services\EmailNotificationService();

    // Test 1: Welcome Email
    echo "\n1. Testing Welcome Email (Registration)...\n";
    $testUser = (object) [
        'id' => 1001,
        'name' => 'Production Test User',
        'email' => 'admin@vitalife.web.id',
        'phone' => '+6281234567890'
    ];

    $welcomeResult = $emailService->sendWelcomeEmail($testUser);
    echo "   Welcome Email: " . ($welcomeResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";

    // Test 2: Spa Booking Email
    echo "\n2. Testing Spa Booking Confirmation...\n";
    $spaBooking = [
        'booking_code' => 'SPA-PROD-' . time(),
        'customer_name' => 'VPS Production Test',
        'customer_email' => 'admin@vitalife.web.id',
        'booking_date' => now()->addDays(1)->format('Y-m-d'),
        'booking_time' => '14:00',
        'duration' => 90,
        'therapist_preference' => 'Female',
        'total_amount' => 350000,
        'status' => 'confirmed',
        'payment_status' => 'paid',
        'payment_method' => 'Bank Transfer',
        'notes' => 'Production test booking from VPS',
        'supportEmail' => env('MAIL_FROM_ADDRESS')
    ];

    $spaResult = $emailService->sendBookingConfirmation($spaBooking, 'spa');
    echo "   Spa Booking Email: " . ($spaResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";

    // Test 3: Payment Success Email
    echo "\n3. Testing Payment Success Notification...\n";
    $paymentResult = $emailService->sendPaymentSuccessNotification($spaBooking, 'spa');
    echo "   Payment Success Email: " . ($paymentResult ? '✅ SUCCESS' : '❌ FAILED') . "\n";

    echo "\n=== PRODUCTION TEST RESULTS ===\n";
    echo "Registration Email: " . ($welcomeResult ? '✅' : '❌') . "\n";
    echo "Booking Confirmation: " . ($spaResult ? '✅' : '❌') . "\n";
    echo "Payment Success: " . ($paymentResult ? '✅' : '❌') . "\n";

    $allSuccess = $welcomeResult && $spaResult && $paymentResult;

    if ($allSuccess) {
        echo "\n🎉 ALL PRODUCTION TESTS PASSED!\n";
        echo "📧 Email system ready for VPS deployment\n";
        echo "📍 Check inbox: admin@vitalife.web.id\n";
    } else {
        echo "\n⚠️  Some tests failed. Check configuration and logs.\n";
    }

} catch (Exception $e) {
    echo "\n❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";

    // Check if it's SMTP authentication error
    if (strpos($e->getMessage(), '535') !== false || strpos($e->getMessage(), 'Authentication failed') !== false) {
        echo "\n🔧 SMTP Authentication Issue:\n";
        echo "- Verify Brevo credentials are correct\n";
        echo "- Check if Brevo account is verified\n";
        echo "- Ensure admin@vitalife.web.id is added as verified sender in Brevo\n";
    }
}

echo "\n=== PRODUCTION TEST COMPLETED ===\n";
