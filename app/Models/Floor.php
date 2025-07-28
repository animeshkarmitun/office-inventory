<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'serial_number',
        'room_count',
        'description',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Update room count when rooms are added/removed
    public function updateRoomCount()
    {
        $this->room_count = $this->rooms()->count();
        $this->save();
    }
}
