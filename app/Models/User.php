<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    // Accessor to get full name
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    // Mutator to set name (for backwards compatibility)
    public function setNameAttribute($value)
    {
        $nameParts = explode(' ', $value, 2);
        $this->attributes['first_name'] = $nameParts[0] ?? '';
        $this->attributes['last_name'] = $nameParts[1] ?? '';
        $this->attributes['name'] = $value;
    }

    // Relationships
    public function intakeSubmission()
    {
        return $this->hasOne(IntakeSubmission::class);
    }

    public function intakePersonalInfo()
    {
        return $this->hasOne(IntakePersonalInfo::class);
    }

    public function intakeSpouseInfo()
    {
        return $this->hasOne(IntakeSpouseInfo::class);
    }

    public function intakeChildren()
    {
        return $this->hasMany(IntakeChild::class)->orderBy('sort_order');
    }

    public function intakeAssets()
    {
        return $this->hasMany(IntakeAsset::class)->orderBy('sort_order');
    }

    public function intakeLiabilities()
    {
        return $this->hasMany(IntakeLiability::class)->orderBy('sort_order');
    }

    public function intakeBeneficiaries()
    {
        return $this->hasMany(IntakeBeneficiary::class)->orderBy('sort_order');
    }

    public function intakeFiduciaries()
    {
        return $this->hasMany(IntakeFiduciary::class)->orderBy('sort_order');
    }

    public function intakeSpecificGifts()
    {
        return $this->hasMany(IntakeSpecificGift::class)->orderBy('sort_order');
    }

    public function intakeHealthcarePreferences()
    {
        return $this->hasOne(IntakeHealthcarePreferences::class);
    }

    public function intakeDistributionPreferences()
    {
        return $this->hasOne(IntakeDistributionPreferences::class);
    }

    public function estatePlans()
    {
        return $this->hasMany(EstatePlan::class);
    }

    public function uploadedPlans()
    {
        return $this->hasMany(EstatePlan::class, 'uploaded_by');
    }

    // NEW: Phase 1 relationships
    public function uploads()
    {
        return $this->hasMany(ClientUpload::class);
    }

    public function adminNotes()
    {
        return $this->hasMany(AdminNote::class);
    }

    public function importantContacts()
    {
        return $this->hasMany(ImportantContact::class)->orderBy('sort_order')->orderBy('role_type');
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    // NEW: Status helper
    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'gray',
            'in_progress' => 'yellow',
            'documents_uploaded' => 'blue',
            'plan_delivered' => 'purple',
            'completed' => 'green',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'documents_uploaded' => 'Documents Uploaded',
            'plan_delivered' => 'Plan Delivered',
            'completed' => 'Completed',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }
}
