<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeChild extends Model
{
    protected $table = 'intake_children';

    protected $fillable = [
        'user_id',
        'full_name',
        'date_of_birth',
        'relationship',
        'minor',
        'special_needs',
        'special_needs_description',
        'current_residence',
        'sort_order',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'minor' => 'boolean',
        'special_needs' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }
        return $this->date_of_birth->diffInYears(now());
    }
}
