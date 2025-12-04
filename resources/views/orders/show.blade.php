@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto mt-10 bg-white p-6 rounded-lg shadow">

    <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>

    <p class="text-gray-600 mb-4">
        Placed on: {{ $order->created_at->format('Y-m-d H:i') }}
    </p>

    <h2 class="text-xl font-semibold mt-6 mb-3">Items</h2>

    <div class="space-y-4">
        @foreach ($order->items as $item)
            <div class="flex items-center gap-4 p-4 border rounded-md">
                
                <img src="{{ $item->product->image_url }}"
                     alt="{{ $item->product->name }}"
                     class="w-16 h-16 object-cover rounded">

                <div class="flex-1">
                    <div class="font-semibold">{{ $item->product->name }}</div>
                    <div class="text-gray-500 text-sm">
                        Quantity: {{ $item->quantity }}
                    </div>
                </div>

                <div class="font-semibold">
                    €{{ number_format($item->price * $item->quantity, 2) }}
                </div>

            </div>
        @endforeach
    </div>

    <hr class="my-6">

    <div class="text-right">
        <p>Subtotal: <strong>€{{ number_format($order->subtotal, 2) }}</strong></p>
        <p>Shipping: <strong>€{{ number_format($order->shipping, 2) }}</strong></p>
        <p class="text-xl font-bold mt-2">Total: €{{ number_format($order->total, 2) }}</p>
    </div>

</div>

@endsection
