<?php

namespace App\Http\Middleware;

use App\Model\rpclinica\Rota;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

class PermissoesUsuario
{
 
  
    public function handle($request, Closure $next)
    {  
        //dd(Route::currentRouteName(),$request->user()->email,Session::get('setar_perfil'),Session::get('perfil'));
        //session()->forget('setar_perfil');
        // Carrega as Permissões na Session
        //HelperSessionUsusario($request->user()->email);

        if(!Route::currentRouteName()){ return true; }

        if(!Session::get('setar_perfil'))
            HelperSessionUsusario($request->user()->email);

        if (!$this->verificaRota()) {
            
            return redirect()->route('sem.permissao');
        }

        return $next($request);
    }

    public function userPermissoes(): array {

        $rotas = array_column(Auth::user()->perfil->rotas->toArray(), "nm_rota");

        return $rotas;
    }
    
    public function userPermissoesUsuario(): bool {
         
        if(in_array(Route::currentRouteName(), Session::get('perfil')))
            return true;
        else
            return false;
    }
    

    public function verificaRota(): bool {
        //Criação das Rotas
 
        $rota=Route::currentRouteName(); 
        if($rota){
            
            Rota::firstOrCreate(
                ['rota' => $rota],
                ['rota' => $rota]
            );
 
        }
         
            
        if (Auth::user()->admin == "S") return true;

        $rotasOk = [
            'sem.permissao',
            'inicio',
            'rpclinica.usuario.alterar', 
            'tutorial',   
        ];
        $rotasOk = Session::get('nao_controla_rota');
        if(in_array(Route::currentRouteName(), $rotasOk)){
            return true;
        }

        //C:\wamp64\www\rpsys\RPclinicOftalmo\vendor\laravel\ui\auth-backend\AuthenticatesUsers.php
        if($this->userPermissoesUsuario()){
            return true;
        }
 
        return false;
    }
}
