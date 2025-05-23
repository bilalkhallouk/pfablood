<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_type',
        'units_needed',
        'urgency',
        'prescription_file',
        'status'
    ];

    protected $casts = [
        'required_by_date' => 'datetime',
        'units_needed' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 