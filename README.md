# Estate Planning Application

A secure Laravel-based estate planning web application for collecting sensitive client information and managing estate plan documents.

## Features

- **User Authentication**: Secure registration and login
- **Multi-step Intake Form**: Comprehensive estate planning questionnaire
- **Admin Dashboard**: Manage users and their documents
- **File Management**: Upload/download completed estate plans (PDF)
- **Security**: Encryption, HTTPS, CSRF protection, role-based access
- **Responsive Design**: Purple gradient theme matching original design

## Technology Stack

- Laravel 10.x
- PostgreSQL
- Tailwind CSS
- Alpine.js
- Laravel Sanctum for authentication

## Installation

### Prerequisites

- PHP 8.1+
- Composer
- PostgreSQL
- Node.js & NPM

### Step 1: Clone and Install Dependencies

```bash
composer install
npm install
npm run build
```

### Step 2: Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:

```env
APP_NAME="Estate Planning"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=estate_planning
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Step 3: Database Setup

```bash
php artisan migrate
php artisan db:seed
```

This will create an admin user:
- Email: admin@estate.local
- Password: ChangeMe123!

**IMPORTANT**: Change this password immediately after first login!

### Step 4: Storage Setup

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

### Step 5: Laravel Forge Deployment

1. Create a new site in Forge
2. Set the web directory to `/public`
3. Enable "Quick Deploy"
4. Add deployment script:

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
php artisan storage:link
```

5. Set up SSL certificate (Let's Encrypt)
6. Configure your database in Forge
7. Set environment variables in Forge

### Step 6: Security Checklist

- [ ] SSL certificate installed and enforced
- [ ] Change default admin password
- [ ] Set `APP_DEBUG=false` in production
- [ ] Configure firewall rules
- [ ] Enable PostgreSQL encryption at rest
- [ ] Set up regular backups
- [ ] Configure fail2ban
- [ ] Review file permissions

## Usage

### Admin Access

Navigate to `/admin/login` to access the admin dashboard.

Admin capabilities:
- View all users
- Download user intake data (JSON)
- Upload completed estate plans (PDF)
- Manage user accounts

### User Access

Users register at `/register` and login at `/login`.

User capabilities:
- Complete multi-step intake form
- Save progress automatically
- Download their submitted data
- Download completed estate plans (when admin uploads them)

### Logo Upload

Admin can upload a company logo at `/admin/settings`:
- Supported formats: PNG, JPG, SVG
- Recommended size: 300x100px
- Used in PDF documents

## File Storage

All files are stored in `storage/app/private`:
- `intakes/` - User intake data (JSON)
- `estate-plans/` - Completed plans (PDF)
- `logos/` - Company logo

Files are encrypted and only accessible through the application.

## Security Features

1. **Authentication**: Laravel Sanctum with session-based auth
2. **Authorization**: Role-based access control (admin/user)
3. **Encryption**: All sensitive files encrypted at rest
4. **CSRF Protection**: Enabled on all forms
5. **SQL Injection Prevention**: Eloquent ORM with prepared statements
6. **XSS Protection**: Blade templating auto-escapes output
7. **HTTPS Enforcement**: Middleware forces SSL in production
8. **Password Hashing**: Bcrypt with salt
9. **Rate Limiting**: Login attempts throttled
10. **Input Validation**: Server-side validation on all inputs

## Maintenance

### Backups

Set up automated backups in Forge or use:

```bash
php artisan backup:run
```

### Logs

Monitor application logs:

```bash
tail -f storage/logs/laravel.log
```

### Updates

```bash
composer update
php artisan migrate
php artisan cache:clear
```

## Support

For issues or questions, contact your development team.

## License

Proprietary - All rights reserved
