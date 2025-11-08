<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(env('MAIL_FROM_ADDRESS', 'noreply@healife.com'), env('MAIL_FROM_NAME', 'HeaLife Team')),
            subject: '🎉 Selamat Datang di HeaLife - Platform Wellness Terbaik!'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
            with: is_array($this->user) ? $this->user : [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'userPhone' => $this->user->phone ?? 'Belum diisi',
                'supportEmail' => env('MAIL_FROM_ADDRESS', 'support@healife.com')
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
