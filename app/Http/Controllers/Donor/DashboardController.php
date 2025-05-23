<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:donor']);
    }

    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'emergencyRequests' => $user->emergencyRequests()
                ->latest()
                ->take(3)
                ->get(),
            'upcomingAppointments' => $user->appointments()
                ->upcoming()
                ->take(3)
                ->get(),
            'badges' => $user->badges()
                ->latest()
                ->take(4)
                ->get(),
            'totalDonations' => $user->donations()->count(),
            'lastDonation' => $user->last_donation_at,
            'isEligible' => $user->isEligibleToDonate(),
        ];

        return view('donor.dashboard', $data);
    }
}