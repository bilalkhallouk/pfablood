<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'activeRequests' => BloodRequest::where('user_id', Auth::id())
                ->where('status', 'pending')
                ->count(),
            'availableDonors' => User::where('role', 'donor')
                ->where('last_donation_at', '<=', now()->subMonths(3))
                ->count(),
            'notifications' => Notification::where('user_id', Auth::id())
                ->where('read', false)
                ->count(),
            'recentRequests' => BloodRequest::where('user_id', Auth::id())
                ->latest()
                ->take(5)
                ->get()
        ];

        return view('patient.dashboard', $data);
    }
}