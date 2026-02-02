<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntakeSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'form_data',
        'current_section',
        'progress_percentage',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'form_data' => 'array',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'current_section' => 'integer',
        'progress_percentage' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsCompleted()
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
            'progress_percentage' => 100,
        ]);
    }
}
