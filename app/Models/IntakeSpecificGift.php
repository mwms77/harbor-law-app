<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntakeSpecificGift extends Model
{
    protected $table = 'intake_specific_gifts';

    protected $fillable = [
        'user_id',
        'item_description',
        'recipient_name',
        'recipient_relationship',
        'conditions',
        'if_predeceased',
        'alternate_recipient',
        'sort_order',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
