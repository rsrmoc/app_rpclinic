<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Model\rpclinica\Origem_paciente;
use App\Model\rpclinica\Profissional_externo;
use Exception;
use Illuminate\Support\Facades\Validator;

class ProfissionalExterno extends Controller
{
 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string',  
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => [$validator->errors()->first()]], 500); 
        }

        try {
           $item = Profissional_externo::create([
                'nm_profissional_externo' => $request->post('nome'),  
                'conselho' => $request->post('conselho'),  
                'cd_usuario' => $request->user()->cd_usuario
            ]);

           $retorno = Profissional_externo::orderBy('nm_profissional_externo')->get(); 
           return response()->json(['retorno' => $retorno,'item'=> $item]);
        }
        catch (Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]], 500);
        }
    }

 
}
