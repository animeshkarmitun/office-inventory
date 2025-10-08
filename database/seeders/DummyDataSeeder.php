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
        // Get existing data for relationships
        $suppliers = Supplier::all();
        $categories = Category::all();
        $departments = Department::all();
        $users = \App\Models\User::all();

        // Create exactly 5 corporate borrowers
        $borrowers = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@company.com',
                'department_id' => $departments->where('name', 'Information Technology')->first()->id,
                'staff_id' => 'EMP101',
                'status' => 'active'
            ],
            [
                'name' => 'Robert Williams',
                'email' => 'robert.williams@company.com',
                'department_id' => $departments->where('name', 'Human Resources')->first()->id,
                'staff_id' => 'EMP102',
                'status' => 'active'
            ],
            [
                'name' => 'Lisa Garcia',
                'email' => 'lisa.garcia@company.com',
                'department_id' => $departments->where('name', 'Finance & Accounting')->first()->id,
                'staff_id' => 'EMP103',
                'status' => 'active'
            ],
            [
                'name' => 'James Brown',
                'email' => 'james.brown@company.com',
                'department_id' => $departments->where('name', 'Marketing & Sales')->first()->id,
                'staff_id' => 'EMP104',
                'status' => 'active'
            ],
            [
                'name' => 'Maria Davis',
                'email' => 'maria.davis@company.com',
                'department_id' => $departments->where('name', 'Operations Management')->first()->id,
                'staff_id' => 'EMP105',
                'status' => 'active'
            ]
        ];
        foreach ($borrowers as $borrower) {
            Borrower::create($borrower);
        }

        // Create exactly 5 corporate items
        $items = [
            [
                'name' => 'Dell OptiPlex Desktop',
                'serial_number' => 'DELL-001',
                'asset_tag' => 'AT-001',
                'barcode' => 'BC-001',
                'rfid_tag' => 'RF-001',
                'description' => 'Dell OptiPlex 7090 Desktop Computer',
                'specifications' => json_encode([
                    'Processor' => 'Intel Core i7-11700',
                    'RAM' => '16GB DDR4',
                    'Storage' => '512GB SSD',
                    'OS' => 'Windows 11 Pro'
                ]),
                'asset_type' => 'fixed',
                'value' => 1200.00,
                'depreciation_cost' => 100.00,
                'purchased_by' => $users->first()->id,
                'supplier_id' => $suppliers->where('name', 'TechCorp Solutions')->first()->id,
                'category_id' => $categories->where('name', 'Computer Equipment')->first()->id,
                'purchase_date' => now()->subDays(30),
                'received_by' => $users->first()->id,
                'status' => 'available',
                'remarks' => 'New desktop for IT department',
                'floor_level' => 'First Floor',
                'room_number' => 'FF-201',
                'location' => 'IT Department',
                'assigned_to' => $users->first()->id,
                'condition' => 'Excellent',
                'is_approved' => true,
                'approved_by' => $users->first()->id,
                'approved_at' => now()->subDays(25),
                'image' => 'items/processed/sample_desktop.webp',
            ],
            [
                'name' => 'Herman Miller Aeron Chair',
                'serial_number' => 'HM-002',
                'asset_tag' => 'AT-002',
                'barcode' => 'BC-002',
                'rfid_tag' => 'RF-002',
                'description' => 'Herman Miller Aeron Ergonomic Office Chair',
                'specifications' => json_encode([
                    'Material' => 'Mesh',
                    'Color' => 'Graphite',
                    'Size' => 'Medium',
                    'Features' => 'Lumbar Support, Adjustable Arms'
                ]),
                'asset_type' => 'fixed',
                'value' => 800.00,
                'depreciation_cost' => 50.00,
                'purchased_by' => $users->first()->id,
                'supplier_id' => $suppliers->where('name', 'OfficeMax Furniture Co.')->first()->id,
                'category_id' => $categories->where('name', 'Office Furniture')->first()->id,
                'purchase_date' => now()->subDays(45),
                'received_by' => $users->first()->id,
                'status' => 'available',
                'remarks' => 'Ergonomic chair for executive office',
                'floor_level' => 'Fourth Floor',
                'room_number' => 'FF-502',
                'location' => 'Executive Office',
                'assigned_to' => $users->where('role', 'super_admin')->first()->id,
                'condition' => 'Excellent',
                'is_approved' => true,
                'approved_by' => $users->first()->id,
                'approved_at' => now()->subDays(40),
                'image' => 'items/processed/sample_chair.webp',
            ],
            [
                'name' => 'Cisco IP Phone 8851',
                'serial_number' => 'CISCO-003',
                'asset_tag' => 'AT-003',
                'barcode' => 'BC-003',
                'rfid_tag' => 'RF-003',
                'description' => 'Cisco IP Phone 8851 with Color Display',
                'specifications' => json_encode([
                    'Type' => 'IP Phone',
                    'Display' => '5-inch Color Touchscreen',
                    'Connectivity' => 'Ethernet, WiFi',
                    'Features' => 'VoIP, Conference Calling'
                ]),
                'asset_type' => 'fixed',
                'value' => 300.00,
                'depreciation_cost' => 25.00,
                'purchased_by' => $users->first()->id,
                'supplier_id' => $suppliers->where('name', 'CommTech Communications')->first()->id,
                'category_id' => $categories->where('name', 'Communication Devices')->first()->id,
                'purchase_date' => now()->subDays(20),
                'received_by' => $users->first()->id,
                'status' => 'available',
                'remarks' => 'IP phone for conference room',
                'floor_level' => 'Third Floor',
                'room_number' => 'TF-403',
                'location' => 'Conference Room A',
                'assigned_to' => null,
                'condition' => 'Good',
                'is_approved' => true,
                'approved_by' => $users->first()->id,
                'approved_at' => now()->subDays(15),
                'image' => 'items/processed/sample_phone.webp',
            ],
            [
                'name' => 'HP LaserJet Pro Printer',
                'serial_number' => 'HP-004',
                'asset_tag' => 'AT-004',
                'barcode' => 'BC-004',
                'rfid_tag' => 'RF-004',
                'description' => 'HP LaserJet Pro 4301fdw Wireless Printer',
                'specifications' => json_encode([
                    'Type' => 'Laser Printer',
                    'Print Speed' => '42 ppm',
                    'Connectivity' => 'WiFi, Ethernet, USB',
                    'Features' => 'Print, Scan, Copy, Fax'
                ]),
                'asset_type' => 'fixed',
                'value' => 400.00,
                'depreciation_cost' => 30.00,
                'purchased_by' => $users->first()->id,
                'supplier_id' => $suppliers->where('name', 'SupplyPro Office Solutions')->first()->id,
                'category_id' => $categories->where('name', 'Office Supplies')->first()->id,
                'purchase_date' => now()->subDays(60),
                'received_by' => $users->first()->id,
                'status' => 'available',
                'remarks' => 'Multi-function printer for office use',
                'floor_level' => 'Second Floor',
                'room_number' => 'SF-302',
                'location' => 'Finance Department',
                'assigned_to' => $users->where('role', 'asset_manager')->first()->id,
                'condition' => 'Good',
                'is_approved' => true,
                'approved_by' => $users->first()->id,
                'approved_at' => now()->subDays(55),
                'image' => 'items/processed/sample_printer.webp',
            ],
            [
                'name' => 'Axis Security Camera',
                'serial_number' => 'AXIS-005',
                'asset_tag' => 'AT-005',
                'barcode' => 'BC-005',
                'rfid_tag' => 'RF-005',
                'description' => 'Axis M3045-V Network Camera',
                'specifications' => json_encode([
                    'Type' => 'IP Security Camera',
                    'Resolution' => '4MP',
                    'Lens' => '2.8mm Fixed',
                    'Features' => 'Night Vision, Motion Detection, Weather Resistant'
                ]),
                'asset_type' => 'fixed',
                'value' => 250.00,
                'depreciation_cost' => 20.00,
                'purchased_by' => $users->first()->id,
                'supplier_id' => $suppliers->where('name', 'SecureGuard Systems')->first()->id,
                'category_id' => $categories->where('name', 'Security Equipment')->first()->id,
                'purchase_date' => now()->subDays(90),
                'received_by' => $users->first()->id,
                'status' => 'available',
                'remarks' => 'Security camera for main entrance',
                'floor_level' => 'Ground Floor',
                'room_number' => 'GF-101',
                'location' => 'Main Reception',
                'assigned_to' => null,
                'condition' => 'Good',
                'is_approved' => true,
                'approved_by' => $users->first()->id,
                'approved_at' => now()->subDays(85),
                'image' => 'items/processed/sample_camera.webp',
            ]
        ];
        foreach ($items as $item) {
            Item::create($item);
        }

        $this->command->info('Created 5 corporate borrowers and 5 corporate items successfully!');
    }
} 