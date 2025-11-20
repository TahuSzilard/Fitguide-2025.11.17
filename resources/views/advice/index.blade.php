<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Advice • FitGuide</title>
 
  <link rel="stylesheet" href="{{ asset('css/advice.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" />
</head>
<body>
 
  {{-- NAV --}}
  @include('partials.navbar')
 
  <header class="container hero hero--tight" style="padding: 28px 0 22px;">
    <div style="max-width: 640px;">
      <h1 class="hero-title" style="margin-bottom:6px;">Fitness Advice</h1>
      <p class="hero-sub">Calculate your Body Mass Index (BMI) and understand your health better.</p>
    </div>
  </header>
 
  <section class="container" style="padding: 40px 0;">
  <div class="bmi-card">
      <h2 class="section-title">BMI Calculator</h2>
 
      <form method="POST" action="{{ route('advice.bmi') }}">
        @csrf
        <div class="form-group" style="margin-bottom: 16px;">
          <label for="weight">Weight (kg):</label>
          <input type="number" id="weight" name="weight" class="form-control"
                 value="{{ session('old_weight') }}" step="0.1" required>
          @error('weight')<small class="error">{{ $message }}</small>@enderror
        </div>
 
        <div class="form-group" style="margin-bottom: 16px;">
          <label for="height">Height (cm):</label>
          <input type="number" id="height" name="height" class="form-control"
                 value="{{ session('old_height') }}" step="0.1" required>
          @error('height')<small class="error">{{ $message }}</small>@enderror
        </div>
 
        <button type="submit" class="btn-login" style="width:100%;">Calculate BMI</button>
      </form>
 
      @if(session('bmi'))
        <div class="bmi-display">
            <div class="bmi-top">
                <div class="bmi-illustration">
                    <img src="{{ asset('images/' . Str::slug(session('category')) . '.png') }}" alt="BMI illustration">
                </div>

                <div class="bmi-info">
                    <div class="bmi-value">{{ session('bmi') }}</div>
                    <div class="bmi-category {{ Str::slug(session('category')) }}">
                        {{ session('category') }}
                    </div>
                    <p class="bmi-advice">{{ session('advice') }}</p>
                </div>
            </div>

            <div class="bmi-scale">
                <div class="scale-bar">
                    <div class="indicator" style="left: {{ session('bmi_position') }}%;"></div>
                </div>
                <div class="scale-labels">
                    <span>Underweight</span>
                    <span>Normal</span>
                    <span>Overweight</span>
                    <span>Obese</span>
                </div>
            </div>
        </div>
        @endif
 
    </div>
  </section>
 
  {{-- FOOTER --}}
  @include('partials.footer')
 
</body>
</html>