# Estate Planning Application - Quick Start Guide

## Overview

This is a complete Laravel-based estate planning web application with:
- User registration and authentication
- Multi-step intake form (based on your Michigan Estate Planning form)
- Admin dashboard for managing users and documents
- Secure file upload/download for estate plans
- Purple gradient theme matching your original design

## What's Included

### Backend (Laravel)
- ✓ Complete authentication system
- ✓ User and admin role separation
- ✓ Database migrations for all tables
- ✓ Models with relationships
- ✓ Controllers for all features
- ✓ Secure file storage system
- ✓ API routes for AJAX operations

### Frontend (Blade Templates)
- ✓ Responsive design with purple gradient theme
- ✓ Login and registration pages
- ✓ User dashboard
- ✓ Admin dashboard
- ✓ User management interface
- ✓ Settings page with logo upload

### Security Features
- ✓ CSRF protection
- ✓ SQL injection prevention (Eloquent ORM)
- ✓ XSS protection (Blade escaping)
- ✓ Password hashing (Bcrypt)
- ✓ Role-based access control
- ✓ Encrypted file storage
- ✓ Session security

## File Structure

```
estate-planning-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   └── RegisterController.php
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── SettingsController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── IntakeController.php
│   │   │   └── EstatePlanController.php
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── IntakeSubmission.php
│       ├── EstatePlan.php
│       └── Setting.php
├── config/
│   └── filesystems.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_users_table.php
│   │   ├── 2024_01_01_000002_create_intake_submissions_table.php
│   │   ├── 2024_01_01_000003_create_estate_plans_table.php
│   │   └── 2024_01_01_000004_create_settings_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── intake/
│       │   └── form.blade.php (YOU NEED TO CREATE THIS)
│       ├── admin/
│       │   ├── dashboard.blade.php (code in DEPLOYMENT_GUIDE.md)
│       │   ├── settings.blade.php (code in DEPLOYMENT_GUIDE.md)
│       │   └── users/
│       │       ├── index.blade.php (code in DEPLOYMENT_GUIDE.md)
│       │       └── show.blade.php (code in DEPLOYMENT_GUIDE.md)
│       ├── dashboard.blade.php
│       └── welcome.blade.php
├── routes/
│   └── web.php
├── .env.example
├── .gitignore
├── composer.json
├── package.json
├── README.md
├── DEPLOYMENT_GUIDE.md
└── setup.sh
```

## Installation Steps

### 1. Upload to Your Server

Upload all files to your Laravel Forge server:

```bash
# Via Git (recommended)
git init
git add .
git commit -m "Initial commit"
git remote add origin your-repo-url
git push -u origin main

# Then in Forge, connect your repository
```

### 2. Run Setup Script

SSH into your server and run:

```bash
cd /home/forge/yourdomain.com
chmod +x setup.sh
./setup.sh
```

### 3. Configure Environment

Edit the `.env` file:

```bash
nano .env
```

Update these critical values:
```
APP_URL=https://yourdomain.com
DB_DATABASE=estate_planning
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 4. Run Migrations

```bash
php artisan migrate
php artisan db:seed
```

### 5. Build Frontend Assets

```bash
npm run build
```

### 6. Create Storage Link

```bash
php artisan storage:link
```

### 7. Set Permissions

```bash
chmod -R 775 storage bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache
```

## Critical Next Steps

### 1. CREATE THE INTAKE FORM

**This is the most important step!**

Create the file: `resources/views/intake/form.blade.php`

1. Copy your entire uploaded HTML file
2. Wrap it in Blade layout:
   ```blade
   @extends('layouts.app')
   @section('content')
   <!-- Your HTML here -->
   @endsection
   ```
3. Add CSRF token to the form
4. Modify JavaScript as described in DEPLOYMENT_GUIDE.md
5. Add auto-save functionality
6. Add form data loading from database

**See DEPLOYMENT_GUIDE.md section #1 for detailed code examples.**

### 2. CREATE ADMIN VIEWS

Create these files in `resources/views/admin/`:
- `dashboard.blade.php`
- `settings.blade.php`
- `users/index.blade.php`
- `users/show.blade.php`

**All code is provided in DEPLOYMENT_GUIDE.md section #2.**

### 3. Configure Laravel Forge

In your Forge panel:

1. **Site Settings**
   - Web Directory: `/public`
   - PHP Version: 8.1+

2. **SSL Certificate**
   - Install Let's Encrypt certificate
   - Force HTTPS

3. **Environment Variables**
   - Add all variables from `.env`

4. **Deployment Script**
   ```bash
   cd /home/forge/yourdomain.com
   git pull origin main
   composer install --no-dev --optimize-autoloader
   npm install
   npm run build
   php artisan migrate --force
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Queue Worker** (if needed)
   - Not required initially

