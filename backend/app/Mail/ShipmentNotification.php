<?php

namespace App\Mail;

use App\Models\Shipment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShipmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $shipment;

    /**
     * Create a new message instance.
     */
    public function __construct(Shipment $shipment)
    {
        $this->shipment = $shipment;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match($this->shipment->status) {
            'pending' => '📦 Tu pedido ha sido procesado',
            'in_transit' => '🚚 Tu pedido está en camino',
            'delivered' => '✅ Tu pedido ha sido entregado',
            'failed' => '⚠️ Problema con tu envío',
            'returned' => '↩️ Tu pedido ha sido devuelto',
            default => 'Actualización de tu envío'
        };

        return new Envelope(
            subject: $subject . ' - ' . $this->shipment->order->order_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.shipment-notification',
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
