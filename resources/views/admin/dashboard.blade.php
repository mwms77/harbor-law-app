@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    /* Responsive stats grid */
    .stats-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
        margin-bottom: 30px;
    }
    
    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
    
    /* Hide table on mobile, show cards */
    @media (max-width: 767px) {
        .desktop-table { display: none !important; }
        .mobile-cards { display: block !important; }
    }
    
    @media (min-width: 768px) {
        .desktop-table { display: block !important; }
        .mobile-cards { display: none !important; }
    }
    
    /* Mobile card styles */
    .user-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .user-card-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    
    .user-card-email {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
        word-break: break-word;
    }
    
    .user-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 12px;
        font-size: 13px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .user-card .btn {
        display: block;
        width: 100%;
        text-align: center;
        padding: 10px;
    }
</style>

<div class="header">
    <h1>Admin Dashboard</h1>
    <p>Estate Planning Application Management</p>
</div>

{{-- Stats Grid --}}
<div class="stats-grid">
    <div class="card">
        <h3 style="color: #667eea; margin-bottom: 10px; font-size: 14px;">Total Users</h3>
        <p style="font-size: 28px; font-weight: bold; color: #333;">{{ $stats['total_users'] }}</p>
    </div>
    
    <div class="card">
        <h3 style="color: #28a745; margin-bottom: 10px; font-size: 14px;">Completed Intakes</h3>
        <p style="font-size: 28px; font-weight: bold; color: #333;">{{ $stats['completed_intakes'] }}</p>
    </div>
    
    <div class="card">
        <h3 style="color: #ffc107; margin-bottom: 10px; font-size: 14px;">Pending Intakes</h3>
        <p style="font-size: 28px; font-weight: bold; color: #333;">{{ $stats['pending_intakes'] }}</p>
    </div>
    
    <div class="card">
        <h3 style="color: #667eea; margin-bottom: 10px; font-size: 14px;">Uploaded Plans</h3>
        <p style="font-size: 28px; font-weight: bold; color: #333;">{{ $stats['uploaded_plans'] }}</p>
    </div>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Recent Users</h2>
    
    {{-- MOBILE: Cards --}}
    <div class="mobile-cards" style="display: none;">
        @foreach($recentUsers as $user)
        <div class="user-card">
            <div class="user-card-name">{{ $user->name }}</div>
            <div class="user-card-email">{{ $user->email }}</div>
            <div class="user-card-meta">
                <div>
                    <strong>Intake:</strong>
                    @if($user->intakeSubmission && $user->intakeSubmission->is_completed)
                        <span class="badge badge-success">Completed</span>
                    @elseif($user->intakeSubmission)
                        <span class="badge badge-warning">{{ $user->intakeSubmission->progress_percentage }}%</span>
                    @else
                        <span class="badge badge-danger">Not Started</span>
                    @endif
                </div>
                <div><strong>Joined:</strong> {{ $user->created_at->format('M j, Y') }}</div>
            </div>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary">
                View Details
            </a>
        </div>
        @endforeach
    </div>
    
    {{-- DESKTOP: Table --}}
    <div class="desktop-table" style="display: none;">
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Intake Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->intakeSubmission && $user->intakeSubmission->is_completed)
                                <span class="badge badge-success">Completed</span>
                            @elseif($user->intakeSubmission)
                                <span class="badge badge-warning">In Progress ({{ $user->intakeSubmission->progress_percentage }}%)</span>
                            @else
                                <span class="badge badge-danger">Not Started</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('M j, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
