<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class PrimeiroAcesso
{
   
    public function handle($request, Closure $next)
    { 
         
        if ( empty(Auth::user()->primeiro_acesso) && !in_array(Route::currentRouteName(), ['rpclinica.usuario.alterar','rpclinica.usuario.alterar-acao']) ) {
             
            return redirect()->route('rpclinica.usuario.alterar');
            
        }
         
        return $next($request);
    }
}
