<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>FitGuide</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
  <script src="{{ asset('js/script.js') }}" defer></script>
  <script src="{{ asset('js/shop.js') }}" defer></script>
</head>
<body>

<!-- NAV -->
<nav class="navbar">
  <div class="container navbar-inner">
    <!-- BAL OLDAL -->
    <div class="nav-left">
      <!-- 🔹 Hamburger ikon alapból látszik mobilon -->
      <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
        <i class="fa-solid fa-bars"></i>
      </button>

      <!-- 🔹 Főmenü linkek -->
      <div class="nav-links" id="navMenu">
        <a href="/">HOME</a>
        <a href="/exercises">EXERCISES</a>
        <a href="/store">STORE</a>
        <a href="/advice">ADVICE</a>
      </div>
    </div>

    <!-- 🔹 Jobb oldal (auth + cart) -->
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
  <!-- HERO -->
  <header class="container hero hero--tight">
    <div>
      <h1 class="hero-title">Your guide<br>to fitness</h1>
      <p class="hero-sub">Explore exercises, shop for products, and get personalized advice.</p>
      <button id="cta-exercises" class="hero-cta">Browse Exercises</button>
    </div>
  </header>


  <!-- FOOTER (új, bővített) -->
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

      <div class="footer-newsletter">
        <h4>Get updates</h4>
        <p>Stay updated with fitness tips & exclusive deals.</p>
        <form class="nl-form" method="post" action="#">
          <input type="email" name="email" placeholder="Enter your email" required>
          <button type="submit" class="nl-btn">Subscribe</button>
        </form>
        <div class="footer-socials">
          <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
          <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
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
