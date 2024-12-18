<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roleIds)
    {
        if (!in_array($request->user()->role_id, $roleIds)) {
            return redirect('/home');  // Redirect if not allowed
        }

        return $next($request);
    }
}
