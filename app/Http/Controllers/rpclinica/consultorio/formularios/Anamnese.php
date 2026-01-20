<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Classificacao;
use App\Model\rpclinica\Oft_anamnese;
use App\Model\rpclinica\Oft_auto_refracao;
use App\Model\rpclinica\Oft_ceratometria;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\Oft_tonometria_pneumatica;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Anamnese extends Controller
{
    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'dt_anamnese' => 'required|date',
                'alergias' => 'nullable|string',
                'conduta' => 'nullable|string',
                'historia' => 'nullable|string',
                'medicamentos' => 'nullable|string',
                'motivo' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
            $dados = $validator->validated();

            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            $dados['cd_usuario_anamnese'] = $request->user()->cd_usuario;
            $dados['dt_cad_anamnese'] = date('Y-m-d H:i:s');

            $query = [
                'cd_agendamento' =>  $dados['cd_agendamento']
            ];

            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_anamnese::updateOrCreate($query, $dados); 
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
        ->join('oft_anamnese','oft_anamnese.cd_agendamento','agendamento.cd_agendamento')
        ->leftJoin('usuarios', 'oft_anamnese.cd_usuario_anamnese', 'usuarios.cd_usuario');
        if($request['cd_formulario']){
           $historico = $historico->where('cd_anamnese',$request['cd_formulario']);
        }    
        $historico = $historico->orderBy('dt_agenda', 'desc')->get();

        return  view('rpclinica.consultorio.formularios.oftalmologia.anamnese.modal', ['historico' => $historico]);
    }

    public function delete(Request $request, $cd_agendamento)
    {
        try { 
            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_anamnese::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_anamnese',
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
