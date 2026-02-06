<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUpload extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'mime_type',
        'category',
        'file_size',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Get the user that owns the upload.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get human-readable category name.
     */
    public function getCategoryNameAttribute(): string
    {
        $categories = [
            'id_documents' => 'ID Documents',
            'property_documents' => 'Property Documents',
            'financial_documents' => 'Financial Documents',
            'beneficiary_information' => 'Beneficiary Information',
            'health_care_directives' => 'Health Care Directives',
            'other' => 'Other',
        ];

        return $categories[$this->category] ?? $this->category;
    }

    /**
     * Get human-readable file size.
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon based on mime type.
     */
    public function getFileIconAttribute(): string
    {
        if (str_contains($this->mime_type, 'pdf')) {
            return 'document-text';
        }
        
        if (str_contains($this->mime_type, 'image')) {
            return 'photograph';
        }
        
        return 'document';
    }
}
