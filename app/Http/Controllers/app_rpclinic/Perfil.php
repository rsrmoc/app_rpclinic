<?php


namespace App\Http\Controllers\app_rpclinic;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Usuario;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Perfil extends Controller
{

    public function index(Request $request)
    {
        return view('app_rpclinic.perfil.create');
    }

    public function saveProfile(Request $request) {

        $validated = $request->validate([
            'nm_header_doc' => 'nullable|string',
            'espec_header_doc' => 'nullable|string',
            'conselho_header_doc' => 'nullable|string',
            'email' => 'nullable|email',
            'celular' => 'nullable|string',
        ]);

        try {
          
            Usuario::whereRaw("cd_usuario='".auth()->guard('rpclinica')->user()->cd_usuario."'")
            ->update([
                'nm_usuario'=>$request['nm_header_doc'],
                'email_contato'=>$request['email'],
                'nm_celular'=>$request['celular'], 
                'conselho_header_doc'=> $request['conselho_header_doc'], 
                'espec_header_doc'=> $request['espec_header_doc'], 
            ]);
            if(auth()->guard('rpclinica')->user()->cd_profissional){ 
                    Profissional::whereRaw("cd_profissional=".auth()->guard('rpclinica')->user()->cd_profissional)
                    ->update([
                        'nm_profissional'=>$request['nm_header_doc'],
                        'email'=>$request['email'],
                        'sms'=>$request['celular'],  
                        'whatsapp'=>$request['celular'],   
    
                    ]);
                
            }
 
            
            return response()->json(['message' => 'Perfil atualizado!']);
        }
        catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

}
