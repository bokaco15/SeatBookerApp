<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatCategory extends Model
{
    const TABLE_NAME = 'seat_categories';

    protected $table = self::TABLE_NAME;
    protected $fillable = ['name', 'price'];

    public function seats() : HasMany
    {
        return $this->hasMany(Seat::class);
    }

    public function booking_items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }
}
