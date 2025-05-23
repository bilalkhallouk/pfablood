<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // Mark all as read first
        Notification::where('user_id', Auth::id())->where('read', false)->update(['read' => true]);
        // Now fetch the updated notifications
        $notifications = Notification::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(20);
        return view('patient.notifications.index', compact('notifications'));
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', Auth::id())
            ->where('read', false)
            ->update(['read' => true]);
        return response()->json(['success' => true]);
    }

    public function markRead($notificationId)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($notificationId);
        $notification->read = true;
        $notification->save();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }
        return back();
    }
} 