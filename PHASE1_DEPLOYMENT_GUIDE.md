# Harbor Law Estate Planning - Phase 1 Deployment Guide

## Overview
This deployment adds three major features to the Harbor Law Estate Planning application:
1. **Client Document Upload System** - Secure file uploads with categorization
2. **Email Notifications** - Automated emails for key events
3. **Enhanced Admin Dashboard** - Better user management and document oversight

## Pre-Deployment Checklist

### 1. Backup Current System
- [ ] Create database backup via Laravel Forge
- [ ] Create VPS snapshot (optional but recommended)
- [ ] Verify backup was successful

### 2. Update .env File
Add these new variables to your production `.env` file:

```env
# Admin Email (already exists, verify it's correct)
ADMIN_EMAIL=matt@harbor.law

# File Upload Settings
UPLOAD_MAX_SIZE=10240
UPLOAD_ALLOWED_MIMES=pdf,jpg,jpeg,png,heic

# Queue Connection (verify this is set)
QUEUE_CONNECTION=database
```

### 3. Email Configuration
Verify your Amazon SES credentials are configured in `.env`:

```env
MAIL_MAILER=ses
MAIL_FROM_ADDRESS=noreply@harbor.law
MAIL_FROM_NAME="Harbor Law"
AWS_ACCESS_KEY_ID=your_ses_key
AWS_SECRET_ACCESS_KEY=your_ses_secret
AWS_DEFAULT_REGION=us-east-1
```

## Deployment Steps

### Step 1: Upload Files to GitHub

#### Option A: GitHub Web Interface (Recommended for you)

1. Go to https://github.com/mwms77/harbor-law-app
2. Create a new branch called `phase1-features`
3. Upload the following files/folders from this deployment package:

**New Files:**
- `app/Models/ClientUpload.php`
- `app/Models/AdminNote.php`
- `app/Http/Controllers/ClientUploadController.php`
- `app/Notifications/IntakeCompletedNotification.php`
- `app/Notifications/ClientDocumentUploadedNotification.php`
- `app/Notifications/AdminDocumentUploadedNotification.php`
- `app/Notifications/EstatePlanReadyNotification.php`
- `database/migrations/2024_02_06_000001_create_client_uploads_table.php`
- `database/migrations/2024_02_06_000002_add_status_to_users_table.php`
- `database/migrations/2024_02_06_000003_create_admin_notes_table.php`
- `resources/views/client/uploads.blade.php`
- `resources/views/admin/uploads/index.blade.php`

**Files to Replace:**
- `app/Models/User.php` (adds new relationships)
- `app/Http/Controllers/Admin/UserController.php` (adds Phase 1 methods)
- `app/Http/Controllers/Admin/DashboardController.php` (adds new stats)
- `routes/web.php` (adds new routes)
- `config/filesystems.php` (adds upload settings)

4. Create a Pull Request from `phase1-features` to `main`
5. Review the changes in the PR
6. Merge the PR (this will trigger auto-deployment via Forge)

#### Option B: Git Command Line

```bash
cd /path/to/local/harbor-law-app
git checkout -b phase1-features
# Copy all files from deployment package
git add .
git commit -m "Add Phase 1: Client uploads, email notifications, enhanced admin dashboard"
git push origin phase1-features
# Then create and merge PR on GitHub
```

### Step 2: Monitor Deployment

1. Log into Laravel Forge
2. Go to your site's deployment history
3. Watch the deployment process
4. Check for any errors in the deployment log

Expected deployment script output:
```
✓ git pull origin main
✓ composer install
✓ php artisan migrate --force
✓ php artisan config:cache
✓ php artisan route:cache
✓ php artisan view:cache
✓ php artisan queue:restart
```

### Step 3: Run Migrations

The deployment script should automatically run migrations, but verify:

```bash
# SSH into your server
cd /home/forge/app.harbor.law
php artisan migrate:status
```

You should see these new migrations as "Ran":
- `2024_02_06_000001_create_client_uploads_table`
- `2024_02_06_000002_add_status_to_users_table`
- `2024_02_06_000003_create_admin_notes_table`

### Step 4: Verify Queue is Running

Emails are sent via queued jobs, so verify the queue worker is running:

```bash
# Check queue worker status
php artisan queue:work --daemon

# Or check if it's running via supervisor (if configured)
sudo supervisorctl status
```

If queue worker is not running, start it:
```bash
php artisan queue:work --daemon &
```

**IMPORTANT**: Set up a proper queue worker via Laravel Forge:
1. Go to your site in Forge
2. Click "Queue" in the sidebar
3. Add a queue worker if not already set up
4. Use connection: `database`
5. Queue: `default`
6. Processes: `1`

### Step 5: Create Required Directories

The system needs a private storage directory for uploads:

```bash
cd /home/forge/app.harbor.law
mkdir -p storage/app/private/client-uploads
chmod 755 storage/app/private/client-uploads
```

### Step 6: Post-Deployment Verification

Visit https://app.harbor.law and verify:

1. **Login Works**
   - [ ] Can log in as admin (matt@harbor.law)
   - [ ] Can log in as a test client user

2. **Client Upload Feature**
   - [ ] Navigate to "Uploads" menu item
   - [ ] Upload page loads without errors
   - [ ] Can select files (try PDF and image)
   - [ ] Can select category
   - [ ] Files upload successfully
   - [ ] Uploaded files appear in "Your Uploaded Documents"
   - [ ] Can download uploaded files

