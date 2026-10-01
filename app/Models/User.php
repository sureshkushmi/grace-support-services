<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'dob',
        'address',
        'email',
        'phone',
        'password',
        'employment_status',
        'worker_screening_expiry',
        'police_check_expiry',
        'first_aid_expiry',
        'drivers_license_expiry',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'dob' => 'date',
        'worker_screening_expiry' => 'date',
        'police_check_expiry' => 'date',
        'first_aid_expiry' => 'date',
        'drivers_license_expiry' => 'date',
        'status' => 'boolean',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function shiftReports(): HasMany
    {
        return $this->hasMany(ShiftReport::class, 'staff_id');
    }

    public function staffDocuments(): HasMany
    {
        return $this->hasMany(StaffDocument::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}