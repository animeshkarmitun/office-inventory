<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'from_user_id',
        'to_user_id',
        'from_location',
        'to_location',
        'movement_type',
        'notes',
        'moved_by'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function movedBy()
    {
        return $this->belongsTo(User::class, 'moved_by');
    }
}
