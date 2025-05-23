<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    public function search(Request $request)
    {
        $query = User::where('role', 'donor')
            ->where(function($q) {
                $q->where('last_donation_at', '<=', now()->subMonths(3))
                    ->orWhereNull('last_donation_at');
            });

        // Filter by blood type
        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        // Filter by location if coordinates are provided
        if ($request->filled(['latitude', 'longitude'])) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius ?? 20; // Default 20km radius

            $query->selectRaw("*, 
                ( 6371 * acos( cos( radians(?) ) * 
                    cos( radians( latitude ) ) * 
                    cos( radians( longitude ) - radians(?) ) + 
                    sin( radians(?) ) * 
                    sin( radians( latitude ) ) 
                ) ) AS distance", [$lat, $lng, $lat])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }

        $donors = $query->paginate(10);
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

        return view('donors.search', compact('donors', 'bloodTypes'));
    }
} 