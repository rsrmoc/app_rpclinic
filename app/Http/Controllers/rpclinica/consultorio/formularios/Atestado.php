<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Oft_documento;
use App\Model\rpclinica\Oft_refracao;
use App\Model\rpclinica\Oft_tonometria_aplanacao;
use App\Model\rpclinica\Paciente;
use App\Model\Support\Log;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Atestado extends Controller
{

    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'descricao' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
            $dados = $validator->validated();

            $dados['cd_agendamento'] = $agendamento['cd_agendamento'];
            // $dados['cd_usuario'] = $request->user()->cd_usuario;
            $dados['cd_formulario'] = 'ATESTADOS';

            $query = [
                'cd_agendamento' =>  $dados['cd_agendamento'],
                'cd_formulario' =>  'ATESTADOS'
            ];

            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_documento::updateOrCreate($query, $dados); 
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
            ->join('oft_documentos', 'oft_documentos.cd_agendamento', 'agendamento.cd_agendamento')
            ->leftJoin('usuarios', 'oft_documentos.cd_usuario', 'usuarios.cd_usuario');
            if($request['cd_formulario']){
                $historico = $historico->where('oft_documentos.cd_documento',$request['cd_formulario']);
            } 
            $historico = $historico->where('oft_documentos.cd_formulario', 'ATESTADOS')
            ->selectRaw("oft_documentos.*,agendamento.*,usuarios.*,oft_documentos.created_at created_data ")
            ->orderBy('dt_agenda', 'desc')->get();  
            dd( $historico->toArray());
        return  view('rpclinica.consultorio.formularios.oftalmologia.atestado.modal', ['historico' => $historico]);
    }


    public function delete(Request $request, $cd_agendamento)
    {
        try { 

            DB::transaction(function () use ($cd_agendamento,$request){
                $tabela = Oft_documento::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_documento',
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
