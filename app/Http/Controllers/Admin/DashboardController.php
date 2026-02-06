<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IntakeSubmission;
use App\Models\EstatePlan;
use App\Models\ClientUpload;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'completed_intakes' => IntakeSubmission::where('is_completed', true)->count(),
            'pending_intakes' => IntakeSubmission::where('is_completed', false)->count(),
            'uploaded_plans' => EstatePlan::count(),
            'users_with_uploads' => User::has('uploads')->count(),
            'total_uploads' => ClientUpload::count(),
            'uploads_this_week' => ClientUpload::where('created_at', '>=', now()->subWeek())->count(),
            'pending_reviews' => User::where('status', 'in_progress')->count(),
            'documents_uploaded_status' => User::where('status', 'documents_uploaded')->count(),
        ];

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(10)
            ->with(['intakeSubmission', 'uploads'])
            ->withCount('uploads')
            ->get();

        $recentUploads = ClientUpload::with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentUploads'));
    }
}
