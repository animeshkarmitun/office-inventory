<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Create exactly 5 corporate asset categories
        $categories = [
            [
                'name' => 'Computer Equipment'
            ],
            [
                'name' => 'Office Furniture'
            ],
            [
                'name' => 'Communication Devices'
            ],
            [
                'name' => 'Office Supplies'
            ],
            [
                'name' => 'Security Equipment'
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        $this->command->info('Created 5 corporate asset categories successfully!');
    }
}
