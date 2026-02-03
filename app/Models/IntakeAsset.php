<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeAsset extends Model
{
    protected $table = 'intake_assets';

    protected $fillable = [
        'user_id',
        'asset_type',
        'description',
        'estimated_value',
        'ownership',
        'co_owner',
        'account_number',
        'institution',
        'location',
        'primary_residence',
        'beneficiary_designation',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'primary_residence' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedValueAttribute(): string
    {
        if (!$this->estimated_value) {
            return 'N/A';
        }
        return '$' . number_format($this->estimated_value, 2);
    }
}
