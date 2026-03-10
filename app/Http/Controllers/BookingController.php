<?php

namespace App\Http\Controllers;

use App\Enums\Booking\BookingStatus;
use App\Enums\BookingItem\BookingItemStatus;
use App\Http\Requests\StoreBookingRequest;
use App\Mail\BookingDownloadMail;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Event;
use App\Models\Seat;
use App\Services\BookingService;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\StripeClient;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $seatIds = explode(',', $data['seat_ids']);

        $bookingService = new BookingService();
        $booking = $bookingService->createBooking($data['event_id'], $seatIds);

        $stripeService = new StripeService();
        $checkout_session_url = $stripeService->createCheckoutSession($booking);

        return redirect($checkout_session_url);
    }


    /*
     *
     *  I need put that into service when I make webbook
     *
     */
    public function success_payment(Request $request) : View
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));
        $session_id = $request->session_id;
        $checkout_session = $stripe->checkout->sessions->retrieve($session_id, []);
        $email = $checkout_session->customer_details->email;
        $booking = Booking::where('session_id', $session_id)->first();
        if (!$booking) {
            abort(403, "This booking does not exist.");
        }
        if ($booking->status == BookingStatus::PAID) {
            abort(403, "You have already opened this session.");
        }

        $bookingItems = $booking->booking_items;
        foreach ($bookingItems as $bookingItem) {
            $bookingItem->status = BookingItemStatus::BOOKED;

            if (!$bookingItem->qr_token) {
                $bookingItem->qr_token = (string) Str::uuid();
            }

            $bookingItem->save();
        }
        $booking->status = BookingStatus::PAID;
        $booking->email = $email;
        $booking->save();

        Mail::to($booking->email)->send(new BookingDownloadMail($booking));

        return view('front.success-payment', compact('email', 'booking'));
    }

}
