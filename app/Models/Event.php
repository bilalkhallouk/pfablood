<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'center_id',
        'capacity',
        'image'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'capacity' => 'integer'
    ];

    /**
     * Get the center that hosts the event
     */
    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    /**
     * Get the participants of the event
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_participants')
            ->withTimestamps()
            ->withPivot('status');
    }

    /**
     * Check if the event is full
     */
    public function isFull()
    {
        return $this->participants()->count() >= $this->capacity;
    }

    /**
     * Check if the event has passed
     */
    public function hasPassed()
    {
        return $this->date->isPast();
    }

    /**
     * Get the formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    /**
     * Get the formatted time
     */
    public function getFormattedTimeAttribute()
    {
        return $this->time->format('H:i');
    }
} 