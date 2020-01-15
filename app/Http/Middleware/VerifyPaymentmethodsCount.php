<?php

namespace App\Http\Middleware;

use Closure;
use App\Paymentmethod;

class VerifyPaymentmethodsCount
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
        if (Paymentmethod::all()->count() == 0) {
            
            return redirect()->route('paymentmethods.create')
                             ->with('error', 'Create at least one payment method to proceed.');
        }
        return $next($request);
    }
}
