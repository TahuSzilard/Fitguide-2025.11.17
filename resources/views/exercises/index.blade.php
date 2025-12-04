<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Exercises • FitGuide</title>

  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/exercises.css') }}" />
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" />
</head>
<body>

<!-- NAV -->
@include('partials.navbar')

{{-- HERO --}}
<header class="container hero hero--tight" style="padding: 28px 0 22px;">
  <div style="max-width: 640px;">
    <h1 class="hero-title" style="margin-bottom:6px;">Exercises</h1>
    <p class="hero-sub">Browse exercises by muscle group and category.</p>
  </div>
</header>

{{-- FILTER --}}
<section class="section-cards" style="padding-top: 26px;">
  <div class="container">

    {{-- CATEGORY FILTER BUTTONS --}}
    <div class="filter-row">
      <a class="filter-btn {{ $category==='all' ? 'active' : '' }}" 
         href="{{ route('exercises.index', ['category'=>'all']) }}">
         All
      </a>

      @foreach($categories as $key => $label)
        <a class="filter-btn {{ $category === $key ? 'active' : '' }}"
           href="{{ route('exercises.index', ['category'=>$key]) }}">
           {{ $label }}
        </a>
      @endforeach
    </div>

    {{-- DIVIDER --}}
    <div class="filter-divider">
      <i class="fa-solid fa-filter"></i>
      <span>Muscle Groups</span>
    </div>

    {{-- MUSCLE LIST --}}
    <div class="muscle-list">
      @forelse($muscles as $muscle)
        <div class="muscle-card">
          <a href="{{ route('exercises.index', ['muscle' => $muscle->slug]) }}">
            {{ $muscle->name }}
          </a>
        </div>
      @empty
        <p>No muscles found for this category.</p>
      @endforelse
    </div>

    {{-- EXERCISES PLACEHOLDER --}}
    <div class="exercise-placeholder">
      <h2>Exercises will be listed here...</h2>
      <p>(Once models and data are available)</p>
    </div>

  </div>
</section>

@include('partials.footer')

</body>
</html>
