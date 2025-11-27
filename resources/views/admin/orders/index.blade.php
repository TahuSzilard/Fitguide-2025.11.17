@extends('admin.layout')

@section('content')
<div class="p-8">

    <h1 class="text-3xl font-bold mb-6">Orders</h1>

    {{-- Status Filter --}}
    <form method="GET" class="mb-6 flex gap-3">
        <select name="status" class="px-4 py-2 border rounded-lg bg-white">
            <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ $status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button>
    </form>

    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 border-b text-gray-600">
                <tr>
                    <th class="py-3 px-4">Order</th>
                    <th class="py-3 px-4">User</th>
                    <th class="py-3 px-7">Total</th>
                    <th class="py-3 px-8">Date</th>
                    <th class="py-3 px-6">Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orders as $order)
                <tr class="border-b hover:bg-gray-50">
                    <td class="py-3 px-4 font-semibold">#{{ $order->id }}</td>
                    <td class="px-4">{{ $order->user->name }}</td>
                    <td class="px-4">€{{ number_format($order->total, 2) }}</td>
                    <td class="px-4">{{ $order->created_at->format('M d, Y') }}</td>
                    <td class="px-4">
                        @include('admin.orders.status-badge', ['status' => $order->status])
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

</div>
@endsection
