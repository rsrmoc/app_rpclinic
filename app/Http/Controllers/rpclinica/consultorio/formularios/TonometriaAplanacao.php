<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Oft_tonometria_aplanacao;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TonometriaAplanacao extends Controller
{

    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'dt_exame' => 'required|date',
                'obs' => 'nullable|string',
                'pressao_od' => 'nullable|string',
                'pressao_oe' => 'nullable|string',
                'cd_equipamento' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = $validator->validated();

            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            $dados['cd_usuario_exame'] = $request->user()->cd_usuario;
            $dados['dt_cad_exame'] = date('Y-m-d H:i:s');

            $query = [
                'cd_agendamento' =>  $dados['cd_agendamento']
            ];

            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_tonometria_aplanacao::updateOrCreate($query, $dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });
 

            return response()->json(['request' => $dados]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function storeImg(Request $request, Agendamento $agendamento)
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
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function modal(Request $request,Paciente $paciente)
    {
        $historico = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
        ->join('oft_tonometria_aplanacao','oft_tonometria_aplanacao.cd_agendamento','agendamento.cd_agendamento')
        ->join('equipamentos', 'equipamentos.cd_equipamento', 'oft_tonometria_aplanacao.cd_equipamento') 
        ->leftJoin('usuarios', 'oft_tonometria_aplanacao.cd_usuario_exame', 'usuarios.cd_usuario');
        if($request['cd_formulario']){
            $historico = $historico->where('cd_tonometria_aplanacao',$request['cd_formulario']);
        } 
        $historico = $historico->orderBy('dt_agenda','desc')->get();

        return  view('rpclinica.consultorio.formularios.oftalmologia.tonometria_aplanacao.modal', ['historico' => $historico]);
    }



    public function delete(Request $request, $cd_agendamento)
    {
        try { 

            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_tonometria_aplanacao::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_tonometria_aplanacao',
                    'registro_id' => $cd_agendamento,
                    'agendamento_id' => $Codigo,
                    'dados' => null,
                    'created_at'=> date('Y-m-d H:i'),
                    'updated_at'=> date('Y-m-d H:i'),
                ]);
            });

        } catch (Exception $e) {
            abort(500);
        }
    }
}
