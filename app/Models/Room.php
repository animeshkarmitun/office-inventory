<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'floor_id',
        'name',
        'description',
        'status',
    ];

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    // Update floor room count when room is created/updated/deleted
    protected static function booted()
    {
        static::created(function ($room) {
            $room->floor->updateRoomCount();
        });

        static::updated(function ($room) {
            $room->floor->updateRoomCount();
        });

        static::deleted(function ($room) {
            $room->floor->updateRoomCount();
        });
    }
}
