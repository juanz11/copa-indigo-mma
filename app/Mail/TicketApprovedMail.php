<?php

namespace App\Mail;

use App\Models\MmaRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public MmaRegistration $registration;
    public string $ticketUrl;
    public string $qrImageUrl;

    public function __construct(MmaRegistration $registration)
    {
        $this->registration = $registration;
        $this->ticketUrl = route('ticket.show', $registration->ticket_token);
        $this->qrImageUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($this->ticketUrl);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ Tu entrada para Copa Índigo MMA fue aprobada',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_approved',
            with: [
                'registration' => $this->registration,
                'ticketUrl'    => $this->ticketUrl,
                'qrImageUrl'   => $this->qrImageUrl,
            ],
        );
    }
}
