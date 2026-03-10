<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\View\View;
class EventController extends Controller
{
    public function index() : View
    {
        $events = Event::with('hall')
            ->where('status', 'published')
            ->where('starts_at', ">=", now())
            ->paginate(5);
        return view('front.index', compact('events'));
    }

    public function show(Event $event) : View
    {
        $eventService = new EventService();
        $data = $eventService->getEventShowData($event);

        return view('front.event', $data);
    }
}
