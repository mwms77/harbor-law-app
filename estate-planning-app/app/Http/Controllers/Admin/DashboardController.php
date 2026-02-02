<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\IntakeSubmission;
use App\Models\EstatePlan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'completed_intakes' => IntakeSubmission::where('is_completed', true)->count(),
            'pending_intakes' => IntakeSubmission::where('is_completed', false)->count(),
            'uploaded_plans' => EstatePlan::count(),
        ];

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(10)
            ->with('intakeSubmission')
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
