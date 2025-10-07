<?php

namespace App\Http\Controllers;

use App\Models\Room;
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
}
