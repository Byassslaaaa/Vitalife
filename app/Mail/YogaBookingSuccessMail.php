<?php

namespace App\Mail;

use App\Models\YogaBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class YogaBookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(YogaBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Pembayaran Yoga Berhasil')
            ->view('emails.yoga_booking_success');
    }
}