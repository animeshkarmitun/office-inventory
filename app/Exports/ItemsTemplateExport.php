<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Return empty collection for template
        return collect([
            [
                'id' => '',
                'name' => 'Sample Laptop',
                'serial_number' => '',
                'asset_tag' => '',
                'barcode' => '',
                'rfid_tag' => '',
                'description' => 'Sample laptop for office use',
                'asset_type' => 'fixed',
                'value' => '1000.00',
                'depreciation_cost' => '100.00',
                'depreciation_method' => 'straight_line',
                'depreciation_rate' => '10',
                'status' => 'available',
                'supplier' => 'Default Supplier',
                'purchase_date' => '2024-01-01',
                'purchased_by' => '',
                'received_by' => '',
                'floor_level' => '1st Floor',
                'room_number' => '101',
                'location' => 'IT Office',
                'assigned_to' => '',
                'tracking_mode' => 'individual',
                'quantity' => '1',
                'remarks' => 'Sample item for import testing',
                'created_at' => '',
                'updated_at' => ''
            ],
            [
                'id' => '',
                'name' => 'Office Chair',
                'serial_number' => '',
                'asset_tag' => '',
                'barcode' => '',
                'rfid_tag' => '',
                'description' => 'Ergonomic office chair',
                'asset_type' => 'fixed',
                'value' => '500.00',
                'depreciation_cost' => '50.00',
                'depreciation_method' => 'straight_line',
                'depreciation_rate' => '10',
                'status' => 'available',
                'supplier' => 'Default Supplier',
                'purchase_date' => '2024-01-15',
                'purchased_by' => '',
                'received_by' => '',
                'floor_level' => '2nd Floor',
                'room_number' => '205',
                'location' => 'HR Department',
                'assigned_to' => '',
                'tracking_mode' => 'individual',
                'quantity' => '1',
                'remarks' => 'Comfortable office chair',
                'created_at' => '',
                'updated_at' => ''
            ]
        ]);
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
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
            // Style the sample data row with light background
            2 => ['fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'F0F0F0']]],
        ];
    }
}
