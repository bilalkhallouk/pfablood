<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    public function search(Request $request)
    {
        $query = User::where('role', 'donor')
            ->where('last_donation_at', '<=', now()->subMonths(3));

        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        if ($request->filled('location')) {
            // Using Haversine formula to calculate distance
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->radius ?? 10; // Default 10km radius

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

        return view('patient.donors', compact('donors'));
    }
} 