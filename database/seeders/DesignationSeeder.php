<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;
use App\Models\Department;

class DesignationSeeder extends Seeder
{
    public function run()
    {
        // Get all departments
        $departments = Department::all();
        
        if ($departments->isEmpty()) {
            $this->command->info('No departments found. Please run DepartmentSeeder first.');
            return;
        }

        // Corporate designations - exactly 5 per department
        $designations = [
            'Information Technology' => [
                'IT Manager',
                'Senior Software Developer',
                'System Administrator',
                'IT Support Specialist',
                'Database Administrator'
            ],
            'Human Resources' => [
                'HR Manager',
                'HR Generalist',
                'Recruitment Specialist',
                'Training Coordinator',
                'Payroll Administrator'
            ],
            'Finance & Accounting' => [
                'Finance Manager',
                'Senior Accountant',
                'Financial Analyst',
                'Accounts Payable Clerk',
                'Budget Analyst'
            ],
            'Marketing & Sales' => [
                'Marketing Manager',
                'Sales Manager',
                'Marketing Specialist',
                'Account Manager',
                'Business Development Manager'
            ],
            'Operations Management' => [
                'Operations Manager',
                'Operations Coordinator',
                'Quality Assurance Manager',
                'Supply Chain Coordinator',
                'Process Analyst'
            ]
        ];

        $createdCount = 0;

        foreach ($departments as $department) {
            $deptName = $department->name;
            
            // Get designations for this specific department
            $deptDesignations = $designations[$deptName] ?? [
                'Manager',
                'Senior Specialist',
                'Specialist',
                'Coordinator',
                'Assistant'
            ];
            
            // Create exactly 5 designations for this department
            foreach ($deptDesignations as $designationName) {
                Designation::create([
                    'name' => $designationName,
                    'department_id' => $department->id,
                ]);
                $createdCount++;
            }
        }

        $this->command->info("Created {$createdCount} designations for {$departments->count()} departments.");
    }
}



