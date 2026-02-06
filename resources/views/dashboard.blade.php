@extends('layouts.app')

@section('title', 'Dashboard - Estate Planning')

@section('content')

<style>
    /* Responsive: Hide table on mobile, show cards */
    @media (max-width: 767px) {
        .desktop-table { display: none !important; }
        .mobile-cards { display: block !important; }
    }
    
    @media (min-width: 768px) {
        .desktop-table { display: block !important; }
        .mobile-cards { display: none !important; }
    }
    
    /* Mobile document cards */
    .document-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .document-card-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 12px;
        word-break: break-word;
    }
    
    .document-card-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
        padding: 10px 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 13px;
    }
    
    .document-card-meta-item {
        display: flex;
        flex-direction: column;
    }
    
    .document-card-meta-label {
        color: #999;
        font-size: 11px;
        text-transform: uppercase;
        margin-bottom: 4px;
    }
    
    .document-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    
    .document-card .btn {
        font-size: 13px;
        padding: 10px;
        text-align: center;
        display: block;
        width: 100%;
    }
    
    /* Responsive buttons on intake section */
    @media (max-width: 640px) {
        .intake-buttons {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .intake-buttons .btn {
            width: 100%;
            text-align: center;
        }
    }
    
    @media (min-width: 641px) {
        .intake-buttons {
            display: flex;
            gap: 10px;
        }
    }
</style>

<div class="header">
    <h1>Welcome, {{ auth()->user()->name }}</h1>
    <p>Manage your estate planning documents</p>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Intake Form Status</h2>
    
    @if($intake && $intake->is_completed)
        <div class="alert alert-success">
            ✓ Your intake form was completed on {{ $intake->completed_at->format('F j, Y') }}
        </div>
        
        <div class="intake-buttons" style="margin-top: 20px;">
            <a href="{{ route('intake.download') }}" class="btn btn-primary">
                Download My Information (JSON)
            </a>
            <a href="{{ route('intake.show') }}" class="btn btn-secondary">
                View/Edit Form
            </a>
        </div>
    @elseif($intake)
        <div class="alert alert-info">
            Your intake form is {{ $intake->progress_percentage }}% complete
        </div>
        
        <div style="margin-top: 20px;">
            <a href="{{ route('intake.show') }}" class="btn btn-primary" style="display: inline-block;">
                Continue Intake Form
            </a>
        </div>
    @else
        <div class="alert alert-info">
            You haven't started your intake form yet
        </div>
        
        <div style="margin-top: 20px;">
            <a href="{{ route('intake.show') }}" class="btn btn-primary" style="display: inline-block;">
                Start Intake Form
            </a>
        </div>
    @endif
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Estate Plan Documents</h2>
    
    @if($estatePlans->count() > 0)
        
        {{-- MOBILE: Cards --}}
        <div class="mobile-cards" style="display: none;">
            @foreach($estatePlans as $plan)
            <div class="document-card">
                {{-- Document Name --}}
                <div class="document-card-name">
                    {{ $plan->original_filename }}
                </div>
                
                {{-- Status Badge --}}
                <div style="margin-bottom: 12px;">
                    {!! $plan->getStatusBadge() !!}
                </div>
                
                {{-- Meta Info --}}
                <div class="document-card-meta">
                    <div class="document-card-meta-item">
                        <span class="document-card-meta-label">Uploaded</span>
                        <span style="font-weight: 500;">{{ $plan->created_at->format('M j, Y') }}</span>
                    </div>
                    
                    <div class="document-card-meta-item">
                        <span class="document-card-meta-label">File Size</span>
                        <span style="font-weight: 500;">{{ $plan->getFileSizeFormatted() }}</span>
                    </div>
                    
                    @if($plan->executed_at)
                    <div class="document-card-meta-item" style="grid-column: 1 / -1;">
                        <span class="document-card-meta-label">Executed</span>
                        <span style="color: #28a745; font-weight: 600;">
                            ✓ {{ $plan->executed_at->format('M j, Y') }}
                        </span>
                    </div>
                    @endif
                </div>
                
                {{-- Actions --}}
                <div class="document-card-actions">
                    <a href="{{ route('estate-plans.view', $plan) }}" class="btn btn-primary" target="_blank">
                        View PDF
                    </a>
                    <a href="{{ route('estate-plans.download', $plan) }}" class="btn btn-success">
                        Download
                    </a>
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
                            <th>Document Name</th>
                            <th>Status</th>
                            <th>Uploaded Date</th>
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($estatePlans as $plan)
                        <tr>
                            <td>{{ $plan->original_filename }}</td>
                            <td>{!! $plan->getStatusBadge() !!}</td>
                            <td>
                                {{ $plan->created_at->format('M j, Y') }}
                                @if($plan->executed_at)
                                    <br><small style="color: #28a745;">✓ Executed: {{ $plan->executed_at->format('M j, Y') }}</small>
                                @endif
                            </td>
                            <td>{{ $plan->getFileSizeFormatted() }}</td>
                            <td>
                                <a href="{{ route('estate-plans.view', $plan) }}" class="btn btn-primary" style="padding: 8px 16px;" target="_blank">
                                    View PDF
                                </a>
                                <a href="{{ route('estate-plans.download', $plan) }}" class="btn btn-success" style="padding: 8px 16px;">
                                    Download
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <p style="color: #666;">No estate plan documents have been uploaded yet. Your attorney will upload completed documents here.</p>
    @endif
</div>
@endsection
