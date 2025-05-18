<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string[]  ...$roles  Array of allowed roles ('donor', 'patient', 'admin')
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // If no roles specified, deny access
        if (empty($roles)) {
            abort(403, 'No roles specified for access check.');
        }

        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first');
        }

        $user = Auth::user();

        // Validate user has a valid role
        if (empty($user->role)) {
            abort(403, 'Your account has no assigned role.');
        }

        // Check role against allowed roles (case-sensitive)
        if (in_array($user->role, $roles, true)) {
            return $next($request);
        }

        // Log unauthorized access attempt (optional)
        \Log::warning("Unauthorized role access attempt", [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'required_roles' => $roles,
            'ip' => $request->ip()
        ]);

        abort(403, 'You do not have permission to access this resource.');
    }
}