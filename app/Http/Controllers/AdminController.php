<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ClientUpload;
use App\Models\AdminNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AdminController extends Controller
{
    /**
     * Display admin dashboard with statistics.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'completed_intakes' => User::whereNotNull('intake_completed_at')->count(),
            'users_with_uploads' => User::has('uploads')->count(),
            'pending_reviews' => User::where('status', 'in_progress')->count(),
            'uploads_this_week' => ClientUpload::where('created_at', '>=', now()->subWeek())->count(),
        ];

        $recentUsers = User::with('uploads')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }

    /**
     * Display all users with filtering.
     */
    public function users(Request $request)
    {
        $query = User::query()->with('uploads');

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by intake completion
        if ($request->filled('intake_completed')) {
            if ($request->intake_completed === 'yes') {
                $query->whereNotNull('intake_completed_at');
            } else {
                $query->whereNull('intake_completed_at');
            }
        }

        // Filter by has uploads
        if ($request->filled('has_uploads')) {
            if ($request->has_uploads === 'yes') {
                $query->has('uploads');
            } else {
                $query->doesntHave('uploads');
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(25);
        $statuses = User::$statuses;

        return view('admin.users', compact('users', 'statuses'));
    }

    /**
     * Display detailed view of a single user.
     */
    public function showUser(User $user)
    {
        $user->load(['uploads', 'adminNotes']);
        $statuses = User::$statuses;

        return view('admin.user-detail', compact('user', 'statuses'));
    }

    /**
     * Update user status.
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', array_keys(User::$statuses))
        ]);

        $user->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'User status updated successfully.');
    }

    /**
     * Add admin note to user.
     */
    public function addNote(Request $request, User $user)
    {
        $request->validate([
            'note' => 'required|string|max:5000'
        ]);

        AdminNote::create([
            'user_id' => $user->id,
            'note' => $request->note,
        ]);

        return redirect()->back()->with('success', 'Note added successfully.');
    }

    /**
     * Delete admin note.
     */
    public function deleteNote(AdminNote $note)
    {
        $note->delete();

        return redirect()->back()->with('success', 'Note deleted successfully.');
    }

    /**
     * Display all client uploads across all users.
     */
    public function uploads(Request $request)
    {
        $query = ClientUpload::with('user');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $uploads = $query->orderBy('created_at', 'desc')->paginate(50);
        $users = User::orderBy('name')->get();
        $categories = ClientUpload::$categories;

        return view('admin.client-uploads', compact('uploads', 'users', 'categories'));
    }

    /**
     * Display uploads for a specific user.
     */
    public function userUploads(User $user)
    {
        $uploads = $user->uploads()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        $categories = ClientUpload::$categories;

        return view('admin.user-uploads', compact('user', 'uploads', 'categories'));
    }

    /**
     * Download a client's uploaded file.
     */
    public function downloadUpload(ClientUpload $upload)
    {
        $filePath = $upload->storage_path;

        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::download($filePath, $upload->original_name);
    }

    /**
     * Delete a client's uploaded file.
     */
    public function deleteUpload(ClientUpload $upload)
    {
        $filePath = $upload->storage_path;

        // Delete physical file
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
        }

        // Soft delete database record
        $upload->delete();

        return redirect()->back()->with('success', 'File deleted successfully.');
    }

    /**
     * Download all uploads from a user as a ZIP file.
     */
    public function downloadUserZip(User $user)
    {
        $uploads = $user->uploads;

        if ($uploads->isEmpty()) {
            return redirect()->back()->with('error', 'No files to download for this user.');
        }

        // Create temporary ZIP file
        $zipFileName = 'uploads_' . $user->id . '_' . now()->format('Y-m-d_His') . '.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($uploads as $upload) {
                $filePath = storage_path('app/' . $upload->storage_path);
                
                if (file_exists($filePath)) {
                    // Add file to ZIP with category prefix
                    $categoryFolder = $upload->category_name . '/';
                    $zip->addFile($filePath, $categoryFolder . $upload->original_name);
                }
            }
            
            $zip->close();

            // Download and delete temp file
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }

        return redirect()->back()->with('error', 'Failed to create ZIP file.');
    }
}
