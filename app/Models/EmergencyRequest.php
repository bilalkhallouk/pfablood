<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class EmergencyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'hospital_name',
        'blood_type',
        'units_needed',
        'urgency_level',
        'location',
        'latitude',
        'longitude',
        'status',
        'description'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'units_needed' => 'integer'
    ];

    // Possible status values
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Urgency levels
    const URGENCY_NORMAL = 'normal';
    const URGENCY_URGENT = 'urgent';
    const URGENCY_CRITICAL = 'critical';

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function donors()
    {
        return $this->belongsToMany(User::class, 'emergency_request_responses')
            ->withPivot('status', 'response_time')
            ->withTimestamps();
    }

    public function responses()
    {
        return $this->hasMany(EmergencyRequestResponse::class);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_IN_PROGRESS]);
    }

    public function getDistanceAttribute()
    {
        // Calculate distance from user's location
        // This is a placeholder - implement actual distance calculation
        return rand(1, 20); // Mock distance for now
    }
} 