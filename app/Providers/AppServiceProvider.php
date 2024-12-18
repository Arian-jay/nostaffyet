<?php

namespace App\Providers;

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->singleton('role', function () {
            return new RoleMiddleware();
        });
        
        // Hook into the authenticated user session
        if (Auth::check()) {
            $user = Auth::user();
            $dbRole = strtolower($user->role);

            // Set PostgreSQL role for the current session
            DB::statement("SET ROLE \"$dbRole\"");
        }
    }
}
