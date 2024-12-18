<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ensure the user is authenticated and has the correct role
        if (!Auth::check() || !Auth::user() || Auth::user()->role_id !== 3) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }
        // Allow the request to proceed
        return $next($request);
    }
}
