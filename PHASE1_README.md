# Harbor Law Estate Planning - Phase 1 Features

## What's New in Phase 1

Phase 1 adds three major enhancements to the Harbor Law Estate Planning application:

### 1. Client Document Upload System ⭐

**What it does:**
- Allows clients to securely upload estate planning documents directly through their portal
- Supports PDFs, images (JPG, PNG, HEIC) up to 10MB per file
- Requires categorization of each document for better organization
- Sends automatic email confirmations to client and admin

**Categories:**
- ID Documents (driver's license, passport, birth certificate)
- Property Documents (deeds, titles, mortgage documents)
- Financial Documents (bank statements, investment accounts)
- Beneficiary Information (beneficiary forms, trust documents)
- Health Care Directives (living will, health care proxy, DNR)
- Other (miscellaneous documents)

**Security:**
- Files stored in private directory (not publicly accessible)
- Filenames hashed to prevent enumeration attacks
- Authorization checks before file downloads
- Automatic user status updates

**Client Features:**
- Drag-and-drop file upload interface
- Upload multiple files at once
- View all uploaded documents organized by category
- Download previously uploaded files
- Email confirmation after each upload

**Admin Features:**
- View all uploads across all clients
- Filter by category, client name, or date
- Download individual files
- Bulk download all files from a client as ZIP
- Delete files (with confirmation)
- See upload counts on user dashboard

---

### 2. Email Notification System ⭐

**What it does:**
- Sends automated email notifications for key application events
- Uses Amazon SES for reliable delivery
- Processes emails asynchronously (no delays for users)
- Professional templates with Harbor Law branding

**Notification Types:**

#### a) Intake Form Completion
- **To:** Admin (matt@harbor.law)
- **When:** Client submits final intake form
- **Contains:** Client name, email, completion time, link to admin dashboard

#### b) Document Upload (Client Confirmation)
- **To:** Client who uploaded
- **When:** Client successfully uploads files
- **Contains:** File names, category, total files uploaded, link to portal

#### c) Document Upload (Admin Notification)
- **To:** Admin (matt@harbor.law)
- **When:** Client uploads files
- **Contains:** Client name, file count, category, link to client's uploads

#### d) Estate Plan Ready
- **To:** Client
- **When:** Admin uploads completed estate plan
- **Contains:** Document name, status, download link, next steps

**Features:**
- Queue-based processing (emails don't block user actions)
- Failed email logging for troubleshooting
- Retry mechanism (3 attempts before giving up)
- Professional Markdown templates
- Unsubscribe mechanism (for non-critical emails in future)

---

### 3. Enhanced Admin Dashboard ⭐

**What it does:**
- Provides better visibility into client progress and documents
- Adds user status tracking throughout estate planning workflow
- Enables private admin notes for internal tracking
- Improves search and filtering capabilities

**New Dashboard Statistics:**
- Total registered users
- Completed intakes
- Users with uploaded documents
- Documents uploaded this week
- Users by status (pending, in progress, etc.)

**User Status Tracking:**

New `status` field on users with these values:
- **Pending** - Just registered, no activity yet
- **In Progress** - Working on intake or uploading docs
- **Documents Uploaded** - Has uploaded required documents
- **Plan Delivered** - Estate plan has been provided
- **Completed** - Estate planning process finished

Status automatically updates when:
- Client uploads first document → "Documents Uploaded"
- Admin uploads estate plan → "Plan Delivered"

**Admin Notes Feature:**
- Add private notes to any user account
- Notes timestamped automatically
- Only visible to admin users
- Quick-add form on user detail page
- Can delete notes as needed

**Enhanced User Management:**

**Search & Filtering:**
- Search by name or email
- Filter by status
- Filter by intake completion
- Filter by document uploads
- Filter by date range

**User Detail Page:**
- All intake information
- All uploaded documents (grouped by category)
- All estate plans
- Admin notes section
- Status update dropdown
- Quick actions (download intake, upload plan, add note)

**Upload Management:**
- Dedicated uploads section in admin panel
- See all uploads across all clients
- Filter by category
- Search by client name/email
- Download individual files
- Bulk download user's files as ZIP
- Delete files with confirmation

---

## Database Changes

### New Tables

**client_uploads**
- Stores uploaded client documents
- Tracks filename, category, size, upload date
- Soft deletes enabled
- Foreign key to users table

**admin_notes**
- Private notes about users (admin only)
- Timestamped entries
- Foreign key to users table

### Modified Tables

**users**
- Added `status` column (enum: pending, in_progress, documents_uploaded, plan_delivered, completed)

---

## New Routes

### Client Routes
```
GET  /uploads              - View uploaded documents
POST /uploads              - Upload new documents
GET  /uploads/{id}/download - Download a document
```

### Admin Routes
```
GET    /admin/uploads                  - View all uploads
GET    /admin/uploads/user/{user}      - View user's uploads
GET    /admin/uploads/{id}/download    - Download upload
DELETE /admin/uploads/{id}             - Delete upload
GET    /admin/uploads/user/{user}/zip  - Download user's files as ZIP

PATCH  /admin/users/{user}/status      - Update user status
POST   /admin/users/{user}/notes       - Add admin note
DELETE /admin/notes/{note}             - Delete admin note
```

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── ClientUploadController.php          (NEW)
│   └── Admin/
│       ├── UserController.php              (ENHANCED)
│       └── DashboardController.php         (ENHANCED)
├── Models/
│   ├── ClientUpload.php                    (NEW)
│   ├── AdminNote.php                       (NEW)
│   └── User.php                            (ENHANCED)
└── Notifications/
    ├── IntakeCompletedNotification.php     (NEW)
    ├── ClientDocumentUploadedNotification.php (NEW)
    ├── AdminDocumentUploadedNotification.php  (NEW)
    └── EstatePlanReadyNotification.php     (NEW)

database/migrations/
├── 2024_02_06_000001_create_client_uploads_table.php (NEW)
├── 2024_02_06_000002_add_status_to_users_table.php   (NEW)
└── 2024_02_06_000003_create_admin_notes_table.php    (NEW)

resources/views/
├── client/
│   └── uploads.blade.php                   (NEW)
└── admin/
    └── uploads/
        └── index.blade.php                 (NEW)

config/
└── filesystems.php                         (ENHANCED)

routes/
└── web.php                                 (ENHANCED)
```

---

## Environment Variables

Add to `.env`:

```env
# Admin Email
ADMIN_EMAIL=matt@harbor.law

# File Upload Settings
UPLOAD_MAX_SIZE=10240
UPLOAD_ALLOWED_MIMES=pdf,jpg,jpeg,png,heic

# Queue (required for emails)
QUEUE_CONNECTION=database
```

---

## Usage Examples

### For Clients

**Uploading Documents:**
1. Log into client portal
2. Click "Uploads" in navigation
3. Select document category (required)
4. Choose files (can select multiple)
5. Click "Upload Documents"
6. Receive email confirmation

**Viewing Documents:**
1. Go to "Uploads" page
2. See documents organized by category
3. Click "Download" to retrieve any file

### For Admin

**Managing Uploads:**
1. Log into admin panel
2. Click "Uploads" in sidebar
3. Filter by category or search by client
4. Download individual files or ZIP all files from one client

**Tracking User Progress:**
1. Go to admin dashboard
2. See statistics on uploads and statuses
3. Click on a user to view details
4. Update status via dropdown
5. Add private notes as needed

**Delivering Estate Plans:**
1. Go to user detail page
2. Upload completed estate plan PDF
3. Client automatically receives email notification
4. User status updates to "Plan Delivered"

---

## Security Considerations

**File Storage:**
- All uploaded files stored in `storage/app/private/` (NOT publicly accessible)
- Filenames hashed to prevent guessing
- Download routes protected by authentication + authorization
- File type validation (MIME type check)
- File size limits enforced

**Authorization:**
- Clients can only view/download their own files
- Admin can view/download all files
- Clients cannot delete files (must request admin)
- Admin can delete files with confirmation

**Data Protection:**
- VPS disk-level encryption (handled by hosting provider)
- HTTPS enforced (Let's Encrypt via Forge)
- User passwords hashed (Laravel default)
- Session data encrypted
- Soft deletes (files retained when user soft-deleted)

---

## Performance Impact

**Database:**
- +3 new tables (minimal impact)
- Indexes on foreign keys and frequently queried columns
- Soft deletes prevent data loss

**Storage:**
- Files stored on local VPS disk
- No external storage (S3) in Phase 1
- Monitor disk usage as clients upload files

**Email:**
- Queued jobs process asynchronously
- No user-facing delays
- Failed jobs retry automatically
- SES provides reliable delivery

---

## Monitoring

**What to Monitor:**

1. **Disk Space**
   ```bash
   df -h
   ```
   Watch `storage/app/private/client-uploads/` directory

2. **Queue Jobs**
   ```bash
   php artisan queue:work --verbose
   ```
   Check failed jobs table: `SELECT * FROM failed_jobs;`

3. **Email Delivery**
   - Check SES dashboard for bounce rate
   - Check Laravel logs for email errors

4. **File Uploads**
   - Monitor upload success rate
   - Check for file size/type validation errors

---

## Future Enhancements (Phase 2)

Potential additions based on usage:

1. **Client Portal Messaging**
   - In-app messaging between admin and clients
   - Reduces email back-and-forth

2. **Document Templates**
   - Pre-built estate planning templates
   - Auto-populate with intake data

3. **Cloud Storage**
   - Move uploads to AWS S3 or Backblaze B2
   - Better scalability and redundancy

4. **Advanced Notifications**
   - SMS notifications for urgent updates
   - Customizable notification preferences

5. **Document Version Control**
   - Track document revisions
   - View change history

---

## Support

**Documentation:**
- PHASE1_DEPLOYMENT_GUIDE.md - Detailed deployment instructions
- Main project README.md - Overall project documentation

**Logs:**
- Laravel logs: `storage/logs/laravel.log`
- Failed jobs: Database table `failed_jobs`
- Queue worker: Laravel Forge queue settings

**Contact:**
- GitHub: https://github.com/mwms77/harbor-law-app
- Email: matt@harbor.law

---

## Changelog

**Version 1.1.0 - Phase 1** (February 2026)
- Added client document upload system
- Added email notification system
- Enhanced admin dashboard with status tracking
- Added admin notes feature
- Improved user management and filtering

**Version 1.0.0** (February 2024)
- Initial release
- User registration and authentication
- Multi-step intake form
- Admin panel
- Estate plan delivery

---

**Phase 1 completes the core document management features needed for efficient estate planning workflows. The system is now ready to handle the full lifecycle from client onboarding through document collection to plan delivery.**
