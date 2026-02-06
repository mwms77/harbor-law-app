@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<style>
    /* Responsive: Hide table on mobile, show cards */
    @media (max-width: 1023px) {
        .desktop-table { display: none !important; }
        .mobile-cards { display: block !important; }
    }
    
    @media (min-width: 1024px) {
        .desktop-table { display: block !important; }
        .mobile-cards { display: none !important; }
    }
    
    /* Mobile user cards */
    .user-card {
        background: white;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .user-card.deleted {
        opacity: 0.6;
        background: #f8f9fa;
    }
    
    .user-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    
    .user-card-name {
        font-size: 16px;
        font-weight: 600;
        color: #333;
        margin-bottom: 4px;
    }
    
    .user-card-email {
        font-size: 14px;
        color: #666;
        word-break: break-word;
    }
    
    .user-card-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 12px;
        padding: 10px 0;
        border-top: 1px solid #f0f0f0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .user-card-meta-item {
        font-size: 13px;
    }
    
    .user-card-meta-label {
        color: #999;
        font-size: 11px;
        text-transform: uppercase;
        display: block;
        margin-bottom: 4px;
    }
    
    .user-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }
    
    .user-card .btn {
        font-size: 13px;
        padding: 8px 12px;
        text-align: center;
        display: block;
        width: 100%;
    }
    
    .user-card-actions-full {
        margin-top: 8px;
    }
    
    .user-card-actions-full .btn {
        width: 100%;
    }
</style>

<div class="header">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1>User Management</h1>
            <p>View and manage all users</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success" style="white-space: nowrap;">
            + Add New User
        </a>
    </div>
</div>

<div class="card">
    {{-- MOBILE: Cards --}}
    <div class="mobile-cards" style="display: none;">
        @forelse($users as $user)
        <div class="user-card {{ $user->trashed() ? 'deleted' : '' }}">
            {{-- Header --}}
            <div class="user-card-header">
                <div style="flex: 1; min-width: 0;">
                    <div class="user-card-name">
                        {{ $user->name }}
                        @if($user->trashed())
                            <span style="color: #dc3545; font-size: 11px;">(Deleted)</span>
                        @endif
                    </div>
                    <div class="user-card-email">{{ $user->email }}</div>
                </div>
                <div style="margin-left: 10px;">
                    <span class="badge" style="background: {{ $user->isAdmin() ? '#667eea' : '#28a745' }}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; white-space: nowrap;">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
            
            {{-- Meta Info --}}
            <div class="user-card-meta">
                <div class="user-card-meta-item">
                    <span class="user-card-meta-label">Status</span>
                    <span class="badge" style="background: {{ $user->is_active ? '#28a745' : '#dc3545' }}; color: white; padding: 3px 8px; border-radius: 4px; font-size: 11px;">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                
                <div class="user-card-meta-item">
                    <span class="user-card-meta-label">Intake</span>
                    @if($user->intakeSubmission)
                        @if($user->intakeSubmission->is_completed)
                            <span style="color: #28a745; font-weight: 600; font-size: 12px;">âœ“ Done</span>
                        @else
                            <span style="color: #667eea; font-weight: 600; font-size: 12px;">{{ $user->intakeSubmission->progress_percentage }}%</span>
                        @endif
                    @else
                        <span style="color: #6c757d; font-size: 12px;">Not Started</span>
                    @endif
                </div>
                
                <div class="user-card-meta-item">
                    <span class="user-card-meta-label">Plans</span>
                    <span style="font-weight: 600;">{{ $user->estatePlans->count() }}</span>
                </div>
                
                <div class="user-card-meta-item">
                    <span class="user-card-meta-label">Joined</span>
                    <span style="font-size: 12px;">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            
            {{-- Actions --}}
            <div class="user-card-actions">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary">View</a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary">Edit</a>
            </div>
            
            @if($user->id !== auth()->id())
                <div class="user-card-actions-full">
                    @if(!$user->trashed())
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                </div>
            @endif
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: #999;">
            No users found. <a href="{{ route('admin.users.create') }}">Add your first user</a>
        </div>
        @endforelse
    </div>

    {{-- DESKTOP: Table --}}
    <div class="desktop-table" style="display: none;">
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Intake</th>
                        <th>Plans</th>
                        <th>Joined</th>
                        <th style="text-align: center;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr style="{{ $user->trashed() ? 'opacity: 0.6; background: #f8f9fa;' : '' }}">
                        <td>
                            <strong>{{ $user->name }}</strong>
                            @if($user->trashed())
                                <span style="color: #dc3545; font-size: 11px; display: block;">(Deleted)</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge" style="background: {{ $user->isAdmin() ? '#667eea' : '#28a745' }}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $user->is_active ? '#28a745' : '#dc3545' }}; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            @if($user->intakeSubmission)
                                @if($user->intakeSubmission->is_completed)
                                    <span style="color: #28a745; font-weight: 600;">âœ“ Completed</span>
                                @else
                                    <span style="color: #667eea; font-weight: 600;">{{ $user->intakeSubmission->progress_percentage }}%</span>
                                @endif
                            @else
                                <span style="color: #6c757d;">Not Started</span>
                            @endif
                        </td>
                        <td>{{ $user->estatePlans->count() }}</td>
                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary" style="font-size: 12px; padding: 6px 12px;">View</a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary" style="font-size: 12px; padding: 6px 12px;">Edit</a>
                                
                                @if($user->id !== auth()->id())
                                    @if($user->trashed())
                                        <form action="{{ route('admin.users.force-destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('PERMANENTLY DELETE?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn" style="background: #8b0000; color: white; font-size: 12px; padding: 6px 12px;">Hard Delete</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 6px 12px;">Delete</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                            No users found. <a href="{{ route('admin.users.create') }}">Add your first user</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

@endsection
