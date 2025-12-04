@extends('layouts.main')

@section('title', 'My Orders • FitGuide')
@section('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">

    {{-- LEFT SIDEBAR --}}
    @include('profile.sidebar')

    {{-- RIGHT CONTENT --}}
    <section class="profile-main">
        <h2 class="section-title">My Orders</h2>

        @if($orders->isEmpty())
            <p style="color:#ccc">You have no orders yet.</p>
        @else
            <div class="orders-list">
                @foreach($orders as $order)
                    <div class="order-card">

                        <div class="order-top">
                            <span class="order-id">Order #{{ $order->id }}</span>
                            <span class="order-status status-{{ strtolower($order->status) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="order-details">
                            <p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
                            <p><strong>Total:</strong> €{{ number_format($order->total, 2) }}</p>
                        </div>

                        <a href="{{ route('orders.show', $order->id) }}" class="view-btn">
                            View Details →
                        </a>
                    </div>
                @endforeach
            </div>
        @endif

    </section>
</div>
@endsection
