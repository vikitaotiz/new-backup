<?php

namespace App\Http\Middleware;

use Closure;
use App\Currency;

class VerifyCurrenciesCount
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
        if(Currency::all()->count() === 0) {
            
            return redirect()->route('currencies.create')
                             ->with('error', 'Create at least one currency to proceed');
        }
        return $next($request);
    }
}
