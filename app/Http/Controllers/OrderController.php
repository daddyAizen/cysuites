<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Discount;
use Inertia\Inertia;
use App\Notifications\OrderStatusUpdated;

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

   public function guestMenu()
{
    $guest = auth('guest')->user();

    if (!$guest) {
        return redirect()->route('guests.login');
    }

    $menus = Menu::all();

    $user = auth()->user();
    if ($user) {
        $user->load('discount');
    }

    return Inertia::render('Guests/Menu', [
        'guest' => $guest,
        'menus' => $menus,
        'user' => $user,
    ]);
}


public function store(Request $request)
{
    $user = auth()->user()->load('discount');
    $guest = auth('guest')->user();


    $userId = $user ? $user->id : null;
    $guestId = $guest ? $guest->id : null;

    $validated = $request->validate([
        'orders' => 'required|array|min:1',
        'orders.*.menu_id' => 'required|exists:menus,id',
        'orders.*.quantity' => 'required|integer|min:1',
    ]);

    $order = Order::create([
        'guest_id' => $guestId,
        'user_id' => $guestId ? null : $userId,
        'status' => 'pending',
        'total' => 0,
    ]);

    $total = 0;

foreach ($validated['orders'] as $item) {
    $menu = Menu::find($item['menu_id']);
    if ($menu) {
        $subtotal = $menu->price * $item['quantity'];
        $total += $subtotal;

        $order->orderItems()->create([
            'menu_id' => $menu->id,
            'quantity' => $item['quantity'],
        ]);
    }
}

$discountAmount = 0;
if ($user && $user->discount) {
    $discount = $user->discount->percentage;
    $discountAmount = ($discount / 100) * $total;
    $total -= $discountAmount;
}

$order->update(['total' => $total]);

    if ($order->user) {
        $order->user->notify(new OrderStatusUpdated($order, 'none', 'pending'));
    } elseif ($order->guest) {
        $order->guest->notify(new OrderStatusUpdated($order, 'none', 'pending'));
    }

    return back()->with('success', '✅ Order placed successfully!');
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
        if (!$menu) {
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
        $oldStatus = $order->status;

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

        if ($order->user) {
            $order->user->notify(new OrderStatusUpdated($order, $oldStatus, $order->status));
        } elseif ($order->guest) {
            $order->guest->notify(new OrderStatusUpdated($order, $oldStatus, $order->status));
        }

        return redirect()->back()->with('success', "Order status updated to {$order->status}, and email sent.");
    }
}
