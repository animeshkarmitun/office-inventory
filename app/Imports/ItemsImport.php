<?php

namespace App\Imports;

use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ItemsImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsErrors
{
    private $importedCount = 0;
    private $updatedCount = 0;
    private $errors = [];

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            try {
                $this->processRow($row);
            } catch (\Exception $e) {
                $this->errors[] = "Row " . ($this->importedCount + $this->updatedCount + 1) . ": " . $e->getMessage();
                Log::error('Import error: ' . $e->getMessage(), ['row' => $row->toArray()]);
            }
        }
    }

    private function processRow($row)
    {
        // Skip if name is empty
        if (empty($row['name'])) {
            return;
        }

        // Check if item exists by ID or create new one
        $item = null;
        if (!empty($row['id']) && is_numeric($row['id'])) {
            $item = Item::find($row['id']);
        }

        // Prepare data for item
        $itemData = $this->prepareItemData($row);

        if ($item) {
            // Update existing item
            $item->update($itemData);
            $this->updatedCount++;
        } else {
            // Create new item
            $itemData['serial_number'] = Item::generateSerialNumber();
            $itemData['asset_tag'] = Item::generateAssetTag();
            Item::create($itemData);
            $this->importedCount++;
        }
    }

    private function prepareItemData($row)
    {
        $data = [
            'name' => $row['name'] ?? '',
            'serial_number' => $row['serial_number'] ?? null,
            'asset_tag' => $row['asset_tag'] ?? null,
            'barcode' => $row['barcode'] ?? null,
            'rfid_tag' => $row['rfid_tag'] ?? null,
            'description' => $row['description'] ?? null,
            'asset_type' => strtolower($row['asset_type'] ?? 'fixed'),
            'value' => $this->parseCurrency($row['value'] ?? 0),
            'depreciation_cost' => $this->parseCurrency($row['depreciation_cost'] ?? 0),
            'depreciation_method' => strtolower(str_replace(' ', '_', $row['depreciation_method'] ?? 'straight_line')),
            'depreciation_rate' => $this->parsePercentage($row['depreciation_rate'] ?? 0),
            'status' => strtolower(str_replace(' ', '_', $row['status'] ?? 'available')),
            'floor_level' => $row['floor_level'] ?? '',
            'room_number' => $row['room_number'] ?? '',
            'location' => $row['location'] ?? null,
            'tracking_mode' => strtolower($row['tracking_mode'] ?? 'individual'),
            'quantity' => intval($row['quantity'] ?? 1),
            'remarks' => $row['remarks'] ?? null,
        ];

        // Handle supplier
        if (!empty($row['supplier'])) {
            $supplier = Supplier::where('name', $row['supplier'])->first();
            if ($supplier) {
                $data['supplier_id'] = $supplier->id;
            } else {
                // Create new supplier if not exists
                $supplier = Supplier::create([
                    'name' => $row['supplier'],
                    'incharge_name' => 'Imported Supplier',
                    'contact_number' => 'N/A',
                ]);
                $data['supplier_id'] = $supplier->id;
            }
        }

        // Handle purchase date
        if (!empty($row['purchase_date'])) {
            $data['purchase_date'] = \Carbon\Carbon::parse($row['purchase_date'])->format('Y-m-d');
        }

        // Handle user relationships
        if (!empty($row['purchased_by'])) {
            $user = User::where('name', $row['purchased_by'])->first();
            if ($user) {
                $data['purchased_by'] = $user->id;
            }
        }

        if (!empty($row['received_by'])) {
            $user = User::where('name', $row['received_by'])->first();
            if ($user) {
                $data['received_by'] = $user->id;
            }
        }

        if (!empty($row['assigned_to'])) {
            $user = User::where('name', $row['assigned_to'])->first();
            if ($user) {
                $data['assigned_to'] = $user->id;
            }
        }

        return $data;
    }

    private function parseCurrency($value)
    {
        if (empty($value)) return 0;
        
        // Remove currency symbols and commas
        $value = preg_replace('/[^0-9.-]/', '', $value);
        return floatval($value);
    }

    private function parsePercentage($value)
    {
        if (empty($value)) return 0;
        
        // Remove percentage symbol
        $value = str_replace('%', '', $value);
        return floatval($value);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'asset_type' => 'nullable|in:fixed,current',
            'status' => 'nullable|in:available,in_use,maintenance,not_traceable,disposed',
            'tracking_mode' => 'nullable|in:bulk,individual',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Item name is required.',
            'asset_type.in' => 'Asset type must be either "Fixed" or "Current".',
            'status.in' => 'Status must be one of: Available, In Use, Maintenance, Not Traceable, Disposed.',
            'tracking_mode.in' => 'Tracking mode must be either "Bulk" or "Individual".',
        ];
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
