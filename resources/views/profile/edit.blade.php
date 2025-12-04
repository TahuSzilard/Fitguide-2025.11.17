@extends('layouts.main')

@section('title', 'Edit Profile • FitGuide')

@section('head')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-container">

    {{-- LEFT SIDEBAR --}}
    @include('profile.sidebar')

    {{-- RIGHT MAIN CONTENT --}}
    <section class="profile-main">
        <h2 class="section-title">Edit Profile</h2>
        {{-- 
        @if(session('status') === 'profile-updated')
            <div class="alert-success">✔ Profile updated successfully!</div>
        @endif
        --}}
        <form method="POST" action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              class="profile-overview">
            @csrf
            @method('PATCH')

            {{-- PROFILE PICTURE --}}
            <div class="overview-item full">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" class="profile-input" accept="image/*">
            </div>

            {{-- NAME --}}
            <div class="overview-item">
                <label>Full Name</label>
                <input class="profile-input" type="text" name="name"
                       value="{{ old('name', $user->name) }}" required>
            </div>

            {{-- EMAIL --}}
            <div class="overview-item">
                <label>Email</label>
                <input class="profile-input" type="email" name="email"
                       value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- PHONE --}}
            <div class="overview-item">
                <label>Phone Number</label>
                <input class="profile-input" type="text" name="phone"
                       value="{{ old('phone', $user->phone) }}">
            </div>

            {{-- DOB --}}
            <div class="overview-item">
                <label>Date of Birth</label>
                <input class="profile-input" type="date" name="dob"
                       value="{{ old('dob', $user->dob) }}">
            </div>

            {{-- GENDER --}}
            <div class="overview-item full">
                <label>Gender</label>
                <select name="gender" class="profile-input">
                    <option value="">Select Gender</option>
                    <option value="male" {{ $user->gender === 'male' ? 'selected' : '' }}>Male</option>
                    <option value="female" {{ $user->gender === 'female' ? 'selected' : '' }}>Female</option>
                    <option value="other" {{ $user->gender === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <button type="submit" class="btn-primary full mt">
                Save Changes
            </button>
        </form>
    </section>
</div>
@endsection
