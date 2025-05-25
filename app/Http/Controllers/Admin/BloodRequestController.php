<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\User;
use App\Models\Notification;
use App\Models\BloodStock;
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
        $stock = \App\Models\BloodStock::where('center_id', $request->center_id)
            ->where('blood_type', $request->blood_type)
            ->first();
        $stockUnits = $stock->units ?? $stock->units_available ?? 0;
        if ($stock && $stockUnits >= $request->units_needed) {
            if (isset($stock->units)) {
                $stock->units -= $request->units_needed;
            } else {
                $stock->units_available -= $request->units_needed;
            }
            $stock->save();
            $request->status = 'approved';
            $request->save();
            Notification::create([
                'user_id' => $request->user_id,
                'type' => 'blood_request',
                'message' => 'Votre demande de sang a été acceptée.',
                'data' => json_encode(['request_id' => $request->id, 'status' => 'approved']),
                'read' => false,
            ]);
            return redirect()->back()->with('success', 'Blood request accepted and stock updated.');
        } else {
            return redirect()->back()->with('error', 'Stock insuffisant pour accepter cette demande.');
        }
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