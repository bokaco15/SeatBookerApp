<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Hall;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function index(): View
    {
        $eventCount = Event::all()->count();
        $seatsCount = Seat::all()->count();
        $hallsCount = Hall::all()->count();
        $bookingCount = Booking::all()->count();

        return view('admin.dashboard', compact('eventCount', 'seatsCount', 'hallsCount', 'bookingCount'));
    }
}
