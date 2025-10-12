<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderItems.menu', 'guest', 'user'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $userId = auth()->check() ? auth()->id() : null;
        $guestId = auth('guest')->check() ? auth('guest')->id() : null;

        $order = Order::create([
            'guest_id' => $guestId ?? $request->guest_id ?? null,
            'user_id' => $userId ?? $request->user_id ?? null,
            'status' => 'pending',
            'total' => 0, // initial total, will recalc when items are added
        ]);

        // If there are any items sent along with creation, add them and recalc total
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $itemData) {
                $menu = Menu::find($itemData['menu_id'] ?? 0);
                if ($menu) {
                    $order->orderItems()->create([
                        'menu_id' => $menu->id,
                        'quantity' => $itemData['quantity'] ?? 1,
                    ]);
                }
            }

            $order->updateTotal();
        }

        return redirect()->back()->with('success', 'Order created successfully.');
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

    public function addItem(Request $request, Order $order)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $menu = Menu::find($validated['menu_id']);
        if (! $menu) {
            return redirect()->back()->with('error', 'Menu item not found.');
        }

        $order->orderItems()->create([
            'menu_id' => $menu->id,
            'quantity' => $validated['quantity'],
        ]);

        return redirect()->back()->with('success', 'Item added successfully.');
    }

    public function removeItem(Order $order, OrderItem $item)
    {
        $item->delete();

        return redirect()->back()->with('success', 'Item removed successfully.');
    }

    public function updateStatus(Order $order)
    {
        switch ($order->status) {
            case 'pending':
                $order->status = 'approved';
                break;
            case 'approved':
                $order->status = 'accepted';
                break;
            case 'accepted':
                return redirect()->back()->with('info', 'Order already accepted.');
        }

        $order->save();

        return redirect()->back()->with('success', "Order status updated to {$order->status}.");
    }
}
