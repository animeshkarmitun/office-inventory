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

    /**
     * Generate the next floor serial number
     */
    public static function generateSerialNumber()
    {
        $year = date('Y');
        $lastFloor = self::where('serial_number', 'like', "FL-{$year}%")
                        ->orderBy('serial_number', 'desc')
                        ->first();
        
        if ($lastFloor) {
            // Extract the number from the last serial number
            preg_match('/FL-' . $year . '-(\d+)/', $lastFloor->serial_number, $matches);
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        
        $serialNumber = "FL-{$year}-" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        
        return $serialNumber;
    }
}
