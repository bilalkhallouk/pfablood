<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloodStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_type',
        'units_available',
        'center_id',
        'last_updated',
        'minimum_threshold',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function getStatusAttribute()
    {
        if ($this->units_available <= $this->minimum_threshold) {
            return 'critical';
        } elseif ($this->units_available <= $this->minimum_threshold * 2) {
            return 'low';
        }
        return 'normal';
    }
} 