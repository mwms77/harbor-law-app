# Quick Upload Reference - GitHub Web Interface

## 📁 File Upload Order & Destinations

Upload files to your GitHub repository in this **exact order** to prevent breaking changes:

### 1️⃣ Configuration Files (2 files)
```
config/uploads.php                           → config/
.env.example                                 → root/ (reference only)
```

### 2️⃣ Database Migrations (3 files)
```
2024_02_06_000001_create_client_uploads_table.php    → database/migrations/
2024_02_06_000002_add_status_to_users_table.php      → database/migrations/
2024_02_06_000003_create_admin_notes_table.php       → database/migrations/
```

### 3️⃣ Models (3 files)
```
ClientUpload.php                             → app/Models/
AdminNote.php                                → app/Models/
User.php                                     → app/Models/ [REPLACE]
```

### 4️⃣ Notifications (4 files)
```
ClientDocumentUploadedNotification.php       → app/Notifications/
AdminDocumentUploadedNotification.php        → app/Notifications/
IntakeCompletedNotification.php              → app/Notifications/
EstatePlanReadyNotification.php              → app/Notifications/
```

### 5️⃣ Controllers (2 files)
```
ClientUploadController.php                   → app/Http/Controllers/
AdminController.php                          → app/Http/Controllers/ [REPLACE]
```

### 6️⃣ Service Provider (1 file)
```
AuthServiceProvider.php                      → app/Providers/ [REPLACE]
```

### 7️⃣ Routes (1 file)
```
web.php                                      → routes/ [REPLACE]
```

### 8️⃣ Client Views (1 file)
```
uploads.blade.php                            → resources/views/client/
```

### 9️⃣ Admin Views (5 files)
```
dashboard.blade.php                          → resources/views/admin/ [REPLACE]
users.blade.php                              → resources/views/admin/ [REPLACE]
user-detail.blade.php                        → resources/views/admin/
client-uploads.blade.php                     → resources/views/admin/
user-uploads.blade.php                       → resources/views/admin/
```

---

## 🎯 GitHub Upload Steps

### Create Feature Branch
1. Go to: https://github.com/mwms77/harbor-law-app
2. Click branch dropdown (shows "main")
3. Type: `feature/phase-1-implementation`
4. Click "Create branch"

### Upload Files
For each file above:
1. Navigate to correct directory in GitHub
2. Click "Add file" → "Upload files"
3. Drag and drop or select file
4. Scroll down, add commit message
5. Click "Commit changes"

### Merge to Main
1. Go to "Pull Requests" tab
2. Click "New Pull Request"
3. Set: base `main` ← compare `feature/phase-1-implementation`
4. Click "Create Pull Request"
5. Review changes
6. Click "Merge Pull Request"
7. **This triggers Forge auto-deployment!**

---

## ⚡ After Merge: Required Commands

### Via Forge Dashboard
1. Navigate to your site in Forge
2. Run these commands in "Quick Commands":

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

### Update .env Variables
In Forge, go to "Environment" and add:
```env
UPLOAD_MAX_SIZE=10240
UPLOAD_ALLOWED_MIMES=pdf,jpg,jpeg,png,heic
UPLOAD_DISK=local
ADMIN_EMAIL=matt@harbor.law
```

---

## ✅ Quick Test

1. Visit: https://app.harbor.law
2. Log in as client
3. Upload a test file
4. Check email for confirmation
5. Log in as admin
6. View dashboard statistics
7. View client documents
8. Download a file

---

## 🚨 Emergency Rollback

If something breaks:

### Via GitHub
1. Go to repository "Commits" tab
2. Find commit before merge
3. Click "..." → "Revert"
4. Confirm revert
5. Forge will auto-deploy rollback

### Via Forge
```bash
php artisan migrate:rollback --step=3
```

---

## 📞 Need Help?

Refer to **DEPLOYMENT_GUIDE.md** for detailed instructions and troubleshooting.

---

**Total Files**: 23 files  
**Estimated Upload Time**: 15-20 minutes  
**Estimated Total Deployment**: 45-60 minutes
