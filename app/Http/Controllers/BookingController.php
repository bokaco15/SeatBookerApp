<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use App\Services\StripeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;


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



    public function successPayment(Request $request) : View
    {
        $stripeService = new StripeService();
        $data = $stripeService->getSuccessPaymentData($request->session_id);
        return view('front.success-payment', $data);
    }

    public function webhook() : Response
    {
        $stripeService = new StripeService();
        return $stripeService->webhook();
    }

}
