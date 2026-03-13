<?php

namespace App\Services;

use App\Enums\Booking\BookingStatus;
use App\Enums\BookingItem\BookingItemStatus;
use App\Mail\BookingDownloadMail;
use App\Models\Booking;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Webhook;
use UnexpectedValueException;


class StripeService
{

    protected $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('services.stripe.secret_key'));
    }

    public function createCheckoutSession(Booking $booking): string
    {
        $stripe = $this->stripe;
        $lineItems = [];
        $total = 0;

        foreach ($booking->booking_items as $product) {
            $total = $total += $product->seat_category->price;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'bam',
                    'product_data' => [
                        'name' => "Row {$product->seat->row} - Num {$product->seat->number}",
                    ],
                    'unit_amount' => $product->seat->seatCategory->price * 100,
                ],
                'quantity' => 1,
            ];
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('success.payment').'?session_id={CHECKOUT_SESSION_ID}',
            'expires_at' => \Illuminate\Support\now()->addMinutes(30)->timestamp,
        ]);

        $booking->status = BookingStatus::UNPAID;
        $booking->session_id = $checkout_session->id;
        $booking->save();

        return $checkout_session->url;
    }

    public function getSuccessPaymentData($sessionId) : array
    {
        $stripe = $this->stripe;
        $checkout_session = $stripe->checkout->sessions->retrieve($sessionId, []);
        $email = $checkout_session->customer_details->email;
        $booking = Booking::where('session_id', $sessionId)->first();
        if (!$booking) {
            abort(404, 'Booking not found');
        }

        return [
            'email' => $email,
            'booking' => $booking,
        ];

    }

    public function webhook() : Response
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));

        $endpoint_secret = config('services.stripe.webhook_sc');

        $payload = @file_get_contents('php://input');
        $event = null;

        try {
            $event = \Stripe\Event::constructFrom(
                json_decode($payload, true)
            );
        } catch(UnexpectedValueException $e) {
            // Invalid payload
            return response('', 404);
        }

        if ($endpoint_secret) {

            $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
            try {
                $event = Webhook::constructEvent(
                    $payload, $sig_header, $endpoint_secret
                );
            } catch(SignatureVerificationException $e) {
                // Invalid signature
                echo '⚠️  Webhook error while validating signature.';
                return response('', 404);
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
