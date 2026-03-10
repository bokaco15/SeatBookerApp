<?php

namespace App\Services;

use App\Models\Seat;

class SeatService
{
    public function createSeats($hall, $vip, $normal) : void
    {
        for($row = 1; $row <= $hall->rows; $row++) {
            for($col = 1; $col <= $hall->columns; $col++) {
                Seat::create([
                    'hall_id' => $hall->id,
                    'row' => $row,
                    'number' => $col,
                    'seat_category_id' => $row <= 3 ? $vip->id : $normal->id,
                ]);
            }
        }
    }
}