3. **Email Notifications**
   - [ ] Upload a file as client
   - [ ] Check admin email for upload notification
   - [ ] Check client email for upload confirmation
   - [ ] Check Laravel logs if emails don't arrive: `storage/logs/laravel.log`
   - [ ] Check failed jobs table: `SELECT * FROM failed_jobs;`

4. **Admin Dashboard**
   - [ ] Login as admin
   - [ ] Dashboard shows new statistics (uploads, status counts)
   - [ ] Can navigate to "Uploads" section
   - [ ] Can see all client uploads
   - [ ] Can filter uploads by category
   - [ ] Can download individual files
   - [ ] Can view user detail page
   - [ ] Can update user status
   - [ ] Can add admin notes to users

5. **User Status Updates**
   - [ ] Go to a user's detail page
   - [ ] Can change user status via dropdown
   - [ ] Status updates successfully

6. **Admin Notes**
   - [ ] Can add note to a user
   - [ ] Note appears with timestamp
   - [ ] Can delete note

## Troubleshooting

### Issue: Files Won't Upload

**Check:**
1. File size limits in `php.ini`:
   ```bash
   php -i | grep upload_max_filesize
   php -i | grep post_max_size
   ```
   Both should be at least 20M.

2. Nginx client max body size:
   ```bash
   grep client_max_body_size /etc/nginx/sites-available/app.harbor.law
   ```
   Should be at least 20M.

3. Directory permissions:
   ```bash
   ls -la storage/app/private/
   ```
   Should be writable by web server user.

**Fix in Forge:**
- Server Settings → Edit php.ini
- Set `upload_max_filesize = 20M`
- Set `post_max_size = 20M`
- Restart PHP-FPM

### Issue: Emails Not Sending

**Check:**
1. Queue worker is running:
   ```bash
   ps aux | grep queue:work
   ```

2. SES credentials are correct in `.env`

3. Check failed jobs:
   ```bash
   php artisan queue:failed
   ```

4. Check Laravel logs:
   ```bash
   tail -f storage/logs/laravel.log
   ```

**Fix:**
- Verify SES credentials in AWS Console
- Restart queue worker: `php artisan queue:restart`
- Manually process failed jobs: `php artisan queue:retry all`

### Issue: 500 Error After Deployment

**Check:**
1. Deployment log in Forge for errors
2. Laravel logs: `tail -f storage/logs/laravel.log`
3. Migrations ran successfully: `php artisan migrate:status`

**Common fixes:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
chmod -R 755 storage bootstrap/cache
```

### Issue: User Status Dropdown Doesn't Save

**Check:**
1. Migration for `status` column ran: `php artisan migrate:status`
2. User model has `status` in `$fillable` array
3. Check browser console for JavaScript errors

**Fix:**
```bash
# Manually run the migration
php artisan migrate --path=database/migrations/2024_02_06_000002_add_status_to_users_table.php
```

## Rollback Procedure

If Phase 1 deployment causes critical issues:

1. **Via GitHub:**
   - Create a new branch from the commit before the merge
   - Merge it to main (triggers re-deployment)

2. **Via Forge:**
   - Go to deployment history
   - Click "Rollback" on previous successful deployment

3. **Via SSH (Manual):**
   ```bash
   cd /home/forge/app.harbor.law
   git reset --hard HEAD~1
   php artisan migrate:rollback --step=3
   php artisan config:cache
   ```

## Testing Checklist

After deployment, test these scenarios:

### Client User Tests
- [ ] Register new account
- [ ] Complete intake form
- [ ] Upload ID document (PDF)
- [ ] Upload property document (image)
- [ ] Upload multiple files at once
- [ ] Download uploaded file
- [ ] Receive email confirmation of upload

### Admin User Tests
- [ ] View enhanced dashboard with new stats
- [ ] View all uploads page
- [ ] Filter uploads by category
- [ ] Search uploads by client name
- [ ] Download client file
- [ ] Download all files from one client as ZIP
- [ ] Delete a client file
- [ ] Update user status
- [ ] Add note to user
- [ ] Delete note from user
- [ ] Upload estate plan to client
- [ ] Verify client receives estate plan notification

## Performance Notes

**Expected Impact:**
- Database: +3 tables, minimal impact
- Storage: Files stored in `storage/app/private/client-uploads/`
- Email: Queued jobs process asynchronously (no user-facing delay)
- Page Load: No significant impact (uploads use async JS)

**Monitor:**
- Disk space usage: `df -h`
- Queue job processing: `php artisan queue:work --verbose`
- Email bounce rate in SES dashboard

## Support

**If you encounter issues:**

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check failed jobs: `SELECT * FROM failed_jobs;`
3. Check Forge deployment logs
4. Review this guide's Troubleshooting section

**Contact Information:**
- GitHub Issues: https://github.com/mwms77/harbor-law-app/issues
- Email: matt@harbor.law (for critical production issues)

## Success Criteria

Phase 1 deployment is successful when:
- [ ] All existing features still work (intake form, estate plan downloads)
- [ ] Clients can upload documents
- [ ] Admin can view and manage all uploads
- [ ] Email notifications are being sent and received
- [ ] User status can be updated from admin panel
- [ ] Admin notes can be added to users
- [ ] No errors in Laravel logs
- [ ] No failed queue jobs
- [ ] All tests in Testing Checklist pass

## Next Steps After Successful Deployment

1. Monitor email deliverability for first few days
2. Check disk space weekly as clients upload files
3. Review failed jobs daily for first week
4. Gather feedback from first few client uploads
5. Plan Phase 2 features based on usage patterns

---

**Deployment Date:** _____________
**Deployed By:** _____________
**Issues Encountered:** _____________
**Resolution:** _____________
