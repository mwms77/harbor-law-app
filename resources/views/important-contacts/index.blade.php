@extends('layouts.app')

@section('title', 'Important Contacts')

@section('content')
<div class="header">
    <h1>Important Contacts</h1>
    <p>Your key contacts for estate planning — trustees, representatives, guardians, and advisors</p>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card" style="margin-bottom: 20px;">
    <div style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
        <a href="{{ route('important-contacts.create') }}" class="btn btn-primary">Add Contact</a>
        <a href="{{ route('important-contacts.pdf') }}" class="btn btn-primary">Download as PDF</a>
        @if(auth()->user()->intakeFiduciaries()->exists())
            <form action="{{ route('important-contacts.import') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">Import from Intake Form</button>
            </form>
        @endif
    </div>
    @if(auth()->user()->intakeFiduciaries()->exists())
        <p style="color: #666; font-size: 14px; margin-top: 12px;">If you completed the intake form, use "Import from Intake Form" to copy those contacts here so you can edit and keep them in one place.</p>
    @endif
</div>

@php
    $order = array_keys(\App\Models\ImportantContact::allowedRoleTypes());
@endphp

@if($contactsByRole->isEmpty())
    <div class="card">
        <p style="color: #666; margin-bottom: 20px;">You don't have any important contacts saved yet.</p>
        @if(auth()->user()->intakeFiduciaries()->exists())
            <p>Click <strong>Import from Intake Form</strong> above to copy your trustees, representatives, guardians, and other contacts from your intake form, then edit them here.</p>
        @else
            <p>Use <strong>Add Contact</strong> above to add your lawyer, CPA, or other contacts. You can also complete the <a href="{{ route('intake.show') }}" style="color: #667eea;">Intake Form</a> and then <strong>Import from Intake Form</strong> to copy trustees, representatives, and guardians here.</p>
        @endif
    </div>
@else
    @foreach($order as $roleType)
        @if($contactsByRole->has($roleType))
            @php $contacts = $contactsByRole->get($roleType); $label = $roleLabels[$roleType] ?? ucwords(str_replace('_', ' ', $roleType)); @endphp
            <div class="card" style="margin-bottom: 20px;">
                <h2 style="color: #667eea; margin-bottom: 16px; font-size: 20px;">{{ $label }}</h2>
                <div class="contact-list">
                    @foreach($contacts as $contact)
                        <div class="contact-item" style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; margin-bottom: 12px; background: #fafafa;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
                                <div style="flex: 1; min-width: 0;">
                                    <strong style="font-size: 16px; color: #333;">{{ $contact->full_name }}</strong>
                                    @if($contact->relationship)
                                        <span style="color: #666; font-size: 14px;"> — {{ $contact->relationship }}</span>
                                    @endif
                                    <div style="margin-top: 8px; font-size: 14px; color: #555;">
                                        @if($contact->phone)
                                            <div>Phone: {{ $contact->phone }}</div>
                                        @endif
                                        @if($contact->email)
                                            <div>Email: <a href="mailto:{{ $contact->email }}" style="color: #667eea;">{{ $contact->email }}</a></div>
                                        @endif
                                        @if($contact->address)
                                            <div>Address: {{ $contact->address }}</div>
                                        @endif
                                        @if(!$contact->phone && !$contact->email && !$contact->address)
                                            <span style="color: #999;">No contact details yet</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('important-contacts.edit', $contact) }}" class="btn btn-secondary" style="flex-shrink: 0;">Edit</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif

<div class="card" style="background: #f8f9fa; border: 1px solid #e0e0e0;">
    <h3 style="color: #667eea; margin-bottom: 10px; font-size: 16px;">Contact types</h3>
    <p style="color: #666; font-size: 14px; margin: 0;">Trustees, Personal Representatives, Guardians, Patient Advocate, Financial POA, Primary Lawyer, Primary CPA, and Other Contact (with your own description). Use <strong>Add Contact</strong> to add any of these, or <strong>Import from Intake Form</strong> to copy contacts from your intake.</p>
</div>
@endsection
