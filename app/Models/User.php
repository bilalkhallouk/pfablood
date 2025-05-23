<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'blood_type',
        'phone',
        'location',
        'latitude',
        'longitude',
        'last_donation_at'
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
        'password' => 'hashed',
        'last_donation_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    /**
     * Check if the user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a donor
     */
    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    /**
     * Check if the user is a patient
     */
    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    public function isEligibleToDonate()
    {
        if (!$this->last_donation_at) {
            return true;
        }

        return $this->last_donation_at->addMonths(3)->isPast();
    }

    /**
     * Get the user's settings
     */
    public function settings()
    {
        return $this->hasOne(UserSettings::class);
    }

    // New relationships for donor features
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function emergencyDonations()
    {
        return $this->donations()->where('is_emergency', true);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    public function emergencyRequests()
    {
        return $this->belongsToMany(EmergencyRequest::class, 'emergency_request_responses')
            ->withPivot('status', 'response_time', 'read_at')
            ->withTimestamps();
    }

    public function unreadEmergencyRequests()
    {
        return $this->emergencyRequests()
            ->wherePivot('read_at', null);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function getDonationStreak()
    {
        $streak = 0;
        $lastDonation = $this->donations()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastDonation) {
            return 0;
        }

        $currentDate = now();
        $threeMonthsAgo = $currentDate->copy()->subMonths(3);

        // If last donation was more than 3 months ago, streak is broken
        if ($lastDonation->created_at->lt($threeMonthsAgo)) {
            return 0;
        }

        // Count consecutive donations within 3-month intervals
        $donations = $this->donations()
            ->orderBy('created_at', 'desc')
            ->get();

        $expectedDate = $currentDate;
        foreach ($donations as $donation) {
            if ($donation->created_at->between(
                $expectedDate->copy()->subMonths(3),
                $expectedDate
            )) {
                $streak++;
                $expectedDate = $donation->created_at;
            } else {
                break;
            }
        }

        return $streak;
    }
}