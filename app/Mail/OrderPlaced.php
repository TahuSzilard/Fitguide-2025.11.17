<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    public array $cart;
    public float $subtotal;
    public float $shipping;
    public float $total;
    public $order;

    public function __construct(array $cart, float $subtotal, float $shipping, float $total, $order)
    {
        $this->cart = $cart;
        $this->subtotal = $subtotal;
        $this->shipping = $shipping;
        $this->total = $total;
        $this->order = $order;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Placed',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order_placed',
            with: [
                'cart' => $this->cart,
                'subtotal' => $this->subtotal,
                'shipping' => $this->shipping,
                'total' => $this->total,
                'order' => $this->order,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
