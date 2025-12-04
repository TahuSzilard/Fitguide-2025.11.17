<nav class="navbar">
  <div class="container navbar-inner">

    <!-- BAL OLDAL: oldalak -->
    <div class="nav-left">
      <button class="nav-toggle" id="navToggle">
        <i class="fa-solid fa-bars"></i>
      </button>

      <div class="nav-links" id="navMenu">
        <a href="/">HOME</a>
        <a href="/exercises">EXERCISES</a>
        <a href="/store">STORE</a>
        <a href="/advice">ADVICE</a>
        @if(auth()->check() && auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}">ADMIN PANEL</a>
        @endif
      </div>
    </div>

      <!-- JOBB OLDAL: Cart + Profile egy sorban -->
  <div class="user-area">

      @auth
      <div class="relative group user-dropdown">

          <!-- BUTTON – csak a név, nincs ikon/keret -->
          <button class="user-btn">
              {{ auth()->user()->name }}
              <i class="fa-solid fa-chevron-down"></i>
          </button>

          <!-- DROPDOWN -->
          <div class="dropdown-menu">
              <a href="{{ route('profile.index') }}" class="dropdown-item">
                  Profile
              </a>
              <a href="{{ route('orders.index') }}" class="dropdown-item">
                  Orders
              </a>
              <a href="{{ route('settings.index') }}" class="dropdown-item">
                  Settings
              </a>
              <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item logout-item">
                      Logout
                  </button>
              </form>
          </div>
      </div>

      <!-- CART ICON -->
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
