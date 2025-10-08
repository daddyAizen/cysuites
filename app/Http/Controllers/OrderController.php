<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Menu;
use App\Models\Guest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $guest = auth()->user();
        $menu = Menu::all();

        return Inertia::render('Guest/Menu', [
            'guest' => $guest,
            'menu' => $menu,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.menu_id' => 'required|exists:menus,id',
            'orders.*.quantity' => 'required|integer|min:1',
        ]);

        $guest = auth()->user();

        foreach ($request->orders as $orderItem) {
            Order::create([
                'guest_id' => $guest->id,
                'menu_id' => $orderItem['menu_id'],
                'quantity' => $orderItem['quantity'],
                'status' => 'pending',
            ]);
        }

        return redirect()->back()->with('success', 'Your order has been placed successfully!');
    }
}
