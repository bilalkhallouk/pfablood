<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'requirement_type',
        'requirement_value',
        'points'
    ];

    // Badge types
    const TYPE_DONATIONS = 'donations';
    const TYPE_EMERGENCY = 'emergency';
    const TYPE_STREAK = 'streak';
    const TYPE_SOCIAL = 'social';

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badges')
            ->withTimestamps()
            ->withPivot('earned_at');
    }

    public static function checkAndAward(User $user)
    {
        // Check if user qualifies for any new badges
        $badges = self::whereDoesntHave('users', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        foreach ($badges as $badge) {
            if ($badge->checkRequirement($user)) {
                $user->badges()->attach($badge->id, ['earned_at' => now()]);
                // You could dispatch an event here to notify the user
            }
        }
    }

    protected function checkRequirement(User $user)
    {
        switch ($this->requirement_type) {
            case self::TYPE_DONATIONS:
                return $user->donations()->count() >= $this->requirement_value;
            case self::TYPE_EMERGENCY:
                return $user->emergencyDonations()->count() >= $this->requirement_value;
            case self::TYPE_STREAK:
                return $user->getDonationStreak() >= $this->requirement_value;
            case self::TYPE_SOCIAL:
                return $user->referrals()->count() >= $this->requirement_value;
            default:
                return false;
        }
    }
} 