<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;
use App\Repositories\SeatRepository;
use Carbon\Carbon;

class EventService
{
    public function getEventShowData(Event $event): array
    {
        $eventRepo = new EventRepository();
        $seatRepo = new SeatRepository();

        $event->load('hall');
        $eventRepo->deleteUserDraftBookingsForEvent($event);

        $seats = $seatRepo->getSeats($event);

        $bookedMap = $seatRepo->bookedSeats($event);

        return [
            'event' => $event,
            'hall' => $event->hall,
            'seats' => $seats,
            'bookedMap' => $bookedMap,
            'rows' => $event->hall->rows,
            'cols' => $event->hall->columns,
        ];
    }


    public function getEventStoreData($data)
    {
        $eventRepo = new EventRepository();

        $starts_at = $data['date'] . ' ' . $data['start_time'];
        $ends_at   = $data['date'] . ' ' . $data['end_time'];

        if ($starts_at < Carbon::now()) {
            throw new \DomainException('The event cannot be in past');
        }

        $overlapping = $eventRepo->createEventOverlapping($data, $starts_at, $ends_at);
        if ($overlapping) {
            throw new \DomainException('In this hall there is already an event in that term.');
        }

        return [
            'hall_id'  => $data['hall_id'],
            'name'     => $data['name'],
            'type'     => $data['type'],
            'starts_at'=> $starts_at,
            'ends_at'  => $ends_at,
            'status'   => 'published',
        ];
    }


    public function getUpdateData($data, Event $event)
    {
        $eventRepo = new EventRepository();

        $starts_at = $data['date'] . ' ' . $data['start_time'];
        $ends_at   = $data['date'] . ' ' . $data['end_time'];

        if ($starts_at < Carbon::now()) {
            throw new \DomainException('The event cannot be in past');
        }

        $overlapping = $eventRepo->updateEventOverlapping($data, $starts_at, $ends_at, $event);
        if ($overlapping) {
            throw new \DomainException('In this hall there is already an event in that term.');
        }

        return [
            'hall_id'   => $data['hall_id'],
            'name'      => $data['name'],
            'type'      => $data['type'],
            'starts_at' => $starts_at,
            'ends_at'   => $ends_at,
            'status'    => $data['status'] ?? $event->status,
        ];

    }

}
