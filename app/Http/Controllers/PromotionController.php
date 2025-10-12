<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use App\Models\User;
use App\Models\Guest;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Notifications\PromotionCreated;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->get();

        return Inertia::render('Promotions/Index', [
            'promotions' => $promotions,
        ]);
    }

public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $promotion = Promotion::create($validated);

        $users  = User::all();
        $guests = Guest::all();

        foreach ($users as $user) {
            if ($user->email) {
                $user->notify(new PromotionCreated($promotion));
            }
        }

        foreach ($guests as $guest) {
            if ($guest->email) {
                $guest->notify(new PromotionCreated($promotion));
            }
        }

        return back()->with('success', 'Promotion created and emails sent to all users & guests!');
    }

}
