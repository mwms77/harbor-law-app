@extends('layouts.app')

@section('title', 'Register - Estate Planning')

@section('content')
<div class="card" style="max-width: 500px; margin: 100px auto;">
    <div class="header">
        <h1>Create Account</h1>
        <p>Start your estate planning journey</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                Must be at least 8 characters with uppercase, lowercase, number, and symbol
            </small>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 15px;">
            Register
        </button>

        <div style="text-align: center;">
            <a href="{{ route('login') }}" style="color: #667eea; text-decoration: none;">
                Already have an account? Login here
            </a>
        </div>
    </form>
</div>
@endsection
