<?php

namespace App\Services;

use App\Enums\Booking\BookingStatus;
use App\Models\Booking;

class StripeService
{

    protected $stripe;
    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));
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

}
