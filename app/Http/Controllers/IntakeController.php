<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\IntakeSubmission;
use App\Models\IntakePersonalInfo;
use App\Models\IntakeSpouseInfo;
use App\Models\IntakeChild;
use App\Models\IntakeAsset;
use App\Models\IntakeLiability;

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
                // Calculate progress based on current step (from request)
                $currentStep = $request->input('current_step', 1);
                $totalSteps = 8;
                $progressPercentage = round(($currentStep / $totalSteps) * 100);
                
                $submission->update([
                    'current_section' => $currentStep,
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
                $submission->save();
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
}
