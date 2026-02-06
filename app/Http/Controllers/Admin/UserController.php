<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EstatePlan;
use App\Models\ClientUpload;
use App\Models\AdminNote;
use App\Notifications\EstatePlanReadyNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use ZipArchive;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withTrashed()
            ->withCount(['intakeSubmission', 'estatePlans', 'uploads'])
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by completion status
        if ($request->filled('intake_completed')) {
            if ($request->intake_completed === 'yes') {
                $query->has('intakeSubmission');
            } else {
                $query->doesntHave('intakeSubmission');
            }
        }

        // Filter by documents uploaded
        if ($request->filled('has_uploads')) {
            if ($request->has_uploads === 'yes') {
                $query->has('uploads');
            } else {
                $query->doesntHave('uploads');
            }
        }

        $users = $query->paginate(20)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'intakeSubmission', 
            'estatePlans', 
            'uploads' => function($query) {
                $query->orderBy('created_at', 'desc');
            },
            'adminNotes' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ]);
        
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:user,admin',
        ]);

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => 'required|in:user,admin',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->name = $request->first_name . ' ' . $request->last_name;
        $user->email = $request->email;
        $user->role = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Soft delete - marks as deleted but keeps in database
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully!');
    }

    public function forceDestroy($userId)
    {
        $user = User::withTrashed()->findOrFail($userId);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Delete associated data
        if ($user->intakeSubmission) {
            $user->intakeSubmission->forceDelete();
        }

        foreach ($user->estatePlans()->withTrashed()->get() as $plan) {
            if ($plan->file_path && Storage::disk('private')->exists($plan->file_path)) {
                Storage::disk('private')->delete($plan->file_path);
            }
            $plan->forceDelete();
        }

        // Delete uploaded files
        foreach ($user->uploads()->withTrashed()->get() as $upload) {
            $filePath = 'private/client-uploads/' . $user->id . '/' . $upload->filename;
            if (Storage::exists($filePath)) {
                Storage::delete($filePath);
            }
            $upload->forceDelete();
        }

        // Hard delete - permanently removes from database
        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User permanently deleted from database!');
    }

    public function downloadIntake(User $user)
    {
        $submission = $user->intakeSubmission;

        if (!$submission || !$submission->is_completed) {
            return back()->with('error', 'No completed intake form found for this user.');
        }

        $data = [
            'metadata' => [
                'formVersion' => '1.0',
                'completedDate' => $submission->completed_at->toISOString(),
                'jurisdiction' => 'Michigan',
                'userName' => $user->name,
                'userEmail' => $user->email,
                'downloadedBy' => auth()->user()->name,
                'downloadedAt' => now()->toISOString(),
            ],
            'formData' => $submission->form_data,
        ];

        $filename = 'intake_' . $user->id . '_' . $user->name . '_' . now()->format('Y-m-d') . '.json';
        $filename = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $filename);

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    public function uploadPlan(Request $request, User $user)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf|max:10240',
                'status' => 'nullable|in:draft,final,executed',
                'executed_at' => 'nullable|date',
                'notes' => 'nullable|string|max:1000',
            ]);

            $file = $request->file('file');
            
            if (!$file) {
                return back()->with('error', 'No file was uploaded. Please select a PDF file.');
            }

            $originalFilename = $file->getClientOriginalName();
            $filename = $user->id . '_' . time() . '_' . $originalFilename;
            $path = $file->storeAs('estate-plans', $filename, 'private');

            $estatePlan = EstatePlan::create([
                'user_id' => $user->id,
                'uploaded_by' => auth()->id(),
                'filename' => $filename,
                'original_filename' => $originalFilename,
                'file_path' => $path,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'status' => $request->status ?? 'final',
                'executed_at' => $request->executed_at,
                'notes' => $request->notes,
            ]);

            // Update user status
            $user->update(['status' => 'plan_delivered']);

            // Send notification to client
            try {
                $user->notify(new EstatePlanReadyNotification($estatePlan));
            } catch (\Exception $e) {
                Log::error('Failed to send estate plan notification: ' . $e->getMessage());
            }
            
            return back()->with('success', 'Estate plan uploaded successfully.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Estate plan upload failed: ' . $e->getMessage());
            return back()->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function deletePlan(EstatePlan $estatePlan)
    {
        $estatePlan->delete();
        
        return back()->with('success', 'Estate plan deleted successfully.');
    }

    public function updatePlanStatus(Request $request, EstatePlan $estatePlan)
    {
        $request->validate([
            'status' => 'required|in:draft,final,executed',
            'executed_at' => 'nullable|date',
        ]);

        $estatePlan->update([
            'status' => $request->status,
            'executed_at' => $request->executed_at,
        ]);

        return back()->with('success', 'Document status updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent deactivating yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deactivate your own account!');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    // PHASE 1: New Methods

    /**
     * Update user status.
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,documents_uploaded,plan_delivered,completed',
        ]);

        $user->update(['status' => $request->status]);

        return back()->with('success', 'User status updated successfully.');
    }

    /**
     * Add admin note to user.
     */
    public function addNote(Request $request, User $user)
    {
        $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        AdminNote::create([
            'user_id' => $user->id,
            'note' => $request->note,
        ]);

        return back()->with('success', 'Note added successfully.');
    }

    /**
     * Delete admin note.
     */
    public function deleteNote(AdminNote $note)
    {
        $note->delete();

        return back()->with('success', 'Note deleted successfully.');
    }

    /**
     * View all uploads across all users.
     */
    public function uploads(Request $request)
    {
        $query = ClientUpload::with('user');

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $uploads = $query->orderBy('created_at', 'desc')->paginate(50)->withQueryString();

        $categories = [
            'id_documents' => 'ID Documents',
            'property_documents' => 'Property Documents',
            'financial_documents' => 'Financial Documents',
            'beneficiary_information' => 'Beneficiary Information',
            'health_care_directives' => 'Health Care Directives',
            'other' => 'Other',
        ];

        return view('admin.uploads.index', compact('uploads', 'categories'));
    }

    /**
     * View uploads for a specific user.
     */
    public function userUploads(User $user)
    {
        $uploads = $user->uploads()->orderBy('created_at', 'desc')->get()->groupBy('category');

        $categories = [
            'id_documents' => 'ID Documents',
            'property_documents' => 'Property Documents',
            'financial_documents' => 'Financial Documents',
            'beneficiary_information' => 'Beneficiary Information',
            'health_care_directives' => 'Health Care Directives',
            'other' => 'Other',
        ];

        return view('admin.uploads.user', compact('user', 'uploads', 'categories'));
    }

    /**
     * Download a client upload.
     */
    public function downloadUpload(ClientUpload $upload)
    {
        $filePath = 'private/client-uploads/' . $upload->user_id . '/' . $upload->filename;
        
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::download($filePath, $upload->original_name);
    }

    /**
     * Delete a client upload.
     */
    public function deleteUpload(ClientUpload $upload)
    {
        $filePath = 'private/client-uploads/' . $upload->user_id . '/' . $upload->filename;
        
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        $upload->delete();

        return back()->with('success', 'File deleted successfully.');
    }

    /**
     * Download all uploads from a user as ZIP.
     */
    public function downloadUserZip(User $user)
    {
        $uploads = $user->uploads;

        if ($uploads->isEmpty()) {
            return back()->with('error', 'No files to download for this user.');
        }

        $zipFileName = 'user_' . $user->id . '_uploads_' . now()->format('Y-m-d') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($uploads as $upload) {
                $filePath = storage_path('app/private/client-uploads/' . $upload->user_id . '/' . $upload->filename);
                
                if (file_exists($filePath)) {
                    // Add file with category prefix for organization
                    $categoryFolder = $upload->category_name . '/';
                    $zip->addFile($filePath, $categoryFolder . $upload->original_name);
                }
            }
            $zip->close();

            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Failed to create ZIP file.');
    }
}
