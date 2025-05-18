<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Admin middleware
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        return $next($request);
    }
}

// Donor middleware
class DonorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'donor') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        return $next($request);
    }
}

// Patient middleware
class PatientMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'patient') {
            return redirect('/')->with('error', 'Accès non autorisé');
        }

        return $next($request);
    }
}