6. **Scheduler** (for backups)
   - Enable Laravel scheduler

### 4. Change Admin Password

**CRITICAL SECURITY STEP**

1. Login at: `https://yourdomain.com/login`
2. Use credentials:
   - Email: `admin@estate.local`
   - Password: `ChangeMe123!`
3. Immediately change password
4. Consider changing email too

## Testing Checklist

Test these features before going live:

- [ ] User registration works
- [ ] User login works
- [ ] Admin login works
- [ ] Intake form loads
- [ ] Intake form saves progress
- [ ] Intake form submits successfully
- [ ] User can download their intake data
- [ ] Admin can view all users
- [ ] Admin can download user intake data
- [ ] Admin can upload PDF estate plans
- [ ] User can download their estate plans
- [ ] Logo upload works
- [ ] File downloads are secure (users can only access their own files)
- [ ] Admin panel is protected
- [ ] HTTPS is enforced

## User Flow

### For Clients (Users)

1. Visit your website
2. Click "Register" → Create account
3. Login → Redirected to dashboard
4. Click "Start Intake Form"
5. Complete multi-step form (auto-saves every 30 seconds)
6. Submit completed form
7. Download their submission as JSON
8. Receive notification when estate plan is ready
9. Download completed estate plan PDF

### For You (Admin)

1. Login at `/admin/login`
2. View dashboard with statistics
3. Click "Users" → See all registered users
4. Click on a user → View their details
5. Download their intake data (JSON)
6. Upload completed estate plan (PDF)
7. User receives access to download

## Troubleshooting

### Common Issues

**500 Error**
- Check `.env` file is configured
- Run: `php artisan config:clear`
- Check file permissions: `chmod -R 775 storage`

**Database Connection Error**
- Verify database credentials in `.env`
- Ensure PostgreSQL is running
- Check database exists

**CSS Not Loading**
- Run: `npm run build`
- Clear browser cache
- Check `APP_URL` in `.env`

**File Upload Fails**
- Check storage permissions: `chmod -R 775 storage`
- Check disk space
- Verify upload limits in `php.ini`

**Can't Login**
- Run migrations: `php artisan migrate`
- Run seeder: `php artisan db:seed`
- Check admin user exists in database

### Getting Help

1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode temporarily: `APP_DEBUG=true`
3. Check web server logs
4. Review DEPLOYMENT_GUIDE.md

## File Size Limits

Current limits:
- Estate Plan PDF: 10MB maximum
- Logo: 2MB maximum
- Form data: Unlimited (JSON storage)

To increase limits, edit:
- PHP configuration: `upload_max_filesize` and `post_max_size`
- Nginx/Apache configuration

## Backup Recommendations

1. **Database**: Daily automated backups via Forge
2. **Files**: Weekly backups of `storage/app/private`
3. **Code**: Use Git for version control

## Security Best Practices

1. ✓ Always use HTTPS
2. ✓ Change default admin credentials
3. ✓ Keep Laravel updated
4. ✓ Set `APP_DEBUG=false` in production
5. ✓ Use strong passwords
6. ✓ Regular backups
7. ✓ Monitor logs for suspicious activity
8. ✓ Keep PHP and dependencies updated

## Performance Optimization

After deployment, optimize:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
composer install --optimize-autoloader --no-dev
```

## Next Features to Consider

Future enhancements you might want:
- Email notifications when estate plans are uploaded
- Document versioning
- E-signature integration
- Payment processing
- Client portal with messaging
- Document expiration tracking
- Bulk user import
- Advanced reporting

## Support

For questions or issues:
1. Check this documentation
2. Review DEPLOYMENT_GUIDE.md
3. Check Laravel documentation: https://laravel.com/docs
4. Contact your development team

---

## IMPORTANT REMINDERS

1. **Create the intake form view** - This is required for the app to work
2. **Create the admin views** - Code provided in DEPLOYMENT_GUIDE.md
3. **Change the admin password** - Critical security step
4. **Enable SSL** - Required for security
5. **Test thoroughly** - Use the testing checklist above

Good luck with your deployment! 🚀
