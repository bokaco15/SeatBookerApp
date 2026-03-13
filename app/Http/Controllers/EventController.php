<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
class EventController extends Controller
{
    public function index(Request $request) : View
    {
        $page = $request->query('page', 1);
        $cacheName = 'events_page_' . $page;

        $events = Cache::tags(['events'])->remember($cacheName, 86400, function () {
           return Event::with('hall')
                ->where('status', 'published')
                ->where('starts_at', ">=", now())
                ->paginate(5);
        });

        return view('front.index', compact('events'));
    }

    public function show(Event $event) : View
    {
        $eventService = new EventService();
        $data = $eventService->getEventShowData($event);

        return view('front.event', $data);
    }
}
