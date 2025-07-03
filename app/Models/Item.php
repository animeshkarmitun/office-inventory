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
        'depreciation_method',
        'depreciation_rate',
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
        'depreciation_rate' => 'decimal:2',
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

    /**
     * Calculate annual depreciation based on method.
     */
    public function annualDepreciation()
    {
        if (!$this->value || !$this->depreciation_method || !$this->depreciation_rate) {
            return null;
        }
        if ($this->depreciation_method === 'straight_line') {
            // Assume useful life is 5 years, residual value is 0
            $usefulLife = 5;
            $residualValue = 0;
            return ($this->value - $residualValue) / $usefulLife;
        } elseif ($this->depreciation_method === 'reducing_balance') {
            // Depreciation rate is a percentage (e.g., 20 for 20%)
            return $this->value * ($this->depreciation_rate / 100);
        }
        return null;
    }

    /**
     * Calculate current book value after depreciation for each year since purchase.
     */
    public function currentBookValue()
    {
        if (!$this->value || !$this->depreciation_method || !$this->depreciation_rate || !$this->purchase_date) {
            return null;
        }
        $years = now()->diffInYears($this->purchase_date);
        $bookValue = $this->value;
        if ($this->depreciation_method === 'straight_line') {
            $annual = $this->annualDepreciation();
            $bookValue -= $annual * $years;
            return max($bookValue, 0);
        } elseif ($this->depreciation_method === 'reducing_balance') {
            $rate = 1 - ($this->depreciation_rate / 100);
            for ($i = 0; $i < $years; $i++) {
                $bookValue *= $rate;
            }
            return max($bookValue, 0);
        }
        return null;
    }
}
