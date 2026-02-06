<?php

namespace App\Http\Controllers;

use App\Models\ClientUpload;
use App\Models\User;
use App\Notifications\ClientDocumentUploadedNotification;
use App\Notifications\AdminDocumentUploadedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class ClientUploadController extends Controller
{
    /**
     * Display client's uploaded documents.
     */
    public function index()
    {
        $uploads = auth()->user()->uploads()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        $categories = ClientUpload::$categories;

        return view('client.uploads', compact('uploads', 'categories'));
    }

    /**
     * Store newly uploaded documents.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => [
                'required',
                'file',
                'max:' . config('uploads.max_size', 10240), // 10MB default
                'mimes:' . str_replace(',', ',', config('uploads.allowed_mimes', 'pdf,jpg,jpeg,png,heic'))
            ],
            'category' => [
                'required',
                'in:' . implode(',', array_keys(ClientUpload::$categories))
            ]
        ], [
            'files.required' => 'Please select at least one file to upload.',
            'files.*.max' => 'Each file must be smaller than 10MB.',
            'files.*.mimes' => 'Only PDF, JPG, PNG, and HEIC files are allowed.',
            'category.required' => 'Please select a category for your documents.',
        ]);

        $uploadedFiles = [];
        $userId = auth()->id();

        foreach ($request->file('files') as $file) {
            // Hash filename to prevent enumeration attacks
            $filename = hash('sha256', $file->getClientOriginalName() . time() . uniqid()) 
                        . '.' . $file->getClientOriginalExtension();

            // Store file in user-specific directory
            $filePath = $file->storeAs(
                'private/client-uploads/' . $userId,
                $filename
            );

            // Create database record
            $upload = ClientUpload::create([
                'user_id' => $userId,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'category' => $validated['category'],
                'file_size' => $file->getSize(),
            ]);

            $uploadedFiles[] = $upload;
        }

        // Update user status if this is their first upload
        $user = auth()->user();
        if ($user->status === 'pending' || $user->status === 'in_progress') {
            $user->update(['status' => 'documents_uploaded']);
        }

        // Send notification to client
        $user->notify(new ClientDocumentUploadedNotification(
            count($uploadedFiles),
            $validated['category']
        ));

        // Send notification to admin
        $admin = User::where('email', config('app.admin_email'))->first();
        if ($admin) {
            $admin->notify(new AdminDocumentUploadedNotification(
                $user,
                count($uploadedFiles),
                $validated['category']
            ));
        }

        return redirect()->route('uploads.index')->with('success', 
            count($uploadedFiles) . ' file(s) uploaded successfully!'
        );
    }

    /**
     * Download a specific file.
     */
    public function download(ClientUpload $upload)
    {
        // Ensure user can only download their own files (or admin can download any)
        if (!Gate::allows('view-upload', $upload)) {
            abort(403, 'Unauthorized access to this file.');
        }

        $filePath = $upload->storage_path;

        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::download($filePath, $upload->original_name);
    }
}
