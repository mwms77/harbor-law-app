<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeLiability extends Model
{
    protected $table = 'intake_liabilities';

    protected $fillable = [
        'user_id',
        'liability_type',
        'description',
        'lender',
        'balance_owed',
        'monthly_payment',
        'account_number',
        'notes',
        'sort_order',
    ];

    protected $casts = [
        'balance_owed' => 'decimal:2',
        'monthly_payment' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedBalanceAttribute(): string
    {
        return '$' . number_format($this->balance_owed, 2);
    }

    public function getFormattedPaymentAttribute(): string
    {
        if (!$this->monthly_payment) {
            return 'N/A';
        }
        return '$' . number_format($this->monthly_payment, 2);
    }
}
