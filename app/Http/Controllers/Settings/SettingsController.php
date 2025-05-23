<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show()
    {
        $user = Auth::user();
        return view('settings.show', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()
                ->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateNotifications(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'emergency_alerts' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = Auth::user();
        
        $user->settings()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => $request->email_notifications ?? false,
                'sms_notifications' => $request->sms_notifications ?? false,
                'emergency_alerts' => $request->emergency_alerts ?? false
            ]
        );

        return back()->with('success', 'Notification preferences updated successfully!');
    }
} 