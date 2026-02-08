@extends('layouts.app')

@section('title', 'Client Document Uploads')

@section('content')

<style>
    @media (max-width: 1023px) {
        .desktop-table { display: none !important; }
        .mobile-cards { display: block !important; }
    }
    
    @media (min-width: 1024px) {
        .desktop-table { display: block !important; }
        .mobile-cards { display: none !important; }
    }
    
    .upload-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .upload-card-name { font-size: 15px; font-weight: 600; color: #333; margin-bottom: 4px; }
    .upload-card-meta { font-size: 13px; color: #666; margin-bottom: 12px; }
    .upload-card-actions { display: flex; gap: 8px; flex-wrap: wrap; }
</style>

<div class="header">
    <h1>Client Document Uploads</h1>
    <p>Manage and review all client-uploaded documents</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Filter Uploads</h2>
    <form method="GET" action="{{ route('admin.uploads') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
        <div class="form-group" style="flex: 1; min-width: 200px; margin-bottom: 0;">
            <label>Search by client name or email</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search...">
        </div>
        <div class="form-group" style="min-width: 180px; margin-bottom: 0;">
            <label>Category</label>
            <select name="category">
                <option value="">All Categories</option>
                @foreach($categories as $key => $label)
                    <option value="{{ $key }}" {{ request('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.uploads') }}" class="btn btn-secondary">Clear</a>
        </div>
    </form>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">All Uploads</h2>
    
    @if($uploads->isEmpty())
        <div style="text-align: center; padding: 40px; color: #666;">
            <p style="margin-bottom: 10px;">No uploads found.</p>
            <p style="font-size: 14px;">No documents match your current filters.</p>
        </div>
    @else
        {{-- MOBILE: Cards --}}
        <div class="mobile-cards" style="display: none;">
            @foreach($uploads as $upload)
            <div class="upload-card">
                <div class="upload-card-name">
                    <a href="{{ route('admin.users.show', $upload->user) }}" style="color: #667eea; text-decoration: none;">{{ $upload->user->full_name }}</a>
                </div>
                <div class="upload-card-meta">{{ $upload->user->email }}</div>
                <div class="upload-card-meta">
                    <strong>{{ $upload->original_name }}</strong><br>
                    <span class="badge" style="background: #e3e8ef; color: #4a5568; padding: 3px 8px; border-radius: 4px; font-size: 12px;">{{ $upload->category_name }}</span>
                    <span style="margin-left: 8px;">{{ $upload->formatted_size }}</span>
                    <span style="margin-left: 8px;">{{ $upload->created_at->format('M j, Y') }}</span>
                </div>
                <div class="upload-card-actions">
                    <a href="{{ route('admin.uploads.download', $upload) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">Download</a>
                    <form action="{{ route('admin.uploads.delete', $upload) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this file?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;">Delete</button>
                    </form>
                    <a href="{{ route('admin.users.show', $upload->user) }}" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px;">View User</a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- DESKTOP: Table --}}
        <div class="desktop-table" style="display: none;">
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>File Name</th>
                            <th>Category</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($uploads as $upload)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.show', $upload->user) }}" style="color: #667eea; font-weight: 600;">{{ $upload->user->full_name }}</a>
                                <div style="font-size: 12px; color: #666;">{{ $upload->user->email }}</div>
                            </td>
                            <td>{{ $upload->original_name }}</td>
                            <td>
                                <span class="badge" style="background: #e3e8ef; color: #4a5568; padding: 4px 8px; border-radius: 4px; font-size: 12px;">{{ $upload->category_name }}</span>
                            </td>
                            <td>{{ $upload->formatted_size }}</td>
                            <td>{{ $upload->created_at->format('M j, Y g:i A') }}</td>
                            <td style="text-align: center;">
                                <a href="{{ route('admin.uploads.download', $upload) }}" class="btn btn-primary" style="font-size: 12px; padding: 6px 12px; margin-right: 5px;">Download</a>
                                <form action="{{ route('admin.uploads.delete', $upload) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 6px 12px;">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div style="margin-top: 20px;">
            {{ $uploads->links() }}
        </div>
    @endif
</div>

@endsection
