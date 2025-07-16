<?php

// Test email configuration
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== VITALIFE EMAIL CONFIGURATION TEST ===\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";

// Test EmailNotificationService
echo "\n=== TESTING EmailNotificationService ===\n";
try {
    $emailService = new App\Services\EmailNotificationService();

    // Test welcome email
    $testUser = (object) [
        'id' => 999,
        'name' => 'Test User',
        'email' => 'ubayagung1122@gmail.com', // Your email for testing
        'phone' => '+6281234567890'
    ];

    echo "Sending test welcome email...\n";
    $result = $emailService->sendWelcomeEmail($testUser);
    echo "Welcome email result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
