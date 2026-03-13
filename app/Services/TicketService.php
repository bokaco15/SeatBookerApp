<?php

namespace App\Services;

use App\Models\BookingItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\Result\ResultInterface;

class TicketService
{

    public function getQrData($qr_token) : ResultInterface
    {
        $bookingItem = BookingItem::where('qr_token', $qr_token)->firstOrFail();

        $builder = new Builder(
            writer: new PngWriter(),
            data: $bookingItem->qr_token,
            size: 300,
            margin: 10
        );

        return $builder->build();
    }

    public function makeBookingPdf($booking) : \Barryvdh\DomPDF\PDF
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

        return Pdf::loadView('pdf.tickets', compact('booking'));
    }

}
