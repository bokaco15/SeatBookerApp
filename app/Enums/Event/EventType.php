<?php

namespace App\Enums\Event;

enum EventType: string
{
    case MOVIE = 'movie';
    case CONCERT = 'concert';
    case CONFERENCE = 'conference';
}
