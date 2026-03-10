<?php

namespace Database\Seeders;

use App\Enums\Booking\BookingStatus;
use App\Enums\BookingItem\BookingItemStatus;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Event;
use App\Models\Seat;
use App\Models\SeatCategory;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $event = DB::table('events')->first(); // ili Event::first()
        if (!$event) return;

        // Mapiranje: [seat_category_id => price]
        $pricesByCategoryId = SeatCategory::pluck('price', 'id');

        $booking = Booking::create([
            'event_id' => $event->id,
            'email' => 'test@gmail.com',
            'status' => BookingStatus::PAID,
            'total_amount' => 0, // izracunamo dole
        ]);

        // Uzmi 2 random sedista iz sale tog eventa
        $seats = Seat::where('hall_id', $event->hall_id)->inRandomOrder()->take(2)->get();

        $total = 0;

        foreach ($seats as $seat) {
            $price = (int) ($pricesByCategoryId[$seat->seat_category_id] ?? 0);
            $total += $price;

            DB::table('booking_items')->insert([
                'booking_id' => $booking->id,
                'event_id' => $event->id,
                'seat_id' => $seat->id,
                'seat_category_id' => $seat->seat_category_id,
                'price' => $price,
                'status' => BookingItemStatus::BOOKED,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $booking->update(['total_amount' => $total]);
    }
}
