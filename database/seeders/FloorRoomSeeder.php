<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Floor;
use App\Models\Room;

class FloorRoomSeeder extends Seeder
{
    public function run()
    {
        // Create sample floors
        $floors = [
            [
                'name' => 'Ground Floor',
                'description' => 'Main entrance and reception area',
            ],
            [
                'name' => 'First Floor',
                'description' => 'Office spaces and meeting rooms',
            ],
            [
                'name' => 'Second Floor',
                'description' => 'Department offices and conference rooms',
            ],
        ];

        foreach ($floors as $floorData) {
            $floorData['serial_number'] = Floor::generateSerialNumber();
            $floor = Floor::create($floorData);

            // Create sample rooms for each floor
            if ($floor->name === 'Ground Floor') {
                $rooms = [
                    ['name' => 'Reception', 'room_number' => 'GF-101', 'status' => 'active', 'description' => 'Main reception area'],
                    ['name' => 'Security Office', 'room_number' => 'GF-102', 'status' => 'active', 'description' => 'Security personnel office'],
                    ['name' => 'Storage Room', 'room_number' => 'GF-103', 'status' => 'active', 'description' => 'General storage area'],
                ];
            } elseif ($floor->name === 'First Floor') {
                $rooms = [
                    ['name' => 'Conference Room A', 'room_number' => 'FF-201', 'status' => 'active', 'description' => 'Large conference room'],
                    ['name' => 'Conference Room B', 'room_number' => 'FF-202', 'status' => 'active', 'description' => 'Medium conference room'],
                    ['name' => 'IT Office', 'room_number' => 'FF-203', 'status' => 'active', 'description' => 'IT department office'],
                    ['name' => 'Break Room', 'room_number' => 'FF-204', 'status' => 'active', 'description' => 'Employee break room'],
                ];
            } else {
                $rooms = [
                    ['name' => 'HR Office', 'room_number' => 'SF-301', 'status' => 'active', 'description' => 'Human Resources office'],
                    ['name' => 'Finance Office', 'room_number' => 'SF-302', 'status' => 'active', 'description' => 'Finance department office'],
                    ['name' => 'Meeting Room', 'room_number' => 'SF-303', 'status' => 'maintenance', 'description' => 'Under renovation'],
                    ['name' => 'Storage Room', 'room_number' => 'SF-304', 'status' => 'inactive', 'description' => 'Currently unused'],
                ];
            }

            foreach ($rooms as $roomData) {
                $roomData['floor_id'] = $floor->id;
                Room::create($roomData);
            }
        }

        $this->command->info('Sample floors and rooms created successfully!');
    }
}
