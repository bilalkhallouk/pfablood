<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyController extends Controller
{
    public function alert(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units' => 'required|integer|min:1',
            'message' => 'nullable|string|max:500'
        ]);

        // Find compatible donors within 20km radius
        $compatibleDonors = User::where('role', 'donor')
            ->where('blood_type', $validated['blood_type'])
            ->where('last_donation_at', '<=', now()->subMonths(3))
            ->selectRaw("*, 
                ( 6371 * acos( cos( radians(?) ) * 
                    cos( radians( latitude ) ) * 
                    cos( radians( longitude ) - radians(?) ) + 
                    sin( radians(?) ) * 
                    sin( radians( latitude ) ) 
                ) ) AS distance", [Auth::user()->latitude, Auth::user()->longitude, Auth::user()->latitude])
            ->having('distance', '<=', 20)
            ->get();

        // Create notifications for compatible donors
        foreach ($compatibleDonors as $donor) {
            Notification::create([
                'user_id' => $donor->id,
                'type' => 'emergency_request',
                'message' => "Emergency blood request for {$validated['blood_type']} blood type. Units needed: {$validated['units']}. " . 
                           ($validated['message'] ? "Message: {$validated['message']}" : ""),
                'data' => json_encode([
                    'requester_id' => Auth::id(),
                    'blood_type' => $validated['blood_type'],
                    'units' => $validated['units']
                ])
            ]);
        }

        return redirect()->back()->with('success', 'Emergency alert sent to ' . $compatibleDonors->count() . ' compatible donors!');
    }
} 