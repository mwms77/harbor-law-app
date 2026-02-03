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
        
        // Ensure submission record exists
        $submission = $user->intakeSubmission ?? IntakeSubmission::create([
            'user_id' => $user->id,
        ]);

        // Load all existing data
        $data = [
            'personalInfo' => $user->intakePersonalInfo,
            'spouseInfo' => $user->intakeSpouseInfo,
            'children' => $user->intakeChildren,
            'assets' => $user->intakeAssets,
            'liabilities' => $user->intakeLiabilities,
            'beneficiaries' => $user->intakeBeneficiaries,
            'fiduciaries' => $user->intakeFiduciaries,
            'specificGifts' => $user->intakeSpecificGifts,
            'healthcarePreferences' => $user->intakeHealthcarePreferences,
            'distributionPreferences' => $user->intakeDistributionPreferences,
        ];

        return view('intake.form', compact('submission', 'user', 'data'));
    }

    public function savePersonalInfo(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'preferred_name' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date',
            'ssn' => 'nullable|string|max:20',
            'marital_status' => 'required|in:single,married,divorced,widowed,domestic_partnership',
            'street_address' => 'required|string',
            'city' => 'required|string',
            'county' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string|max:10',
            'mailing_address' => 'nullable|string',
            'primary_phone' => 'required|string',
            'secondary_phone' => 'nullable|string',
            'email' => 'required|email',
            'occupation' => 'nullable|string',
            'us_citizen' => 'boolean',
            'citizenship_country' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        IntakePersonalInfo::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        $user->intakeSubmission->markSectionComplete('personal_info');

        return response()->json([
            'success' => true,
            'message' => 'Personal information saved',
            'progress' => $user->intakeSubmission->progress_percentage
        ]);
    }

    public function saveSpouseInfo(Request $request)
    {
        $validated = $request->validate([
            'spouse_name' => 'nullable|string|max:255',
            'spouse_dob' => 'nullable|date',
            'spouse_ssn' => 'nullable|string',
            'spouse_occupation' => 'nullable|string',
            'spouse_us_citizen' => 'boolean',
            'spouse_citizenship_country' => 'nullable|string',
            'marriage_date' => 'nullable|date',
            'marriage_location' => 'nullable|string',
            'prenuptial_agreement' => 'boolean',
            'previous_marriage' => 'boolean',
            'previous_marriage_details' => 'nullable|string',
        ]);

        $user = Auth::user();
        
        IntakeSpouseInfo::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        $user->intakeSubmission->markSectionComplete('spouse_info');

        return response()->json([
            'success' => true,
            'message' => 'Spouse information saved',
            'progress' => $user->intakeSubmission->progress_percentage
        ]);
    }

    public function saveChildren(Request $request)
    {
        $validated = $request->validate([
            'children' => 'nullable|array',
            'children.*.id' => 'nullable|exists:intake_children,id',
            'children.*.full_name' => 'required|string',
            'children.*.date_of_birth' => 'nullable|date',
            'children.*.relationship' => 'required|in:biological,adopted,step,other',
            'children.*.minor' => 'boolean',
            'children.*.special_needs' => 'boolean',
            'children.*.special_needs_description' => 'nullable|string',
            'children.*.current_residence' => 'nullable|string',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $validated) {
            $keepIds = collect($validated['children'] ?? [])->pluck('id')->filter();
            $user->intakeChildren()->whereNotIn('id', $keepIds)->delete();

            foreach ($validated['children'] ?? [] as $index => $childData) {
                $childData['sort_order'] = $index;
                
                if (isset($childData['id'])) {
                    IntakeChild::where('id', $childData['id'])->update($childData);
                } else {
                    IntakeChild::create(array_merge($childData, ['user_id' => $user->id]));
                }
            }
        });

        $user->intakeSubmission->markSectionComplete('children');

        return response()->json([
            'success' => true,
            'message' => 'Children information saved',
            'progress' => $user->intakeSubmission->progress_percentage
        ]);
    }

    public function saveAssets(Request $request)
    {
        $validated = $request->validate([
            'assets' => 'nullable|array',
            'assets.*.id' => 'nullable|exists:intake_assets,id',
            'assets.*.asset_type' => 'required|in:real_estate,bank_account,investment,retirement,business,vehicle,personal_property,life_insurance,other',
            'assets.*.description' => 'required|string',
            'assets.*.estimated_value' => 'nullable|numeric',
            'assets.*.ownership' => 'required|in:individual,joint,trust,other',
            'assets.*.co_owner' => 'nullable|string',
            'assets.*.account_number' => 'nullable|string',
            'assets.*.institution' => 'nullable|string',
            'assets.*.location' => 'nullable|string',
            'assets.*.primary_residence' => 'boolean',
            'assets.*.beneficiary_designation' => 'nullable|string',
            'assets.*.notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $validated) {
            $keepIds = collect($validated['assets'] ?? [])->pluck('id')->filter();
            $user->intakeAssets()->whereNotIn('id', $keepIds)->delete();

            foreach ($validated['assets'] ?? [] as $index => $assetData) {
                $assetData['sort_order'] = $index;
                
                if (isset($assetData['id'])) {
                    IntakeAsset::where('id', $assetData['id'])->update($assetData);
                } else {
                    IntakeAsset::create(array_merge($assetData, ['user_id' => $user->id]));
                }
            }
        });

        $user->intakeSubmission->markSectionComplete('assets');

        return response()->json([
            'success' => true,
            'message' => 'Assets saved',
            'progress' => $user->intakeSubmission->progress_percentage
        ]);
    }

    public function saveLiabilities(Request $request)
    {
        $validated = $request->validate([
            'liabilities' => 'nullable|array',
            'liabilities.*.id' => 'nullable|exists:intake_liabilities,id',
            'liabilities.*.liability_type' => 'required|in:mortgage,home_equity,auto_loan,student_loan,credit_card,personal_loan,business_debt,other',
            'liabilities.*.description' => 'required|string',
            'liabilities.*.lender' => 'required|string',
            'liabilities.*.balance_owed' => 'required|numeric',
            'liabilities.*.monthly_payment' => 'nullable|numeric',
            'liabilities.*.account_number' => 'nullable|string',
            'liabilities.*.notes' => 'nullable|string',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $validated) {
            $keepIds = collect($validated['liabilities'] ?? [])->pluck('id')->filter();
            $user->intakeLiabilities()->whereNotIn('id', $keepIds)->delete();

            foreach ($validated['liabilities'] ?? [] as $index => $liabilityData) {
                $liabilityData['sort_order'] = $index;
                
                if (isset($liabilityData['id'])) {
                    IntakeLiability::where('id', $liabilityData['id'])->update($liabilityData);
                } else {
                    IntakeLiability::create(array_merge($liabilityData, ['user_id' => $user->id]));
                }
            }
        });

        $user->intakeSubmission->markSectionComplete('liabilities');

        return response()->json([
            'success' => true,
            'message' => 'Liabilities saved',
            'progress' => $user->intakeSubmission->progress_percentage
        ]);
    }

    public function submit(Request $request)
    {
        $user = Auth::user();
        $submission = $user->intakeSubmission;

        if (!$submission) {
            return response()->json(['success' => false, 'message' => 'No submission found.'], 404);
        }

        $submission->markAsCompleted();

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
                'formVersion' => '2.0',
                'completedDate' => $submission->completed_at->toISOString(),
                'jurisdiction' => 'Michigan',
                'userName' => $user->full_name,
                'userEmail' => $user->email,
                'downloadedAt' => now()->toISOString(),
            ],
            'personalInfo' => $user->intakePersonalInfo,
            'spouseInfo' => $user->intakeSpouseInfo,
            'children' => $user->intakeChildren,
            'assets' => $user->intakeAssets,
            'liabilities' => $user->intakeLiabilities,
            'beneficiaries' => $user->intakeBeneficiaries,
            'fiduciaries' => $user->intakeFiduciaries,
            'specificGifts' => $user->intakeSpecificGifts,
            'healthcarePreferences' => $user->intakeHealthcarePreferences,
            'distributionPreferences' => $user->intakeDistributionPreferences,
        ];

        $filename = 'intake_' . $user->id . '_' . str_replace(' ', '_', $user->full_name) . '_' . now()->format('Y-m-d') . '.json';

        return response()->json($data)
            ->header('Content-Type', 'application/json')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
