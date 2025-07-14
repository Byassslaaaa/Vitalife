<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mail\WelcomeEmail;
use App\Mail\BookingSuccessMail;
use App\Mail\YogaBookingSuccessMail;
use App\Mail\SpaBookingSuccessMail;
use App\Mail\GymBookingSuccessMail;
use App\Models\User;
use App\Models\YogaBooking;
use App\Models\SpaBooking;
use App\Models\GymBooking;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test {type} {email}';
    protected $description = 'Test email functionality for different booking types';

    public function handle()
    {
        $type = $this->argument('type');
        $email = $this->argument('email');

        try {
            switch ($type) {
                case 'welcome':
                    $this->testWelcomeEmail($email);
                    break;
                case 'booking':
                    $this->testBookingEmail($email);
                    break;
                case 'yoga':
                    $this->testYogaBookingEmail($email);
                    break;
                case 'spa':
                    $this->testSpaBookingEmail($email);
                    break;
                case 'gym':
                    $this->testGymBookingEmail($email);
                    break;
                default:
                    $this->error('Invalid email type. Available types: welcome, booking, yoga, spa, gym');
                    return;
            }

            $this->info("✅ {$type} email sent successfully to {$email}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
        }
    }

    private function testWelcomeEmail($email)
    {
        $userData = [
            'userName' => 'Testing User',
            'userEmail' => $email,
            'userPhone' => '+62 812-3456-7890',
            'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
        ];

        Mail::to($email)->send(new WelcomeEmail($userData));
    }

    private function testBookingEmail($email)
    {
        $bookingData = [
            'bookingCode' => 'VTL-' . strtoupper(uniqid()),
            'customerName' => 'Testing Customer',
            'customerEmail' => $email,
            'bookingDate' => now()->addDays(1)->format('Y-m-d'),
            'bookingTime' => '10:00',
            'totalAmount' => '150.000',
            'status' => 'confirmed',
            'paymentStatus' => 'paid',
            'paymentMethod' => 'Credit Card',
            'notes' => 'Test booking email notification',
            'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
        ];

        Mail::to($email)->send(new BookingSuccessMail($bookingData));
    }

    private function testYogaBookingEmail($email)
    {
        $yogaBookingData = [
            'bookingCode' => 'YOGA-' . strtoupper(uniqid()),
            'customerName' => 'Testing Yoga Student',
            'customerEmail' => $email,
            'bookingDate' => now()->addDays(1)->format('Y-m-d'),
            'bookingTime' => '07:00',
            'classType' => 'Hatha Yoga',
            'participants' => 1,
            'totalAmount' => '100.000',
            'status' => 'confirmed',
            'paymentStatus' => 'paid',
            'paymentMethod' => 'Bank Transfer',
            'specialRequests' => 'Please provide yoga mat',
            'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
        ];

        Mail::to($email)->send(new YogaBookingSuccessMail($yogaBookingData));
    }

    private function testSpaBookingEmail($email)
    {
        $spaBookingData = [
            'bookingCode' => 'SPA-' . strtoupper(uniqid()),
            'customerName' => 'Testing Spa Guest',
            'customerEmail' => $email,
            'bookingDate' => now()->addDays(1)->format('Y-m-d'),
            'bookingTime' => '14:00',
            'duration' => 90,
            'therapistPreference' => 'Female Therapist',
            'totalAmount' => '350.000',
            'status' => 'confirmed',
            'paymentStatus' => 'paid',
            'paymentMethod' => 'Cash',
            'notes' => 'Relaxing massage therapy session',
            'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
        ];

        Mail::to($email)->send(new SpaBookingSuccessMail($spaBookingData));
    }

    private function testGymBookingEmail($email)
    {
        $gymBookingData = [
            'bookingCode' => 'GYM-' . strtoupper(uniqid()),
            'customerName' => 'Testing Gym Member',
            'customerEmail' => $email,
            'bookingDate' => now()->addDays(1)->format('Y-m-d'),
            'bookingTime' => '18:00',
            'duration' => 2,
            'totalAmount' => '75.000',
            'status' => 'confirmed',
            'paymentStatus' => 'paid',
            'paymentMethod' => 'E-Wallet',
            'notes' => 'Evening workout session',
            'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
        ];

        Mail::to($email)->send(new GymBookingSuccessMail($gymBookingData));
    }
}
