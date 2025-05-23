<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\BloodRequest;
use App\Models\Center;
use App\Models\Event;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_donors' => User::where('role', 'donor')->count(),
            'total_patients' => User::where('role', 'patient')->count(),
            'total_donations' => Donation::count(),
            'total_centers' => Center::count(),
            'total_events' => Event::count(),
        ];

        return view('admin.statistics.index', compact('stats'));
    }

    public function donations()
    {
        $monthlyDonations = Donation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        $bloodTypeStats = Donation::select('blood_type', DB::raw('COUNT(*) as total'))
            ->groupBy('blood_type')
            ->get();

        return view('admin.statistics.donations', compact('monthlyDonations', 'bloodTypeStats'));
    }

    public function users()
    {
        $userGrowth = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->take(30)
        ->get();

        $usersByRole = User::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->get();

        return view('admin.statistics.users', compact('userGrowth', 'usersByRole'));
    }

    public function centers()
    {
        $centerStats = Center::select(
            'city',
            DB::raw('COUNT(*) as total_centers'),
            DB::raw('SUM(blood_units) as total_units')
        )
        ->groupBy('city')
        ->get();

        return view('admin.statistics.centers', compact('centerStats'));
    }

    public function events()
    {
        $upcomingEvents = Event::where('date', '>=', now())
            ->orderBy('date')
            ->take(5)
            ->get();

        $pastEvents = Event::where('date', '<', now())
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        $eventsByMonth = Event::select(
            DB::raw('MONTH(date) as month'),
            DB::raw('YEAR(date) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->take(12)
        ->get();

        return view('admin.statistics.events', compact('upcomingEvents', 'pastEvents', 'eventsByMonth'));
    }
} 