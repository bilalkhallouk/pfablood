<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\EmergencyRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorEmergencyRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:donor']);
    }

    public function index()
    {
        $user = auth()->user();
        $emergencyRequests = $user->emergencyRequests()
            ->latest()
            ->paginate(10);

        $responseStats = [
            'total' => $user->emergencyRequests()->count(),
            'accepted' => $user->emergencyRequests()->wherePivot('status', 'accept')->count(),
            'rate' => $user->emergencyRequests()->count() > 0
                ? round(($user->emergencyRequests()->wherePivot('status', 'accept')->count() / $user->emergencyRequests()->count()) * 100)
                : 0
        ];

        return view('donor.emergency-requests.index', compact('emergencyRequests', 'responseStats'));
    }

    public function show(EmergencyRequest $emergencyRequest)
    {
        // Mark the request as read
        auth()->user()->emergencyRequests()
            ->updateExistingPivot($emergencyRequest->id, [
                'read_at' => now()
            ]);

        return view('donor.emergency-requests.show', compact('emergencyRequest'));
    }

    public function respond(Request $request, EmergencyRequest $emergencyRequest)
    {
        $validated = $request->validate([
            'response' => 'required|in:accept,decline'
        ]);

        auth()->user()->emergencyRequests()
            ->updateExistingPivot($emergencyRequest->id, [
                'status' => $validated['response'],
                'response_time' => now()
            ]);

        $message = $validated['response'] === 'accept' 
            ? 'Thank you for accepting to help! The hospital will contact you shortly.'
            : 'Thank you for your response.';

        return redirect()->route('donor.emergency-requests')
            ->with('success', $message);
    }
} 