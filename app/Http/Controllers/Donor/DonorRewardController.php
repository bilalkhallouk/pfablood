<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;

class DonorRewardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:donor']);
    }

    public function index()
    {
        $user = auth()->user();
        
        $data = [
            'earnedBadges' => $user->badges()
                ->orderBy('earned_at', 'desc')
                ->get(),
            
            'availableBadges' => Badge::whereNotIn('id', $user->badges->pluck('id'))
                ->get(),
            
            'donationStreak' => $user->getDonationStreak(),
            
            'totalDonations' => $user->donations()->count(),
            
            'emergencyDonations' => $user->emergencyDonations()->count(),
            
            'referralCount' => User::where('referred_by', $user->id)->count(),
            
            'nextMilestone' => $this->getNextMilestone($user),
            
            'recentAchievements' => $user->badges()
                ->orderBy('user_badges.earned_at', 'desc')
                ->take(5)
                ->get()
        ];

        return view('donor.rewards.index', $data);
    }

    private function getNextMilestone($user)
    {
        $totalDonations = $user->donations()->count();
        $milestones = [1, 3, 5, 10, 25, 50, 100];
        
        foreach ($milestones as $milestone) {
            if ($totalDonations < $milestone) {
                return [
                    'donations' => $milestone,
                    'remaining' => $milestone - $totalDonations
                ];
            }
        }
        
        return null;
    }

    public function share(Badge $badge)
    {
        $this->authorize('share', $badge);

        // Generate social sharing links
        $shareLinks = [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . route('donor.rewards'),
            'twitter' => "https://twitter.com/intent/tweet?text=I just earned the {$badge->name} badge on PFA Blood!&url=" . route('donor.rewards'),
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url=" . route('donor.rewards')
        ];

        return view('donor.rewards.share', compact('badge', 'shareLinks'));
    }
} 