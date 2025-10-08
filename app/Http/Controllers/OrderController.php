<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $guest = auth('guest')->user();

        $menus = Menu::where('is_out_of_stock', false)->get();

        return Inertia::render('Guests/Menu', [
            'guest' => $guest,
            'menus' => $menus,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'orders' => 'required|array|min:1',
            'orders.*.menu_id' => 'required|exists:menus,id',
            'orders.*.quantity' => 'required|integer|min:1',
        ]);

        $guest = auth('guest')->user();

        // Create the main order
        $order = Order::create([
            'guest_id' => $guest->id,
            'status' => 'pending',
        ]);

        // Attach order items
        foreach ($request->orders as $item) {
            $order->orderItems()->create([
                'menu_id' => $item['menu_id'],
                'quantity' => $item['quantity'],
            ]);
        }

        return redirect()->back()->with('success', 'Your order has been placed successfully!');
    }

    public function guestOrders()
    {
        $guest = auth('guest')->user();

        $orders = Order::with('orderItems.menu')
            ->where('guest_id', $guest->id)
            ->latest()
            ->get();

        return Inertia::render('Guests/Orders', [
            'guest' => $guest,
            'orders' => $orders,
        ]);
    }

    public function cancel(Order $order)
    {
        $guest = auth('guest')->user();

        if ($order->guest_id !== $guest->id) {
            abort(403, 'You cannot delete this order.');
        }
        $order->delete();

        return redirect()->back()->with('success', 'Order deleted successfully!');
    }
}
