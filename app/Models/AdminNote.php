<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'note',
    ];

    /**
     * Get the user that this note belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
