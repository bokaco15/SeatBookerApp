<?php

namespace App\Models;

use App\Enums\Event\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    const TABLE_NAME = 'events';
    protected $table = self::TABLE_NAME;
    protected $fillable = [
        'hall_id',
        'name',
        'type',
        'starts_at',
        'ends_at',
        'status'
    ];

    protected $casts = [
        'type' => EventType::class,
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];
    public function hall() : BelongsTo
    {
        return $this->belongsTo(Hall::class);
    }

    public function booking_items() : HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    public function booking(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


}
