<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Department;
use App\Models\Borrower;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // Create dummy suppliers
        $suppliers = [
            ['name' => 'Supplier A', 'contact' => '123-456-7890', 'email' => 'supplierA@example.com', 'incharge_name' => 'Alice Manager', 'contact_number' => '123-456-7890'],
            ['name' => 'Supplier B', 'contact' => '098-765-4321', 'email' => 'supplierB@example.com', 'incharge_name' => 'Bob Supervisor', 'contact_number' => '098-765-4321'],
        ];
        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create dummy categories
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Furniture'],
            ['name' => 'Office Supplies'],
        ];
        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create dummy departments
        $departments = [
            ['name' => 'IT', 'location' => 'Floor 1, Room 101'],
            ['name' => 'HR', 'location' => 'Floor 2, Room 201'],
            ['name' => 'Finance', 'location' => 'Floor 3, Room 301'],
        ];
        foreach ($departments as $department) {
            Department::create($department);
        }

        // Create dummy borrowers
        $borrowers = [
            ['name' => 'John Doe', 'email' => 'john@example.com', 'department_id' => 1, 'staff_id' => 'EMP001'],
            ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'department_id' => 2, 'staff_id' => 'EMP002'],
        ];
        foreach ($borrowers as $borrower) {
            Borrower::create($borrower);
        }

        // Create dummy items
        $items = [
            [
                'name' => 'Laptop',
                'serial_number' => 'SN001',
                'asset_tag' => 'AT001',
                'barcode' => 'BC001',
                'rfid_tag' => 'RF001',
                'description' => 'Dell XPS 13',
                'specifications' => json_encode(['RAM' => '16GB', 'Storage' => '512GB SSD']),
                'asset_type' => 'fixed',
                'value' => 1200.00,
                'depreciation_cost' => 100.00,
                'purchased_by' => 1,
                'supplier_id' => 1,
                'purchase_date' => now(),
                'received_by' => 1,
                'status' => 'available',
                'remarks' => 'New laptop',
                'floor_level' => '1st Floor',
                'room_number' => '101',
                'location' => 'IT Department',
                'assigned_to' => 1,
                'condition' => 'Good',
                'is_approved' => true,
                'approved_by' => 1,
                'approved_at' => now(),
            ],
            [
                'name' => 'Office Chair',
                'serial_number' => 'SN002',
                'asset_tag' => 'AT002',
                'barcode' => 'BC002',
                'rfid_tag' => 'RF002',
                'description' => 'Ergonomic Office Chair',
                'specifications' => json_encode(['Material' => 'Leather', 'Color' => 'Black']),
                'asset_type' => 'fixed',
                'value' => 300.00,
                'depreciation_cost' => 30.00,
                'purchased_by' => 1,
                'supplier_id' => 2,
                'purchase_date' => now(),
                'received_by' => 1,
                'status' => 'available',
                'remarks' => 'New chair',
                'floor_level' => '2nd Floor',
                'room_number' => '201',
                'location' => 'HR Department',
                'assigned_to' => 2,
                'condition' => 'Good',
                'is_approved' => true,
                'approved_by' => 1,
                'approved_at' => now(),
            ],
        ];
        foreach ($items as $item) {
            Item::create($item);
        }
    }
} 