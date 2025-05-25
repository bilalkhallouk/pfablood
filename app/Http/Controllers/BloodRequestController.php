<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\BloodRequestToAdmin;
use Illuminate\Support\Facades\Mail;

class BloodRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_type' => 'required|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'units_needed' => 'required|integer|min:1',
            'urgency' => 'required|in:low,medium,high',
            'prescription' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $bloodRequest = new BloodRequest();
        $bloodRequest->user_id = Auth::id();
        $bloodRequest->blood_type = $validated['blood_type'];
        $bloodRequest->units_needed = $validated['units_needed'];
        $bloodRequest->urgency = $validated['urgency'];
        $bloodRequest->status = 'pending';
        $bloodRequest->city = $request->input('city');

        if ($request->hasFile('prescription')) {
            $path = $request->file('prescription')->store('prescriptions', 'public');
            $bloodRequest->prescription_file = $path;
        }

        $bloodRequest->save();

        // Send email to admin with ordonnance attached
        $adminEmail = 'admin@example.com'; // Change to your admin's email
        Mail::to($adminEmail)->send(new BloodRequestToAdmin($bloodRequest));

        return redirect()->back()->with('success', 'Blood request submitted successfully!');
    }
} 