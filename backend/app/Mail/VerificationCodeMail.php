<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\VerificationCode;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public int $expiresInMinutes;
    public string $type;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, int $expiresInMinutes, string $type = VerificationCode::TYPE_EMAIL_VERIFICATION)
    {
        $this->code = $code;
        $this->expiresInMinutes = $expiresInMinutes;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            VerificationCode::TYPE_EMAIL_VERIFICATION => 'Código de Verificación - Petunia Play',
            VerificationCode::TYPE_PASSWORD_RESET => 'Código para Restablecer Contraseña - Petunia Play',
            default => 'Código de Verificación - Petunia Play',
        };

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verification-code',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
