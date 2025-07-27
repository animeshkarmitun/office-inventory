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
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function inventoryItems()
    {
        return $this->hasMany(\App\Models\Item::class);
    }
} 