@extends('layouts.guest')

@section('content')

<style>
    body {
        margin: 0;
        font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
        background:
            radial-gradient(1100px 700px at 18% 8%, #1f53a6 0%, #0d2a57 35%, rgba(13,42,87,0) 62%),
            linear-gradient(180deg, #0c1d33 0%, #0a1930 55%, #081629 100%);
        background-attachment: fixed;
        background-repeat: no-repeat;
        background-size: cover;
        background-color: #0a1930;
    }

    .auth-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .auth-box {
        width: 100%;
        max-width: 420px;
        background: white;
        padding: 32px;
        border-radius: 18px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.25);
        text-align: center;
    }

    .auth-box h2 {
        font-size: 26px;
        margin-bottom: 25px;
        font-weight: 700;
        color: #0a1930;
    }

    .input-style {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #cfd6e4;
        border-radius: 8px;
        margin-bottom: 14px;
        font-size: 15px;
        transition: .2s;
    }

    .input-style:focus {
        border-color: #1f53a6;
        box-shadow: 0 0 0 2px rgba(31,83,166,0.2);
        outline: none;
    }

    .submit-btn {
        width: 100%;
        background: #1f53a6;
        color: white;
        padding: 12px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 8px;
    }

    .submit-btn:hover {
        background: #174284;
    }

    .social-btn {
        width: 100%;
        background: white;
        border: 1px solid #ddd;
        padding: 12px;
        border-radius: 8px;
        font-size: 15px;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        margin-top: 15px;
        transition: .2s;
    }

    .social-btn:hover {
        background: #f3f6fa;
    }

    .divider {
        display: flex;
        align-items: center;
        margin: 18px 0;
        color: #666;
        font-size: 14px;
    }

    .divider::before, .divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #ddd;
    }

    .divider span {
        padding: 0 10px;
        user-select: none;
    }

    .bottom-text {
        margin-top: 18px;
        font-size: 14px;
        color: #555;
    }

    .bottom-text a {
        color: #1f53a6;
        font-weight: 600;
        text-decoration: none;
    }

    .bottom-text a:hover {
        text-decoration: underline;
    }
</style>


<div class="auth-wrapper">

    <div class="auth-box">

        <h2>Sign In</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input class="input-style"
                   type="email"
                   name="email"
                   placeholder="Email Address"
                   required>

            <input class="input-style"
                   type="password"
                   name="password"
                   placeholder="Password"
                   required>

            <button class="submit-btn">
                Sign In
            </button>

        </form>

        <div class="divider"><span>OR</span></div>

            <!-- Google Login -->
        <a href="{{ route('google.redirect') }}">
            <div class="social-btn">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="22">
                Sign in with Google
            </div>
        </a>
        <!--Facebook Login -->
        <a href="{{ route('facebook.redirect') }}">
            <div class="social-btn">
                <img src="https://www.svgrepo.com/show/475647/facebook-color.svg" width="22">
                Sign in with Facebook
            </div>
        </a>
        <div class="bottom-text">
            Don't have an account?
            <a href="{{ route('register') }}">Sign Up</a>
        </div>

    </div>

</div>

@endsection
