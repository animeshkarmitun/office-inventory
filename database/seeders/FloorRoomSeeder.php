<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;
use App\Models\Room;

class FloorRoomSeeder extends Seeder
{
    public function run()
    {
        // Create exactly 5 corporate floors
        $floors = [
            [
                'name' => 'Ground Floor',
                'description' => 'Main entrance, reception, and security area',
            ],
            [
                'name' => 'First Floor',
                'description' => 'IT Department and server rooms',
            ],
            [
                'name' => 'Second Floor',
                'description' => 'Human Resources and Finance departments',
            ],
            [
                'name' => 'Third Floor',
                'description' => 'Marketing, Sales, and meeting rooms',
            ],
            [
                'name' => 'Fourth Floor',
                'description' => 'Operations Management and executive offices',
            ],
        ];

        foreach ($floors as $floorData) {
            $floorData['serial_number'] = Floor::generateSerialNumber();
            $floor = Floor::create($floorData);

            // Create exactly 5 rooms for each floor
            $rooms = [];
            
            if ($floor->name === 'Ground Floor') {
                $rooms = [
                    ['name' => 'Main Reception', 'room_number' => 'GF-101', 'status' => 'active', 'description' => 'Main reception and visitor area'],
                    ['name' => 'Security Office', 'room_number' => 'GF-102', 'status' => 'active', 'description' => 'Security personnel office'],
                    ['name' => 'Storage Room', 'room_number' => 'GF-103', 'status' => 'active', 'description' => 'General storage and supplies'],
                    ['name' => 'Cafeteria', 'room_number' => 'GF-104', 'status' => 'active', 'description' => 'Employee cafeteria and dining area'],
                    ['name' => 'Parking Office', 'room_number' => 'GF-105', 'status' => 'active', 'description' => 'Parking management office'],
                ];
            } elseif ($floor->name === 'First Floor') {
                $rooms = [
                    ['name' => 'IT Department', 'room_number' => 'FF-201', 'status' => 'active', 'description' => 'IT department main office'],
                    ['name' => 'Server Room', 'room_number' => 'FF-202', 'status' => 'active', 'description' => 'Main server room and data center'],
                    ['name' => 'IT Support', 'room_number' => 'FF-203', 'status' => 'active', 'description' => 'IT support and help desk'],
                    ['name' => 'Network Room', 'room_number' => 'FF-204', 'status' => 'active', 'description' => 'Network equipment and communications'],
                    ['name' => 'IT Storage', 'room_number' => 'FF-205', 'status' => 'active', 'description' => 'IT equipment storage room'],
                ];
            } elseif ($floor->name === 'Second Floor') {
                $rooms = [
                    ['name' => 'HR Department', 'room_number' => 'SF-301', 'status' => 'active', 'description' => 'Human Resources department office'],
                    ['name' => 'Finance Department', 'room_number' => 'SF-302', 'status' => 'active', 'description' => 'Finance and Accounting office'],
                    ['name' => 'Payroll Office', 'room_number' => 'SF-303', 'status' => 'active', 'description' => 'Payroll processing office'],
                    ['name' => 'Training Room', 'room_number' => 'SF-304', 'status' => 'active', 'description' => 'Employee training and development room'],
                    ['name' => 'Records Room', 'room_number' => 'SF-305', 'status' => 'active', 'description' => 'Employee records and documentation'],
                ];
            } elseif ($floor->name === 'Third Floor') {
                $rooms = [
                    ['name' => 'Marketing Office', 'room_number' => 'TF-401', 'status' => 'active', 'description' => 'Marketing department office'],
                    ['name' => 'Sales Office', 'room_number' => 'TF-402', 'status' => 'active', 'description' => 'Sales department office'],
                    ['name' => 'Conference Room A', 'room_number' => 'TF-403', 'status' => 'active', 'description' => 'Large conference room for meetings'],
                    ['name' => 'Conference Room B', 'room_number' => 'TF-404', 'status' => 'active', 'description' => 'Medium conference room'],
                    ['name' => 'Client Meeting Room', 'room_number' => 'TF-405', 'status' => 'active', 'description' => 'Client presentation and meeting room'],
                ];
            } else { // Fourth Floor
                $rooms = [
                    ['name' => 'Operations Office', 'room_number' => 'FF-501', 'status' => 'active', 'description' => 'Operations Management office'],
                    ['name' => 'Executive Office', 'room_number' => 'FF-502', 'status' => 'active', 'description' => 'Executive and senior management office'],
                    ['name' => 'Board Room', 'room_number' => 'FF-503', 'status' => 'active', 'description' => 'Board of directors meeting room'],
                    ['name' => 'Executive Conference', 'room_number' => 'FF-504', 'status' => 'active', 'description' => 'Executive conference room'],
                    ['name' => 'Executive Storage', 'room_number' => 'FF-505', 'status' => 'active', 'description' => 'Executive storage and archives'],
                ];
            }

            foreach ($rooms as $roomData) {
                $roomData['floor_id'] = $floor->id;
                Room::create($roomData);
            }
        }

        $this->command->info('Created 5 corporate floors with 25 rooms successfully!');
    }
}
