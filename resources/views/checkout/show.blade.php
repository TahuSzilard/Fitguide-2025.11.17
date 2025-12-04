@extends('layouts.shop')
@section('title', 'Checkout • FitGuide')


@section('content')
<div class="container" style="max-width: 1120px; margin: 0 auto; padding: 24px 16px;">

  {{-- FEJLÉC --}}
  <div class="checkout-header">
    <div class="checkout-icon"><i class="fa-solid fa-receipt"></i></div>
    <div>
      <h1>Checkout</h1>
      <p>Review your order and enter your shipping details.</p>
    </div>
  </div>

  {{-- FLASH üzenetek --}}
  @if(session('success'))
    <div class="flash flash--success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="flash flash--error">{{ session('error') }}</div>
  @endif

  <div class="checkout-grid">
    {{-- BAL: űrlap --}}
    <div class="checkout-main">
      <h2 class="section-title">Shipping details</h2>

      <form method="POST" action="{{ route('checkout.place') }}" class="checkout-form">
        @csrf

        <div class="field">
          <label for="full_name">Full name *</label>
          <input class="input" type="text" id="full_name" name="full_name"
                 value="{{ old('full_name', Auth::user()->name) }}" required>
          @error('full_name') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="address_line1">Address line 1 *</label>
          <input class="input" type="text" id="address_line1" name="address_line1"
                 value="{{ old('address_line1') }}" required>
          @error('address_line1') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field">
          <label for="address_line2">Address line 2 (optional)</label>
          <input class="input" type="text" id="address_line2" name="address_line2"
                 value="{{ old('address_line2') }}">
          @error('address_line2') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="field-row">
          <div class="field">
            <label for="city">City *</label>
            <input class="input" type="text" id="city" name="city"
                   value="{{ old('city') }}" required>
            @error('city') <div class="err">{{ $message }}</div> @enderror
          </div>
          <div class="field">
            <label for="postal_code">Postal code *</label>
            <input class="input" type="text" id="postal_code" name="postal_code"
                   value="{{ old('postal_code') }}" required>
            @error('postal_code') <div class="err">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="field">
          <label for="country">Country *</label>
          <input class="input" type="text" id="country" name="country"
                 value="{{ old('country', 'Hungary') }}" required>
          @error('country') <div class="err">{{ $message }}</div> @enderror
        </div>

        <div class="submit-row">
          <button type="submit" class="btn pill large">Place order</button>
        </div>
      </form>
    </div>

    {{-- JOBB: összegzés + tételek képpel --}}
    <aside class="checkout-aside">
      <div class="summary-card">
        <h3>Order summary</h3>

        <div class="items-list">
          @foreach($cart as $item)
            @php
              // Bélyegkép beszerzése a product_id alapján
              $product = \App\Models\Product::find($item['product_id'] ?? null);
              $img = $product?->image
                    ? (\Illuminate\Support\Str::startsWith($product->image, ['http://','https://'])
                        ? $product->image
                        : \Illuminate\Support\Facades\Storage::url($product->image))
                    : asset('images/placeholder-product.png');
            @endphp
            <div class="item-row">
              <div class="thumb"><img src="{{ $img }}" alt="{{ $item['name'] }}"></div>
              <div class="meta">
                <div class="name">{{ $item['name'] }}</div>
                <div class="muted">Qty: {{ $item['qty'] }}</div>
              </div>
              <div class="line-total">€{{ number_format($item['price'] * $item['qty'], 2) }}</div>
            </div>
          @endforeach
        </div>

        <div class="sum-row">
          <span>Subtotal</span>
          <strong id="summary-subtotal">€{{ number_format($subtotal, 2) }}</strong>
        </div>
        <div class="sum-row">
          <span>Shipping</span>
          <strong id="summary-shipping">€{{ number_format($shipping, 2) }}</strong>
        </div>
        <div class="sum-row total">
          <span>Total</span>
          <strong id="summary-total">€{{ number_format($total, 2) }}</strong>
        </div>

        {{-- Ha szeretnéd külön checkout gombot is ide (nem kötelező) --}}
        {{-- <a href="{{ route('checkout.place') }}" class="btn pill large" style="width:100%;margin-top:12px;">Place order</a> --}}
      </div>
    </aside>
  </div>
</div>

{{-- Minimal CSS csak ehhez a nézethez (a globális stílusaidra támaszkodik) --}}

@endsection
