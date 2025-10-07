<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guest;
use App\Models\Room;

class GuestSeeder extends Seeder
{
    public function run(): void
    {
        $room = Room::where('room_name', 'Room C3')->first();

        if ($room) {
            Guest::create([
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '0712345678',
                'room_id' => $room->id,
                'room_code' => $room->room_code,
                'check_in' => now()->subDay(),
                'check_out' => null,
            ]);
        }
    }
}
