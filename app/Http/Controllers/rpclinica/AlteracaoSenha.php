<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AlteracaoSenha extends Controller
{
    public function edit() {
        return view('rpclinica.usuario.alterar_senha');
    }

    public function update(Request $request) {
         
        try {

            $request->validate([
                'atual' => 'required|string|min:3|password',
                'nova_senha' => 'required|string|min:3|confirmed',
            ]);
 
            $data = ['password' => Hash::make($request->nova_senha)];

            if (empty(Auth::user()->primeiro_acesso)) {
                $data['primeiro_acesso'] = now();
            }
           
            Auth::user()->update($data);

            return redirect()->route('inicio')->with('success', 'Senha alterada com sucesso!');

        }
        catch(Exception $e) {
            return back()->withErrors(['error' => 'Houve um erro ao alterar sua senha!'])->withInput();
        }
    }
}
