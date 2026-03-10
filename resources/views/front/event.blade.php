@extends('front._layout')

@section('title', "{$event->name} - SeatBooker")

@push('header_styles')
    <style>
        body { background-color: #f5f6f8; }
        .card-modern { background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,.05); }
        .screen { height:8px;border-radius:30px;background:linear-gradient(to right,#dee2e6,#adb5bd,#dee2e6);margin-bottom:25px; }
        .seat-wrapper { overflow:hidden;border-radius:14px;border:1px solid #e5e7eb;background:#fff; }
        .seat-scroll { max-height:500px; overflow:auto; padding:20px; }
        .seat-grid {
            display:grid;
            grid-template-columns: repeat({{ $cols }}, 16px); /* ✅ dinamično */
            grid-auto-rows:16px;
            gap:6px;
            justify-content:center;
            min-width:max-content;
        }
        .seat { width:16px;height:16px;border-radius:4px;border:1px solid #dee2e6;background:#f1f3f5;cursor:pointer;transition:all .15s ease; }
        .seat.available { background:#d1fae5; border-color:#10b981; }
        .seat.booked { background:#fee2e2; border-color:#ef4444; cursor:not-allowed; opacity:.8; }
        .seat.selected { background:#2563eb; border-color:#1d4ed8; box-shadow:0 0 6px rgba(37,99,235,.4); }
        .legend-box { display:inline-flex; align-items:center; gap:8px; padding:8px 14px; border-radius:30px; border:1px solid #dee2e6; background:#fff; font-size:14px; }
        .dot { width:12px;height:12px;border-radius:50%; }
        .dot.available { background:#10b981; }
        .dot.booked { background:#ef4444; }
        .dot.selected { background:#2563eb; }
    </style>
@endpush

@section('content')
    @include('front._navbar')
    <div class="container my-5">
        <div class="row g-4">

            <!-- LEFT SIDE -->
            <div class="col-lg-4">
                <div class="card card-modern p-4">

                    <span class="badge bg-primary mb-2">{{ $event->type instanceof \BackedEnum ? ucfirst($event->type->value) : ucfirst($event->type) }}</span>
                    <h2 class="fw-bold">{{ $event->name }}</h2>

                    <p class="text-muted">
                        Choose seats and buy tickets simply and quickly.
                    </p>

                    <hr>

                    <div class="mb-2"><strong>Hall:</strong> {{ $hall->name }}</div>
                    <div class="mb-2"><strong>Start:</strong> {{ $event->starts_at?->format('d.m.Y H:i') }}</div>
                    <div class="mb-2"><strong>End:</strong> {{ $event->ends_at?->format('d.m.Y H:i') }}</div>

                    <hr>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <div class="legend-box"><span class="dot available"></span> Free</div>
                        <div class="legend-box"><span class="dot booked"></span> Busy</div>
                        <div class="legend-box"><span class="dot selected"></span> Selected</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Selected</small>
                            <div class="fw-bold fs-5" id="selectedCount">0</div>
                            <div class="mt-2">
                                <small class="text-muted">Total</small>
                                <div class="fw-bold fs-4" id="totalPrice">0 KM</div>
                            </div>
                        </div>

                        <button id="buyBtn" class="btn btn-primary" disabled>
                            Buy tickets
                        </button>
                    </div>

                    <form id="buyForm" class="mt-3" method="POST" action="{{route('bookings.store')}}">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <input type="hidden" name="seat_ids" id="seatIdsInput" value="">
                    </form>

                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="col-lg-8">
                <div class="card card-modern p-4">

                    <h5 class="fw-bold mb-3">Available tickets ({{ $rows }} x {{ $cols }})</h5>

                    <div class="screen"></div>

                    <div class="seat-wrapper">
                        <div class="seat-scroll">
                            <div class="seat-grid" id="seatGrid">
                                @foreach($seats as $seat)
                                    @php $isBooked = isset($bookedMap[$seat->id]); @endphp
                                    <div
                                        class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                        data-seat-id="{{ $seat->id }}"
                                        data-price="{{$seat->seatCategory->price}}"
                                        title="Row {{ $seat->row }} Seat {{ $seat->number }}"
                                    ></div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>

        window.addEventListener("pageshow", function (event) {
            if (event.persisted) {
                window.location.reload();
            }
        });

        $(document).ready(function () {

            let selectedSeats = [];
            let total = 0;

            function formatKM(amount) {
                return amount.toLocaleString('sr-RS') + ' KM';
            }

            function updateUI() {
                $('#selectedCount').text(selectedSeats.length);
                $('#buyBtn').prop('disabled', selectedSeats.length === 0);
                $('#seatIdsInput').val(selectedSeats.join(','));
                $('#totalPrice').text(formatKM(total));
            }

            $('.seat.available').on('click', function () {

                const seatId = $(this).data('seat-id');
                const price  = parseInt($(this).data('price'), 10) || 0;

                $(this).toggleClass('selected');

                if ($(this).hasClass('selected')) {
                    // add
                    if (!selectedSeats.includes(seatId)) {
                        selectedSeats.push(seatId);
                        total += price;
                    }
                } else {
                    selectedSeats = selectedSeats.filter(id => id != seatId);
                    total -= price;
                    if (total < 0) total = 0;
                }

                updateUI();
            });

            $('#buyBtn').on('click', function () {
                $('#buyForm').submit();
            });

            updateUI();
        });
    </script>
@endpush
