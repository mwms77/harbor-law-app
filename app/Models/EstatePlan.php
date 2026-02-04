<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class EstatePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'uploaded_by',
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'mime_type',
        'notes',
        'status',
        'executed_at',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'executed_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileUrl()
    {
        return route('estate-plans.download', $this);
    }

    public function getFileSizeFormatted()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getStatusBadge()
    {
        $colors = [
            'draft' => '#6c757d',    // Gray
            'final' => '#667eea',    // Purple
            'executed' => '#28a745', // Green
        ];
        
        $labels = [
            'draft' => 'Draft',
            'final' => 'Final',
            'executed' => 'Executed',
        ];
        
        $color = $colors[$this->status] ?? '#6c757d';
        $label = $labels[$this->status] ?? 'Unknown';
        
        return "<span style='background: {$color}; color: white; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 600;'>{$label}</span>";
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($plan) {
            if (!$plan->isForceDeleting()) {
                return;
            }
            
            if (Storage::disk('private')->exists($plan->file_path)) {
                Storage::disk('private')->delete($plan->file_path);
            }
        });
    }
}
