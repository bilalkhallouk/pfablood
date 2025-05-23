<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\BloodStock;
use App\Models\DonationAppointment;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Event;
use App\Models\Center;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Show the admin dashboard with real data
     */
    public function index()
    {
        $data = [
            // Total counts
            'totalUsers' => User::count(),
            'totalDonors' => User::where('role', 'donor')->count(),
            'totalPatients' => User::where('role', 'patient')->count(),
            'totalCenters' => Center::count(),
            
            // Recent users
            'recentUsers' => User::orderBy('created_at', 'desc')
                ->take(5)
                ->get(),
            
            // Upcoming events
            'upcomingEvents' => Event::with('center', 'participants')
                ->where('date', '>=', now())
                ->orderBy('date', 'asc')
                ->take(5)
                ->get()
        ];

        return view('admin.dashboard', $data);
    }
}