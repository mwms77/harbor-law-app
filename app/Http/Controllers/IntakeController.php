<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\IntakeSubmission;
use App\Models\IntakePersonalInfo;
use App\Models\IntakeSpouseInfo;
use App\Models\IntakeChild;
use App\Models\IntakeAsset;
use App\Models\IntakeLiability;
use App\Models\User;
use App\Notifications\IntakeCompletedNotification;

class IntakeController extends Controller
{
    /**
     * Display the intake form
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get or create intake submission
        $submission = IntakeSubmission::firstOrCreate(
            ['user_id' => $user->id],
            ['current_section' => 1, 'progress_percentage' => 0]
        );

        // Load all existing data
        $personalInfo = $user->intakePersonalInfo;
        $spouseInfo = $user->intakeSpouseInfo;
        $children = $user->intakeChildren;
        $assets = $user->intakeAssets;
        $liabilities = $user->intakeLiabilities;
        $fiduciaries = $user->intakeFiduciaries;

        return view('intake.form', compact(
            'user',
            'submission',
            'personalInfo',
            'spouseInfo', 
            'children',
            'assets',
            'liabilities',
            'fiduciaries'
        ));
    }

    /**
     * Save all form data at once
     */
    public function saveAll(Request $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Save personal info
            if (isset($request->data['personal'])) {
                IntakePersonalInfo::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->data['personal']
                );
            }

            // Save spouse info
            if (isset($request->data['spouse'])) {
                IntakeSpouseInfo::updateOrCreate(
                    ['user_id' => $user->id],
                    $request->data['spouse']
                );
            }

            // Save children
            if (isset($request->data['children'])) {
                // Delete existing children
                $user->intakeChildren()->delete();
                
                // Create new ones
                foreach ($request->data['children'] as $index => $childData) {
                    $childData['sort_order'] = $index;
                    IntakeChild::create(array_merge($childData, ['user_id' => $user->id]));
                }
            }

            // Save assets
            if (isset($request->data['assets'])) {
                // Delete existing assets
                $user->intakeAssets()->delete();
                
                // Create new ones
                foreach ($request->data['assets'] as $index => $assetData) {
                    $assetData['sort_order'] = $index;
                    IntakeAsset::create(array_merge($assetData, ['user_id' => $user->id]));
                }
            }

            // Save liabilities
            if (isset($request->data['liabilities'])) {
                // Delete existing liabilities
                $user->intakeLiabilities()->delete();
                
                // Create new ones
                foreach ($request->data['liabilities'] as $index => $liabilityData) {
                    $liabilityData['sort_order'] = $index;
                    IntakeLiability::create(array_merge($liabilityData, ['user_id' => $user->id]));
                }
            }

            // Save fiduciaries
            if (isset($request->data['fiduciaries'])) {
                // Delete existing fiduciaries
                $user->intakeFiduciaries()->delete();
                
                $sortOrder = 0;
                // Save all fiduciary types
                foreach ($request->data['fiduciaries'] as $type => $fiduciaryList) {
                    foreach ($fiduciaryList as $fiduciaryData) {
                        // Only save if name is provided
                        if (!empty($fiduciaryData['full_name'])) {
                            $fiduciaryData['sort_order'] = $sortOrder++;
                            \App\Models\IntakeFiduciary::create(array_merge($fiduciaryData, ['user_id' => $user->id]));
                        }
                    }
                }
            }

            // Update submission progress
            $submission = IntakeSubmission::where('user_id', $user->id)->first();
            if ($submission) {
                $currentStep = $request->input('current_step', 1);
                
                // Calculate progress based on completed sections (sections with data)
                $completedSections = 0;
                $totalSteps = 8;
                
                // Step 1: Personal Info - required fields filled
                $personalInfo = $user->intakePersonalInfo;
                if ($personalInfo && 
                    !empty($personalInfo->first_name) && 
                    !empty($personalInfo->last_name) && 
                    !empty($personalInfo->email)) {
                    $completedSections++;
                }
                
                // Step 2: Spouse Info - either not married OR has spouse data
                if ($personalInfo) {
                    if ($personalInfo->marital_status === 'married') {
                        $spouseInfo = $user->intakeSpouseInfo;
                        if ($spouseInfo && !empty($spouseInfo->spouse_name)) {
                            $completedSections++;
                        }
                    } else {
                        // Not married, so spouse section is "complete" by default
                        $completedSections++;
                    }
                }
                
                // Step 3: Children - has at least one child OR explicitly skipped (count as complete if user has been past this step)
                if ($currentStep > 3 || $user->intakeChildren()->count() > 0) {
                    $completedSections++;
                }
                
                // Step 4: Assets - has at least one asset OR user has been past this step
                if ($currentStep > 4 || $user->intakeAssets()->count() > 0) {
                    $completedSections++;
                }
                
                // Step 5: Liabilities - has at least one liability OR user has been past this step
                if ($currentStep > 5 || $user->intakeLiabilities()->count() > 0) {
                    $completedSections++;
                }
                
                // Step 6: Fiduciaries - has at least one fiduciary OR user has been past this step
                if ($currentStep > 6 || $user->intakeFiduciaries()->count() > 0) {
                    $completedSections++;
                }
                
                // Step 7: Pets - has pet data OR user has been past this step
                if ($currentStep > 7 || !empty($submission->notes)) {
                    $completedSections++;
                }
                
                // Step 8: Review - only complete if user has submitted
                if ($submission->is_completed) {
                    $completedSections++;
                }
                
                $progressPercentage = round(($completedSections / $totalSteps) * 100);
                
                // Track the highest step user has reached
                $highestStep = max($submission->current_section ?? 1, $currentStep);
                
                $submission->update([
                    'current_section' => $highestStep,
                    'progress_percentage' => $progressPercentage,
                    'notes' => isset($request->data['pets']) ? json_encode($request->data['pets']) : $submission->notes
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data saved successfully',
                'progress' => $progressPercentage ?? 0
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error saving data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit the complete form
     */
    public function submitAll(Request $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();

            // Save all data first
            $this->saveAll($request);

            // Mark submission as complete
            $submission = IntakeSubmission::where('user_id', $user->id)->first();
            if ($submission) {
                $submission->is_completed = true;
                $submission->completed_at = now();
                $submission->progress_percentage = 100;
                $submission->status = 'submitted';
                $submission->save();

                // Update user status
                $user->update(['status' => 'in_progress']);

                // PHASE 1: Send notification to admin
                try {
                    $adminEmail = config('mail.admin_email', 'matt@harbor.law');
                    $admin = User::where('email', $adminEmail)->first();
                    if ($admin) {
                        $admin->notify(new IntakeCompletedNotification($user, $submission));
                    }
                } catch (\Exception $e) {
                    // Log error but don't fail the submission
                    Log::error('Failed to send intake completion notification: ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Form submitted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error submitting form: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download intake submission
     */
    public function download()
    {
        $user = Auth::user();
        
        // Get the user's most recent intake submission
        $submission = IntakeSubmission::where('user_id', $user->id)
            ->where('status', 'submitted')
            ->latest()
            ->first();
        
        if (!$submission) {
            return redirect()->route('dashboard')
                ->with('error', 'No intake submission found.');
        }
        
        // Check if file exists
        if (!$submission->file_path || !Storage::disk('private')->exists($submission->file_path)) {
            return redirect()->route('dashboard')
                ->with('error', 'Intake file not found. Please contact support.');
        }
        
        return Storage::disk('private')->download(
            $submission->file_path,
            'intake-submission-' . $submission->id . '.pdf'
        );
    }
}
