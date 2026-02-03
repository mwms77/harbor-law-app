<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntakeSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'personal_info_complete',
        'spouse_info_complete',
        'children_complete',
        'assets_complete',
        'liabilities_complete',
        'beneficiaries_complete',
        'fiduciaries_complete',
        'specific_gifts_complete',
        'healthcare_complete',
        'distribution_complete',
        'current_section',
        'progress_percentage',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'personal_info_complete' => 'boolean',
        'spouse_info_complete' => 'boolean',
        'children_complete' => 'boolean',
        'assets_complete' => 'boolean',
        'liabilities_complete' => 'boolean',
        'beneficiaries_complete' => 'boolean',
        'fiduciaries_complete' => 'boolean',
        'specific_gifts_complete' => 'boolean',
        'healthcare_complete' => 'boolean',
        'distribution_complete' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'current_section' => 'integer',
        'progress_percentage' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);
    }

    public function markSectionComplete(string $section)
    {
        $field = $section . '_complete';
        if (in_array($field, $this->fillable)) {
            $this->update([$field => true]);
            $this->recalculateProgress();
        }
    }

    public function recalculateProgress()
    {
        $sections = [
            'personal_info_complete',
            'spouse_info_complete',
            'children_complete',
            'assets_complete',
            'liabilities_complete',
            'beneficiaries_complete',
            'fiduciaries_complete',
            'specific_gifts_complete',
            'healthcare_complete',
            'distribution_complete',
        ];

        $completedCount = collect($sections)->filter(function ($section) {
            return $this->$section === true;
        })->count();

        $percentage = ($completedCount / count($sections)) * 100;

        $this->update(['progress_percentage' => round($percentage)]);

        if ($percentage === 100) {
            $this->markAsCompleted();
        }
    }

    public function getCompletedSectionsAttribute(): array
    {
        return [
            'Personal Information' => $this->personal_info_complete,
            'Spouse Information' => $this->spouse_info_complete,
            'Children' => $this->children_complete,
            'Assets' => $this->assets_complete,
            'Liabilities' => $this->liabilities_complete,
            'Beneficiaries' => $this->beneficiaries_complete,
            'Fiduciaries' => $this->fiduciaries_complete,
            'Specific Gifts' => $this->specific_gifts_complete,
            'Healthcare Preferences' => $this->healthcare_complete,
            'Distribution Preferences' => $this->distribution_complete,
        ];
    }
}
