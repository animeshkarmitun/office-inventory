<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'approved_at',
        'tracking_mode',
        'quantity',
        'image',
        'purchase_id',
        'floor_id',
        'room_id'
    ];

    protected $casts = [
        'specifications' => 'string',
        'purchase_date' => 'date',
        'approved_at' => 'datetime',
        'value' => 'decimal:2',
        'depreciation_cost' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'is_approved' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        // Create movement record after item is updated
        static::updated(function ($item) {
            $originalAssignedTo = $item->getOriginal('assigned_to');
            $originalLocation = $item->getOriginal('location');
            $originalFloorId = $item->getOriginal('floor_id');
            $originalRoomId = $item->getOriginal('room_id');
            $originalFloorLevel = $item->getOriginal('floor_level');
            $originalRoomNumber = $item->getOriginal('room_number');
            
            $newAssignedTo = $item->assigned_to;
            $newLocation = $item->location;
            $newFloorId = $item->floor_id;
            $newRoomId = $item->room_id;
            $newFloorLevel = $item->floor_level;
            $newRoomNumber = $item->room_number;

            // Check if assignment, location, floor, or room changed
            $assignmentChanged = $originalAssignedTo != $newAssignedTo;
            $locationChanged = $originalLocation != $newLocation;
            $floorChanged = $originalFloorId != $newFloorId || $originalFloorLevel != $newFloorLevel;
            $roomChanged = $originalRoomId != $newRoomId || $originalRoomNumber != $newRoomNumber;

            if ($assignmentChanged || $locationChanged || $floorChanged || $roomChanged) {
                // Determine movement type based on what changed
                $movementType = 'assignment';
                if ($assignmentChanged && $newAssignedTo) {
                    $movementType = 'transfer';
                } elseif (!$assignmentChanged && ($locationChanged || $floorChanged || $roomChanged)) {
                    $movementType = 'location_change';
                }

                // Get formatted location strings
                $fromLocation = self::getFormattedLocationForMovement($originalLocation, $originalFloorId, $originalRoomId, $originalFloorLevel, $originalRoomNumber);
                $toLocation = self::getFormattedLocationForMovement($newLocation, $newFloorId, $newRoomId, $newFloorLevel, $newRoomNumber);

                try {
                    // Only create movement if we have a valid user ID and the table exists
                    $movedBy = Auth::check() ? Auth::id() : (\App\Models\User::first()->id ?? null);
                    if ($movedBy && \Schema::hasTable('asset_movements')) {
                        \App\Models\AssetMovement::create([
                            'item_id' => $item->id,
                            'from_user_id' => $originalAssignedTo,
                            'to_user_id' => $newAssignedTo,
                            'from_location' => $fromLocation,
                            'to_location' => $toLocation,
                            'movement_type' => $movementType,
                            'notes' => $movementType === 'location_change' ? 'Location updated via item edit' : 'Assignment updated via item edit',
                            'moved_by' => $movedBy
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log the error but don't break the item creation
                    \Log::error('Failed to create asset movement: ' . $e->getMessage());
                }
            }
        });

        // Create initial movement record when item is created with assignment
        static::created(function ($item) {
            if ($item->assigned_to) {
                try {
                    // Only create movement if we have a valid user ID and the table exists
                    $movedBy = Auth::check() ? Auth::id() : (\App\Models\User::first()->id ?? null);
                    if ($movedBy && \Schema::hasTable('asset_movements')) {
                        $toLocation = self::getFormattedLocationForMovement($item->location, $item->floor_id, $item->room_id, $item->floor_level, $item->room_number);
                        
                        \App\Models\AssetMovement::create([
                            'item_id' => $item->id,
                            'from_user_id' => null,
                            'to_user_id' => $item->assigned_to,
                            'from_location' => null,
                            'to_location' => $toLocation,
                            'movement_type' => 'assignment',
                            'notes' => 'Initial assignment',
                            'moved_by' => $movedBy
                        ]);
                    }
                } catch (\Exception $e) {
                    // Log the error but don't break the item creation
                    \Log::error('Failed to create initial asset movement: ' . $e->getMessage());
                }
            }
        });
    }

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

    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ItemImage::class)->orderBy('sort_order');
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get formatted location with floor and room information
     */
    public function getFormattedLocationAttribute()
    {
        $locationParts = [];
        
        // Add basic location if available
        if (!empty($this->location)) {
            $locationParts[] = $this->location;
        }
        
        // Add floor information
        if ($this->floor) {
            $floorInfo = $this->floor->name;
            if ($this->floor->serial_number) {
                $floorInfo .= " ({$this->floor->serial_number})";
            }
            $locationParts[] = "Floor: {$floorInfo}";
        } elseif (!empty($this->floor_level)) {
            $locationParts[] = "Floor: {$this->floor_level}";
        }
        
        // Add room information
        if ($this->room) {
            $roomInfo = $this->room->name;
            if ($this->room->room_number) {
                $roomInfo .= " ({$this->room->room_number})";
            }
            $locationParts[] = "Room: {$roomInfo}";
        } elseif (!empty($this->room_number)) {
            $locationParts[] = "Room: {$this->room_number}";
        }
        
        return !empty($locationParts) ? implode(' | ', $locationParts) : 'N/A';
    }

    /**
     * Get formatted location string for movement records
     */
    public static function getFormattedLocationForMovement($location, $floorId, $roomId, $floorLevel, $roomNumber)
    {
        $locationParts = [];
        
        // Add basic location if available
        if (!empty($location)) {
            $locationParts[] = $location;
        }
        
        // Add floor information
        if ($floorId) {
            $floor = \App\Models\Floor::find($floorId);
            if ($floor) {
                $floorInfo = $floor->name;
                if ($floor->serial_number) {
                    $floorInfo .= " ({$floor->serial_number})";
                }
                $locationParts[] = "Floor: {$floorInfo}";
            }
        } elseif (!empty($floorLevel)) {
            $locationParts[] = "Floor: {$floorLevel}";
        }
        
        // Add room information
        if ($roomId) {
            $room = \App\Models\Room::find($roomId);
            if ($room) {
                $roomInfo = $room->name;
                if ($room->room_number) {
                    $roomInfo .= " ({$room->room_number})";
                }
                $locationParts[] = "Room: {$roomInfo}";
            }
        } elseif (!empty($roomNumber)) {
            $locationParts[] = "Room: {$roomNumber}";
        }
        
        return !empty($locationParts) ? implode(' | ', $locationParts) : 'N/A';
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

    /**
     * Generate the next serial number
     */
    public static function generateSerialNumber($suffix = '')
    {
        $year = date('Y');
        $lastItem = self::where('serial_number', 'like', "COSMOS-SN-{$year}%")
                        ->orderBy('serial_number', 'desc')
                        ->first();
        
        if ($lastItem) {
            // Extract the number from the last serial number
            preg_match('/COSMOS-SN-' . $year . '-(\d+)/', $lastItem->serial_number, $matches);
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        
        $serialNumber = "COSMOS-SN-{$year}-" . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        if ($suffix) {
            $serialNumber .= "-{$suffix}";
        }
        
        return $serialNumber;
    }

    /**
     * Generate the next asset tag
     */
    public static function generateAssetTag($suffix = '')
    {
        $year = date('Y');
        $lastItem = self::where('asset_tag', 'like', "COSMOS-AT-{$year}%")
                        ->orderBy('asset_tag', 'desc')
                        ->first();
        
        if ($lastItem) {
            // Extract the number from the last asset tag
            preg_match('/COSMOS-AT-' . $year . '-(\d+)/', $lastItem->asset_tag, $matches);
            $nextNumber = (int)$matches[1] + 1;
        } else {
            $nextNumber = 1;
        }
        
        $assetTag = "COSMOS-AT-{$year}-" . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        if ($suffix) {
            $assetTag .= "-{$suffix}";
        }
        
        return $assetTag;
    }

    /**
     * Calculate depreciation cost based on value and depreciation rate
     */
    public function calculateDepreciationCost()
    {
        if ($this->value && $this->depreciation_rate) {
            return ($this->value * $this->depreciation_rate) / 100;
        }
        return $this->depreciation_cost;
    }

    /**
     * Get the effective depreciation cost (calculated or stored)
     */
    public function getEffectiveDepreciationCostAttribute()
    {
        return $this->calculateDepreciationCost();
    }
}
