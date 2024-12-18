<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SetDBConnection
{
    public function handle(Request $request, Closure $next)
    {
        // Default connection
        $connection = config('database.default');

        if (Auth::check()) {
            $user = Auth::user();

            // Dynamically set connection based on role
            $connection = match ($user->role_id) {
                1 => 'admin',
                2 => 'staff',
                3 => 'customer',
                default => config('database.default'),
            };
        }

        // Purge and reconnect
        DB::purge($connection);
        config(['database.default' => $connection]);
        DB::reconnect($connection);

        DB::statement("SET myapp.user_id = " . (int) $user->user_id);

        return $next($request);
    }
}