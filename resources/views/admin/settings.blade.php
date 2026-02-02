@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="header">
    <h1>Application Settings</h1>
    <p>Configure your estate planning application</p>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Company Logo</h2>
    
    @if($logo)
        <div style="margin-bottom: 20px;">
            <img src="{{ Storage::url($logo) }}" alt="Company Logo" style="max-width: 300px; border: 1px solid #e0e0e0; padding: 10px; border-radius: 6px;">
        </div>
        
        <form action="{{ route('admin.settings.delete-logo') }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the logo?')">
                Delete Logo
            </button>
        </form>
    @else
        <p style="color: #666; margin-bottom: 20px;">No logo uploaded</p>
    @endif
    
    <form action="{{ route('admin.settings.upload-logo') }}" method="POST" enctype="multipart/form-data" style="margin-top: 30px;">
        @csrf
        
        <div class="form-group">
            <label for="logo">Upload New Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" required>
            <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                Recommended size: 300x100px. Formats: PNG, JPG, SVG
            </small>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Logo</button>
    </form>
</div>
@endsection
