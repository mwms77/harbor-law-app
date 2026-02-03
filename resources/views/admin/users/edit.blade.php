@php 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="header">
    <h1>Edit User</h1>
    <p>Update user information</p>
</div>

<div class="card">
    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
                @error('first_name')
                    <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
                @error('last_name')
                    <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role *</label>
            <select id="role" name="role" required>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <hr style="margin: 30px 0; border: 1px solid #e0e0e0;">

        <h3 style="color: #667eea; margin-bottom: 15px;">Change Password</h3>
        <p style="color: #666; margin-bottom: 20px; font-size: 14px;">Leave blank to keep current password</p>

        <div class="form-group">
            <label for="password">New Password (min 8 characters)</label>
            <input type="password" id="password" name="password">
            @error('password')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
