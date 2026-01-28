<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class GuestAppBusiness
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
       
        if (Cookie::has('business') && Auth::guard('rpclinica')->check() ) {
            return redirect()->route('app.inicial');
        }
        

        

        return $next($request);
    }
}
