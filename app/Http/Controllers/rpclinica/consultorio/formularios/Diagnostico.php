<?php


namespace App\Http\Controllers\rpclinica\consultorio_formularios\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Oft_documento;
use App\Model\rpclinica\Oft_refracao;
use App\Model\rpclinica\Oft_tonometria_aplanacao;  
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Diagnostico extends Controller
{
 
    public function store(Request $request, Agendamento $agendamento)
    {

        try {

            $validator = Validator::make($request->all(), [ 
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional', 
                'dt_exame' => 'required|date',
                'dt_liberacao' => 'nullable|date',
                'dp' => 'nullable|string', 
                'comentario' => 'nullable|string',
                'od_dc_dinamica' => 'nullable|string',
                'od_dc_estatica' => 'nullable|string',
                'od_de_dinamica' => 'nullable|string',
                'od_de_estatica' => 'nullable|string',
                'od_eixo_dinamica' => 'nullable|string',
                'od_eixo_estatica' => 'nullable|string',
                'od_reflexo_dinamica' => 'nullable|string',
                'od_reflexo_estatica' => 'nullable|string',
                'oe_dc_estatica' => 'nullable|string',
                'oe_de_dinamica' => 'nullable|string',
                'oe_de_estatica' => 'nullable|string',
                'oe_eixo_dinamica' => 'nullable|string',
                'oe_eixo_estatica' => 'nullable|string',
                'oe_reflexo_dinamica' => 'nullable|string',
                'oe_reflexo_estatica' => 'nullable|string',
            ]);
 
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
           $dados = $validator->validated();
         
         

            return response()->json(['request' => $dados]);

        }
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }


    }
  
    public function modal(Oft_documento $formulario)
    {
        return  view('rpclinica.consulta_formularios.formularios.diagnostico.modal',['retorno'=>null]);
    }
 
 

    public function delete(Oft_documento $formulario)
    {
        try {
            $formulario->delete();
        }
        catch (Exception $e) {
            abort(500);
        }
    }

}
