<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{
    public function show()
    {
        return view('settings.show');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    public function updateNotifications(Request $request)
    {
        $request->validate([
            'email_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'emergency_alerts' => ['boolean']
        ]);

        $user = Auth::user();
        $user->settings()->update($request->only([
            'email_notifications',
            'sms_notifications',
            'emergency_alerts'
        ]));

        return redirect()->back()->with('success', 'Notification settings updated successfully!');
    }
} 