<?php

// Test Production Brevo Configuration
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== PRODUCTION BREVO EMAIL TEST ===\n";
echo "Environment: " . env('APP_ENV') . "\n";
echo "MAIL_HOST: " . env('MAIL_HOST') . "\n";
echo "MAIL_PORT: " . env('MAIL_PORT') . "\n";
echo "MAIL_USERNAME: " . env('MAIL_USERNAME') . "\n";
echo "MAIL_FROM_ADDRESS: " . env('MAIL_FROM_ADDRESS') . "\n";
echo "MAIL_ENCRYPTION: " . env('MAIL_ENCRYPTION') . "\n";
echo "APP_URL: " . env('APP_URL') . "\n";

// Test SMTP Connection
echo "\n=== TESTING SMTP CONNECTION ===\n";
try {
    // Create Swift transport
    $transport = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport(
        env('MAIL_HOST'),
        env('MAIL_PORT'),
        env('MAIL_ENCRYPTION') === 'tls'
    );

    $transport->setUsername(env('MAIL_USERNAME'));
    $transport->setPassword(env('MAIL_PASSWORD'));

    echo "✅ SMTP Transport created successfully\n";

    // Test EmailNotificationService
    echo "\n=== TESTING PRODUCTION EMAIL SEND ===\n";
    $emailService = new App\Services\EmailNotificationService();

    // Test welcome email to admin address
    $testUser = (object) [
        'id' => 999,
        'name' => 'Production Test User',
        'email' => 'admin@vitalife.web.id', // Send to your admin email
        'phone' => '+6281234567890'
    ];

    echo "Sending production test welcome email...\n";
    $result = $emailService->sendWelcomeEmail($testUser);
    echo "Production email result: " . ($result ? 'SUCCESS ✅' : 'FAILED ❌') . "\n";

    if (!$result) {
        echo "Check Laravel logs for detailed error information.\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== PRODUCTION TEST COMPLETED ===\n";
