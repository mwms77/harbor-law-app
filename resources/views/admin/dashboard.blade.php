@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="header">
    <h1>Admin Dashboard</h1>
    <p>Estate Planning Application Management</p>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <div class="card">
        <h3 style="color: #667eea; margin-bottom: 10px;">Total Users</h3>
        <p style="font-size: 36px; font-weight: bold; color: #333;">{{ $stats['total_users'] }}</p>
    </div>
    
    <div class="card">
        <h3 style="color: #28a745; margin-bottom: 10px;">Completed Intakes</h3>
        <p style="font-size: 36px; font-weight: bold; color: #333;">{{ $stats['completed_intakes'] }}</p>
    </div>
    
    <div class="card">
        <h3 style="color: #ffc107; margin-bottom: 10px;">Pending Intakes</h3>
        <p style="font-size: 36px; font-weight: bold; color: #333;">{{ $stats['pending_intakes'] }}</p>
    </div>
    
    <div class="card">
        <h3 style="color: #667eea; margin-bottom: 10px;">Uploaded Plans</h3>
        <p style="font-size: 36px; font-weight: bold; color: #333;">{{ $stats['uploaded_plans'] }}</p>
    </div>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Recent Users</h2>
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
@endsection
