<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        // Create exactly 5 corporate suppliers
        $suppliers = [
            [
                'name' => 'TechCorp Solutions',
                'incharge_name' => 'John Smith',
                'contact_number' => '+1-555-0101',
                'email' => 'john.smith@techcorp.com',
                'address' => '123 Technology Drive, Silicon Valley, CA 94000',
                'notes' => 'Leading provider of computer equipment and IT solutions'
            ],
            [
                'name' => 'OfficeMax Furniture Co.',
                'incharge_name' => 'Sarah Johnson',
                'contact_number' => '+1-555-0102',
                'email' => 'sarah.johnson@officemax.com',
                'address' => '456 Business Avenue, New York, NY 10001',
                'notes' => 'Premium office furniture and workspace solutions'
            ],
            [
                'name' => 'CommTech Communications',
                'incharge_name' => 'Michael Brown',
                'contact_number' => '+1-555-0103',
                'email' => 'michael.brown@commtech.com',
                'address' => '789 Communication Street, Austin, TX 73301',
                'notes' => 'Specialized in communication devices and networking equipment'
            ],
            [
                'name' => 'SupplyPro Office Solutions',
                'incharge_name' => 'Emily Davis',
                'contact_number' => '+1-555-0104',
                'email' => 'emily.davis@supplypro.com',
                'address' => '321 Supply Chain Blvd, Chicago, IL 60601',
                'notes' => 'Comprehensive office supplies and stationery provider'
            ],
            [
                'name' => 'SecureGuard Systems',
                'incharge_name' => 'Robert Wilson',
                'contact_number' => '+1-555-0105',
                'email' => 'robert.wilson@secureguard.com',
                'address' => '654 Security Plaza, Miami, FL 33101',
                'notes' => 'Advanced security equipment and surveillance systems'
            ]
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::create($supplierData);
        }

        $this->command->info('Created 5 corporate suppliers successfully!');
    }
}
