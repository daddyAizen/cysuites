<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::create([
            'room_name' => 'Room A1',
            'room_code' => 'A1X12',
            'is_booked' => false,
        ]);

        Room::create([
            'room_name' => 'Room B2',
            'room_code' => 'B2Y34',
            'is_booked' => false,
        ]);

        Room::create([
            'room_name' => 'Room C3',
            'room_code' => 'C3Z56',
            'is_booked' => true,
        ]);
    }
}
