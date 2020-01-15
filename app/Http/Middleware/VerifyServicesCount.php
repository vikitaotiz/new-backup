<?php

namespace App\Http\Middleware;

use Closure;
use App\Service;

class VerifyServicesCount
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
        if (Service::all()->count() === 0) {
            
            return redirect()->route('services.create')
                             ->with('error', 'Create at least one services to proceed');
        }
        return $next($request);
    }
}
