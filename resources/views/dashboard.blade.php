@extends('layouts.app')

@section('title', 'Dashboard - Estate Planning')

@section('content')
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
        
        <div style="margin-top: 20px;">
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
            <a href="{{ route('intake.show') }}" class="btn btn-primary">
                Continue Intake Form
            </a>
        </div>
    @else
        <div class="alert alert-info">
            You haven't started your intake form yet
        </div>
        
        <div style="margin-top: 20px;">
            <a href="{{ route('intake.show') }}" class="btn btn-primary">
                Start Intake Form
            </a>
        </div>
    @endif
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Estate Plan Documents</h2>
    
    @if($estatePlans->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Document Name</th>
                    <th>Uploaded Date</th>
                    <th>Size</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estatePlans as $plan)
                <tr>
                    <td>{{ $plan->original_filename }}</td>
                    <td>{{ $plan->created_at->format('M j, Y') }}</td>
                    <td>{{ $plan->getFileSizeFormatted() }}</td>
                    <td>
                        <a href="{{ route('estate-plans.download', $plan) }}" class="btn btn-success" style="padding: 8px 16px;">
                            Download
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #666;">No estate plan documents have been uploaded yet. Your attorney will upload completed documents here.</p>
    @endif
</div>
@endsection
