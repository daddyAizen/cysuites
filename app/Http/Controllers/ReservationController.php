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
        return Inertia::render('Reservations/Index', [
            'reservations' => Reservation::with(['guest', 'table'])->latest()->get(),
            'tables' => Table::all(),
            'guests' => Guest::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
        ]);

        Reservation::create($validated);

        return back()->with('success', 'Reservation created successfully.');
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'table_id' => 'required|exists:tables,id',
            'reservation_date' => 'required|date',
            'reservation_time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $reservation->update($validated);

        $table = $reservation->table;
        if ($validated['status'] === 'confirmed') {
            $table->status = 'unavailable';
        } elseif (in_array($validated['status'], ['cancelled', 'completed'])) {
            $table->status = 'available';
        }
        $table->save();

        return back()->with('success', 'Reservation updated successfully.');
    }

    public function guestView()
{
    $guest = auth('guest')->user();

    return Inertia::render('Guests/Reservation', [
        'guest' => $guest,
        'tables' => Table::all(),
        'reservations' => Reservation::with('table')
            ->where('guest_id', $guest->id)
            ->latest()
            ->get(),
    ]);
}

    public function destroy(Reservation $reservation)
    {
        $table = $reservation->table;
        if ($reservation->status === 'confirmed' && $table) {
            $table->status = 'available';
            $table->save();
        }

        $reservation->delete();

        return back()->with('success', 'Reservation deleted successfully.');
    }
}
