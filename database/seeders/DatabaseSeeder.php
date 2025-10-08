<?php

namespace Database\Seeders;

use App\Models\Borrower;
use App\Models\Category;
use App\Models\Department;
use App\Models\Item;
use App\Models\Supplier;
use Database\Factories\BorrowerFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Run seeders in correct order to maintain foreign key relationships
        $this->call([
            SuperAdminSeeder::class,           // Create super admin first
            DepartmentSeeder::class,           // Create departments
            FloorRoomSeeder::class,            // Create floors and rooms
            CategorySeeder::class,             // Create asset categories
            SupplierSeeder::class,             // Create suppliers
            UserSeeder::class,                 // Create corporate users
            DesignationSeeder::class,          // Create designations (depends on departments)
            DummyDataSeeder::class,            // Create sample items and borrowers
            PurchaseSeeder::class,             // Create sample purchases
        ]);
    }
}
