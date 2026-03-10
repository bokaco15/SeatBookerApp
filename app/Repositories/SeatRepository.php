<?php

namespace App\Repositories;

use App\Enums\BookingItem\BookingItemStatus;
use App\Models\BookingItem;
use App\Models\Event;
use App\Models\Seat;
use Illuminate\Support\Collection;

class SeatRepository
{
    public function getSeats(Event $event) : Collection
    {
        return Seat::where('hall_id', $event->hall->id)
            ->orderBy('row')
            ->orderBy('number')
            ->get(['id','row','number','seat_category_id']);
    }

    public function bookedSeats(Event $event) : array
    {
        $bookedSeatIds = BookingItem::where('event_id', $event->id)
            ->whereIn('status', [BookingItemStatus::BOOKED, BookingItemStatus::HELD])
            ->pluck('seat_id')
            ->unique()
            ->values()
            ->all();

        return array_fill_keys($bookedSeatIds, true);
    }
}
