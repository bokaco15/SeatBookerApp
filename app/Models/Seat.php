<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seat extends Model
{
    const TABLE_NAME = 'seats';
    protected $table = self::TABLE_NAME;
    protected $fillable = [
        'hall_id',
        'row',
        'number',
        'seat_category_id'
    ];

    public function hall() : BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function seatCategory() : BelongsTo
    {
        return $this->belongsTo(SeatCategory::class);
    }

    public function booking_items(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

}
