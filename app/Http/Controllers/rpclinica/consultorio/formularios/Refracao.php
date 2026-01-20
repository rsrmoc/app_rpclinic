<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Oft_refracao;
use App\Model\rpclinica\Oft_tonometria_aplanacao;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Refracao extends Controller
{

    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'dt_exame' => 'required|date',
                'dt_liberacao' => 'nullable|date',
                'rd_receita' => 'nullable|string',
                'dp' => 'nullable|string',
                'ard_od_de' => 'nullable|string',
                'ard_od_dc' => 'nullable|string',
                'ard_od_eixo' => 'nullable|string',
                'ard_od_av' => 'nullable|string',
                'ard_od_add' => 'nullable|string',
                'ard_od_add_av' => 'nullable|string',
                'ard_oe_de' => 'nullable|string',
                'ard_oe_dc' => 'nullable|string',
                'ard_oe_eixo' => 'nullable|string',
                'ard_oe_av' => 'nullable|string',
                'ard_oe_add' => 'nullable|string',
                'ard_oe_add_av' => 'nullable|string',
                're_receita' => 'nullable|string',
                'are_od_de' => 'nullable|string',
                'are_od_dc' => 'nullable|string',
                'are_od_eixo' => 'nullable|string',
                'are_od_av' => 'nullable|string',
                'are_oe_de' => 'nullable|string',
                'are_oe_dc' => 'nullable|string',
                'are_oe_eixo' => 'nullable|string',
                'are_oe_av' => 'nullable|string',
                'obs' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = $validator->validated();

            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            $dados['cd_usuario_exame'] = $request->user()->cd_usuario;
            $dados['dt_cad_exame'] = date('Y-m-d H:i:s');

            if ($request->input('rd_receita') == 'on') {
                $dados['rd_receita'] = "1";
            } else {
                $dados['rd_receita'] = "0";
            }

            if ($request->input('re_receita') == 'on') {
                $dados['re_receita'] = "1";
            } else {
                $dados['re_receita'] = "0";
            }

            // return response()->json(['request' => $dados]);

            $query = [
                'cd_agendamento' =>  $dados['cd_agendamento']
            ];
  
            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_refracao::updateOrCreate($query, $dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });

            return response()->json(['request' => $refra]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function modal(Request $request,Paciente $paciente)
    {
        $historico = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
            ->join('oft_refracao', 'oft_refracao.cd_agendamento', 'agendamento.cd_agendamento')
            ->leftJoin('usuarios', 'oft_refracao.cd_usuario_exame', 'usuarios.cd_usuario');
            if($request['cd_formulario']){
                $historico = $historico->where('cd_refracao',$request['cd_formulario']);
            } 
            $historico = $historico->orderBy('dt_agenda','desc')->get();
       

        return view('rpclinica.consultorio.formularios.oftalmologia.refracao.modal', ['historico' => $historico]);
    }



    public function delete(Request $request, $cd_agendamento)
    {
        try { 

            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_refracao::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_refracao',
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
