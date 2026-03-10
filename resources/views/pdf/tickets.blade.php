<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tickets PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111;
            font-size: 14px;
        }

        .ticket {
            border: 1px solid #ccc;
            padding: 20px;
            margin-bottom: 20px;
        }

        .ticket h2,
        .ticket h3,
        .ticket h4,
        .ticket p {
            margin: 0 0 10px 0;
        }

        .qr {
            margin-top: 20px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

@foreach($booking->booking_items as $item)
    <div class="ticket">
        <h2>Ticket #{{ $item->id }}</h2>
        <h3>{{ $item->event->name }}</h3>

        <p><strong>Row:</strong> {{ $item->seat->row }}</p>
        <p><strong>Number:</strong> {{ $item->seat->number }}</p>
        <p><strong>Email:</strong> {{ $booking->email }}</p>

        <div class="qr">
            <img src="{{ $item->qr_base64 }}" width="180" height="180" alt="QR code">
        </div>
    </div>

    @if(!$loop->last)
        <div class="page-break"></div>
    @endif
@endforeach

</body>
</html>
