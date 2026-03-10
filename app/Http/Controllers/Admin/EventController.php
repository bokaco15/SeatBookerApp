<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Event\EventType;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventStoreRequest;
use App\Http\Requests\EventUpdateRequest;
use App\Models\Event;
use App\Models\Hall;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $halls = Hall::where('is_active', 1)->get();
        $types = EventType::cases();
        return view('admin.events.create', compact('halls', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventStoreRequest $request) : RedirectResponse
    {
        $eventService = new EventService();
        try {
            $data = $eventService->getEventStoreData($request->validated());
            Event::create($data);
        } catch (\DomainException $e) {
            return back()->withErrors(['start_time' => $e->getMessage()])
                ->withInput();
        }

        return redirect()->route('admin.events.create')
            ->with('success', 'Event created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event) : View
    {
        $halls = Hall::all();
        $types = EventType::cases();
        return view('admin.events.edit', compact('event', 'halls', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(EventUpdateRequest $request, Event $event) : RedirectResponse
    {
        $eventService = new EventService();

        try {
            $data = $eventService->getUpdateData($request->validated(), $event);
            $event->update($data);
        } catch (\DomainException $e) {
            return back()->withErrors(['start_time' => $e->getMessage()])->withInput();
        }

        return redirect()->back()->with('success', 'Event updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event) : RedirectResponse
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
