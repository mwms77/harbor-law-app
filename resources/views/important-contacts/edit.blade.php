@extends('layouts.app')

@section('title', 'Edit Contact - ' . $contact->full_name)

@section('content')
<div class="header">
    <h1>Edit Contact</h1>
    <p>{{ $roleLabels[$contact->role_type] ?? $contact->role_type }}</p>
</div>

<div class="card">
    <form action="{{ route('important-contacts.update', $contact) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="full_name">Full Name *</label>
            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $contact->full_name) }}" required>
            @error('full_name')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="relationship">{{ $contact->role_type === 'other_contact' ? 'Relationship or profession' : 'Relationship' }}</label>
            <input type="text" id="relationship" name="relationship" value="{{ old('relationship', $contact->relationship) }}" placeholder="{{ $contact->role_type === 'other_contact' ? 'e.g., financial advisor, family friend' : 'e.g., Spouse, Child, Attorney' }}">
            @error('relationship')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone" value="{{ old('phone', $contact->phone) }}">
            @error('phone')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email', $contact->email) }}">
            @error('email')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="2" placeholder="Street, City, State, ZIP">{{ old('address', $contact->address) }}</textarea>
            @error('address')
                <span style="color: #dc3545; font-size: 14px;">{{ $message }}</span>
            @enderror
        </div>

        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 24px;">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('important-contacts.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
