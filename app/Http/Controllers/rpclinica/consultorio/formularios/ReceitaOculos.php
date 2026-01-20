<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Oft_documento;
use App\Model\rpclinica\Oft_receita_oculos;
use App\Model\rpclinica\Oft_refracao;
use App\Model\rpclinica\Oft_tonometria_aplanacao;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReceitaOculos extends Controller
{
    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'tipo_lente' => 'nullable|string',
                'orientacao' => 'nullable|string',
                'longe_od_de' => 'nullable|string',
                'longe_od_dc' => 'nullable|string',
                'longe_od_eixo' => 'nullable|string',
                'longe_od_add' => 'nullable|string',
                'longe_oe_de' => 'nullable|string',
                'longe_oe_dc' => 'nullable|string',
                'longe_oe_eixo' => 'nullable|string',
                'longe_oe_add' => 'nullable|string',
                'perto_od_de' => 'nullable|string',
                'perto_od_dc' => 'nullable|string',
                'perto_od_eixo' => 'nullable|string',
                'perto_od_add' => 'nullable|string',
                'perto_oe_de' => 'nullable|string',
                'perto_oe_dc' => 'nullable|string',
                'perto_oe_eixo' => 'nullable|string',
                'perto_oe_add' => 'nullable|string',
                'inter_od_de' => 'nullable|string',
                'inter_od_dc' => 'nullable|string',
                'inter_od_eixo' => 'nullable|string',
                'inter_oe_de' => 'nullable|string',
                'inter_oe_dc' => 'nullable|string',
                'inter_oe_eixo' => 'nullable|string',
                'obs' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
            $dados = $validator->validated();


            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            $dados['cd_usuario'] = $request->user()->cd_usuario;

            $query = [
                'cd_agendamento' =>  $dados['cd_agendamento']
            ];
 
            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_receita_oculos::updateOrCreate($query, $dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });

            return response()->json(['request' => $refra]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function modal(Request $request, Paciente $paciente)
    {
        $historico = Agendamento::with(['profissional'])->where('cd_paciente', $paciente->cd_paciente)
            ->join('oft_receita_oculos', 'oft_receita_oculos.cd_agendamento', 'agendamento.cd_agendamento')
            ->leftJoin('usuarios', 'oft_receita_oculos.cd_usuario', 'usuarios.cd_usuario');
            if($request['cd_formulario']){
                $historico = $historico->where('cd_receita_oculo',$request['cd_formulario']);
            } 
            $historico = $historico->orderBy('dt_agenda','desc')->get();

        return  view('rpclinica.consultorio.formularios.oftalmologia.receita_oculos.modal', ['historico' => $historico]);
    }

    public function delete(Request $request, $cd_agendamento)
    {
        try { 

            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_receita_oculos::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_receita_oculos',
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
