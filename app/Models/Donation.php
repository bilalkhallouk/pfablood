<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'center_id',
        'appointment_id',
        'blood_type',
        'units',
        'is_emergency',
        'emergency_request_id',
        'notes'
    ];

    protected $casts = [
        'is_emergency' => 'boolean',
        'units' => 'float'
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function emergencyRequest()
    {
        return $this->belongsTo(EmergencyRequest::class);
    }

    protected static function booted()
    {
        static::created(function ($donation) {
            // Update user's last donation date
            $donation->donor->update(['last_donation_at' => $donation->created_at]);
            
            // Check for new badges
            Badge::checkAndAward($donation->donor);
        });
    }
} 