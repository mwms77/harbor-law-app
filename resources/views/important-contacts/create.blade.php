@extends('layouts.app')

@section('title', 'Add Contact')

@section('content')
<div class="header">
    <h1>Add Contact</h1>
    <p>Add a lawyer, CPA, other professional, or any important contact</p>
</div>

<div class="card">
    <form action="{{ route('important-contacts.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="role_type">Contact type *</label>
            <select id="role_type" name="role_type" required>
                <option value="">Select type...</option>
                @foreach($roleLabels as $value => $label)
                    <option value="{{ $value }}" {{ old('role_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('role_type')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
            @error('full_name')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="relationship-group">
            <label for="relationship">Relationship or profession</label>
            <input type="text" id="relationship" name="relationship" value="{{ old('relationship') }}" placeholder="e.g., estate planning attorney, family CPA, financial advisor">
            <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">A few words to describe their role or how you know them</small>
            @error('relationship')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}">
            @error('phone')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
            @error('email')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2" placeholder="Street, City, State, ZIP">{{ old('address') }}</textarea>
            @error('address')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Add Contact</button>
            <a href="{{ route('important-contacts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
