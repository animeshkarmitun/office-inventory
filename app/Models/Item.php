<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'serial_number',
        'asset_tag',
        'barcode',
        'rfid_tag',
        'location',
        'assigned_to',
        'condition',
        'description',
        'specifications',
        'asset_type',
        'value',
        'depreciation_cost',
        'purchased_by',
        'supplier_id',
        'purchase_date',
        'received_by',
        'status',
        'remarks',
        'floor_level',
        'room_number',
        'is_approved',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'specifications' => 'array',
        'purchase_date' => 'date',
        'approved_at' => 'datetime',
        'value' => 'decimal:2',
        'depreciation_cost' => 'decimal:2',
        'is_approved' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function borrowers()
    {
        return $this->hasMany(Borrower::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function movements()
    {
        return $this->hasMany(AssetMovement::class);
    }

    public function purchasedBy()
    {
        return $this->belongsTo(User::class, 'purchased_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
