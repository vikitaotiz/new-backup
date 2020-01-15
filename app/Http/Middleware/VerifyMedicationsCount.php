<?php

namespace App\Http\Middleware;

use Closure;
use App\Medication;

class VerifyMedicationsCount
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
        if(Medication::all()->count() === 0) {
            
            return redirect()->route('medications.create')
                             ->with('error', 'Create at least one medication to proceed');
        }
        return $next($request);
    }
}
