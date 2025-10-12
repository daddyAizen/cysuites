<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Guest;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', '7days'); // default filter

        // Pending Orders count
        $pendingOrdersQuery = Order::where('status', 'pending');
        $totalOrdersQuery = Order::query();

        if ($filter === '7days') {
            $pendingOrdersQuery->where('created_at', '>=', Carbon::now()->subDays(7));
            $totalOrdersQuery->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($filter === '30days') {
            $pendingOrdersQuery->where('created_at', '>=', Carbon::now()->subDays(30));
            $totalOrdersQuery->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        $pendingOrders = $pendingOrdersQuery->count();
        $totalOrders = $totalOrdersQuery->count();

        // Total Guests and Menu Items (always total)
        $totalGuests = Guest::count();
        $totalMenuItems = Menu::count();

        // Orders Over Time (for line chart)
        $ordersOverTimeQuery = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        );

        if ($filter === '7days') {
            $ordersOverTimeQuery->where('created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($filter === '30days') {
            $ordersOverTimeQuery->where('created_at', '>=', Carbon::now()->subDays(30));
        }

        $ordersOverTime = $ordersOverTimeQuery
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Selling Items (bar chart)
        $topSellingQuery = DB::table('order_items')
            ->join('menus', 'order_items.menu_id', '=', 'menus.id')
            ->select('menus.name', DB::raw('SUM(order_items.quantity) as quantity'));

        if ($filter === '7days') {
            $topSellingQuery->where('order_items.created_at', '>=', Carbon::now()->subDays(7));
        } elseif ($filter === '30days') {
            $topSellingQuery->where('order_items.created_at', '>=', Carbon::now()->subDays(30));
        }

        $topSellingItems = $topSellingQuery
            ->groupBy('menus.name')
            ->orderByDesc('quantity')
            ->limit(10)
            ->get();

        return Inertia::render('Dashboard', [
            'pendingOrders'   => $pendingOrders,
            'totalOrders'     => $totalOrders,
            'totalGuests'     => $totalGuests,
            'totalMenuItems'  => $totalMenuItems,
            'ordersOverTime'  => $ordersOverTime,
            'topSellingItems' => $topSellingItems,
        ]);
    }
}
