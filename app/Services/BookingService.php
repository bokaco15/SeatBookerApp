<?php

namespace App\Services;

use App\Enums\Booking\BookingStatus;
use App\Enums\BookingItem\BookingItemStatus;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Event;
use App\Models\Seat;
use Illuminate\Support\Facades\DB;

class BookingService
{

    public function createBooking(int $eventId, array $seatIds)
    {
        $seatIds = collect($seatIds);
        if ($seatIds->isEmpty()) {
            return back()->withErrors(['seat_ids' => "You didn't choose seats"])->withInput();
        }

        $event = Event::with('hall')->findOrFail($eventId);

        $booking = DB::transaction(function () use ($seatIds, $event) {

            $taken = BookingItem::where('event_id', $event->id)
                ->whereIn('seat_id', $seatIds)
                ->where(function($query) {
                    $query->where('status', BookingItemStatus::BOOKED)
                        ->orWhere(function($q) {
                            $q->where('status', BookingItemStatus::HELD)
                                ->whereHas('booking', function($b) {
                                    $b->where('expires_at', '>', now());
                                });
                        });
                })
                ->exists();

            if ($taken) {
                abort(403, "Some seats are taken! Try refreshing the page again!");
            }

            $seats = Seat::with('seatCategory')
                ->whereIn('id', $seatIds)
                ->get();

            $total = $seats->sum(fn($s) => (int)($s->seatCategory->price ?? 0));

            $expires_at = \Illuminate\Support\now()->addMinutes(30);

            $booking = Booking::create([
                'event_id' => $event->id,
                'status' => BookingStatus::PENDING,
                'total_amount' => $total,
                'expires_at' => $expires_at,
                'browser_session_id' => session()->getId(),
            ]);

            foreach ($seats as $seat) {
                BookingItem::create([
                    'booking_id' => $booking->id,
                    'event_id' => $event->id,
                    'seat_id' => $seat->id,
                    'seat_category_id' => $seat->seat_category_id,
                    'price' => (int)($seat->seatCategory->price ?? 0),
                    'status' => BookingItemStatus::HELD,
                ]);
            }

            return $booking;

        });

        return $booking;
    }

}
