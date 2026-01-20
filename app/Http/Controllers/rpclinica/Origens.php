<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use App\Model\rpclinica\Origem_paciente; 
use Exception;
use Illuminate\Support\Facades\Validator;

class Origens extends Controller
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
           $item = Origem_paciente::create([
                'nm_origem' => $request->post('nome'),  
                'cd_usuario' => $request->user()->cd_usuario
            ]);

           $retorno = Origem_paciente::orderBy('nm_origem')->get(); 
           return response()->json(['retorno' => $retorno,'item'=> $item]);
        }
        catch (Exception $e) {
            return response()->json(['errors' => [$e->getMessage()]], 500);
        }
    }

 
}
