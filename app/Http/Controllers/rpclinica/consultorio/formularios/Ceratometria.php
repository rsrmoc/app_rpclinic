<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Oft_auto_refracao;
use App\Model\rpclinica\Oft_ceratometria;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Ceratometria extends Controller
{

    public function store(Request $request, Agendamento $agendamento)
    {

        try {

            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'dt_exame' => 'required|date',
                'dt_liberacao' => 'nullable|date',
                'cd_usuario_liberacao' => 'nullable|string',
                'dt_cad_liberacao' => 'nullable|string',
                'od_curva1_ceratometria' => 'nullable|string',
                'od_curva1_milimetros' => 'nullable|string',
                'od_eixo1_ceratometria' => 'nullable|string',
                'od_curva2_ceratometria' => 'nullable|string',
                'od_curva2_milimetros' => 'nullable|string',
                'od_eixo2_ceratometria' => 'nullable|string',
                'od_media_ceratometria' => 'nullable|string',
                'od_media_milimetros' => 'nullable|string',
                'od_cilindro_neg' => 'nullable|string',
                'od_eixo_neg' => 'nullable|string',
                'od_cilindro_pos' => 'nullable|string',
                'od_eixo_pos' => 'nullable|string',
                'oe_curva1_ceratometria' => 'nullable|string',
                'oe_curva1_milimetros' => 'nullable|string',
                'oe_eixo1_ceratometria' => 'nullable|string',
                'oe_curva2_ceratometria' => 'nullable|string',
                'oe_curva2_milimetros' => 'nullable|string',
                'oe_eixo2_ceratometria' => 'nullable|string',
                'oe_media_ceratometria' => 'nullable|string',
                'oe_media_milimetros' => 'nullable|string',
                'oe_cilindro_neg' => 'nullable|string',
                'oe_eixo_neg' => 'nullable|string',
                'oe_cilindro_pos' => 'nullable|string',
                'oe_eixo_pos' => 'nullable|string',
                'obs' => 'nullable|string',
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
                $tabela = Oft_ceratometria::updateOrCreate($query, $dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });
            $retorno = Oft_ceratometria::find($refra->cd_ceratometria);
            return response()->json(['request' => $refra,'retorno'=>$retorno]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function modal(Request $request, Paciente $paciente)
    {
        $historico = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
        ->join('oft_ceratometria','oft_ceratometria.cd_agendamento','agendamento.cd_agendamento') 
        ->leftJoin('usuarios', 'oft_ceratometria.cd_usuario_exame', 'usuarios.cd_usuario');
        if($request['cd_formulario']){
           $historico = $historico->where('cd_ceratometria',$request['cd_formulario']);
        }    
        $historico = $historico->orderBy('dt_agenda', 'desc')->get();

        return  view('rpclinica.consultorio.formularios.oftalmologia.ceratometria.modal', ['historico' => $historico]);
    }



    public function delete(Request $request, $cd_agendamento)
    {
        try { 

            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_ceratometria::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_ceratometria',
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
