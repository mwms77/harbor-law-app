<?php

namespace App\Http\Controllers;

use App\Models\ClientUpload;
use App\Models\User;
use App\Notifications\ClientDocumentUploadedNotification;
use App\Notifications\AdminDocumentUploadedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ClientUploadController extends Controller
{
    /**
     * Display a listing of the user's uploads.
     */
    public function index()
    {
        $uploads = auth()->user()->uploads()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('category');

        $categories = [
            'id_documents' => 'ID Documents',
            'property_documents' => 'Property Documents',
            'financial_documents' => 'Financial Documents',
            'beneficiary_information' => 'Beneficiary Information',
            'health_care_directives' => 'Health Care Directives',
            'other' => 'Other',
        ];

        return view('client.uploads', compact('uploads', 'categories'));
    }

    /**
     * Store newly uploaded files.
     */
    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*' => [
                'required',
                'file',
                'max:' . config('uploads.max_size', 10240),
                'mimes:' . config('uploads.allowed_mimes', 'pdf,jpg,jpeg,png,heic')
            ],
            'category' => [
                'required',
                'in:id_documents,property_documents,financial_documents,beneficiary_information,health_care_directives,other'
            ]
        ], [
            'files.*.max' => 'Each file must not exceed ' . (config('uploads.max_size', 10240) / 1024) . 'MB.',
            'files.*.mimes' => 'Only PDF, JPG, PNG, and HEIC files are allowed.',
        ]);

        $uploadedFiles = [];
        
        foreach ($request->file('files') as $file) {
            // Hash filename to prevent enumeration attacks
            $filename = hash('sha256', $file->getClientOriginalName() . time() . uniqid()) 
                        . '.' . $file->getClientOriginalExtension();
            
            // Store file in private directory
            $filePath = $file->storeAs(
                'private/client-uploads/' . auth()->id(),
                $filename
            );
            
            // Create database record
            $upload = ClientUpload::create([
                'user_id' => auth()->id(),
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'category' => $request->category,
                'file_size' => $file->getSize(),
            ]);

            $uploadedFiles[] = $upload;
        }

        // Update user status if this is their first upload
        $user = auth()->user();
        if ($user->status === 'pending' || $user->status === 'in_progress') {
            $user->update(['status' => 'documents_uploaded']);
        }

        // Send notifications
        try {
            // Notify client
            $user->notify(new ClientDocumentUploadedNotification($uploadedFiles));
            
            // Notify admin
            $adminEmail = config('app.admin_email', 'matt@harbor.law');
            $admin = User::where('email', $adminEmail)->first();
            if ($admin) {
                $admin->notify(new AdminDocumentUploadedNotification($user, $uploadedFiles));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('Failed to send upload notification: ' . $e->getMessage());
        }

        return redirect()->route('uploads.index')
            ->with('success', count($uploadedFiles) . ' file(s) uploaded successfully!');
    }

    /**
     * Download a specific upload.
     */
    public function download(ClientUpload $upload)
    {
        // Check authorization
        if (auth()->id() !== $upload->user_id && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access to file.');
        }

        $filePath = 'private/client-uploads/' . $upload->user_id . '/' . $upload->filename;
        
        if (!Storage::exists($filePath)) {
            abort(404, 'File not found.');
        }

        return Storage::download($filePath, $upload->original_name);
    }
}
