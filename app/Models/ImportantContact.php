<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportantContact extends Model
{
    protected $fillable = [
        'user_id',
        'role_type',
        'full_name',
        'relationship',
        'address',
        'phone',
        'email',
        'sort_order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All allowed contact role types.
     */
    public static function allowedRoleTypes(): array
    {
        return [
            'trustee' => 'Primary Trustee',
            'successor_trustee' => 'Successor Trustee',
            'personal_representative' => 'Personal Representative',
            'successor_personal_representative' => 'Successor Personal Representative',
            'guardian' => 'Guardian for Minor Children',
            'successor_guardian' => 'Successor Guardian',
            'healthcare_poa' => 'Patient Advocate (Healthcare POA)',
            'financial_poa' => 'Financial Power of Attorney',
            'primary_lawyer' => 'Primary Lawyer',
            'primary_cpa' => 'Primary CPA',
            'other_contact' => 'Other Contact',
        ];
    }

    public function getRoleLabelAttribute(): string
    {
        return self::allowedRoleTypes()[$this->role_type] ?? ucwords(str_replace('_', ' ', $this->role_type));
    }
}
