<div class="store-grid">
  @forelse ($products as $product)
    <article class="product-card" data-category="{{ $product->productType->slug ?? 'unknown' }}">
      <div class="product-thumb">
        @php
          $img = $product->image
            ? (\Illuminate\Support\Str::startsWith($product->image, ['http://','https://'])
                ? $product->image
                : asset('images/' . ltrim($product->image, '/')))
            : asset('images/placeholder-product.png');
        @endphp
        <img src="{{ $img }}" alt="{{ $product->name }}" />
      </div>

      <div class="product-body">
        <h3 class="product-name">{{ $product->name }}</h3>
        @if($product->description)
          <p class="product-desc">{{ \Illuminate\Support\Str::limit($product->description, 90) }}</p>
        @endif
      </div>

      <div class="product-foot">
      <div class="product-price">{{ number_format($product->price, 2, ',', ' ') }} €</div>
        @auth
          <form method="POST" action="{{ route('cart.add', $product) }}" class="add-to-cart-form">
            @csrf
            <button type="submit" class="btn pill add-btn">
              <span class="label">Add to cart</span>
              <span class="check-icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
            </button>
          </form>
        @else
          <a class="btn pill" href="{{ route('login') }}?redirect={{ urlencode(request()->fullUrl()) }}">
            Log in to buy
          </a>
        @endauth
      </div>
    </article>
  @empty
    <p style="color:#cfe3ff">No products yet.</p>
  @endforelse
</div>

{{-- =========================
     PAGINATION SECTION
   ========================= --}}
@if ($products->total() > 0)
  <div class="pagi-stack">
    {{-- Oldalszámok + Previous / Next --}}
    <div class="page-links">
      {{ $products->onEachSide(1)->links('vendor.pagination.fitguide') }}
    </div>

    {{-- "Showing x to y of z results" --}}
    <div class="pagination-info">
      Showing {{ $products->firstItem() }}
      to {{ $products->lastItem() }}
      of {{ $products->total() }} results
    </div>
  </div>
@endif
