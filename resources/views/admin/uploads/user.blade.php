@extends('layouts.app')

@section('title', 'Documents for ' . $user->full_name)

@section('content')

<style>
    @media (max-width: 767px) {
        .desktop-table { display: none !important; }
        .mobile-cards { display: block !important; }
    }
    
    @media (min-width: 768px) {
        .desktop-table { display: block !important; }
        .mobile-cards { display: none !important; }
    }
    
    .category-section {
        margin-bottom: 25px;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    
    .category-header {
        background: #f8f9fa;
        padding: 12px 20px;
        border-bottom: 1px solid #e0e0e0;
        font-weight: 600;
        color: #667eea;
    }
    
    .category-header .badge {
        background: #667eea;
        color: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 12px;
        margin-left: 8px;
    }
    
    .file-row {
        padding: 15px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }
    
    .file-row:last-child { border-bottom: none; }
    
    .file-row:hover { background: #f8f9fa; }
    
    .file-info .file-name { font-weight: 600; color: #333; margin-bottom: 4px; }
    .file-info .file-meta { font-size: 13px; color: #666; }
</style>

<div class="header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1>Documents for {{ $user->full_name }}</h1>
            <p>{{ $user->email }}</p>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @if($uploads->flatten()->count() > 0)
                <a href="{{ route('admin.uploads.zip', $user) }}" class="btn btn-success">
                    Download All as ZIP
                </a>
            @endif
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">Back to User</a>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if($uploads->flatten()->isEmpty())
    <div class="card">
        <div style="text-align: center; padding: 40px; color: #666;">
            <p style="margin-bottom: 10px; font-size: 16px;">No documents uploaded</p>
            <p style="font-size: 14px;">This user has not uploaded any documents yet.</p>
        </div>
    </div>
@else
    @foreach($categories as $categoryKey => $categoryLabel)
        @if(isset($uploads[$categoryKey]) && $uploads[$categoryKey]->count() > 0)
        <div class="card" style="padding: 0; overflow: hidden;">
            <div class="category-header">
                {{ $categoryLabel }}
                <span class="badge">{{ $uploads[$categoryKey]->count() }} {{ $uploads[$categoryKey]->count() === 1 ? 'file' : 'files' }}</span>
            </div>
            <div>
                @foreach($uploads[$categoryKey] as $upload)
                <div class="file-row">
                    <div class="file-info" style="flex: 1; min-width: 0;">
                        <div class="file-name">{{ $upload->original_name }}</div>
                        <div class="file-meta">{{ $upload->formatted_size }} • {{ $upload->created_at->format('M j, Y g:i A') }}</div>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="{{ route('admin.uploads.download', $upload) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">Download</a>
                        <form action="{{ route('admin.uploads.delete', $upload) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;">Delete</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    <div class="card" style="background: linear-gradient(135deg, #f0f4ff 0%, #e8ecff 100%); border: 1px solid #c5d3ff;">
        <h3 style="color: #667eea; margin-bottom: 15px; font-size: 16px;">Upload Summary</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 20px;">
            <div>
                <p style="font-size: 24px; font-weight: bold; color: #333;">{{ $uploads->flatten()->count() }}</p>
                <p style="font-size: 13px; color: #666;">Total Files</p>
            </div>
            <div>
                <p style="font-size: 24px; font-weight: bold; color: #333;">{{ number_format($uploads->flatten()->sum('file_size') / 1024 / 1024, 2) }} MB</p>
                <p style="font-size: 13px; color: #666;">Total Size</p>
            </div>
            <div>
                <p style="font-size: 24px; font-weight: bold; color: #333;">{{ $uploads->count() }}</p>
                <p style="font-size: 13px; color: #666;">Categories</p>
            </div>
        </div>
    </div>
@endif

@endsection
