<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'invoice_image',
        'purchase_date',
        'total_value',
        'purchased_by',
        'received_by',
        'department_id',
        'purchase_number',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'total_value' => 'decimal:2',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(\App\Models\Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchasedBy()
    {
        return $this->belongsTo(User::class, 'purchased_by');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Generate a unique purchase number
     */
    public static function generatePurchaseNumber()
    {
        $year = date('Y');
        $lastPurchase = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastPurchase && $lastPurchase->purchase_number) {
            // Extract the number from the last purchase number
            $lastNumber = (int) substr($lastPurchase->purchase_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'PUR-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
} 