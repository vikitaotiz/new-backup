<?php

namespace App\Http\Middleware;

use Closure;
use App\CompanyDetail;

class VerifyCompanyCount
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
        if (CompanyDetail::all()->count() == 0) {

            return redirect()->route('settings.index')
                             ->with('error', 'Create at least one company to proceed.');
        }
        return $next($request);
    }
}
