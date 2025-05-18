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
            return $request->wantsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Handle JSON response for API requests
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Login successful',
                    'redirect' => $this->getDashboardRoute($user->role)
                ]);
            }

            // Redirect based on user role
            return redirect()->intended($this->getDashboardRoute($user->role));
        }

        return $request->wantsJson()
            ? response()->json(['message' => 'These credentials do not match our records.'], 401)
            : redirect()->back()
                ->withInput($request->except('password'))
                ->withErrors(['email' => 'Ces identifiants ne correspondent pas à nos enregistrements.']);
    }

    /**
     * Get dashboard route based on user role
     */
    protected function getDashboardRoute($role)
    {
        return match($role) {
            'admin' => route('admin.dashboard'),
            'donor' => route('donor.dashboard'),
            'patient' => route('patient.dashboard'),
            default => '/',
        };
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
            'role' => 'required|in:donor,patient,admin', // Added 'admin' option
            'phone' => 'nullable|string|max:20',
            'cin' => 'nullable|string|max:20',
            'age' => 'nullable|integer|min:16|max:100',
            'last_donation_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $request->wantsJson()
                ? response()->json(['errors' => $validator->errors()], 422)
                : redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'cin' => $request->cin,
            'age' => $request->age,
            'last_donation_at' => $request->last_donation_at,
        ]);

        Auth::login($user);

        return $request->wantsJson()
            ? response()->json([
                'message' => 'Registration successful',
                'redirect' => $this->getDashboardRoute($user->role)
            ])
            : redirect()->intended($this->getDashboardRoute($user->role));
    }

    /**
     * Log the user out
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? response()->json(['message' => 'Logout successful'])
            : redirect('/');
    }
}