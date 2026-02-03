@php 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Facades\Auth;
@endphp
@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="header">
    <h1>Add New User</h1>
    <p>Create a new user account</p>
</div>

<div class="card">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name *</label>
                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name')
                    <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="last_name">Last Name *</label>
                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                @error('last_name')
                    <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="role">Role *</label>
            <select id="role" name="role" required>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <hr style="margin: 30px 0; border: 1px solid #e0e0e0;">

        <div class="form-group">
            <label for="password">Password * (min 8 characters)</label>
            <input type="password" id="password" name="password" required>
            @error('password')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
