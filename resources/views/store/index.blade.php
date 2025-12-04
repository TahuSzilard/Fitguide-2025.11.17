<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Store • FitGuide</title>

  {{-- Global styles --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  {{-- Store-specifikus CSS --}}
  <link rel="stylesheet" href="{{ asset('css/shop.css') }}" />
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" />

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script>
    window.FG = {
      cartCountUrl: "{{ route('cart.count') }}",
    };
    window.STORE_URL = "{{ url('/store') }}";
  </script>

  <script src="{{ asset('js/script.js') }}" defer></script>
  <script src="{{ asset('js/shop.js') }}" defer></script>

  <style>
    #product-list.loading {
      opacity: .5;
      pointer-events: none;
      transition: opacity .15s;
    }
  </style>
</head>

<body>

  {{-- NAVBAR (dropdownnal) --}}
  @include('partials.navbar')

  {{-- HERO --}}
  <header class="container hero hero--tight" style="padding: 28px 0 22px;">
    <div style="max-width: 640px;">
      <h1 class="hero-title" style="margin-bottom:6px;">Store</h1>
      <p class="hero-sub">Shop supplements & fitness equipment.</p>
    </div>
  </header>

  {{-- FILTER + TERMÉKLISTA --}}
  <section class="section-cards" style="padding-top: 26px;">
    <div class="container">

      @php $active = request('type', 'all'); @endphp

      <div class="filter-row">
        <a class="filter-btn {{ $active==='all' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'all']) }}">All</a>

        <a class="filter-btn {{ $active==='supplements' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'supplements']) }}">Supplements</a>

        <a class="filter-btn {{ $active==='snacks' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'snacks']) }}">Snacks</a>

        <a class="filter-btn {{ $active==='equipment' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'equipment']) }}">Equipment</a>

        <a class="filter-btn {{ $active==='clothing' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'clothing']) }}">Clothing</a>

        <a class="filter-btn {{ $active==='accessories' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'accessories']) }}">Accessories</a>

        <a class="filter-btn {{ $active==='packages-gift' ? 'active' : '' }}"
           href="{{ route('store.index', ['type'=>'packages-gift']) }}">Packages & gift</a>
      </div>

      <div class="filter-dropdown">
        <label for="product-filter">Filter by:</label>
        <select id="product-filter" name="product-filter">
          @foreach ([
            'all' => 'All',
            'supplements' => 'Supplements',
            'snacks' => 'Snacks',
            'equipment' => 'Equipment',
            'clothing' => 'Clothing',
            'accessories' => 'Accessories',
            'packages-gift' => 'Packages & gift'
          ] as $slug => $label)
            <option value="{{ $slug }}" @selected($active===$slug)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      {{-- PRODUCT LIST WRAPPER (AJAX ide tölti be) --}}
      <div id="product-list">
        @include('store.partials.products', ['products' => $products, 'type' => $active])
      </div>

    </div>
  </section>

  {{-- FOOTER --}}
  @include('partials.footer')

</body>
</html>
