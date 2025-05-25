<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'city',
        'phone',
        'email',
        'latitude',
        'longitude',
        'is_active',
        'operating_hours',
        'available_blood_types',
        'description',
        'website',
        'emergency_contact'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'operating_hours' => 'array',
        'available_blood_types' => 'array'
    ];

    public function getFormattedOperatingHoursAttribute()
    {
        if (!$this->operating_hours) return 'Not available';
        
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $formatted = [];
        
        foreach ($days as $day) {
            if (isset($this->operating_hours[$day])) {
                $hours = $this->operating_hours[$day];
                $formatted[] = "$day: {$hours['open']} - {$hours['close']}";
            }
        }
        
        return $formatted;
    }

    public function bloodStocks()
    {
        return $this->hasMany(\App\Models\BloodStock::class);
    }
} 