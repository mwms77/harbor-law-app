# Phase 1 Testing Checklist

## Pre-Deployment Testing (Local/Development)

### Environment Setup
- [ ] .env file updated with all Phase 1 variables
- [ ] Database migrations run successfully
- [ ] Queue worker is running
- [ ] Email configured (Mailtrap for dev, SES for production)
- [ ] Private storage directory created

### Database Verification
```bash
php artisan migrate:status
```
- [ ] `create_client_uploads_table` migration ran
- [ ] `add_status_to_users_table` migration ran
- [ ] `create_admin_notes_table` migration ran

### Code Syntax Check
- [ ] No PHP syntax errors: `php artisan route:list`
- [ ] No missing dependencies: `composer install`
- [ ] No view errors: Visit each new route manually

---

## Post-Deployment Testing (Production)

### 1. Application Health Check

**Basic Functionality:**
- [ ] Site loads: https://app.harbor.law
- [ ] Can access login page
- [ ] Can access registration page
- [ ] No JavaScript console errors
- [ ] No 500 errors in any page

**Admin Access:**
- [ ] Can login as matt@harbor.law
- [ ] Admin dashboard loads
- [ ] Navigation menu includes "Uploads" link
- [ ] No missing routes errors

**Client Access:**
- [ ] Can login as test client user
- [ ] Client dashboard loads
- [ ] Navigation menu includes "Uploads" link
- [ ] Intake form still works

---

### 2. Client Document Upload Feature

#### Upload Functionality
- [ ] Navigate to /uploads
- [ ] Upload form displays correctly
- [ ] All 6 categories appear in dropdown
- [ ] Can select category (required field works)
- [ ] Can select single PDF file
- [ ] Can select single image file (JPG/PNG)
- [ ] Can select multiple files at once
- [ ] File size validation works (try 15MB file - should fail)
- [ ] File type validation works (try .exe file - should fail)
- [ ] Upload progress indicator works (if implemented)
- [ ] Success message appears after upload
- [ ] Email confirmation sent to client
- [ ] Email notification sent to admin

#### File Display
- [ ] Uploaded files appear in "Your Uploaded Documents" section
- [ ] Files grouped by category correctly
- [ ] File name displays correctly
- [ ] File size displays correctly
- [ ] Upload timestamp displays correctly
- [ ] Download button appears for each file

#### Download Functionality
- [ ] Can download PDF file
- [ ] Downloaded file opens correctly
- [ ] Can download image file
- [ ] Downloaded filename is original name (not hashed)

#### Authorization & Security
- [ ] Create second test user
- [ ] User A cannot see User B's files
- [ ] User A cannot download User B's files (try manipulating URL)
- [ ] Client cannot access /admin/uploads
- [ ] Client cannot delete files (no delete button visible)

#### Edge Cases
- [ ] Upload file with special characters in name (e.g., "O'Brien's Document #2.pdf")
- [ ] Upload 2 files with same name - both saved with unique hash
- [ ] Upload without selecting category - validation error appears
- [ ] Upload empty form - validation error appears
- [ ] Very long filename (200+ chars) - handled gracefully

---

### 3. Email Notifications

#### Intake Completion Email
**Setup:**
1. Create new test user
2. Complete entire intake form
3. Submit form

**Verify:**
- [ ] Admin receives email at matt@harbor.law
- [ ] Subject line: "Client Completed Intake Form - [Name]"
- [ ] Email contains client name
- [ ] Email contains client email
- [ ] Email contains completion timestamp
- [ ] Email contains link to admin dashboard
- [ ] Link in email works
- [ ] Email formatting looks professional
- [ ] Harbor Law branding visible

#### Document Upload Email (Client)
**Setup:**
1. Login as client
2. Upload 1-3 documents

**Verify:**
- [ ] Client receives email confirmation
- [ ] Subject line appropriate
- [ ] Email lists uploaded file names
- [ ] Email shows category
- [ ] Email shows total file count
- [ ] Email contains link to uploads page
- [ ] Link works

#### Document Upload Email (Admin)
**Setup:**
1. Same upload as above

**Verify:**
- [ ] Admin receives notification
- [ ] Subject includes client name
- [ ] Email lists uploaded files
- [ ] Email shows category
- [ ] Email contains link to client's uploads
- [ ] Link works correctly

#### Estate Plan Ready Email
**Setup:**
1. Login as admin
2. Go to user detail page
3. Upload estate plan PDF

**Verify:**
- [ ] Client receives email notification
- [ ] Subject: "Your Estate Plan is Ready"
- [ ] Email contains document name
- [ ] Email contains next steps
- [ ] Email contains download link
- [ ] Link works
- [ ] Professional formatting

#### Email Delivery Verification
- [ ] Check queue jobs: `SELECT * FROM jobs;`
- [ ] Check failed jobs: `SELECT * FROM failed_jobs;`
- [ ] Check Laravel logs: `tail -f storage/logs/laravel.log`
- [ ] Check SES dashboard for delivery status
- [ ] Verify no bounces in SES
- [ ] Verify no complaints in SES

