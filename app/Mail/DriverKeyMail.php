<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DriverKeyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $keyCode,
        public string $requesterName
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Driver Registration Key - SSTS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.driver-key',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
