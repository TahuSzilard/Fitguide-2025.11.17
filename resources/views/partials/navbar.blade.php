<nav class="navbar">
  <div class="container navbar-inner">
    <div class="nav-left">
      <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="nav-links" id="navMenu">
        <a href="/">HOME</a>
        <a href="/exercises">EXERCISES</a>
        <a href="/store">STORE</a>
        <a href="/advice" class="active">ADVICE</a>
      </div>
    </div>

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
          @if($cartCount)
            <span class="cart-badge">{{ $cartCount }}</span>
          @endif
        </a>
      @else
        <a class="btn-login" href="{{ route('login') }}">LOG IN</a>
        <a class="btn-login" href="{{ route('register') }}">REGISTER</a>
      @endauth
    </div>
  </div>
</nav>
