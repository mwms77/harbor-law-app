<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $intake = $user->intakeSubmission;
        $estatePlans = $user->estatePlans()->latest()->get();

        return view('dashboard', compact('intake', 'estatePlans'));
    }
}
