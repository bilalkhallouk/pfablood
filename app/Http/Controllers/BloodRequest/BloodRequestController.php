<?php

namespace App\Http\Controllers\BloodRequest;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BloodRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_needed' => 'required|integer|min:1',
            'urgency' => 'required|in:low,medium,high',
            'prescription' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        // Handle file upload
        $prescriptionPath = null;
        if ($request->hasFile('prescription')) {
            $prescriptionPath = $request->file('prescription')->store('prescriptions', 'public');
        }

        // Create blood request
        $bloodRequest = BloodRequest::create([
            'user_id' => Auth::id(),
            'blood_type' => $request->blood_type,
            'units_needed' => $request->units_needed,
            'urgency' => $request->urgency,
            'prescription_file' => $prescriptionPath,
            'status' => 'pending',
            'city' => $request->city,
        ]);

        return redirect()->back()->with('success', 'Blood request submitted successfully!');
    }
} 