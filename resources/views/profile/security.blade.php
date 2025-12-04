@extends('layouts.main')
@section('title', 'Security • FitGuide')
@section('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    
    @include('profile.sidebar')

    <section class="profile-main">
        <h2 class="section-title">Security Settings</h2>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('profile.security.update') }}" method="POST" class="security-form">
            @csrf

            <label>Current Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="password" required>

            <label>Confirm New Password</label>
            <input type="password" name="password_confirmation" required>

            @error('current_password')
                <p class="error">{{ $message }}</p>
            @enderror

            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn-primary">Update Password</button>
        </form>

    </section>

</div>
@endsection
