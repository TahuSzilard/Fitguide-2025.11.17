@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
@endphp

<aside class="profile-sidebar">

    <div class="user-info">
        <div class="user-avatar">
            @if($user?->profile_picture)
                <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="Profile Picture">
            @else
                <i class="fa-solid fa-user"></i>
            @endif
        </div>

        <h3>{{ $user?->name }}</h3>

        <p class="member-text {{ $user?->role === 'admin' ? 'member-admin' : 'member-user' }}">
            {{ $user?->role === 'admin' ? 'FitGuide Admin' : 'FitGuide Member' }}
        </p>
    </div>

    <nav class="sidebar-menu">

        <a class="{{ request()->routeIs('profile.index') ? 'active' : '' }}"
           href="{{ route('profile.index') }}">
           <i class="fa-solid fa-user"></i> Profile
        </a>

        <a class="{{ request()->routeIs('orders.*') ? 'active' : '' }}"
           href="{{ route('orders.index') }}">
           <i class="fa-solid fa-box"></i> Orders
        </a>

        <a class="{{ request()->routeIs('profile.rewards') ? 'active' : '' }}"
           href="{{ route('profile.rewards') }}">
           <i class="fa-solid fa-star"></i> Rewards
        </a>

        <a class="{{ request()->routeIs('profile.discounts') ? 'active' : '' }}"
           href="{{ route('profile.discounts') }}">
           <i class="fa-solid fa-ticket"></i> Discounts
        </a>

        <a class="{{ request()->routeIs('profile.security') ? 'active' : '' }}" 
            href="{{ route('profile.security') }}">
            <i class="fa-solid fa-lock"></i> Security
        </a>

        <a class="{{ request()->routeIs('settings.index') ? 'active' : '' }}"
           href="{{ route('settings.index') }}">
           <i class="fa-solid fa-gear"></i> Settings
        </a>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="menu-logout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </button>
        </form>

    </nav>
</aside>
