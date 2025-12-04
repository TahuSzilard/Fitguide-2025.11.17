<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cart • FitGuide</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/cart.css') }}" />
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script>
    window.FG = {
      cartCountUrl: "{{ route('cart.count') }}"
    };
  </script>

  <script src="{{ asset('js/shop.js') }}" defer></script>
</head>

@php
    $user = Auth::user();
@endphp

<body>

{{-- NAVBAR (dropdownnal) --}}
  @include('partials.navbar')
<!-- CART HEADER -->
<div class="cart-header">
  <div class="cart-icon">
    <i class="fa-solid fa-cart-shopping"></i>
  </div>
  <div class="cart-text">
    <h1>Shopping Cart</h1>
    <p>Review your items and proceed to checkout</p>
  </div>
</div>


<section class="section-cards">
  <div class="container" style="max-width: 1000px;">

    @if(empty($cart))
      <div class="empty-cart">
        <div class="empty-cart__icon">
          <i class="fa-solid fa-store"></i>
        </div>
        <h2>Your cart is empty</h2>
        <p>Looks like you haven’t added anything yet.</p>
        <a class="btn pill empty-cart__cta" href="{{ route('store.index') }}">Go to store</a>
      </div>

    @else
      <div class="cart-card container">

        <div class="cart-card__main">
          <div class="cart-table">

            <div class="cart-head row">
              <div class="Fejlec">Product</div>
              <div class="Fejlec">Price</div>
              <div class="Fejlec">Quantity</div>
              <div class="Fejlec">Total</div>
              <div class="Fejlec">Actions</div>
            </div>

            @foreach ($cart as $item)
              <div class="cart-row row">
                
                <div class="cell cell--name">
                  <div class="title">{{ $item['name'] }}</div>
                </div>

                <div class="cell cell--price">
                  <span class="price">
                    {{ number_format($item['price'], 2, ',', ' ') }} €
                  </span>
                </div>

                <div class="cell cell--qty">
                  <form class="qty-form" data-update-url="{{ route('cart.update', $item['product_id']) }}">
                    @csrf
                    <button type="button" class="qty-btn minus">−</button>
                    <input type="number" name="qty" min="1" value="{{ $item['qty'] }}" class="qty-input" />
                    <button type="button" class="qty-btn plus">+</button>
                  </form>
                </div>

                <div class="cell cell--total">
                  <span class="line-total" data-price="{{ $item['price'] }}">
                    {{ number_format($item['price'] * $item['qty'], 2, ',', ' ') }} €
                  </span>
                </div>

                <div class="cell cell--actions">
                  <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}">
                    @csrf
                    <button type="submit" class="icon-btn is-danger">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>

              </div>
            @endforeach

          </div>

          <form method="POST" action="{{ route('cart.clear') }}">
            @csrf
            <button class="btn ghost danger clear-cart-btn">
              <i class="fa-solid fa-trash-can"></i> Clear cart
            </button>
          </form>

        </div>


        <aside class="cart-card__aside">
          <div class="summary-card">
            <h3>Order summary</h3>

            <div class="sum-row">
              <span>Subtotal</span>
              <strong id="cart-subtotal">
                {{ number_format($subtotal, 2, ',', ' ') }} €
              </strong>
            </div>

            <div class="sum-row">
              <span>Shipping</span>
              <strong id="cart-shipping">
                {{ number_format($shipping, 2, ',', ' ') }} €
              </strong>
            </div>

            <div class="sum-row total">
              <span>Total</span>
              <strong id="cart-total">
                {{ number_format($total, 2, ',', ' ') }} €
              </strong>
            </div>

            <a href="{{ route('checkout.show') }}" class="btn pill large" style="width:100%; margin-top:12px;">
              Proceed to checkout
            </a>
          </div>
        </aside>

      </div>
    @endif

  </div>
</section>


<footer class="footer">
  <div class="container footer-grid">
    <div class="footer-brand">
      <div class="logo">FitGuide</div>
      <p>Your personal guide to a healthier lifestyle.</p>
    </div>
    <nav class="footer-links">
      <h4>Explore</h4>
      <a>Exercises</a><a>Store</a><a>Advice</a>
    </nav>
    <nav class="footer-links">
      <h4>Company</h4>
      <a>About Us</a><a>Contact</a><a>Careers</a>
    </nav>
  </div>
  <div class="container footer-bottom">
    <span>© {{ date('Y') }} FitGuide</span>
    <div class="legal">
      <a>Privacy</a><span>•</span>
      <a>Terms</a><span>•</span>
      <a>Cookies</a>
    </div>
  </div>
</footer>

</body>
</html>
