<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'sms_notifications',
        'emergency_alerts'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'emergency_alerts' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 