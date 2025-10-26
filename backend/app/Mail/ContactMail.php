<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;

    public $contactData;
    public $type; // 'admin' or 'customer'

    /**
     * Create a new message instance.
     */
    public function __construct(array $contactData, string $type)
    {
        $this->contactData = $contactData;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        if ($this->type === 'admin') {
            return new Envelope(
                subject: 'Nuevo mensaje de contacto - ' . $this->contactData['subject'],
                replyTo: [$this->contactData['email']]
            );
        } else {
            return new Envelope(
                subject: 'Hemos recibido tu mensaje - Petunia Play'
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'contactData' => $this->contactData,
                'type' => $this->type
            ]
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
