# Harbor Law Estate Planning App - Phase 1 File Map

## 📁 Complete File Structure

```
harbor-law-app/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminController.php                    [REPLACE] ✅ Enhanced with uploads & notes
│   │       └── ClientUploadController.php             [NEW] ✅ Client file upload handling
│   ├── Models/
│   │   ├── AdminNote.php                              [NEW] ✅ Admin notes model
│   │   ├── ClientUpload.php                           [NEW] ✅ File upload model
│   │   └── User.php                                   [REPLACE] ✅ Enhanced with relationships
│   ├── Notifications/
│   │   ├── AdminDocumentUploadedNotification.php      [NEW] ✅ Admin upload alert
│   │   ├── ClientDocumentUploadedNotification.php     [NEW] ✅ Client upload confirmation
│   │   ├── EstatePlanReadyNotification.php            [NEW] ✅ Estate plan ready alert
│   │   └── IntakeCompletedNotification.php            [NEW] ✅ Intake completion alert
│   └── Providers/
│       └── AuthServiceProvider.php                    [REPLACE] ✅ Authorization gates
├── config/
│   └── uploads.php                                    [NEW] ✅ Upload configuration
├── database/
│   └── migrations/
│       ├── 2024_02_06_000001_create_client_uploads_table.php    [NEW] ✅
│       ├── 2024_02_06_000002_add_status_to_users_table.php      [NEW] ✅
│       └── 2024_02_06_000003_create_admin_notes_table.php       [NEW] ✅
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── client-uploads.blade.php               [NEW] ✅ All uploads view
│       │   ├── dashboard.blade.php                    [REPLACE] ✅ Enhanced with stats
│       │   ├── user-detail.blade.php                  [NEW] ✅ User detail with notes
│       │   ├── user-uploads.blade.php                 [NEW] ✅ User-specific uploads
│       │   └── users.blade.php                        [REPLACE] ✅ Enhanced with filters
│       └── client/
│           └── uploads.blade.php                      [NEW] ✅ Client upload interface
├── routes/
│   └── web.php                                        [REPLACE] ✅ All Phase 1 routes
├── .env.example                                       [UPDATE] ✅ New variables documented
└── DEPLOYMENT_GUIDE.md                                [NEW] ✅ Step-by-step deployment
```

## 📊 Files by Category

### Database Layer (3 files)
- `database/migrations/2024_02_06_000001_create_client_uploads_table.php`
- `database/migrations/2024_02_06_000002_add_status_to_users_table.php`
- `database/migrations/2024_02_06_000003_create_admin_notes_table.php`

### Models (3 files)
- `app/Models/ClientUpload.php` (NEW)
- `app/Models/AdminNote.php` (NEW)
- `app/Models/User.php` (ENHANCED)

### Controllers (2 files)
- `app/Http/Controllers/ClientUploadController.php` (NEW)
- `app/Http/Controllers/AdminController.php` (ENHANCED)

### Notifications (4 files)
- `app/Notifications/ClientDocumentUploadedNotification.php` (NEW)
- `app/Notifications/AdminDocumentUploadedNotification.php` (NEW)
- `app/Notifications/IntakeCompletedNotification.php` (NEW)
- `app/Notifications/EstatePlanReadyNotification.php` (NEW)

### Views - Client (1 file)
- `resources/views/client/uploads.blade.php` (NEW)

### Views - Admin (5 files)
- `resources/views/admin/dashboard.blade.php` (ENHANCED)
- `resources/views/admin/users.blade.php` (ENHANCED)
- `resources/views/admin/user-detail.blade.php` (NEW)
- `resources/views/admin/client-uploads.blade.php` (NEW)
- `resources/views/admin/user-uploads.blade.php` (NEW)

### Configuration & Routes (4 files)
- `config/uploads.php` (NEW)
- `routes/web.php` (ENHANCED)
- `app/Providers/AuthServiceProvider.php` (ENHANCED)
- `.env.example` (UPDATED)

### Documentation (1 file)
- `DEPLOYMENT_GUIDE.md` (NEW)

## 🎯 Total Files Created/Modified

- **NEW FILES**: 18
- **REPLACED/ENHANCED FILES**: 5
- **TOTAL FILES**: 23

## 🔑 Key Features Implemented

### 1. Client Document Upload System ✅
**Files Involved**: 8 files
- ClientUploadController.php
- ClientUpload.php model
- client/uploads.blade.php
- create_client_uploads_table.php migration
- Authorization gates
- Routes
- Configuration

**Features**:
- Drag-and-drop file upload interface
- Required category selection (6 categories)
- File type validation (PDF, JPG, PNG, HEIC)
- File size validation (10MB limit)
- Secure storage (outside public directory)
- Hashed filenames to prevent enumeration
- Upload progress indication
- Organized display by category
- Client can only access own files

### 2. Email Notifications ✅
**Files Involved**: 4 notification classes

**Implemented Notifications**:
1. **Client Upload Confirmation** → Sent to client after upload
2. **Admin Upload Alert** → Sent to admin when client uploads
3. **Intake Completion** → Sent to admin when client completes intake
4. **Estate Plan Ready** → Sent to client when plan is complete (ready for Phase 2)

**Features**:
- Professional email templates
- Queued for async processing
- Harbor Law branding
- Clear call-to-action buttons
- Transactional (no unsubscribe)

### 3. Enhanced Admin Dashboard ✅
**Files Involved**: 9 files (controllers + views)

**New Admin Features**:
- **Dashboard Statistics**:
  - Total users
  - Completed intakes
  - Users with uploads
  - Pending reviews
  - Uploads this week

- **User Management**:
  - Search by name/email
  - Filter by status, intake completion, uploads
  - Status tracking (5 statuses)
  - Private admin notes (timestamped)
  - Quick actions panel

