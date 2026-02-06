<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientUpload extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'mime_type',
        'category',
        'file_size',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Available file categories for estate planning documents.
     *
     * @var array<string, string>
     */
    public static $categories = [
        'id_documents' => 'ID Documents',
        'property_documents' => 'Property Documents',
        'financial_documents' => 'Financial Documents',
        'beneficiary_information' => 'Beneficiary Information',
        'health_care_directives' => 'Health Care Directives',
        'other' => 'Other',
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
     *
     * @return string
     */
    public function getCategoryNameAttribute()
    {
        return self::$categories[$this->category] ?? 'Unknown';
    }

    /**
     * Get formatted file size.
     *
     * @return string
     */
    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file extension from original filename.
     *
     * @return string
     */
    public function getExtensionAttribute()
    {
        return pathinfo($this->original_name, PATHINFO_EXTENSION);
    }

    /**
     * Get full storage path for this file.
     *
     * @return string
     */
    public function getStoragePathAttribute()
    {
        return 'private/client-uploads/' . $this->user_id . '/' . $this->filename;
    }

    /**
     * Check if file is an image.
     *
     * @return bool
     */
    public function isImage()
    {
        return in_array($this->mime_type, [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/heic',
        ]);
    }

    /**
     * Check if file is a PDF.
     *
     * @return bool
     */
    public function isPdf()
    {
        return $this->mime_type === 'application/pdf';
    }
}
