<?php

// Simple test script for email functionality
require __DIR__ . '/vendor/autoload.php';

use App\Mail\WelcomeEmail;
use App\Mail\YogaBookingSuccessMail;
use App\Mail\SpaBookingSuccessMail;
use App\Mail\GymBookingSuccessMail;
use Illuminate\Support\Facades\Mail;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testing Vitalife Email System...\n\n";

// Test Welcome Email
echo "📧 Testing Welcome Email...\n";
try {
    $welcomeData = [
        'userName' => 'Test User',
        'userEmail' => 'kikiaa491@gmail.com',
        'userPhone' => '+62 812-3456-7890',
        'supportEmail' => 'support@vitalife.com'
    ];

    Mail::to('kikiaa491@gmail.com')->send(new WelcomeEmail($welcomeData));
    echo "✅ Welcome Email sent successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Welcome Email failed: " . $e->getMessage() . "\n\n";
}

// Test Yoga Booking Email
echo "🧘‍♀️ Testing Yoga Booking Email...\n";
try {
    $yogaData = [
        'bookingCode' => 'YOGA-' . strtoupper(uniqid()),
        'customerName' => 'Test Yoga Student',
        'customerEmail' => 'kikiaa491@gmail.com',
        'bookingDate' => date('Y-m-d', strtotime('+1 day')),
        'bookingTime' => '07:00',
        'classType' => 'Hatha Yoga',
        'participants' => 1,
        'totalAmount' => '100.000',
        'status' => 'confirmed',
        'paymentStatus' => 'paid',
        'paymentMethod' => 'Bank Transfer',
        'specialRequests' => 'Please provide yoga mat',
        'supportEmail' => 'support@vitalife.com'
    ];

    Mail::to('kikiaa491@gmail.com')->send(new YogaBookingSuccessMail($yogaData));
    echo "✅ Yoga Booking Email sent successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Yoga Booking Email failed: " . $e->getMessage() . "\n\n";
}

// Test Spa Booking Email
echo "💆‍♀️ Testing Spa Booking Email...\n";
try {
    $spaData = [
        'bookingCode' => 'SPA-' . strtoupper(uniqid()),
        'customerName' => 'Test Spa Guest',
        'customerEmail' => 'kikiaa491@gmail.com',
        'bookingDate' => date('Y-m-d', strtotime('+1 day')),
        'bookingTime' => '14:00',
        'duration' => 90,
        'therapistPreference' => 'Female Therapist',
        'totalAmount' => '350.000',
        'status' => 'confirmed',
        'paymentStatus' => 'paid',
        'paymentMethod' => 'Cash',
        'notes' => 'Relaxing massage therapy session',
        'supportEmail' => 'support@vitalife.com'
    ];

    Mail::to('kikiaa491@gmail.com')->send(new SpaBookingSuccessMail($spaData));
    echo "✅ Spa Booking Email sent successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Spa Booking Email failed: " . $e->getMessage() . "\n\n";
}

// Test Gym Booking Email
echo "💪 Testing Gym Booking Email...\n";
try {
    $gymData = [
        'bookingCode' => 'GYM-' . strtoupper(uniqid()),
        'customerName' => 'Test Gym Member',
        'customerEmail' => 'kikiaa491@gmail.com',
        'bookingDate' => date('Y-m-d', strtotime('+1 day')),
        'bookingTime' => '18:00',
        'duration' => 2,
        'totalAmount' => '75.000',
        'status' => 'confirmed',
        'paymentStatus' => 'paid',
        'paymentMethod' => 'E-Wallet',
        'notes' => 'Evening workout session',
        'supportEmail' => 'support@vitalife.com'
    ];

    Mail::to('kikiaa491@gmail.com')->send(new GymBookingSuccessMail($gymData));
    echo "✅ Gym Booking Email sent successfully!\n\n";
} catch (Exception $e) {
    echo "❌ Gym Booking Email failed: " . $e->getMessage() . "\n\n";
}

echo "🎉 Email testing completed! Check your inbox at kikiaa491@gmail.com\n";
echo "📊 All email templates have been tested with SMTP integration.\n";
