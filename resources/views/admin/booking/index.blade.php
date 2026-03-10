@extends('layouts.app')

@push('header_scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')

    <div class="container mt-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Bookings</h2>
        </div>

        <div class="card shadow-sm p-3">

            <table id="bookingsTable" class="table table-striped">

                <thead>
                <tr>
                    <th>Event</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Expires At</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                @foreach($bookings as $booking)

                    @php
                        $items = $booking->booking_items
                            ? $booking->booking_items->map(function ($item) {
                                return [
                                    'id' => $item->id ?? null,
                                    'seat' => $item->seat->label ?? $item->seat_label ?? $item->seat_id ?? null,
                                    'price' => $item->price ?? $item->unit_price ?? null,
                                    'status' => $item->status,
                                ];
                            })->values()
                            : collect([]);
                    @endphp

                    <tr>

                        <td>
                            {{ $booking->event->name ?? ('Event #' . $booking->event_id) }}
                        </td>

                        <td>{{ $booking->email }}</td>

                        <td>
                            <span class="badge bg-primary">
                                {{ $booking->status}}
                            </span>
                        </td>

                        <td>{{ $booking->expires_at }}</td>

                        <td>{{ $booking->total_amount }}</td>

                        <td>
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary js-show-booking"
                                data-bs-toggle="modal"
                                data-bs-target="#bookingModal"
                                data-booking-id="{{ $booking->id }}"
                                data-event-name="{{ e($booking->event->name ?? ('Event #' . $booking->event_id)) }}"
                                data-email="{{ e($booking->email) }}"
                                data-guest="{{ e($booking->guest_name) }}"
                                data-status="{{ e($booking->status) }}"
                                data-expires="{{ e($booking->expires_at) }}"
                                data-total="{{ e($booking->total_amount) }}"
                                data-items='@json($items)'>
                                Show
                            </button>
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>

    </div>

    {{-- BOOTSTRAP MODAL --}}
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <div>
                        <h5 class="modal-title mb-1" id="bookingModalTitle">Booking</h5>
                        <div class="text-muted small" id="bookingModalSubtitle"></div>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="small text-muted">Email</div>
                            <div class="fw-semibold" id="bmEmail">-</div>
                        </div>

                        <div class="col-md-6">
                            <div class="small text-muted">Guest</div>
                            <div class="fw-semibold" id="bmGuest">-</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-muted">Status</div>
                            <div class="fw-semibold" id="bmStatus">-</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-muted">Expires At</div>
                            <div class="fw-semibold" id="bmExpires">-</div>
                        </div>

                        <div class="col-md-4">
                            <div class="small text-muted">Total</div>
                            <div class="fw-semibold" id="bmTotal">-</div>
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Tickets</h6>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Seat</th>
                                <th>Status</th>
                                <th>Price</th>
                            </tr>
                            </thead>
                            <tbody id="bmItemsBody">
                            <tr>
                                <td colspan="4" class="text-muted">No tickets.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('footer_scripts')

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function(){

            $('#bookingsTable').DataTable({
                columnDefs: [
                    {
                        orderable: false, targets: 5
                    },
                    {
                        searchable: false, targets: [0,3,5]
                    },
                ],
                pageLength: 10,
                responsive: true
            });

            $(document).on('click', '.js-show-booking', function () {

                const $btn = $(this);

                const bookingId = $btn.data('booking-id');
                const eventName = $btn.data('event-name');

                $('#bookingModalTitle').text('Booking #' + bookingId);
                $('#bookingModalSubtitle').text(eventName);

                $('#bmEmail').text($btn.data('email') || '-');
                $('#bmGuest').text($btn.data('guest') || '-');
                $('#bmStatus').text($btn.data('status') ?? '-');
                $('#bmExpires').text($btn.data('expires') || '-');
                $('#bmTotal').text($btn.data('total') || '-');

                let items = $btn.data('items');
                if (typeof items === 'string') {
                    try { items = JSON.parse(items); } catch(e) { items = []; }
                }

                const $body = $('#bmItemsBody');
                $body.empty();

                if (!items || !items.length) {
                    $body.append('<tr><td colspan="4" class="text-muted">No tickets.</td></tr>');
                    return;
                }

                items.forEach((it, idx) => {
                    const seat = it.seat ?? '-';
                    const status = it.status;
                    const price = (it.price !== null && it.price !== undefined) ? it.price : '-';

                    $body.append(`
                        <tr>
                            <td>${idx + 1}</td>
                            <td>${seat}</td>
                            <td>${status}</td>
                            <td>${price}</td>
                        </tr>
                    `);
                });

            });

        });
    </script>

@endpush
