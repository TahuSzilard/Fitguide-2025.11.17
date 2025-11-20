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
<body>

<!-- NAV -->
<nav class="navbar">
  <div class="container navbar-inner">

    <!-- BAL OLDAL -->
    <div class="nav-left">
      <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="nav-links" id="navMenu">
        <a href="/">HOME</a>
        <a href="/exercises">EXERCISES</a>
        <a href="/store">STORE</a>
        <a href="/advice">ADVICE</a>
      </div>
    </div>

    <!-- JOBB OLDAL (AUTH + CART) -->
    <div class="auth-buttons" id="authMenu">
      @auth
        <span class="username">{{ Auth::user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn-login">LOG OUT</button>
        </form>

        @php $cartCount = collect(session('cart', []))->sum('qty'); @endphp
        <a href="{{ route('cart.index') }}" class="nav-cart">
          <i class="fa-solid fa-cart-shopping"></i>
          @if($cartCount)<span class="cart-badge">{{ $cartCount }}</span>@endif
        </a>

      @else
        <a class="btn-login" href="{{ route('login') }}">LOG IN</a>
        <a class="btn-login" href="{{ route('register') }}">REGISTER</a>
      @endauth
    </div>
  </div>
</nav>


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


<!-- CART CONTENT -->
<section class="section-cards">
  <div class="container" style="max-width: 1000px;">

    @if(empty($cart))
      <div class="empty-cart">
        <div class="empty-cart__icon">
          <i class="fa-solid fa-store" aria-hidden="true"></i>
        </div>
        <h2 class="empty-cart__title">Your cart is empty</h2>
        <p class="empty-cart__text">
          Looks like you haven’t added anything yet. Explore our store and find what fits you best.
        </p>
        <a href="{{ route('store.index') }}" class="btn pill empty-cart__cta">
          Go to store
        </a>
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

              <div class="cart-row row" data-product-id="{{ $item['product_id'] }}">

                <div class="cell cell--name">
                  <div class="title" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
                </div>

                <div class="cell cell--price">
                  <span class="price">{{ number_format($item['price'], 0, '', '') }} Ft</span>
                </div>

                <div class="cell cell--qty">
                  <form class="qty-form" data-update-url="{{ route('cart.update', $item['product_id']) }}">
                    @csrf
                    <button type="button" class="qty-btn minus" aria-label="Decrease quantity">−</button>
                    <input type="number" name="qty" min="1" value="{{ $item['qty'] }}" class="qty-input" />
                    <button type="button" class="qty-btn plus" aria-label="Increase quantity">+</button>
                  </form>
                </div>

                <div class="cell cell--total">
                  <span class="line-total" data-price="{{ $item['price'] }}">
                    {{ number_format($item['price'] * $item['qty'], 0, '', '') }} Ft
                  </span>
                </div>

                <div class="cell cell--actions">
                  <form method="POST" action="{{ route('cart.remove', $item['product_id']) }}" class="inline" style="margin:0">
                    @csrf
                    <button type="submit" class="icon-btn is-danger" title="Remove" aria-label="Remove">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>

              </div>
            @endforeach

          </div>

          <form method="POST" action="{{ route('cart.clear') }}" class="inline">
            @csrf
            <button class="btn ghost danger clear-cart-btn" type="submit">
              <i class="fa-solid fa-trash-can"></i>
              Clear cart
            </button>
          </form>

        </div>

        <aside class="cart-card__aside">
          <div class="summary-card">
            <h3>Order summary</h3>

            <div class="sum-row">
              <span>Subtotal</span>
              <strong id="cart-subtotal">{{ number_format($subtotal, 0, '', '') }} Ft</strong>
            </div>

            <div class="sum-row">
              <span>Shipping</span>
              <strong id="cart-shipping">{{ number_format($shipping, 0, '', '') }} Ft</strong>
            </div>

            <div class="sum-row total">
              <span>Total</span>
              <strong id="cart-total">{{ number_format($total, 0, '', '') }} Ft</strong>
            </div>

            <a href="{{ route('checkout.show') }}" 
               class="btn pill large" 
               style="width:100%;margin-top:12px; text-align:center; text-decoration: none;">
              Proceed to checkout
            </a>
          </div>
        </aside>

      </div>
    @endif

  </div>
</section>


<!-- FOOTER -->
<footer class="footer">
  <div class="container footer-grid">

    <div class="footer-brand">
      <div class="logo">FitGuide</div>
      <p class="tagline">Your personal guide to a healthier lifestyle.</p>
    </div>

    <nav class="footer-links">
      <h4>Explore</h4>
      <a href="/exercises">Exercises</a>
      <a href="/store">Store</a>
      <a href="/advice">Advice</a>
    </nav>

    <nav class="footer-links">
      <h4>Company</h4>
      <a href="#">About Us</a>
      <a href="#">Contact</a>
      <a href="#">Careers</a>
    </nav>

  </div>

  <div class="container footer-bottom">
    <span>© {{ date('Y') }} FitGuide</span>
    <div class="legal">
      <a href="#">Privacy</a><span>•</span>
      <a href="#">Terms</a><span>•</span>
      <a href="#">Cookies</a>
    </div>
  </div>
</footer>

</body>
</html>
