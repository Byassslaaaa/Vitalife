<?php

namespace App\Mail;

use App\Models\YogaBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class YogaBookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'yoga@vitalife.com'), env('MAIL_FROM_NAME', 'Vitalife Yoga')),
            subject: '🧘‍♀️ Konfirmasi Booking Yoga - ' . $this->booking->booking_code
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.yoga_booking_success',
            with: is_array($this->booking) ? $this->booking : [
                'bookingCode' => $this->booking->booking_code,
                'customerName' => $this->booking->customer_name,
                'customerEmail' => $this->booking->customer_email,
                'bookingDate' => $this->booking->booking_date,
                'bookingTime' => $this->booking->booking_time,
                'classType' => $this->booking->class_type,
                'participants' => $this->booking->participants,
                'totalAmount' => number_format($this->booking->total_amount, 0, ',', '.'),
                'status' => $this->booking->status,
                'paymentStatus' => $this->booking->payment_status,
                'paymentMethod' => $this->booking->payment_method,
                'notes' => $this->booking->notes,
                'specialRequests' => $this->booking->special_requests,
                'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@vitalife.com')
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
