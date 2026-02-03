<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakePersonalInfo extends Model
{
    protected $table = 'intake_personal_info';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'preferred_name',
        'date_of_birth',
        'ssn',
        'marital_status',
        'street_address',
        'city',
        'county',
        'state',
        'zip_code',
        'mailing_address',
        'primary_phone',
        'secondary_phone',
        'email',
        'occupation',
        'us_citizen',
        'citizenship_country',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'us_citizen' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ]);
        return implode(' ', $parts);
    }
}
