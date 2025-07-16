<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== BREVO SMTP CONNECTION TEST ===\n";

// Test manual SMTP connection
try {
    echo "Testing SMTP connection to Brevo...\n";
    echo "Host: " . env('MAIL_HOST') . "\n";
    echo "Port: " . env('MAIL_PORT') . "\n";
    echo "Username: " . env('MAIL_USERNAME') . "\n";
    echo "Password: " . (env('MAIL_PASSWORD') ? str_repeat('*', strlen(env('MAIL_PASSWORD'))) : 'NOT SET') . "\n";
    echo "Encryption: " . env('MAIL_ENCRYPTION') . "\n\n";

    // Test basic Laravel Mail function
    echo "Testing Laravel Mail with simple message...\n";

    Illuminate\Support\Facades\Mail::raw('Test email from Vitalife Laravel app. Time: ' . now(), function ($message) {
        $message->to('ubayagung1122@gmail.com')
               ->subject('Vitalife SMTP Test - ' . now()->format('Y-m-d H:i:s'))
               ->from(env('MAIL_FROM_ADDRESS'), 'Vitalife Test');
    });

    echo "✅ SUCCESS: Email sent successfully via Brevo SMTP!\n";
    echo "Check email ubayagung1122@gmail.com for confirmation.\n";

} catch (Swift_TransportException $e) {
    echo "❌ SMTP Transport Error: " . $e->getMessage() . "\n";
    echo "This is likely an authentication or connection issue.\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\n=== TEST COMPLETED ===\n";
