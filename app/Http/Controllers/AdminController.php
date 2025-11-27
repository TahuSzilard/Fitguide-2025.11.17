<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
public function dashboard()
{
    $sales = Order::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
        ->groupBy('date')
        ->pluck('count', 'date');

    // Labels (Mon–Sun)
    $chartLabels = collect([
        now()->startOfWeek(),
        now()->startOfWeek()->addDays(1),
        now()->startOfWeek()->addDays(2),
        now()->startOfWeek()->addDays(3),
        now()->startOfWeek()->addDays(4),
        now()->startOfWeek()->addDays(5),
        now()->startOfWeek()->addDays(6),
    ])->map(fn($d) => $d->format('D'));

    // Values – ha egy nap nem szerepel, 0 legyen
    $chartValues = $chartLabels->map(function ($label) use ($sales) {
        $date = now()->startOfWeek()->modify($label)->format("Y-m-d");
        return $sales[$date] ?? 0;
    });

    return view('admin.dashboard', [
        'salesToday'     => Order::whereDate('created_at', today())->count(),
        'activeProducts' => Product::where('is_active', true)->count(),
        'outOfStock'     => Product::where('stock', '<=', 0)->count(),
        'activeUsers'    => User::count(),
        'recentOrders'   => Order::latest()->take(5)->get(),

        // 📊 Chart adatok
        'chartLabels' => $chartLabels,
        'chartValues' => $chartValues,
    ]);
}

}
