<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EstatePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'user')
            ->with('intakeSubmission', 'estatePlans')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        if ($user->isAdmin()) {
            abort(404);
        }

        $user->load('intakeSubmission', 'estatePlans');

        return view('admin.users.show', compact('user'));
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
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            'notes' => 'nullable|string|max:1000',
        ]);

        $file = $request->file('file');
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
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Estate plan uploaded successfully.');
    }

    public function deletePlan(EstatePlan $estatePlan)
    {
        $estatePlan->delete();
        
        return back()->with('success', 'Estate plan deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->isAdmin()) {
            abort(403);
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}
