@extends('layouts.main')

@section('title', 'Discounts • FitGuide')
@section('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">

    @include('profile.sidebar')

    <section class="profile-main">
        <h2 class="section-title">My Discounts</h2>

        @if ($discounts->isEmpty())
            <p style="color:#ccc">You currently have no discounts.</p>
        @else
            <div class="discount-list">
                @foreach ($discounts as $d)
                    <div class="discount-card {{ 
                        $d->usedOrNot 
                            ? 'used' 
                            : ($d->expiryDate && $d->expiryDate->isPast() 
                                ? 'expired' 
                                : 'active') 
                    }}">
                        
                        <div class="discount-left">
                            <strong class="discount-code">{{ $d->discountCode }}</strong>
                            <span class="amount">-{{ $d->discountAmount }}%</span>

                            @if($d->usedOrNot)
                                <span class="status used">✔ Used</span>
                            @elseif($d->expiryDate && $d->expiryDate->isPast())
                                <span class="status expired">✖ Expired</span>
                            @else
                                <span class="status active">Usable 🎉</span>
                            @endif
                        </div>

                        <div class="discount-right">
                            <span class="expiry-label">Expiry date</span>
                            <span class="expiry">
                                <i class="fa-regular fa-clock"></i>
                                {{ $d->expiryDate?->format('Y-m-d') ?? 'No limit' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </section>
</div>
@endsection


