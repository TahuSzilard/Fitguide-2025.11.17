<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
public function dashboard()
{
    // Heti eladások napi bontásban
    $sales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('date')
        ->pluck('total', 'date');

    // Label-ek: Hétfő → Vasárnap
    $weekDates = collect(range(0,6))->map(
        fn ($i) => now()->startOfWeek()->copy()->addDays($i)
    );

    $chartLabels = $weekDates->map(
        fn ($date) => $date->format('D')
    );

    $chartValues = $weekDates->map(
        fn ($date) => $sales[$date->format('Y-m-d')] ?? 0
    );

    return view('admin.dashboard', [
        'salesToday'     => Order::whereDate('created_at', today())->count(),
        'activeProducts' => Product::where('is_active', true)->count(),
        'outOfStock'     => Product::where('stock', '<=', 0)->count(),
        'activeUsers'    => User::count(),
        'recentOrders'   => Order::latest()->take(5)->get(),

        'chartLabels' => $chartLabels,
        'chartValues' => $chartValues,
    ]);
}

}
