<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        // Create exactly 5 corporate departments
        $departments = [
            [
                'name' => 'Information Technology',
                'location' => 'Level 1'
            ],
            [
                'name' => 'Human Resources',
                'location' => 'Level 2'
            ],
            [
                'name' => 'Finance & Accounting',
                'location' => 'Level 3'
            ],
            [
                'name' => 'Marketing & Sales',
                'location' => 'Level 4'
            ],
            [
                'name' => 'Operations Management',
                'location' => 'Level 5'
            ]
        ];

        foreach ($departments as $departmentData) {
            Department::create($departmentData);
        }

        $this->command->info('Created 5 corporate departments successfully!');
    }
}