---

### 4. Enhanced Admin Dashboard

#### Statistics Display
**Navigate to:** /admin/dashboard

**Verify:**
- [ ] "Total Users" count displays
- [ ] "Completed Intakes" count displays
- [ ] "Users with Uploads" count displays
- [ ] "Total Uploads" count displays
- [ ] "Uploads This Week" count displays
- [ ] "Pending Reviews" count displays
- [ ] Numbers are accurate (verify against database)

#### Recent Uploads Section
- [ ] "Recent Uploads" section displays
- [ ] Shows last 10 uploads
- [ ] Each upload shows: client name, file name, category, timestamp
- [ ] Can click client name to go to user detail
- [ ] Can click to download file

---

### 5. User Status Management

#### Status Dropdown
**Navigate to:** User detail page (/admin/users/{id})

**Verify:**
- [ ] Status dropdown appears
- [ ] Shows current status
- [ ] All 5 status options available:
  - [ ] Pending
  - [ ] In Progress
  - [ ] Documents Uploaded
  - [ ] Plan Delivered
  - [ ] Completed
- [ ] Can change status via dropdown
- [ ] Status saves correctly
- [ ] Success message appears
- [ ] Status badge color changes

#### Automatic Status Updates
**Test 1: Upload triggers status change**
1. [ ] Create user with status "Pending"
2. [ ] Login as that user, upload document
3. [ ] Verify status changed to "Documents Uploaded"

**Test 2: Estate plan upload triggers status change**
1. [ ] Create user with status "Documents Uploaded"
2. [ ] Admin uploads estate plan for user
3. [ ] Verify status changed to "Plan Delivered"

#### Status Filtering
**Navigate to:** /admin/users

**Verify:**
- [ ] Status filter dropdown appears
- [ ] Can filter by each status
- [ ] Filter results are accurate
- [ ] URL updates with filter parameter
- [ ] Pagination works with filters

---

### 6. Admin Notes Feature

#### Add Note
**Navigate to:** User detail page

**Verify:**
- [ ] "Admin Notes" section appears
- [ ] "Add Note" form visible
- [ ] Can type note (test with 100+ characters)
- [ ] Can submit note
- [ ] Success message appears
- [ ] Note appears in list immediately
- [ ] Note shows timestamp
- [ ] Most recent note appears first

#### View Notes
- [ ] Multiple notes display correctly
- [ ] Notes sorted by most recent first
- [ ] Timestamp format is readable
- [ ] Long notes don't break layout

#### Delete Note
- [ ] Delete button appears for each note
- [ ] Confirmation dialog appears
- [ ] Can cancel deletion
- [ ] Can confirm deletion
- [ ] Note removed from list
- [ ] Success message appears

---

### 7. Upload Management (Admin)

#### All Uploads View
**Navigate to:** /admin/uploads

**Verify:**
- [ ] Page loads without error
- [ ] All uploads from all users display
- [ ] Table shows: Client, File Name, Category, Size, Date
- [ ] Pagination works
- [ ] Shows 50 items per page

#### Filtering
- [ ] Category filter dropdown works
- [ ] Search by client name works
- [ ] Search by client email works
- [ ] Can combine filters
- [ ] Clear filters button works
- [ ] URL updates with filter parameters

#### Actions
- [ ] Can download any file
- [ ] Can delete any file
- [ ] Delete confirmation works
- [ ] File removed after deletion
- [ ] Success message appears

#### User-Specific Uploads
**Navigate to:** /admin/uploads/user/{id}

**Verify:**
- [ ] Shows only that user's files
- [ ] Files grouped by category
- [ ] Upload summary statistics accurate
- [ ] "Download All as ZIP" button appears
- [ ] ZIP download works
- [ ] ZIP contains all files
- [ ] ZIP organizes files by category folder
- [ ] Original filenames preserved in ZIP

---

### 8. Search & Filter Testing

#### User Search
**Navigate to:** /admin/users

**Test:**
- [ ] Search by first name
- [ ] Search by last name
- [ ] Search by email
- [ ] Partial name search works
- [ ] Case-insensitive search works
- [ ] No results shows appropriate message

#### Combined Filters
- [ ] Search + Status filter
- [ ] Search + Intake completed filter
- [ ] Search + Has uploads filter
- [ ] All filters combined
- [ ] Clear all filters works

#### Upload Search
**Navigate to:** /admin/uploads

**Test:**
- [ ] Search by client name
- [ ] Search by client email
- [ ] Filter by category
- [ ] Search + Category filter combined

---

### 9. Responsive Design Testing

#### Mobile (iPhone/Android)
- [ ] Upload page works on mobile
- [ ] Can select files via mobile camera
- [ ] File upload works
- [ ] Admin dashboard readable
- [ ] Tables scroll horizontally if needed
- [ ] Buttons are tappable (not too small)

#### Tablet (iPad)
- [ ] All pages render correctly
- [ ] Touch interactions work
- [ ] No layout breaks

