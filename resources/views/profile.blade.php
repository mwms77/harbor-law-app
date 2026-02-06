@php 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="header">
    <h1>My Profile</h1>
    <p>Update your account information and password</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Account Information</h2>
    
    <form action="{{ route('admin.profile.update') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', Auth::user()->first_name) }}" required>
                @error('first_name')
                    <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', Auth::user()->last_name) }}" required>
                @error('last_name')
                    <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
            @error('email')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <hr style="margin: 30px 0; border: 1px solid #e0e0e0;">

        <h3 style="color: #667eea; margin-bottom: 20px;">Change Password</h3>
        <p style="color: #666; margin-bottom: 20px;">Leave blank if you don't want to change your password</p>

        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" autocomplete="current-password">
            @error('current_password')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" autocomplete="new-password">
            <small style="color: #666; font-size: 12px;">Must be at least 8 characters</small>
            @error('new_password')
                <span style="color: #dc3545; font-size: 14px; display: block;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Confirm New Password</label>
            <input type="password" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
        </div>

        <div style="margin-top: 30px;">
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </div>
    </form>
</div>
@endsection
