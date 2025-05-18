<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'donor') {
                return redirect()->route('donor.dashboard');
            } elseif ($user->role === 'patient') {
                return redirect()->route('patient.dashboard');
            }

            // Default fallback
            return redirect()->intended('/');
        }

        return redirect()->back()
            ->withInput($request->except('password'))
            ->withErrors(['email' => 'Ces identifiants ne correspondent pas à nos enregistrements.']);
    }

    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle the registration request
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:donor,patient',
            'blood_type' => 'nullable|string|max:5',
            'phone' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:16|max:100',
            'location' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'blood_type' => $request->blood_type,
            'phone' => $request->phone,
            'age' => $request->age,
            'location' => $request->location,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        Auth::login($user);

        if ($user->role === 'donor') {
            return redirect()->route('donor.dashboard');
        } elseif ($user->role === 'patient') {
            return redirect()->route('patient.dashboard');
        }

        return redirect('/');
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}