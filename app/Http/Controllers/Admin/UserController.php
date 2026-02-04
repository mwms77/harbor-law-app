<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EstatePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withTrashed()
            ->withCount(['intakeSubmission', 'estatePlans'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['intakeSubmission', 'estatePlans']);
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
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
            'status' => 'required|in:draft,final,executed',
            'executed_at' => 'nullable|date',
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
            'status' => $request->status,
            'executed_at' => $request->executed_at,
            'notes' => $request->notes,
        ]);

        // Send email notification to user
        try {
            \Mail::to($user->email)->send(new \App\Mail\EstatePlanUploaded($estatePlan, $user));
        } catch (\Exception $e) {
            // Log error but don't fail the upload
            \Log::error('Failed to send estate plan upload email: ' . $e->getMessage());
        }

        return back()->with('success', 'Estate plan uploaded successfully and user has been notified by email.');
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
}
