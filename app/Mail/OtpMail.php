<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sends OTP verification codes for the password reset flow.
 */
class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'RHU Lucban - Password Reset OTP',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
