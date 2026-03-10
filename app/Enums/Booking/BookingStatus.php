<?php

namespace App\Enums\Booking;

enum BookingStatus : string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case UNPAID = 'unpaid';
}
