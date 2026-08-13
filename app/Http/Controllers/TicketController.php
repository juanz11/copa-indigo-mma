<?php

namespace App\Http\Controllers;

use App\Models\MmaRegistration;

class TicketController extends Controller
{
    public function show(string $token)
    {
        $registration = MmaRegistration::where('ticket_token', $token)
            ->with(['approver', 'mesa'])
            ->first();

        $ticketUrl = $registration ? route('ticket.show', $token) : '';
        $qrImageUrl = $ticketUrl ? 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($ticketUrl) : '';

        return view('tickets.show', compact('registration', 'qrImageUrl'));
    }
}
