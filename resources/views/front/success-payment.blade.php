@extends('front._layout')

@section('content')
    @include('front._navbar')
    <div class="container">
        Hello, {{$email}}! Your payment is successfull

        <h2>Your tickets</h2>

        @foreach($booking->booking_items as $item)

            <div style="border:1px solid #ccc; padding:20px; margin-bottom:20px; width:300px">

                <h4>Ticket #{{ $item->id }}</h4>
                <h4>{{ $item->event->name }}</h4>

                <p>Row: {{ $item->seat->row }}</p>
                <p>Number: {{ $item->seat->number  }}</p>

                <img src="{{ route('tickets.qr', $item->qr_token) }}" width="200">

            </div>

        @endforeach

        <form action="{{route('tickets.download.pdf', $booking->id)}}" method="get">
            @csrf
            <button class="btn btn-primary">Download PDF Tickets</button>
        </form>

    </div>
@endsection
