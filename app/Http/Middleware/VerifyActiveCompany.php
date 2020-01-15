<?php

namespace App\Http\Middleware;

use Closure;

use App\CompanyDetail;

class VerifyActiveCompany
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
        $companies = CompanyDetail::all();

        foreach ($companies as $company) {
            $company->where('status', 1)->first();
            if (!$company) {
                return redirect()->route('settings.index')->with('error', 'Kindly make one company active');
            } else {
                return $next($request);
            }
        }
        return $next($request);
    }
}
