<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeHealthcarePreferences extends Model
{
    protected $table = 'intake_healthcare_preferences';

    protected $fillable = [
        'user_id',
        'life_sustaining_treatment',
        'organ_donation',
        'organ_donation_specifics',
        'anatomical_gift',
        'anatomical_gift_details',
        'funeral_preference',
        'funeral_instructions',
        'funeral_prepaid',
        'additional_healthcare_wishes',
        'religious_preferences',
    ];

    protected $casts = [
        'organ_donation' => 'boolean',
        'anatomical_gift' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
