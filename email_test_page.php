<!DOCTYPE html>
<html>
<head>
    <title>Email Notification Test</title>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; }
        .success { color: green; }
        .error { color: red; }
        button { padding: 10px 15px; margin: 5px; }
    </style>
</head>
<body>
    <h1>🧪 Vitalife Email Notification Test</h1>

    <div class="test-section">
        <h3>📧 Current Email Configuration</h3>
        <p><strong>Host:</strong> <?= env('MAIL_HOST') ?></p>
        <p><strong>Port:</strong> <?= env('MAIL_PORT') ?></p>
        <p><strong>From:</strong> <?= env('MAIL_FROM_ADDRESS') ?></p>
        <p><strong>Username:</strong> <?= env('MAIL_USERNAME') ?></p>
    </div>

    <div class="test-section">
        <h3>🧪 Test Email Functions</h3>
        <form action="" method="POST">
            <button type="submit" name="test" value="registration">Test Registration Email</button>
            <button type="submit" name="test" value="spa_booking">Test Spa Booking Email</button>
            <button type="submit" name="test" value="yoga_booking">Test Yoga Booking Email</button>
            <button type="submit" name="test" value="gym_booking">Test Gym Booking Email</button>
        </form>
    </div>

    <?php if (isset($_POST['test'])): ?>
    <div class="test-section">
        <h3>📋 Test Results</h3>
        <?php
        require_once __DIR__ . '/vendor/autoload.php';
        $app = require_once __DIR__ . '/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        try {
            $emailService = new App\Services\EmailNotificationService();
            $testType = $_POST['test'];

            switch ($testType) {
                case 'registration':
                    $testUser = (object) [
                        'id' => 999,
                        'name' => 'Test User - ' . date('H:i:s'),
                        'email' => 'ubayagung1122@gmail.com',
                        'phone' => '+6281234567890'
                    ];
                    $result = $emailService->sendWelcomeEmail($testUser);
                    echo $result ? '<p class="success">✅ Registration email sent successfully!</p>' : '<p class="error">❌ Failed to send registration email</p>';
                    break;

                case 'spa_booking':
                    $booking = [
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
                        'notes' => 'Test spa booking',
                        'supportEmail' => env('MAIL_FROM_ADDRESS')
                    ];
                    $result = $emailService->sendBookingConfirmation($booking, 'spa');
                    echo $result ? '<p class="success">✅ Spa booking email sent successfully!</p>' : '<p class="error">❌ Failed to send spa booking email</p>';
                    break;

                case 'yoga_booking':
                    $booking = [
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
                        'special_requests' => 'Test yoga booking',
                        'supportEmail' => env('MAIL_FROM_ADDRESS')
                    ];
                    $result = $emailService->sendBookingConfirmation($booking, 'yoga');
                    echo $result ? '<p class="success">✅ Yoga booking email sent successfully!</p>' : '<p class="error">❌ Failed to send yoga booking email</p>';
                    break;

                case 'gym_booking':
                    $booking = [
                        'booking_code' => 'GYM-TEST-' . time(),
                        'customer_name' => 'Test Customer',
                        'customer_email' => 'ubayagung1122@gmail.com',
                        'booking_date' => now()->addDays(1)->format('Y-m-d'),
                        'booking_time' => '18:00',
                        'duration' => 2,
                        'total_amount' => 75000,
                        'status' => 'confirmed',
                        'payment_status' => 'paid',
                        'payment_method' => 'E-Wallet',
                        'notes' => 'Test gym booking',
                        'supportEmail' => env('MAIL_FROM_ADDRESS')
                    ];
                    $result = $emailService->sendBookingConfirmation($booking, 'gym');
                    echo $result ? '<p class="success">✅ Gym booking email sent successfully!</p>' : '<p class="error">❌ Failed to send gym booking email</p>';
                    break;
            }

        } catch (Exception $e) {
            echo '<p class="error">❌ Error: ' . $e->getMessage() . '</p>';
            echo '<p class="error">File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
        }
        ?>
    </div>
    <?php endif; ?>

    <div class="test-section">
        <h3>🔧 Troubleshooting</h3>
        <p>Jika email tidak masuk, cek:</p>
        <ol>
            <li>Spam folder di email ubayagung1122@gmail.com</li>
            <li>Laravel logs: storage/logs/laravel.log</li>
            <li>Queue jobs (jika menggunakan queue)</li>
            <li>SMTP credentials di .env file</li>
        </ol>
    </div>
</body>
</html>
