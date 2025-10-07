<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    // Show all reservations
    public function index()
    {
        $reservations = Reservation::with(['guest', 'table'])->latest()->get();
        return Inertia::render('Reservations/Index', compact('reservations'));
    }

    // Store new reservation
    public function store(Request $request)
    {
        $data = $request->validate([
            'guest_id' => 'required|exists:guests,id', // linked to guests
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);

        Reservation::create($data);

        return redirect()->back()->with('success', 'Reservation created successfully.');
    }

    // Update reservation
    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation->update($data);

        return redirect()->back()->with('success', 'Reservation updated successfully.');
    }

    // Delete reservation
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return redirect()->back()->with('success', 'Reservation deleted successfully.');
    }
}
