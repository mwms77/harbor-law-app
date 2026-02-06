# Estate Planning Application - Complete Deployment Package

## REMAINING FILES TO CREATE

Due to the comprehensive nature of this application, here are the remaining critical files you'll need to create. I'll provide detailed instructions and code samples for each.

### 1. INTAKE FORM VIEW
**File:** `resources/views/intake/form.blade.php`

This file should contain the entire intake form from your uploaded HTML. The key modifications needed:

1. Wrap the form in the Blade layout:
```blade
@extends('layouts.app')
@section('content')
<!-- Your HTML form content here -->
@endsection
```

2. Add CSRF protection to the form:
```html
<form id="intakeForm">
    @csrf
    <!-- rest of form -->
</form>
```

3. Modify the JavaScript submission functions to use Laravel routes:
```javascript
// Replace the submitToHarborLaw function with:
function submitForm() {
    const formData = new FormData(document.getElementById('intakeForm'));
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    // Get checkboxes
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => {
        data[cb.name] = cb.checked;
    });
    
    fetch('{{ route('intake.submit') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ form_data: data })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = data.redirect;
        }
    });
}

// Add auto-save functionality every 30 seconds:
setInterval(() => {
    const formData = new FormData(document.getElementById('intakeForm'));
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => {
        data[cb.name] = cb.checked;
    });
    
    fetch('{{ route('intake.save') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            form_data: data,
            current_section: currentSection,
            progress_percentage: Math.round((currentSection / 12) * 100)
        })
    });
}, 30000); // Every 30 seconds

// Load existing data on page load:
window.addEventListener('load', function() {
    const savedData = @json($submission->form_data ?? []);
    
    Object.keys(savedData).forEach(key => {
        const field = document.querySelector(`[name="${key}"]`);
        if (field) {
            if (field.type === 'checkbox') {
                field.checked = savedData[key];
            } else {
                field.value = savedData[key];
            }
        }
    });
});
```

### 2. ADMIN VIEWS

#### File: `resources/views/admin/dashboard.blade.php`
```blade
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
```

#### File: `resources/views/admin/users/index.blade.php`
```blade
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
```

#### File: `resources/views/admin/users/show.blade.php`
```blade
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
```

#### File: `resources/views/admin/settings.blade.php`
```blade
@extends('layouts.app')

@section('title', 'Settings')

@section('content')
<div class="header">
    <h1>Application Settings</h1>
    <p>Configure your estate planning application</p>
</div>

<div class="card">
    <h2 style="color: #667eea; margin-bottom: 20px;">Company Logo</h2>
    
    @if($logo)
        <div style="margin-bottom: 20px;">
            <img src="{{ Storage::url($logo) }}" alt="Company Logo" style="max-width: 300px; border: 1px solid #e0e0e0; padding: 10px; border-radius: 6px;">
        </div>
        
        <form action="{{ route('admin.settings.delete-logo') }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete the logo?')">
                Delete Logo
            </button>
        </form>
    @else
        <p style="color: #666; margin-bottom: 20px;">No logo uploaded</p>
    @endif
    
    <form action="{{ route('admin.settings.upload-logo') }}" method="POST" enctype="multipart/form-data" style="margin-top: 30px;">
        @csrf
        
        <div class="form-group">
            <label for="logo">Upload New Logo</label>
            <input type="file" id="logo" name="logo" accept="image/*" required>
            <small style="color: #666; font-size: 12px; display: block; margin-top: 5px;">
                Recommended size: 300x100px. Formats: PNG, JPG, SVG
            </small>
        </div>
        
        <button type="submit" class="btn btn-primary">Upload Logo</button>
    </form>
</div>
@endsection
```

### 3. LARAVEL CONFIGURATION FILES

#### File: `app/Http/Kernel.php`
Add the admin middleware to the route middleware array:

```php
protected $routeMiddleware = [
    // ... existing middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

#### File: `app/Providers/RouteServiceProvider.php`
Set the home path:

```php
public const HOME = '/dashboard';
```

### 4. ADDITIONAL CONFIGURATION

#### Create storage directories:
```bash
mkdir -p storage/app/private/intakes
mkdir -p storage/app/private/estate-plans
mkdir -p storage/app/public/logos
```

#### Set proper permissions:
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 5. VITE CONFIGURATION

#### File: `vite.config.js`
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```

### 6. TAILWIND CONFIGURATION (Optional, already using inline styles)

#### File: `tailwind.config.js`
```javascript
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
```

## DEPLOYMENT CHECKLIST

1. âœ“ Clone repository to your VPS
2. âœ“ Run `composer install --no-dev`
3. âœ“ Run `npm install && npm run build`
4. âœ“ Copy `.env.example` to `.env`
5. âœ“ Configure database credentials in `.env`
6. âœ“ Run `php artisan key:generate`
7. âœ“ Run `php artisan migrate`
8. âœ“ Run `php artisan db:seed`
9. âœ“ Run `php artisan storage:link`
10. âœ“ Set file permissions
11. âœ“ Configure Laravel Forge
12. âœ“ Install SSL certificate
13. âœ“ Change admin password
14. âœ“ Test application thoroughly

## SECURITY NOTES

1. Always use HTTPS in production
2. Change the default admin password immediately
3. Set `APP_DEBUG=false` in production
4. Keep Laravel and dependencies updated
5. Regularly backup database and files
6. Monitor application logs
7. Use strong passwords for all accounts
8. Consider implementing 2FA for admin accounts

## BACKUP STRATEGY

Set up automated backups in Laravel Forge or create a cron job:

```bash
# Daily backup at 2 AM
0 2 * * * cd /home/forge/yourdomain.com && php artisan backup:run
```

## SUPPORT

For assistance, refer to:
- Laravel Documentation: https://laravel.com/docs
- Laravel Forge Documentation: https://forge.laravel.com/docs
- This README file

---

**IMPORTANT:** The intake form view needs to be created by copying your uploaded HTML file and making the modifications described in section #1 above.
