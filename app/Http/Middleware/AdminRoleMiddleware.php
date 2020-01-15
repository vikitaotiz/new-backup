<?php

namespace App\Http\Middleware;

use Closure;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->user()->role_id == 2) {
            return $next($request);
        } else {
            return redirect()->back()->with('error', 'You do not have permission to access this module.');
        }
    }
}
