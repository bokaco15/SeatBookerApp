<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hall extends Model
{
    const TABLE_NAME = 'halls';

    protected $table = self::TABLE_NAME;
    protected $fillable = [
        'name',
        'rows',
        'columns',
        'is_active',
    ];

    public function event() : HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function seats() : HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
