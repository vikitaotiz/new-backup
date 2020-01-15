<?php

namespace App\Http\Middleware;

use Closure;
use App\Charge;

class VerifyChargesCount
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
        if (Charge::all()->count() === 0) {
            
            return redirect()->route('charges.create')
                             ->with('error', 'Create at least one charge to proceed');
        }
        return $next($request);
    }
}
