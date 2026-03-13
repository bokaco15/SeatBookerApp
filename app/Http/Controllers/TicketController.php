<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\TicketService;

use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function qr($qr_token) : Response
    {
        $ticketService = new TicketService();

        $result = $ticketService->getQrData($qr_token);

        return response(
            $result->getString(),
            200,
            ['Content-Type' => 'image/png']
        );
    }

    public function downloadPdf(Booking $booking) : Response
    {
        $ticketService = new TicketService();
        $pdf = $ticketService->makeBookingPdf($booking);

        return $pdf->download('tickets-booking-' . $booking->id . '.pdf');
    }


}
