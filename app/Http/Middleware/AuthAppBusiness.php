<?php

namespace App\Http\Middleware;

use App\Model\rpclinica\AppTelas;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthAppBusiness
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
       
        // Ignora validação se já está em app.login ou app.permission
        if ($request->routeIs('app.login') || $request->routeIs('app.permission') || $request->routeIs('app.not-found')) {
            return $next($request);
        }
      
        if (!Cookie::has('business') || !Auth::guard('rpclinica')->check() ) {
            return redirect()->route('app.login');
        }
      
        if($request->user('rpclinica')->app_name=='adonhiran'){
        
            // Ignora todas as rotas de API (app.api.*)
            if(!str_starts_with(request()->route()->getName(), 'app.api.')) {
                ;
                if(AppTelas::where('app_adonhiran','1')->where('cd_app_tela',request()->route()->getName())->count() == 0){
                    return redirect()->route('app.permission', ['path' => request()->path()]);
                }
            }
            
        }

        if($request->user('rpclinica')->app_name=='consultorio'){
        
            // Ignora todas as rotas de API (app.api.*)
            if(!str_starts_with(request()->route()->getName(), 'app.api.')) {
                if(AppTelas::where('app_consultorio','1')->where('cd_app_tela',request()->route()->getName())->count() == 0){
                    return redirect()->route('app.permission', ['path' => request()->path()]);
                }
            }
            
        }

        return $next($request);
    }
}