#### Desktop
- [ ] All pages render correctly
- [ ] Hover states work
- [ ] Modals/dropdowns position correctly

---

### 10. Performance Testing

#### File Upload Performance
- [ ] Single small file (< 1MB) uploads quickly
- [ ] Large file (10MB) uploads successfully
- [ ] Multiple files (5 files, 5MB each) upload successfully
- [ ] No timeout errors
- [ ] Progress indicator accurate

#### Page Load Performance
- [ ] Admin dashboard loads in < 3 seconds
- [ ] User list with 100+ users loads in < 5 seconds
- [ ] Uploads page with 100+ files loads in < 5 seconds
- [ ] No N+1 query issues (check query count)

#### Email Queue Performance
- [ ] Emails process without blocking user actions
- [ ] Queue worker processes jobs promptly
- [ ] No buildup of pending jobs

---

### 11. Error Handling

#### Upload Errors
- [ ] File too large - clear error message
- [ ] Invalid file type - clear error message
- [ ] No category selected - validation error
- [ ] Network error during upload - user-friendly message
- [ ] Disk full - appropriate error (unlikely but check)

#### Email Errors
- [ ] Failed email logged to failed_jobs table
- [ ] Failed email doesn't break upload functionality
- [ ] Can retry failed jobs: `php artisan queue:retry all`

#### Authorization Errors
- [ ] Non-admin accessing admin routes - redirect/403
- [ ] User accessing another user's files - 403
- [ ] Unauthenticated user - redirect to login

---

### 12. Data Integrity

#### Soft Deletes
- [ ] Delete user - uploads retained in database
- [ ] Delete user - files retained on disk
- [ ] Restore user - uploads still accessible
- [ ] Force delete user - uploads also deleted

#### File Storage
- [ ] Files stored in correct directory: `storage/app/private/client-uploads/{user_id}/`
- [ ] Filenames are hashed (not original names)
- [ ] Original names stored in database
- [ ] File permissions correct (not world-readable)

#### Database Consistency
- [ ] Foreign keys working (delete user cascades correctly)
- [ ] Timestamps accurate
- [ ] No orphaned records
- [ ] Indexes working (check query performance)

---

### 13. Security Audit

#### File Access
- [ ] Direct URL to file path returns 404/403
- [ ] Cannot access files by guessing filenames
- [ ] Cannot access /storage/app/private/ via browser
- [ ] Authorization checked before every download

#### SQL Injection
- [ ] Search inputs don't allow SQL injection
- [ ] Filter inputs sanitized
- [ ] Eloquent ORM prevents SQL injection

#### XSS Prevention
- [ ] File names with <script> tags handled safely
- [ ] Notes with HTML don't render as HTML
- [ ] Blade escaping working correctly

#### CSRF Protection
- [ ] All forms have CSRF token
- [ ] POST requests without token fail
- [ ] File upload form has CSRF protection

---

### 14. Rollback Testing (Optional but Recommended)

**If you have a staging environment:**
1. [ ] Deploy Phase 1 to staging
2. [ ] Run all tests above
3. [ ] Perform rollback: `git reset --hard HEAD~1`
4. [ ] Run migrations rollback: `php artisan migrate:rollback --step=3`
5. [ ] Verify application still works
6. [ ] Redeploy Phase 1
7. [ ] Verify everything works again

---

## Production Monitoring (First Week)

### Daily Checks
- [ ] Check Laravel logs for errors
- [ ] Check failed jobs table
- [ ] Check disk space usage
- [ ] Check SES email statistics
- [ ] Verify queue worker is running

### Weekly Checks
- [ ] Review upload counts and trends
- [ ] Check for any user-reported issues
- [ ] Verify all emails delivering successfully
- [ ] Check storage usage growth rate

---

## Success Criteria

Phase 1 deployment is successful when:
- [ ] All tests in this checklist pass
- [ ] No critical errors in Laravel logs
- [ ] No failed jobs in queue
- [ ] Email notifications delivering reliably
- [ ] Clients can upload documents successfully
- [ ] Admin can manage all uploads effectively
- [ ] Performance is acceptable (pages load quickly)
- [ ] Security audits pass
- [ ] Existing features (intake, estate plans) still work

---

## Issue Tracking

**If you encounter issues during testing:**

| Issue | Severity | Status | Notes |
|-------|----------|--------|-------|
| Example: Upload fails for HEIC files | High | Fixed | Added HEIC to allowed types |
|  |  |  |  |
|  |  |  |  |

**Severity Levels:**
- **Critical** - Breaks core functionality, prevents use
- **High** - Major feature doesn't work, has workaround
- **Medium** - Minor feature issue, doesn't block use
- **Low** - Cosmetic issue, doesn't affect functionality

---

**Tested By:** _____________  
**Date:** _____________  
**Environment:** _____________  
**All Tests Passed:** ☐ Yes  ☐ No  
**Ready for Production:** ☐ Yes  ☐ No  
**Notes:** _____________________________________________
