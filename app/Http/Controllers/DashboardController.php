<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IntakeSubmission;
use App\Models\EstatePlan;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's intake submission
        $intakeSubmission = IntakeSubmission::where('user_id', $user->id)->first();
        
        // Get user's estate plans
        $estatePlans = EstatePlan::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard', compact('user', 'intakeSubmission', 'estatePlans'));
    }
}
