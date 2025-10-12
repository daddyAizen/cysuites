<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Room;

class RefreshRoomCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rooms:refresh-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the room_code every 5 minutes for rooms that are not booked';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $rooms = Room::where('is_booked', 0)->get();

        foreach ($rooms as $room) {
            do {
                $newCode = 'RM' . strtoupper(str()->random(5));
            } while (Room::where('room_code', $newCode)->exists());

            $room->update(['room_code' => $newCode]);
        }

        $this->info('Room codes refreshed for all unbooked rooms.');
    }
}
