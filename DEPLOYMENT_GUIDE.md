# Harbor Law Estate Planning App - Phase 1 Implementation Guide

## 📋 Overview
This guide provides step-by-step instructions for implementing Phase 1 features:
- ✅ Client Document Upload System
- ✅ Email Notifications  
- ✅ Enhanced Admin Dashboard

## 🚀 Pre-Deployment Checklist

### Before Starting
- [ ] Backup your current database
- [ ] Backup your current codebase
- [ ] Ensure you have SSH access to your VPS
- [ ] Verify Forge deployment is working
- [ ] Test current application is functioning

## 📦 Step 1: Upload Files via GitHub Web Interface

Since you upload code through GitHub's web interface, follow this process:

### A. Create Feature Branch on GitHub
1. Go to https://github.com/mwms77/harbor-law-app
2. Click on "main" branch dropdown
3. Type "feature/phase-1-implementation" in the search box
4. Click "Create branch: feature/phase-1-implementation from main"

### B. Upload Files to GitHub

Upload files in this specific order to avoid breaking changes:

**FIRST - Configuration Files:**
1. Upload `config/uploads.php` to `config/` directory
2. Upload `.env.example` (for reference only, don't replace your actual .env)

**SECOND - Database Migrations:**
3. Upload all 3 migration files to `database/migrations/`:
   - `2024_02_06_000001_create_client_uploads_table.php`
   - `2024_02_06_000002_add_status_to_users_table.php`
   - `2024_02_06_000003_create_admin_notes_table.php`

**THIRD - Models:**
4. Upload to `app/Models/`:
   - `ClientUpload.php`
   - `AdminNote.php`
   - `User.php` (REPLACE existing file)

**FOURTH - Notifications:**
5. Upload all 4 notification files to `app/Notifications/`:
   - `ClientDocumentUploadedNotification.php`
   - `AdminDocumentUploadedNotification.php`
   - `IntakeCompletedNotification.php`
   - `EstatePlanReadyNotification.php`

**FIFTH - Controllers:**
6. Upload to `app/Http/Controllers/`:
   - `ClientUploadController.php`
   - `AdminController.php` (REPLACE existing file)

**SIXTH - Service Provider:**
7. Upload `app/Providers/AuthServiceProvider.php` (REPLACE existing file)

**SEVENTH - Routes:**
8. Upload `routes/web.php` (REPLACE existing file)

**EIGHTH - Client Views:**
9. Upload to `resources/views/client/`:
   - `uploads.blade.php`

**NINTH - Admin Views:**
10. Upload to `resources/views/admin/`:
    - `dashboard.blade.php` (REPLACE existing file)
    - `users.blade.php` (REPLACE existing file)
    - `user-detail.blade.php`
    - `client-uploads.blade.php`
    - `user-uploads.blade.php`

### C. Create Pull Request and Merge
1. Go to "Pull Requests" tab on GitHub
2. Click "New Pull Request"
3. Set base: `main` and compare: `feature/phase-1-implementation`
4. Click "Create Pull Request"
5. Review the changes
6. Click "Merge Pull Request"
7. Click "Confirm Merge"

**This will trigger automatic deployment via Forge!**

## 🗄️ Step 2: Run Database Migrations

### Via Forge Dashboard
1. Log into Laravel Forge: https://forge.laravel.com
2. Navigate to your server and site
3. Click "Sites" → Click your site name
4. Scroll to "Quick Commands"
5. In the command input, type: `php artisan migrate --force`
6. Click "Run Command"
7. Verify success in command output

### Via SSH (Alternative)
```bash
cd /home/forge/app.harbor.law
php artisan migrate --force
```

Expected output:
```
Migrating: 2024_02_06_000001_create_client_uploads_table
Migrated:  2024_02_06_000001_create_client_uploads_table (XX.XXms)
Migrating: 2024_02_06_000002_add_status_to_users_table
Migrated:  2024_02_06_000002_add_status_to_users_table (XX.XXms)
Migrating: 2024_02_06_000003_create_admin_notes_table
Migrated:  2024_02_06_000003_create_admin_notes_table (XX.XXms)
```

## ⚙️ Step 3: Update Environment Variables

### Via Forge Dashboard
1. In Forge, go to your site
2. Click "Environment" in the left sidebar
3. Add these new variables to your `.env` file:

```env
# File Upload Settings
UPLOAD_MAX_SIZE=10240
UPLOAD_ALLOWED_MIMES=pdf,jpg,jpeg,png,heic
UPLOAD_DISK=local

# Admin Email (if not already set)
ADMIN_EMAIL=matt@harbor.law
```

4. Click "Save" at the bottom

### Via SSH (Alternative)
```bash
cd /home/forge/app.harbor.law
nano .env
# Add the variables above
# Press CTRL+X, then Y, then ENTER to save
```

## 🔧 Step 4: Clear Caches

### Via Forge Quick Commands
Run each command one at a time:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Via SSH (Alternative)
```bash
cd /home/forge/app.harbor.law
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📧 Step 5: Queue Worker Setup

Ensure the queue worker is running to process email notifications.

### Via Forge
1. Go to "Daemons" in the left sidebar
2. If you don't see a queue worker, click "New Daemon"
3. Configure:
   - **Command**: `php artisan queue:work --sleep=3 --tries=3`
   - **Directory**: `/home/forge/app.harbor.law`
   - **User**: `forge`
4. Click "Create Daemon"

### Restart Queue Worker (Important!)
After deployment, always restart the queue worker:

Via Forge:
1. Go to "Daemons"
2. Find your queue worker
3. Click "Restart"

Via SSH:
```bash
php artisan queue:restart
```

## 🔐 Step 6: Set File Permissions

### Via SSH
```bash
cd /home/forge/app.harbor.law
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache
sudo chown -R forge:forge storage
sudo chown -R forge:forge bootstrap/cache
```

## ✅ Step 7: Verification Testing

### Test 1: Check Application Loads
1. Visit: https://app.harbor.law
2. Verify no errors on homepage
3. Log in as admin (matt@harbor.law)
4. Verify admin dashboard shows statistics

### Test 2: Client Document Upload
1. Log in as a test client (or create one)
2. Navigate to "My Documents" or "Uploads" menu
3. Select a category
4. Upload a test PDF file (< 10MB)
5. Verify success message appears
6. Verify file appears in the uploaded documents list

### Test 3: Admin Upload Management
1. Log in as admin
2. Go to Admin Dashboard
3. Verify statistics are displaying
4. Click "Client Documents"
5. Verify you can see the uploaded test file
6. Try downloading the file
7. Verify download works

### Test 4: Email Notifications
1. Check your email (matt@harbor.law) for upload notification
2. If using Mailtrap in development, check Mailtrap inbox
3. Verify email formatting looks professional

### Test 5: Admin Notes
1. Log in as admin
2. Go to a user's detail page
3. Add a test note
4. Verify note appears in the sidebar
5. Try deleting the note

### Test 6: Status Updates
1. On user detail page
2. Change user status dropdown
3. Verify status updates successfully
4. Verify status badge changes color

## 🚨 Troubleshooting

### Issue: Migration Fails
**Error**: Table already exists
**Solution**:
```bash
php artisan migrate:status  # Check which migrations ran
# If needed, manually drop the table and re-run
```

### Issue: File Upload Returns 500 Error
**Check**:
1. Storage directory permissions: `ls -la storage/`
2. PHP upload limits in `php.ini`:
   - `upload_max_filesize = 20M`
   - `post_max_size = 20M`
3. Nginx client_max_body_size: Should be 20M+

### Issue: Emails Not Sending
**Check**:
1. Queue worker is running: `ps aux | grep queue`
2. Failed jobs table: `SELECT * FROM failed_jobs;`
3. Laravel logs: `tail -f storage/logs/laravel.log`
4. SES credentials are correct in .env

### Issue: Authorization Errors
**Check**:
1. AuthServiceProvider is registered in `config/app.php`
2. Clear config cache: `php artisan config:cache`
3. Check user has correct `is_admin` value in database

### Issue: 404 on New Routes
**Solution**:
```bash
php artisan route:cache
php artisan config:cache
```

## 📊 Post-Deployment Checklist

After successful deployment:

- [ ] Homepage loads without errors
- [ ] Admin can log in
- [ ] Admin dashboard shows statistics
- [ ] Client can upload files
- [ ] Client receives email confirmation
- [ ] Admin receives upload notification
- [ ] Admin can view all uploads
- [ ] Admin can download files
- [ ] Admin can add notes to users
- [ ] Admin can update user status
- [ ] ZIP download works for bulk downloads
- [ ] File deletion works (with confirmation)
- [ ] All emails are formatted correctly

## 🔄 Rollback Procedure (If Needed)

If something goes critically wrong:

### Via GitHub
1. Go to your repository
2. Find the commit before the merge
3. Click "Revert" on that commit
4. This will create a new commit that undoes the changes
5. Forge will auto-deploy the rollback

### Via SSH
```bash
cd /home/forge/app.harbor.law
git log --oneline  # Find the commit hash before merge
git reset --hard COMMIT_HASH
git push -f origin main  # Forces push (use with caution!)
```

### Database Rollback
```bash
php artisan migrate:rollback --step=3
```

## 📞 Support

If you encounter issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check Forge deployment logs
3. Check server error logs via Forge
4. Review this guide's troubleshooting section

## 🎯 Next Steps After Successful Deployment

1. **Test with Real Clients**: Have 2-3 clients test the upload feature
2. **Monitor Email Delivery**: Check SES dashboard for bounce/complaint rates
3. **Monitor Disk Space**: Watch storage folder growth
4. **Review Admin Workflow**: Ensure admin dashboard meets your needs
5. **Plan Phase 2**: Begin planning messaging system or document templates

## 📝 Notes

- All uploaded files are stored in `storage/app/private/client-uploads/`
- Files are NOT publicly accessible via URL
- Users can only download their own files
- Admin can download all files
- Soft deletes are enabled for users and uploads
- Email notifications are queued for background processing

---

**Congratulations!** You've successfully implemented Phase 1 of the Harbor Law Estate Planning App! 🎉