- **Document Management**:
  - View all uploads across all clients
  - Filter by user, category, date range
  - Download individual files
  - Bulk download as ZIP
  - Delete files with confirmation
  - File size and type indicators

## 🔒 Security Features Implemented

1. **Authorization Gates**
   - Clients can only view own files
   - Admin can view all files
   - File access protected by authentication + authorization

2. **File Storage Security**
   - Files stored outside public directory
   - Filenames hashed with SHA-256
   - No direct URL access to files
   - Downloads through controller with auth checks

3. **Validation**
   - File type whitelist (MIME type check)
   - File size limits
   - Category validation
   - Sanitized filenames

4. **Database Security**
   - Soft deletes for users and files
   - Foreign key constraints
   - SQL injection prevention (Eloquent)
   - XSS prevention (Blade auto-escapes)

## 📧 Email Notification Flow

```
CLIENT UPLOADS FILE
    ↓
[ClientUploadController@store]
    ↓
File saved to storage/app/private/client-uploads/{user_id}/
    ↓
Database record created
    ↓
User status updated (if needed)
    ↓
[Queue] → ClientDocumentUploadedNotification → CLIENT
    ↓
[Queue] → AdminDocumentUploadedNotification → ADMIN
```

## 🗄️ Database Schema Changes

### New Tables

**client_uploads**
- id
- user_id (FK to users)
- filename (hashed)
- original_name
- mime_type
- category (enum: 6 options)
- file_size
- timestamps
- deleted_at (soft delete)

**admin_notes**
- id
- user_id (FK to users)
- note (text)
- timestamps

### Modified Tables

**users**
- Added: `status` (enum: pending, in_progress, documents_uploaded, plan_delivered, completed)

## 🚀 Routes Added

### Client Routes
```
GET  /uploads                    → View uploads
POST /uploads                    → Upload files
GET  /uploads/{upload}/download  → Download file
```

### Admin Routes
```
GET    /admin/dashboard                    → Statistics dashboard
GET    /admin/users                        → User list with filters
GET    /admin/users/{user}                 → User detail
PATCH  /admin/users/{user}/status          → Update status
POST   /admin/users/{user}/notes           → Add note
DELETE /admin/notes/{note}                 → Delete note
GET    /admin/uploads                      → All uploads
GET    /admin/uploads/user/{user}          → User uploads
GET    /admin/uploads/{upload}/download    → Download file
DELETE /admin/uploads/{upload}             → Delete file
GET    /admin/uploads/user/{user}/zip      → Download ZIP
```

## ⚙️ Environment Variables Added

```env
UPLOAD_MAX_SIZE=10240
UPLOAD_ALLOWED_MIMES=pdf,jpg,jpeg,png,heic
UPLOAD_DISK=local
ADMIN_EMAIL=matt@harbor.law
```

## 📦 Dependencies

No new Composer packages required! All features use built-in Laravel functionality:
- Laravel 10.x (already installed)
- PostgreSQL (already installed)
- Amazon SES (already configured)
- ZipArchive (built-in PHP extension)

## ✅ Testing Checklist

Copy this to use during testing:

```
FUNCTIONAL TESTING:
□ Client can upload PDF file
□ Client can upload image (JPG/PNG)
□ Category selection is required
□ Files over 10MB are rejected
□ Invalid file types are rejected
□ Client sees success message after upload
□ Client receives email confirmation
□ Admin receives email notification

ADMIN TESTING:
□ Admin dashboard shows statistics
□ Admin can search users
□ Admin can filter by status
□ Admin can update user status
□ Admin can add notes to user
□ Admin can delete notes
□ Admin can view all uploads
□ Admin can download files
□ Admin can delete files (with confirmation)
□ Admin can download ZIP of all user files

SECURITY TESTING:
□ Client cannot access other users' files (test with URL manipulation)
□ Non-logged-in users cannot access upload routes
□ Non-admin users cannot access admin routes
□ Files are NOT accessible via public URL
□ File type validation prevents .exe, .sh, etc.

EMAIL TESTING:
□ Emails have correct branding
□ Email links work correctly
□ Emails are professionally formatted
□ Queue worker is processing jobs
□ No emails in failed_jobs table
```

## 🎓 How It All Works Together

1. **Client uploads document**:
   - Selects category (required)
   - Chooses file(s)
   - Submits form

2. **Server processes upload**:
   - Validates file type and size
   - Hashes filename for security
   - Stores in user-specific folder
   - Creates database record
   - Updates user status if needed

3. **Notifications sent**:
   - Client receives confirmation email
   - Admin receives notification email
   - Both processed via queue

4. **Admin manages documents**:
   - Views uploads in dashboard
   - Filters/searches as needed
   - Downloads files for review
   - Updates client status
   - Adds private notes

5. **Client views uploads**:
   - Sees organized list by category
   - Can download own files
   - Cannot delete (must request admin)

## 📝 Important Notes

- **File Retention**: When user is soft-deleted, files persist (not auto-deleted)
- **Email Queue**: Requires queue worker to be running
- **Storage**: Files stored in `storage/app/private/client-uploads/`
- **Backups**: VPS disk-level encryption handles security
- **Scaling**: When storage reaches 80% capacity, consider cloud storage (Phase 2)

## 🎉 Success Criteria

Phase 1 implementation is successful when:
- ✅ All 23 files uploaded to GitHub
- ✅ Migrations run successfully
- ✅ Client can upload and view documents
- ✅ Admin can manage all uploads
- ✅ Email notifications working
- ✅ Admin dashboard shows statistics
- ✅ All security gates functioning
- ✅ No errors in Laravel logs
- ✅ Application passes all testing checklist items

---

**Ready for deployment!** Follow DEPLOYMENT_GUIDE.md for step-by-step instructions.
