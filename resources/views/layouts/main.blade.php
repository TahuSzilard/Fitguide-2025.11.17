<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'FitGuide')</title>

    {{-- Global Styles --}}
    <link rel="stylesheet" href="{{ asset('css/orders.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}" />
    <link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">

    {{-- Scripts --}}
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ asset('js/shop.js') }}" defer></script>
</head>

<body>

    {{-- NAVIGATION --}}
    @include('partials.navbar')

    {{-- MAIN CONTENT --}}
    <main style="min-height: 70vh; padding-top: 20px;">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

</body>
</html>
