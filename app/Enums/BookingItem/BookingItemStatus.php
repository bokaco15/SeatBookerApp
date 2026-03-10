<?php

namespace App\Enums\BookingItem;

enum BookingItemStatus : string
{
    case HELD = 'held';
    case BOOKED = 'booked';
}
