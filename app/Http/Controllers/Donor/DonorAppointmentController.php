<?php

namespace App\Http\Controllers\Donor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Center;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonorAppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:donor']);
    }

    public function index()
    {
        $appointments = Auth::user()->appointments()
            ->with('center')
            ->orderBy('scheduled_at')
            ->paginate(10);

        return view('donor.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $centers = Center::all();
        return view('donor.appointments.create', compact('centers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'center_id' => 'required|exists:centers,id',
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:500'
        ]);

        $appointment = Auth::user()->appointments()->create([
            'center_id' => $request->center_id,
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
            'status' => Appointment::STATUS_PENDING
        ]);

        return redirect()->route('donor.appointments')
            ->with('success', 'Appointment scheduled successfully!');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        return view('donor.appointments.show', compact('appointment'));
    }

    public function cancel(Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);
        
        $appointment->update(['status' => Appointment::STATUS_CANCELLED]);
        
        return redirect()->route('donor.appointments')
            ->with('success', 'Appointment cancelled successfully.');
    }
} 