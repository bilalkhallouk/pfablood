<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\Center;

class DashboardController extends Controller
{
    public function index()
    {
        $patient = Auth::user();
        $centers = Center::all();
        $cities = Center::select('city')->distinct()->pluck('city');
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
                ->get(),
            'patient' => $patient,
            'centers' => $centers,
            'cities' => $cities,
        ];

        return view('patient.patientdashboard', $data);
    }
}