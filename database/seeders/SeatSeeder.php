<?php

namespace Database\Seeders;

use App\Models\Hall;
use App\Models\SeatCategory;
use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hall = Hall::first();
        $vip = SeatCategory::where('name', 'VIP')->first();
        $normal = SeatCategory::where('name', 'Normal')->first();

        for ($row = 1; $row <= $hall->rows; $row++) {
            for ($number = 1; $number <= $hall->columns; $number++) {

                Seat::create([
                    'hall_id' => $hall->id,
                    'row' => $row,
                    'number' => $number,
                    'seat_category_id' => $row <= 3 ? $vip->id : $normal->id,
                ]);
            }
        }
    }
}
