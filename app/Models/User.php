<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'is_admin',
        'intake_completed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'intake_completed_at' => 'datetime',
        'is_admin' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Available user statuses.
     *
     * @var array<string, string>
     */
    public static $statuses = [
        'pending' => 'Pending',
        'in_progress' => 'In Progress',
        'documents_uploaded' => 'Documents Uploaded',
        'plan_delivered' => 'Plan Delivered',
        'completed' => 'Completed',
    ];

    /**
     * Get the uploads for the user.
     */
    public function uploads()
    {
        return $this->hasMany(ClientUpload::class);
    }

    /**
     * Get the admin notes for the user.
     */
    public function adminNotes()
    {
        return $this->hasMany(AdminNote::class);
    }

    /**
     * Get human-readable status name.
     *
     * @return string
     */
    public function getStatusNameAttribute()
    {
        return self::$statuses[$this->status] ?? 'Unknown';
    }

    /**
     * Get status badge color class.
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-gray-100 text-gray-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'documents_uploaded' => 'bg-blue-100 text-blue-800',
            'plan_delivered' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get upload count badge color.
     *
     * @return string
     */
    public function getUploadBadgeColorAttribute()
    {
        $count = $this->uploads()->count();
        
        if ($count === 0) {
            return 'bg-gray-100 text-gray-800';
        } elseif ($count <= 5) {
            return 'bg-yellow-100 text-yellow-800';
        } else {
            return 'bg-green-100 text-green-800';
        }
    }

    /**
     * Check if user has completed intake.
     *
     * @return bool
     */
    public function hasCompletedIntake()
    {
        return !is_null($this->intake_completed_at);
    }

    /**
     * Check if user has uploaded documents.
     *
     * @return bool
     */
    public function hasUploads()
    {
        return $this->uploads()->count() > 0;
    }
}
