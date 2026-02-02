@extends('layouts.app')

@section('title', 'Welcome - Estate Planning')

@section('content')
<div class="card" style="max-width: 800px; margin: 100px auto;">
    <div class="header">
        <h1>Estate Planning Application</h1>
        <p>Secure, confidential estate planning made simple</p>
    </div>

    <div style="text-align: center; padding: 30px 0;">
        <p style="font-size: 18px; color: #555; margin-bottom: 30px;">
            Create your comprehensive estate plan in a secure, easy-to-use platform.
        </p>

        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
            <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
        </div>
    </div>

    <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e0e0e0;">
        <h3 style="color: #667eea; margin-bottom: 20px;">Features:</h3>
        <ul style="list-style: none; padding: 0;">
            <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                ✓ Secure account creation and login
            </li>
            <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                ✓ Comprehensive intake questionnaire
            </li>
            <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                ✓ Auto-save functionality
            </li>
            <li style="padding: 10px 0; border-bottom: 1px solid #f0f0f0;">
                ✓ Download your submitted information
            </li>
            <li style="padding: 10px 0;">
                ✓ Access completed estate plan documents
            </li>
        </ul>
    </div>
</div>
@endsection
