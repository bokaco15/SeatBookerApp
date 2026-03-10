<?php

namespace App\Models;

use App\Enums\Booking\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    const TABLE_NAME = 'bookings';

    protected $table = self::TABLE_NAME;
    protected $fillable = [
        'event_id',
        'email',
        'guest_name',
        'status',
        'expires_at',
        'total_amount',
        'session_id',
        'browser_session_id',
    ];
    protected $casts = [
        'status' => BookingStatus::class,
        'expires_at' => 'datetime',
    ];
    public function booking_items() : HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
