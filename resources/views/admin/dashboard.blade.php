@extends('admin.layout')

@section('content')
<div class="p-6">

    <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-500 text-white rounded-xl p-6 shadow-md">
            <div class="text-4xl font-bold">{{ $salesToday }}</div>
            <div class="opacity-90">Sales Today</div>
        </div>

        <div class="bg-green-500 text-white rounded-xl p-6 shadow-md">
            <div class="text-4xl font-bold">{{ $activeProducts }}</div>
            <div class="opacity-90">Active Products</div>
        </div>

        <div class="bg-red-500 text-white rounded-xl p-6 shadow-md">
            <div class="text-4xl font-bold">{{ $outOfStock }}</div>
            <div class="opacity-90">Out of Stock</div>
        </div>

        <div class="bg-yellow-500 text-white rounded-xl p-6 shadow-md">
            <div class="text-4xl font-bold">{{ $activeUsers }}</div>
            <div class="opacity-90">Active Users</div>
        </div>
    </div>

    {{-- ROW: CHART + RECENT ORDERS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- SALES CHART --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Sales This Week</h2>

            <canvas id="salesChart" height="140"></canvas>
        </div>

        {{-- RECENT ORDERS --}}
        <div class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Recent Orders</h2>

            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="py-2 font-medium">Order</th>
                        <th class="py-2 font-medium">Customer</th>
                        <th class="py-2 font-medium">Date</th>
                        <th class="py-2 font-medium">Total</th>
                    </tr>
                </thead>

                <tbody>
                @forelse ($recentOrders as $order)
                    <tr class="border-b">
                        <td class="py-2 font-semibold">#{{ $order->id }}</td>
                        <td>{{ $order->user->name ?? 'Unknown' }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 text-gray-500" colspan="4">
                            No recent orders.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        window.salesChartLabels = @json($chartLabels);
        window.salesChartValues = @json($chartValues);
    </script>

    <script src="{{ asset('js/chart.js') }}"></script>
@endpush
