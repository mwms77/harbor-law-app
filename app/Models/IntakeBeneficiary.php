<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeBeneficiary extends Model
{
    protected $table = 'intake_beneficiaries';

    protected $fillable = [
        'user_id',
        'beneficiary_type',
        'full_name',
        'relationship',
        'address',
        'phone',
        'email',
        'date_of_birth',
        'share_percentage',
        'conditions',
        'sort_order',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'share_percentage' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedShareAttribute(): string
    {
        if (!$this->share_percentage) {
            return 'N/A';
        }
        return number_format($this->share_percentage, 2) . '%';
    }
}
