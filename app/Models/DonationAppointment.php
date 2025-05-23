<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DonationAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'center_id',
        'appointment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
} 