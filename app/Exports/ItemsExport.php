<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Item::with(['supplier', 'assignedUser', 'purchasedBy', 'receivedBy'])->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Serial Number',
            'Asset Tag',
            'Barcode',
            'RFID Tag',
            'Description',
            'Specifications',
            'Asset Type',
            'Value',
            'Depreciation Cost',
            'Depreciation Method',
            'Depreciation Rate',
            'Status',
            'Supplier',
            'Purchase Date',
            'Purchased By',
            'Received By',
            'Floor Level',
            'Room Number',
            'Location',
            'Assigned To',
            'Tracking Mode',
            'Quantity',
            'Remarks',
            'Created At',
            'Updated At'
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->serial_number,
            $row->asset_tag,
            $row->barcode,
            $row->rfid_tag,
            $row->description,
            $row->specifications ?? '',
            ucfirst($row->asset_type ?? ''),
            $row->value ? '৳' . number_format($row->value, 2) : '',
            $row->depreciation_cost ? '৳' . number_format($row->depreciation_cost, 2) : '',
            ucfirst(str_replace('_', ' ', $row->depreciation_method ?? '')),
            $row->depreciation_rate ? $row->depreciation_rate . '%' : '',
            ucfirst(str_replace('_', ' ', $row->status ?? '')),
            $row->supplier ? $row->supplier->name : '',
            $row->purchase_date ? $row->purchase_date->format('Y-m-d') : '',
            $row->purchasedBy ? $row->purchasedBy->name : '',
            $row->receivedBy ? $row->receivedBy->name : '',
            $row->floor_level,
            $row->room_number,
            $row->location,
            $row->assignedUser ? $row->assignedUser->name : '',
            ucfirst($row->tracking_mode ?? ''),
            $row->quantity,
            $row->remarks,
            $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
            $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}
