<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeSpouseInfo extends Model
{
    protected $table = 'intake_spouse_info';

    protected $fillable = [
        'user_id',
        'spouse_name',
        'spouse_dob',
        'spouse_ssn',
        'spouse_occupation',
        'spouse_us_citizen',
        'spouse_citizenship_country',
        'marriage_date',
        'marriage_location',
        'prenuptial_agreement',
        'previous_marriage',
        'previous_marriage_details',
    ];

    protected $casts = [
        'spouse_dob' => 'date',
        'marriage_date' => 'date',
        'spouse_us_citizen' => 'boolean',
        'prenuptial_agreement' => 'boolean',
        'previous_marriage' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
