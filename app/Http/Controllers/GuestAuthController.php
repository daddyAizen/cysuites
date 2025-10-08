<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class GuestAuthController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Auth/GuestLogin');
    }

 public function login(Request $request)
{
    $request->validate([
        'room_name' => 'required|string',
        'room_code' => 'required|string',
    ]);

    $room = Room::where('room_name', $request->room_name)->first();

    if (! $room) {
        return back()->withErrors(['room_name' => 'Room not found.']);
    }

    if ($room->room_code !== $request->room_code) {
        return back()->withErrors(['room_code' => 'Invalid room code.']);
    }

    $guest = Guest::where('room_id', $room->id)->first();

    if (! $guest) {
        return back()->withErrors(['room_name' => 'No guest found for this room.']);
    }

    Auth::guard('guest')->login($guest);

    return redirect()->route('guests.dashboard');
}

    public function logout(Request $request)
    {
        Auth::guard('guest')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('guests.login.form');
    }
}
