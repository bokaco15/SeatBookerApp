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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\Stripe;
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
        $stripe = new StripeClient(config('services.stripe.secret_key'));
        $session_id = $request->session_id;
        $checkout_session = $stripe->checkout->sessions->retrieve($session_id, []);
        $email = $checkout_session->customer_details->email;
        $booking = Booking::where('session_id', $session_id)->first();


        return view('front.success-payment', compact('email', 'booking'));
    }

    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
// Replace this endpoint secret with your endpoint's unique secret
// If you are testing with the CLI, find the secret by running 'stripe listen'
// If you are using an endpoint defined with the API or dashboard, look in your webhook settings
// at https://dashboard.stripe.com/webhooks
        $endpoint_secret = config('services.stripe.webhook_sc');

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit();
        }

        if ($endpoint_secret) {
            // Only verify the event if you've defined an endpoint secret
            // Otherwise, use the basic decoded event
            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch(\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                echo '⚠️  Webhook error while validating signature.';
                http_response_code(400);
                exit();
            }
        }

// Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $session_id = $session->id;
                $booking = Booking::where('session_id', $session_id)->first();

                if (!$booking) {
                    return response('Booking not found', 404);
                }
                if ($booking->status == BookingStatus::PAID) {
                    return response('Already processed', 200);
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
                $booking->email = $session->customer_details->email;
                $booking->save();

                Mail::to($booking->email)->send(new BookingDownloadMail($booking));
                break;
            default:
                echo 'Received unknown event type ' . $event->type;
        }

        return response('', 200);

    }

}
