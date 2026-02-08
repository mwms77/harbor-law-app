@extends('layouts.app')

@section('title', 'Login - Estate Planning')

@section('content')
<div class="card card-hero" style="max-width: 500px; margin: 100px auto;">
    <div class="header">
        <h1>Login</h1>
        <p>Access your estate planning account</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label style="display: flex; align-items: center; cursor: pointer;">
                <input type="checkbox" name="remember" style="width: auto; margin-right: 8px;">
                <span>Remember Me</span>
            </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 15px;">
            Login
        </button>

        <div style="text-align: center;">
            <a href="{{ route('register') }}" style="color: #667eea; text-decoration: none;">
                Don't have an account? Register here
            </a>
        </div>
    </form>
</div>
@endsection
