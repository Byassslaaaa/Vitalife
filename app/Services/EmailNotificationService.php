<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\WelcomeEmail;
use App\Mail\SpaBookingSuccessMail;
use App\Mail\GymBookingSuccessMail;
use App\Mail\YogaBookingSuccessMail;
use Exception;

class EmailNotificationService
{
    /**
     * Send welcome email after user registration
     */
    public function sendWelcomeEmail($user)
    {
        try {
            $emailData = [
                'userName' => $user->name,
                'userEmail' => $user->email,
                'userPhone' => $user->phone ?? 'Tidak disediakan',
                'supportEmail' => env('MAIL_FROM_ADDRESS')
            ];

            // Send email immediately (sync) instead of queuing
            Mail::to($user->email)->send(new WelcomeEmail($emailData));

            Log::info('Welcome email sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'smtp_host' => env('MAIL_HOST'),
                'from_address' => env('MAIL_FROM_ADDRESS')
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Failed to send welcome email', [
                'user_id' => $user->id ?? null,
                'email' => $user->email ?? null,
                'error' => $e->getMessage(),
                'smtp_host' => env('MAIL_HOST'),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return false;
        }
    }

    /**
     * Send booking confirmation email
     */
    public function sendBookingConfirmation($booking, $type = 'spa')
    {
        try {
            switch ($type) {
                case 'spa':
                    $mailable = new SpaBookingSuccessMail($booking);
                    break;
                case 'gym':
                    $mailable = new GymBookingSuccessMail($booking);
                    break;
                case 'yoga':
                    $mailable = new YogaBookingSuccessMail($booking);
                    break;
                default:
                    throw new Exception("Unknown booking type: {$type}");
            }

            $email = is_array($booking) ? $booking['customer_email'] : $booking->customer_email;
            $bookingCode = is_array($booking) ? $booking['booking_code'] : $booking->booking_code;

            Mail::to($email)->send($mailable);

            Log::info('Booking confirmation email sent', [
                'booking_code' => $bookingCode,
                'type' => $type,
                'email' => $email
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_code' => $bookingCode ?? 'unknown',
                'type' => $type,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Send payment success notification
     */
    public function sendPaymentSuccessNotification($booking, $type = 'spa')
    {
        try {
            // Update booking status to paid
            if (is_object($booking)) {
                $booking->payment_status = 'paid';
                $booking->status = 'confirmed';
            } elseif (is_array($booking)) {
                $booking['payment_status'] = 'paid';
                $booking['status'] = 'confirmed';
            }

            // Send updated booking confirmation
            return $this->sendBookingConfirmation($booking, $type);

        } catch (Exception $e) {
            Log::error('Failed to send payment success notification', [
                'booking_code' => is_array($booking) ? $booking['bookingCode'] : $booking->booking_code,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Test email functionality
     */
    public function testEmailConnection()
    {
        try {
            $testData = [
                'userName' => 'Test User',
                'userEmail' => env('MAIL_FROM_ADDRESS'),
                'userPhone' => '+62812345678',
                'supportEmail' => env('MAIL_FROM_ADDRESS')
            ];

            Mail::to(env('MAIL_FROM_ADDRESS'))->send(new WelcomeEmail($testData));

            Log::info('Email test successful');
            return true;

        } catch (Exception $e) {
            Log::error('Email test failed', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get email configuration status
     */
    public function getEmailStatus()
    {
        return [
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'from_address' => env('MAIL_FROM_ADDRESS'),
            'from_name' => env('MAIL_FROM_NAME'),
            'username' => env('MAIL_USERNAME'),
            'configured' => !empty(env('MAIL_HOST')) && !empty(env('MAIL_USERNAME'))
        ];
    }
}
