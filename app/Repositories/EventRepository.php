<?php

namespace App\Repositories;

use App\Enums\Booking\BookingStatus;
use App\Models\Booking;
use App\Models\Event;

class EventRepository
{
    public function deleteUserDraftBookingsForEvent(Event $event) : void
    {
        Booking::where('browser_session_id', session()->getId())
            ->where('event_id', $event->id)
            ->whereIn('status', [BookingStatus::PENDING, BookingStatus::UNPAID])
            ->delete();
    }

    public function createEventOverlapping($data, $starts_at, $ends_at) : bool
    {
        return Event::where('hall_id', $data['hall_id'])
            ->where(function ($query) use ($starts_at, $ends_at) {
                $query->whereBetween('starts_at', [$starts_at, $ends_at])
                    ->orWhereBetween('ends_at', [$starts_at, $ends_at])
                    ->orWhere(function ($q) use ($starts_at, $ends_at) {
                        $q->where('starts_at', '<=', $starts_at)
                            ->where('ends_at', '>=', $ends_at);
                    });
            })
            ->exists();
    }

    public function updateEventOverlapping($data, $starts_at, $ends_at, $event) : bool
    {
       return Event::where('hall_id', $data['hall_id'])
            ->where('id', '!=', $event->id)
            ->where(function ($query) use ($starts_at, $ends_at) {
                $query->whereBetween('starts_at', [$starts_at, $ends_at])
                    ->orWhereBetween('ends_at', [$starts_at, $ends_at])
                    ->orWhere(function ($q) use ($starts_at, $ends_at) {
                        $q->where('starts_at', '<=', $starts_at)
                            ->where('ends_at', '>=', $ends_at);
                    });
            })
            ->exists();
    }
}
