<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeDistributionPreferences extends Model
{
    protected $table = 'intake_distribution_preferences';

    protected $fillable = [
        'user_id',
        'distribution_method',
        'age_restrictions',
        'distribution_age',
        'staged_distribution',
        'disinherit_anyone',
        'disinheritance_details',
        'equal_distribution',
        'unequal_distribution_explanation',
        'residue_distribution',
        'charitable_bequests',
        'charitable_bequest_details',
        'special_instructions',
        'digital_assets_instructions',
    ];

    protected $casts = [
        'age_restrictions' => 'boolean',
        'disinherit_anyone' => 'boolean',
        'equal_distribution' => 'boolean',
        'charitable_bequests' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
