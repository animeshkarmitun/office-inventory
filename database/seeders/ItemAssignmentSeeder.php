<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class ItemAssignmentSeeder extends Seeder
{
    public function run()
    {
        // Get existing data
        $floors = Floor::with('rooms')->get();
        $categories = Category::all();
        $suppliers = Supplier::all();
        $departments = Department::all();

        if ($floors->isEmpty() || $categories->isEmpty() || $suppliers->isEmpty() || $departments->isEmpty()) {
            $this->command->error('Please run FloorRoomSeeder, CategorySeeder, SupplierSeeder, and DepartmentSeeder first!');
            return;
        }

        // Office Equipment Items
        $officeItems = [
            [
                'name' => 'HP LaserJet Pro M404n Printer',
                'serial_number' => 'HP-LJ-2024-001',
                'category_id' => $categories->where('name', 'Printers')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-01-15',
                'purchase_price' => 299.99,
                'value' => 250.00,
                'location' => 'IT Office',
                'status' => true,
                'description' => 'Black and white laser printer for office use',
                'specifications' => 'Print Speed: 40 ppm, Resolution: 600 x 600 dpi',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'IT Office')->first()->id,
            ],
            [
                'name' => 'Dell OptiPlex 7090 Desktop',
                'serial_number' => 'DELL-OPT-2024-002',
                'category_id' => $categories->where('name', 'Computers')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-02-10',
                'purchase_price' => 899.99,
                'value' => 750.00,
                'location' => 'IT Office',
                'status' => true,
                'description' => 'Desktop computer for IT department',
                'specifications' => 'Intel i7, 16GB RAM, 512GB SSD',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'IT Office')->first()->id,
            ],
            [
                'name' => 'Samsung 55" Smart TV',
                'serial_number' => 'SAMSUNG-TV-2024-003',
                'category_id' => $categories->where('name', 'Electronics')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-01-20',
                'purchase_price' => 599.99,
                'value' => 500.00,
                'location' => 'Conference Room A',
                'status' => true,
                'description' => 'Smart TV for presentations and meetings',
                'specifications' => '4K UHD, Smart TV features, HDMI ports',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'Conference Room A')->first()->id,
            ],
            [
                'name' => 'Steelcase Think Office Chair',
                'serial_number' => 'STEEL-CHAIR-2024-004',
                'category_id' => $categories->where('name', 'Furniture')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-03-05',
                'purchase_price' => 399.99,
                'value' => 350.00,
                'location' => 'HR Office',
                'status' => true,
                'description' => 'Ergonomic office chair for HR manager',
                'specifications' => 'Adjustable height, lumbar support, mesh back',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Second Floor')->first()->id,
                'room_id' => $floors->where('name', 'Second Floor')->first()->rooms->where('name', 'HR Office')->first()->id,
            ],
            [
                'name' => 'Canon EOS R6 Camera',
                'serial_number' => 'CANON-CAM-2024-005',
                'category_id' => $categories->where('name', 'Electronics')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-02-28',
                'purchase_price' => 2499.99,
                'value' => 2200.00,
                'location' => 'Marketing Department',
                'status' => true,
                'description' => 'Professional camera for marketing and events',
                'specifications' => '20.1MP, 4K video, Dual card slots',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Second Floor')->first()->id,
                'room_id' => $floors->where('name', 'Second Floor')->first()->rooms->where('name', 'Finance Office')->first()->id,
            ],
            [
                'name' => 'Brother MFC-L8900CDW Printer',
                'serial_number' => 'BROTHER-MFC-2024-006',
                'category_id' => $categories->where('name', 'Printers')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-01-10',
                'purchase_price' => 449.99,
                'value' => 380.00,
                'location' => 'Finance Office',
                'status' => true,
                'description' => 'Color laser printer for finance department',
                'specifications' => 'Color printing, scanning, copying, faxing',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Second Floor')->first()->id,
                'room_id' => $floors->where('name', 'Second Floor')->first()->rooms->where('name', 'Finance Office')->first()->id,
            ],
            [
                'name' => 'IKEA Bekant Desk',
                'serial_number' => 'IKEA-DESK-2024-007',
                'category_id' => $categories->where('name', 'Furniture')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-03-15',
                'purchase_price' => 199.99,
                'value' => 180.00,
                'location' => 'IT Office',
                'status' => true,
                'description' => 'Adjustable standing desk for IT staff',
                'specifications' => 'Electric height adjustment, 160x80cm surface',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'IT Office')->first()->id,
            ],
            [
                'name' => 'Cisco Catalyst 2960 Switch',
                'serial_number' => 'CISCO-SW-2024-008',
                'category_id' => $categories->where('name', 'Networking')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-02-05',
                'purchase_price' => 299.99,
                'value' => 250.00,
                'location' => 'Server Room',
                'status' => true,
                'description' => 'Network switch for office connectivity',
                'specifications' => '24 ports, Gigabit Ethernet, PoE support',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Ground Floor')->first()->id,
                'room_id' => $floors->where('name', 'Ground Floor')->first()->rooms->where('name', 'Storage Room')->first()->id,
            ],
            [
                'name' => 'Dell Latitude 5520 Laptop',
                'serial_number' => 'DELL-LAT-2024-009',
                'category_id' => $categories->where('name', 'Computers')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-03-20',
                'purchase_price' => 1299.99,
                'value' => 1100.00,
                'location' => 'HR Office',
                'status' => true,
                'description' => 'Laptop for HR department mobile work',
                'specifications' => 'Intel i5, 8GB RAM, 256GB SSD, 15.6"',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Second Floor')->first()->id,
                'room_id' => $floors->where('name', 'Second Floor')->first()->rooms->where('name', 'HR Office')->first()->id,
            ],
            [
                'name' => 'Samsung Refrigerator',
                'serial_number' => 'SAMSUNG-FRIDGE-2024-010',
                'category_id' => $categories->where('name', 'Appliances')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-01-25',
                'purchase_price' => 799.99,
                'value' => 650.00,
                'location' => 'Break Room',
                'status' => true,
                'description' => 'Refrigerator for employee break room',
                'specifications' => '18 cu ft, French door, ice maker',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'Break Room')->first()->id,
            ],
            [
                'name' => 'Projector Screen',
                'serial_number' => 'PROJ-SCREEN-2024-011',
                'category_id' => $categories->where('name', 'Electronics')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-02-15',
                'purchase_price' => 149.99,
                'value' => 120.00,
                'location' => 'Conference Room B',
                'status' => true,
                'description' => 'Retractable projector screen for meetings',
                'specifications' => '120" diagonal, motorized, remote control',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'Conference Room B')->first()->id,
            ],
            [
                'name' => 'Security Camera System',
                'serial_number' => 'SEC-CAM-2024-012',
                'category_id' => $categories->where('name', 'Security')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-01-30',
                'purchase_price' => 899.99,
                'value' => 750.00,
                'location' => 'Security Office',
                'status' => true,
                'description' => 'CCTV system for office security',
                'specifications' => '4 cameras, DVR, night vision, motion detection',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Ground Floor')->first()->id,
                'room_id' => $floors->where('name', 'Ground Floor')->first()->rooms->where('name', 'Security Office')->first()->id,
            ],
            [
                'name' => 'Office Filing Cabinet',
                'serial_number' => 'FILE-CAB-2024-013',
                'category_id' => $categories->where('name', 'Furniture')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-03-10',
                'purchase_price' => 299.99,
                'value' => 250.00,
                'location' => 'Finance Office',
                'status' => true,
                'description' => '4-drawer filing cabinet for document storage',
                'specifications' => 'Steel construction, lockable, letter size',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Second Floor')->first()->id,
                'room_id' => $floors->where('name', 'Second Floor')->first()->rooms->where('name', 'Finance Office')->first()->id,
            ],
            [
                'name' => 'Air Purifier',
                'serial_number' => 'AIR-PUR-2024-014',
                'category_id' => $categories->where('name', 'Appliances')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-02-20',
                'purchase_price' => 199.99,
                'value' => 170.00,
                'location' => 'Reception',
                'status' => true,
                'description' => 'HEPA air purifier for reception area',
                'specifications' => 'HEPA filter, 3-speed fan, 500 sq ft coverage',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'Ground Floor')->first()->id,
                'room_id' => $floors->where('name', 'Ground Floor')->first()->rooms->where('name', 'Reception')->first()->id,
            ],
            [
                'name' => 'Coffee Machine',
                'serial_number' => 'COFFEE-MACH-2024-015',
                'category_id' => $categories->where('name', 'Appliances')->first()->id ?? $categories->first()->id,
                'supplier_id' => $suppliers->first()->id,
                'purchase_date' => '2024-01-12',
                'purchase_price' => 399.99,
                'value' => 320.00,
                'location' => 'Break Room',
                'status' => false, // Under maintenance
                'description' => 'Commercial coffee machine for employees',
                'specifications' => '12-cup capacity, programmable, thermal carafe',
                'asset_type' => 'fixed',
                'floor_id' => $floors->where('name', 'First Floor')->first()->id,
                'room_id' => $floors->where('name', 'First Floor')->first()->rooms->where('name', 'Break Room')->first()->id,
            ],
        ];

        // Create items and assign to rooms
        foreach ($officeItems as $itemData) {
            // Remove floor_id and room_id from item data as they might not exist in items table
            $floorId = $itemData['floor_id'] ?? null;
            $roomId = $itemData['room_id'] ?? null;
            unset($itemData['floor_id'], $itemData['room_id']);

            $item = Item::create($itemData);

            // Update the item with floor and room information if the columns exist
            if ($this->columnExists('items', 'floor_id') && $this->columnExists('items', 'room_id')) {
                $item->update([
                    'floor_id' => $floorId,
                    'room_id' => $roomId
                ]);
            }

            $this->command->info("Created item: {$item->name} assigned to room ID: {$roomId}");
        }

        $this->command->info('Item assignment completed successfully!');
        $this->command->info('Created ' . count($officeItems) . ' items assigned to various floors and rooms.');
    }

    private function columnExists($table, $column)
    {
        return DB::getSchemaBuilder()->hasColumn($table, $column);
    }
}
