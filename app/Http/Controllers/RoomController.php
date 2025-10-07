<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
{
    /**
     * Display all rooms
     */
    public function index()
    {
        $rooms = Room::all();

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_name' => 'required|string|max:255|unique:rooms,room_name',
        ]);

        do {
            $roomCode = 'RM'.strtoupper(str()->random(5));
        } while (Room::where('room_code', $roomCode)->exists());

        Room::create([
            'room_name' => $validated['room_name'],
            'room_code' => $roomCode,
            'is_booked' => 0,
        ]);

        return redirect()->back()->with('success', 'Room added successfully.');
    }

    public function toggle(Room $room)
    {
        $room->is_booked = !$room->is_booked;
        $room->save();

        return redirect()->back()->with('success', 'Room status updated.');
    }
}
