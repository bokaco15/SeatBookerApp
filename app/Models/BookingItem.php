<?php

namespace App\Models;

use App\Enums\Booking\BookingStatus;
use App\Enums\BookingItem\BookingItemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookingItem extends Model
{
    protected $table = 'booking_items';
    protected $fillable = [
        'booking_id',
        'event_id',
        'seat_id',
        'seat_category_id',
        'price',
        'status',
    ];

    protected $casts = [
        'status' => BookingItemStatus::class,
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }

    public function seat_category():BelongsTo
    {
        return $this->belongsTo(SeatCategory::class);
    }
}
