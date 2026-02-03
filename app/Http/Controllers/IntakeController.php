<?php

namespace App\Http\Controllers;

use App\Models\IntakeSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function save(Request $request)
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission ?? IntakeSubmission::create(['user_id' => $user->id]);

        $formData = $submission->form_data ?? [];
        $newData = $request->input('form_data', []);
        
        // Merge new data with existing data
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
            return response()->json([
                'success' => false,
                'message' => 'No intake form found',
            ], 404);
        }

        $formData = $submission->form_data ?? [];
        $newData = $request->input('form_data', []);
        $formData = array_merge($formData, $newData);

        $submission->update([
            'form_data' => $formData,
            'is_completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Intake form submitted successfully',
            'redirect' => route('dashboard'),
        ]);
    }

    public function download()
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission;

        if (!$submission || !$submission->is_completed) {
            abort(404);
        }

        $data = [
            'metadata' => [
                'formVersion' => '1.0',
                'completedDate' => $submission->completed_at->toISOString(),
                'jurisdiction' => 'Michigan',
                'userName' => $user->name,
                'userEmail' => $user->email,
            ],
            'formData' => $submission->form_data,
        ];

        $filename = 'estate_plan_intake_' . $user->id . '_' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
