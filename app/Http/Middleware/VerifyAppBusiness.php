<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class VerifyAppBusiness
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
        if (Cookie::has('business')) {
            
            $database = json_decode(Cookie::get('business'));
            
            config(['database.default' => 'mysql']);
            config(['database.connections.mysql.host' => $database->host]);
            config(['database.connections.mysql.username' => $database->username]);
            config(['database.connections.mysql.password' => $database->password]);
            config(['database.connections.mysql.database' => $database->database]);
            
        }

        return $next($request);
    }
}
