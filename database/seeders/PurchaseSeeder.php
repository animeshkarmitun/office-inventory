<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run()
    {
        // Get existing data for relationships
        $suppliers = Supplier::all();
        $departments = Department::all();
        $users = User::all();

        if ($suppliers->isEmpty() || $departments->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Required data (suppliers, departments, users) not found. Please run other seeders first.');
            return;
        }

        // Sample purchase data
        $purchases = [
            [
                'supplier' => $suppliers->where('name', 'Tech Solutions Inc')->first() ?? $suppliers->first(),
                'invoice_number' => 'INV-2024-001',
                'purchase_date' => Carbon::now()->subDays(30),
                'purchased_by' => $users->where('role', 'asset_manager')->first() ?? $users->first(),
                'received_by' => $users->where('role', 'employee')->first() ?? $users->first(),
                'department' => $departments->where('name', 'Information Technology')->first() ?? $departments->first(),
                'items' => [
                    [
                        'item_name' => 'Dell OptiPlex 7090 Desktop',
                        'quantity' => 5,
                        'unit_price' => 899.99,
                        'item_type' => 'Computer Hardware'
                    ],
                    [
                        'item_name' => 'Dell 24" Monitor',
                        'quantity' => 5,
                        'unit_price' => 199.99,
                        'item_type' => 'Computer Hardware'
                    ],
                    [
                        'item_name' => 'Logitech Wireless Mouse',
                        'quantity' => 10,
                        'unit_price' => 29.99,
                        'item_type' => 'Computer Accessories'
                    ]
                ]
            ],
            [
                'supplier' => $suppliers->where('name', 'Office Furniture Co')->first() ?? $suppliers->first(),
                'invoice_number' => 'INV-2024-002',
                'purchase_date' => Carbon::now()->subDays(25),
                'purchased_by' => $users->where('role', 'asset_manager')->first() ?? $users->first(),
                'received_by' => $users->where('role', 'employee')->first() ?? $users->first(),
                'department' => $departments->where('name', 'Human Resources')->first() ?? $departments->first(),
                'items' => [
                    [
                        'item_name' => 'Ergonomic Office Chair',
                        'quantity' => 8,
                        'unit_price' => 249.99,
                        'item_type' => 'Furniture'
                    ],
                    [
                        'item_name' => 'Adjustable Standing Desk',
                        'quantity' => 4,
                        'unit_price' => 599.99,
                        'item_type' => 'Furniture'
                    ]
                ]
            ],
            [
                'supplier' => $suppliers->where('name', 'Tech Solutions Inc')->first() ?? $suppliers->first(),
                'invoice_number' => null, // Testing nullable invoice number
                'purchase_date' => Carbon::now()->subDays(20),
                'purchased_by' => $users->where('role', 'asset_manager')->first() ?? $users->first(),
                'received_by' => $users->where('role', 'employee')->first() ?? $users->first(),
                'department' => $departments->where('name', 'Finance & Accounting')->first() ?? $departments->first(),
                'items' => [
                    [
                        'item_name' => 'HP LaserJet Pro Printer',
                        'quantity' => 2,
                        'unit_price' => 299.99,
                        'item_type' => 'Office Equipment'
                    ],
                    [
                        'item_name' => 'Printer Paper (A4)',
                        'quantity' => 20,
                        'unit_price' => 8.99,
                        'item_type' => 'Office Supplies'
                    ]
                ]
            ],
            [
                'supplier' => $suppliers->where('name', 'Office Furniture Co')->first() ?? $suppliers->first(),
                'invoice_number' => 'INV-2024-003',
                'purchase_date' => Carbon::now()->subDays(15),
                'purchased_by' => $users->where('role', 'asset_manager')->first() ?? $users->first(),
                'received_by' => $users->where('role', 'employee')->first() ?? $users->first(),
                'department' => $departments->where('name', 'Marketing & Sales')->first() ?? $departments->first(),
                'items' => [
                    [
                        'item_name' => 'Conference Table (8-seater)',
                        'quantity' => 1,
                        'unit_price' => 1299.99,
                        'item_type' => 'Furniture'
                    ],
                    [
                        'item_name' => 'Office Chairs (Conference)',
                        'quantity' => 8,
                        'unit_price' => 199.99,
                        'item_type' => 'Furniture'
                    ]
                ]
            ],
            [
                'supplier' => $suppliers->where('name', 'Tech Solutions Inc')->first() ?? $suppliers->first(),
                'invoice_number' => 'INV-2024-004',
                'purchase_date' => Carbon::now()->subDays(10),
                'purchased_by' => $users->where('role', 'asset_manager')->first() ?? $users->first(),
                'received_by' => $users->where('role', 'employee')->first() ?? $users->first(),
                'department' => $departments->where('name', 'Information Technology')->first() ?? $departments->first(),
                'items' => [
                    [
                        'item_name' => 'Cisco Network Switch 24-Port',
                        'quantity' => 2,
                        'unit_price' => 399.99,
                        'item_type' => 'Network Equipment'
                    ],
                    [
                        'item_name' => 'Cat6 Ethernet Cable (100ft)',
                        'quantity' => 10,
                        'unit_price' => 25.99,
                        'item_type' => 'Network Accessories'
                    ],
                    [
                        'item_name' => 'WiFi Access Point',
                        'quantity' => 4,
                        'unit_price' => 149.99,
                        'item_type' => 'Network Equipment'
                    ]
                ]
            ],
            [
                'supplier' => $suppliers->where('name', 'Office Furniture Co')->first() ?? $suppliers->first(),
                'invoice_number' => 'INV-2024-005',
                'purchase_date' => Carbon::now()->subDays(5),
                'purchased_by' => $users->where('role', 'asset_manager')->first() ?? $users->first(),
                'received_by' => $users->where('role', 'employee')->first() ?? $users->first(),
                'department' => $departments->where('name', 'Operations')->first() ?? $departments->first(),
                'items' => [
                    [
                        'item_name' => 'Filing Cabinet (4-drawer)',
                        'quantity' => 3,
                        'unit_price' => 189.99,
                        'item_type' => 'Furniture'
                    ],
                    [
                        'item_name' => 'Storage Boxes (Set of 5)',
                        'quantity' => 6,
                        'unit_price' => 24.99,
                        'item_type' => 'Office Supplies'
                    ]
                ]
            ]
        ];

        foreach ($purchases as $purchaseData) {
            // Calculate total value
            $totalValue = collect($purchaseData['items'])->sum(function($item) {
                return $item['quantity'] * $item['unit_price'];
            });

            // Create purchase
            $purchase = Purchase::create([
                'supplier_id' => $purchaseData['supplier']->id,
                'invoice_number' => $purchaseData['invoice_number'],
                'purchase_date' => $purchaseData['purchase_date'],
                'total_value' => $totalValue,
                'purchased_by' => $purchaseData['purchased_by']->id,
                'received_by' => $purchaseData['received_by']->id,
                'department_id' => $purchaseData['department']->id,
                'purchase_number' => Purchase::generatePurchaseNumber(),
            ]);

            // Create purchase items
            foreach ($purchaseData['items'] as $itemData) {
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'item_name' => $itemData['item_name'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'item_type' => $itemData['item_type'],
                ]);
            }

            $this->command->info("Created purchase: {$purchase->purchase_number} - Total: $" . number_format($totalValue, 2));
        }

        $this->command->info('Purchase seeder completed successfully!');
    }
}