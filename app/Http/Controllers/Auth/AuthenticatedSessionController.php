<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{

    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }
    /**
     * Handle an incoming authentication request.
     */


     public function store(Request $request)
     {
         // Validate the login request
         $request->validate([
             'email' => 'required|email',
             'password' => 'required|string',
         ]);
     
         // Attempt to authenticate the user
         if (Auth::attempt($request->only('email', 'password'))) {
            //  $user = Auth::user();
     
            //  // Switch PostgreSQL role for the current session
            //  $role = strtolower($user->role); // Ensure the role matches PostgreSQL naming
            //  DB::statement("SET ROLE \"$role\"");
     
             return redirect()->intended(route('dashboard')); // Redirect to the intended dashboard
         }
     
         // If authentication fails, redirect back with an error
         return back()->withErrors([
             'email' => 'The provided credentials do not match our records.',
         ]);
     }


    
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

