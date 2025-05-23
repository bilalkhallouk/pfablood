<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class BloodRequestController extends Controller
{
    public function index()
    {
        $requests = BloodRequest::with('user')
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('admin.blood_requests.index', compact('requests'));
    }

    public function accept($id)
    {
        $request = BloodRequest::findOrFail($id);
        $request->status = 'approved';
        $request->save();
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'blood_request',
            'message' => 'Votre demande de sang a été acceptée.',
            'data' => json_encode(['request_id' => $request->id, 'status' => 'approved']),
            'read' => false,
        ]);
        return redirect()->back()->with('success', 'Blood request accepted.');
    }

    public function reject($id)
    {
        $request = BloodRequest::findOrFail($id);
        $request->status = 'rejected';
        $request->save();
        Notification::create([
            'user_id' => $request->user_id,
            'type' => 'blood_request',
            'message' => 'Votre demande de sang a été rejetée.',
            'data' => json_encode(['request_id' => $request->id, 'status' => 'rejected']),
            'read' => false,
        ]);
        return redirect()->back()->with('success', 'Blood request rejected.');
    }
} 