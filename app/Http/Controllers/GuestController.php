<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Room;
use App\Models\GuestHistory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuestController extends Controller
{
    public function index()
    {
        $guests = Guest::with('room')->get();
        $rooms = Room::where('is_booked', false)->get();

        return Inertia::render('Guests/Index', [
            'guests' => $guests,
            'rooms' => $rooms,
        ]);
    }

 public function store(Request $request)
{
    $validated = $request->validate([
        'name'      => 'required|string|max:255',
        'email'     => 'required|email|unique:guests,email',
        'phone'     => 'required|string|max:20',
        'room_id'   => 'required|exists:rooms,id',
    ]);

    $room = Room::findOrFail($validated['room_id']);
    $validated['room_code'] = $room->room_code;

    $guest = Guest::create($validated);

    GuestHistory::create([
        'name'         => $guest->name,
        'email'        => $guest->email,
        'phone'        => $guest->phone,
        'room_id'      => $guest->room_id,
        'room_code'    => $guest->room_code,
        'checked_in_at'=> now(),
    ]);

    $room->update(['is_booked' => true]);

    return redirect()->back()->with('success', 'Guest added successfully.');
}

    public function checkout($id)
{
    $guest = Guest::findOrFail($id);

    GuestHistory::where('email', $guest->email)
        ->latest()
        ->first()
        ?->update(['checked_out_at' => now()]);

    $room = $guest->room;
    $room->is_booked = 0;
    $room->save();

    $guest->delete();

    return redirect()->back()->with('success', 'Guest checked out, room is now available.');
}

}
