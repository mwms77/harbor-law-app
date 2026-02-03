<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\IntakeSubmission;
use App\Models\IntakePersonalInfo;
use App\Models\IntakeSpouseInfo;
use App\Models\IntakeChild;
use App\Models\IntakeAsset;
use App\Models\IntakeLiability;
use App\Models\IntakeBeneficiary;
use App\Models\IntakeFiduciary;
use App\Models\IntakeSpecificGift;
use App\Models\IntakeHealthcarePreferences;
use App\Models\IntakeDistributionPreferences;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IntakeController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission ?? IntakeSubmission::create([
            'user_id' => $user->id,
            'form_data' => [],
        ]);

        return view('intake.form', compact('submission', 'user'));
    }

    // Legacy save method for backward compatibility with old JavaScript
    public function save(Request $request)
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission ?? IntakeSubmission::create(['user_id' => $user->id]);

        // Also save to legacy JSON for backward compatibility
        $formData = $submission->form_data ?? [];
        $newData = $request->input('form_data', []);
        $formData = array_merge($formData, $newData);

        $submission->update([
            'form_data' => $formData,
            'current_section' => $request->input('current_section', 0),
            'progress_percentage' => $request->input('progress_percentage', 0),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progress saved successfully',
        ]);
    }

    public function submit(Request $request)
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission;

        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'No submission found.'], 404);
        }

        // Save final form data
        $formData = $submission->form_data ?? [];
        $newData = $request->input('form_data', []);
        $formData = array_merge($formData, $newData);

        $submission->update([
            'form_data' => $formData,
            'is_completed' => true,
            'completed_at' => now(),
            'submitted_at' => now(),
            'progress_percentage' => 100,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Intake form submitted successfully!',
        ]);
    }

    public function download()
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission;

        if (!$submission || !$submission->is_completed) {
            return back()->with('error', 'No completed intake form found.');
        }

        $data = [
            'metadata' => [
                'formVersion' => '1.0',
                'completedDate' => $submission->completed_at->toISOString(),
                'jurisdiction' => 'Michigan',
                'userName' => $user->name,
                'userEmail' => $user->email,
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
}
