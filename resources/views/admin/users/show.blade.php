@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="header">
    <h1>{{ $user->name }}</h1>
    <p>{{ $user->email }}</p>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">User Information</h2>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div>
            <strong>Registered:</strong> {{ $user->created_at->format('F j, Y') }}
        </div>
        <div>
            <strong>Status:</strong>
            @if($user->is_active)
                <span class="badge badge-success">Active</span>
            @else
                <span class="badge badge-danger">Inactive</span>
            @endif
        </div>
    </div>
    
    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" style="display: inline;">
        @csrf
        <button type="submit" class="btn btn-secondary">
            {{ $user->is_active ? 'Deactivate User' : 'Activate User' }}
        </button>
    </form>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Intake Form</h2>
    
    @if($user->intakeSubmission && $user->intakeSubmission->is_completed)
        <div class="alert alert-success">
            Completed on {{ $user->intakeSubmission->completed_at->format('F j, Y') }}
        </div>
        
        <a href="{{ route('admin.users.download-intake', $user) }}" class="btn btn-primary">
            Download Intake Data (JSON)
        </a>
    @elseif($user->intakeSubmission)
        <div class="alert alert-warning">
            In Progress: {{ $user->intakeSubmission->progress_percentage }}% complete
        </div>
    @else
        <p>User has not started the intake form.</p>
    @endif
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Estate Plan Documents</h2>
    
    <form action="{{ route('admin.users.upload-plan', $user) }}" method="POST" enctype="multipart/form-data" style="margin-bottom: 30px;">
        @csrf
        
        <div class="form-group">
            <label for="file">Upload Estate Plan (PDF)</label>
            <input type="file" id="file" name="file" accept=".pdf" required>
        </div>
        
        <div class="form-group">
            <label for="notes">Notes (Optional)</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Add any notes about this document..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Document</button>
    </form>
    
    @if($user->estatePlans->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Document</th>
                    <th>Size</th>
                    <th>Uploaded</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($user->estatePlans as $plan)
                <tr>
                    <td>{{ $plan->original_filename }}</td>
                    <td>{{ $plan->getFileSizeFormatted() }}</td>
                    <td>{{ $plan->created_at->format('M j, Y') }}</td>
                    <td>{{ $plan->notes ?? '-' }}</td>
                    <td>
                        <a href="{{ route('estate-plans.download', $plan) }}" class="btn btn-success" style="padding: 6px 12px; font-size: 12px;">
                            Download
                        </a>
                        <form action="{{ route('admin.users.delete-plan', [$user, $plan]) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px;" onclick="return confirm('Are you sure?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No documents uploaded yet.</p>
    @endif
</div>
@endsection
