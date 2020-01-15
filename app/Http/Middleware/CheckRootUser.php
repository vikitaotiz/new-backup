<?php

namespace App\Http\Middleware;

use Closure;

class CheckRootUser
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
        if(auth()->user()->role_id == 5){
            return redirect()->back()->with('error', 'Permission denied!');
        }  else {
            return $next($request);
        }
    }
}
