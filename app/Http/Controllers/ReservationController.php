<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Guest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['guest', 'table'])->latest()->get();
        $tables = Table::all();
        $guests = Guest::all();

        return Inertia::render('Reservations/Index', [
            'reservations' => $reservations,
            'tables' => $tables,
            'guests' => $guests,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);

        // When creating a reservation, table is assumed available
        $reservation = Reservation::create($data);

        return redirect()->back()->with('success', 'Reservation created successfully.');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $data = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation->update($data);

        // Handle table availability based on reservation status
        $table = Table::find($reservation->table_id);
        if ($data['status'] === 'confirmed') {
            $table->is_available = false; // mark table as unavailable
        } elseif (in_array($data['status'], ['cancelled', 'completed'])) {
            $table->is_available = true; // make table available again
        }
        $table->save();

        return redirect()->back()->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        // Make table available again if reservation was confirmed
        if ($reservation->status === 'confirmed') {
            $table = Table::find($reservation->table_id);
            if ($table) {
                $table->is_available = true;
                $table->save();
            }
        }

        $reservation->delete();

        return redirect()->back()->with('success', 'Reservation deleted successfully.');
    }
}
