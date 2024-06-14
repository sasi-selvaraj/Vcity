<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartialPaymentMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $payment;
    protected $daysDifference;

    /**
     * Create a new message instance.
     */
    public function __construct($payment, $daysDifference)
    {
        $this->payment = $payment;
        $this->daysDifference = $daysDifference;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reminder',
        );
    }

    /**
     * Get the message content definition.
     */
    // public function content(): Content
    // {
    //     return new Content(
    //         view: 'mail.partpaymail',
    //     );
    // }

    public function build()
    {
        return $this->view('mail.partpaymail', [
            'payment' => $this->payment,
            'daysDifference' => $this->daysDifference,
        ]);
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
