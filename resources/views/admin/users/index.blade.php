@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>User Management</h1>
            <p>View and manage all users</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">+ Add New User</a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
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
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
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
                        @if($user->intakeSubmission && $user->intakeSubmission->is_completed)
                            <span style="color: #28a745;">✓ Completed</span>
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
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user? This will delete all their data including intake forms and estate plans.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="font-size: 12px; padding: 6px 12px;">Delete</button>
                            </form>
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

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
