@extends('layouts.main')

@section('title', 'My Profile • FitGuide')

@section('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">

    {{-- LEFT SIDEBAR --}}
    @include('profile.sidebar')

    {{-- RIGHT MAIN CONTENT --}}
    <section class="profile-main">
        <h2 class="section-title">Profile Summary</h2>

        {{-- 🔹 STAT KÁRTYÁK --}}
        <div class="profile-stats">

            <div class="stat-card">
                <span class="stat-label">Orders</span>
                <span class="stat-value">{{ $ordersCount }}</span>
            </div>

            <div class="stat-card">
                <span class="stat-label">Points</span>
                <span class="stat-value">{{ $points }}</span>
            </div>

        </div>

        {{-- 🔹 USER INFO BLOCKS --}}
        <div class="profile-overview">

            <div class="overview-item">
                <label>Full Name</label>
                <p>{{ $user->name }}</p>
            </div>

            <div class="overview-item">
                <label>Email</label>
                <p>{{ $user->email }}</p>
            </div>

            <div class="overview-item">
                <label>Phone Number</label>
                <p>{{ $user->phone ?? '—' }}</p>
            </div>

            <div class="overview-item">
                <label>Date of Birth</label>
                <p>{{ $user->dob ?? '—' }}</p>
            </div>

            <div class="overview-item">
                <label>Gender</label>
                <p style="text-transform: capitalize">
                    {{ $user->gender ?? '—' }}
                </p>
            </div>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn-primary mt">
            Edit Profile
        </a>

        <form method="POST" action="{{ route('profile.destroy') }}" class="mt"
              onsubmit="return confirm('Are you sure? This cannot be undone!')">
            @csrf
            @method('DELETE')
            <button class="btn-danger">Delete Account</button>
        </form>

    </section>

</div>
@endsection
