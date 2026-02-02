@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="header">
    <h1>User Management</h1>
    <p>View and manage all registered users</p>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Intake Status</th>
                <th>Estate Plans</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->intakeSubmission && $user->intakeSubmission->is_completed)
                        <span class="badge badge-success">Completed</span>
                    @elseif($user->intakeSubmission)
                        <span class="badge badge-warning">{{ $user->intakeSubmission->progress_percentage }}%</span>
                    @else
                        <span class="badge badge-danger">Not Started</span>
                    @endif
                </td>
                <td>{{ $user->estatePlans->count() }}</td>
                <td>
                    @if($user->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-danger">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">
                        Manage
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>
@endsection
