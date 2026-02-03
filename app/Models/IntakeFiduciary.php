<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeFiduciary extends Model
{
    protected $table = 'intake_fiduciaries';

    protected $fillable = [
        'user_id',
        'role_type',
        'full_name',
        'relationship',
        'address',
        'phone',
        'email',
        'date_of_birth',
        'professional',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'professional' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role_type) {
            'personal_representative' => 'Personal Representative',
            'successor_personal_representative' => 'Successor Personal Representative',
            'trustee' => 'Trustee',
            'successor_trustee' => 'Successor Trustee',
            'guardian' => 'Guardian',
            'successor_guardian' => 'Successor Guardian',
            'healthcare_poa' => 'Healthcare Power of Attorney',
            'financial_poa' => 'Financial Power of Attorney',
            default => ucwords(str_replace('_', ' ', $this->role_type)),
        };
    }
}
