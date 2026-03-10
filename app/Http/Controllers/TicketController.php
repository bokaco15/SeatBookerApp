<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Response;

class TicketController extends Controller
{
    public function qr($qr_token) : Response
    {
        $bookingItem = BookingItem::where('qr_token', $qr_token)->firstOrFail();

        $builder = new Builder(
            writer: new PngWriter(),
            data: $bookingItem->qr_token,
            size: 300,
            margin: 10
        );

        $result = $builder->build();

        return response(
            $result->getString(),
            200,
            ['Content-Type' => 'image/png']
        );
    }

    public function downloadPdf(Booking $booking) : Response
    {
        $booking->load(['booking_items.event', 'booking_items.seat']);

        foreach ($booking->booking_items as $item) {
            $builder = new Builder(
                writer: new PngWriter(),
                data: $item->qr_token,
                size: 300,
                margin: 10
            );

            $result = $builder->build();

            $item->qr_base64 = 'data:image/png;base64,' . base64_encode($result->getString());
        }

        $pdf = Pdf::loadView('pdf.tickets', compact('booking'));

        return $pdf->download('tickets-booking-' . $booking->id . '.pdf');
    }


}
