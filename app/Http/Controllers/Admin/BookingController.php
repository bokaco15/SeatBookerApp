<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        $bookings = Booking::with(['event', 'booking_items.seat'])->latest()->get();
        return view('admin.booking.index', compact('bookings'));
    }

}
