<?php

namespace App\Http\Controllers;

use App\Models\EstatePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EstatePlanController extends Controller
{
    public function download(EstatePlan $estatePlan)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->id !== $estatePlan->user_id && !$user->isAdmin()) {
            abort(403);
        }

        if (!Storage::disk('private')->exists($estatePlan->file_path)) {
            abort(404, 'File not found');
        }

        return Storage::disk('private')->download(
            $estatePlan->file_path,
            $estatePlan->original_filename
        );
    }

    public function view(EstatePlan $estatePlan)
    {
        $user = Auth::user();

        // Check authorization
        if ($user->id !== $estatePlan->user_id && !$user->isAdmin()) {
            abort(403);
        }

        if (!Storage::disk('private')->exists($estatePlan->file_path)) {
            abort(404, 'File not found');
        }

        return response()->file(
            Storage::disk('private')->path($estatePlan->file_path),
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $estatePlan->original_filename . '"'
            ]
        );
    }
}
