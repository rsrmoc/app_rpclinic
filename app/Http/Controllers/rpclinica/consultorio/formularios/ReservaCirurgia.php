<?php


namespace App\Http\Controllers\rpclinica\consultorio\formularios;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Oft_formularios_imagens;
use Exception;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Oft_reserva_cirurgia;
use App\Model\rpclinica\Oft_reserva_cirurgia_opme;
use App\Model\Support\Log;

class ReservaCirurgia extends Controller
{
    public function store(Request $request, Agendamento $agendamento)
    {
        try {
            $validator = Validator::make($request->all(), [
                'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
                'cd_cirurgiao' => 'nullable|string',
                'cd_cirurgia' => 'nullable|string',
                'comentarios' => 'nullable|string',
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
            $opme = $request->input('opme');

            $refra = DB::transaction(function () use ($query,$dados,$request){
                $tabela = Oft_reserva_cirurgia::updateOrCreate($query, $dados); 
                $usuario_logado = $request->user(); 
               $tabela ->criarLogCadastro($usuario_logado,'agendamento','pep',$dados['cd_agendamento']); 
                return $tabela;
            });
  
            Oft_reserva_cirurgia_opme::where('cd_reserva_cirurgia', '=', $refra->cd_reserva_cirurgia)->delete();

            if ($opme) {
                foreach ($opme as $item) {
                    Oft_reserva_cirurgia_opme::create(['cd_produto' => $item, 'cd_reserva_cirurgia' => $refra->cd_reserva_cirurgia]);
                }
            }

            return response()->json(['request' => $request->toArray()]);
        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    public function modal(Request $request, Paciente $paciente)
    {
 

            $historico = Oft_reserva_cirurgia::with('profissional','cirurgiao','cirurgia','opme.produtos')
            ->join('agendamento', 'oft_reserva_cirurgia.cd_agendamento', 'agendamento.cd_agendamento')
            ->selectRaw("oft_reserva_cirurgia.*,agendamento.*,oft_reserva_cirurgia.created_at created_data ");
            if($request['cd_formulario']){
                $historico = $historico->where('cd_reserva_cirurgia',$request['cd_formulario']);
            } 
            $historico = $historico->where('cd_paciente', $paciente->cd_paciente)
            ->orderBy('dt_agenda', 'desc')->get();

        return  view('rpclinica.consultorio.formularios.oftalmologia.reserva_cirurgia.modal', ['historico' => $historico]);
    }

    public function delete(Request $request, $cd_agendamento)
    {
        try { 
            
            DB::transaction(function () use ($cd_agendamento,$request){
                Oft_reserva_cirurgia_opme::where('cd_reserva_cirurgia',$cd_agendamento)->delete();
                $tabela = Oft_reserva_cirurgia::find($cd_agendamento);
                $Codigo =$tabela->cd_agendamento;
                $tabela->delete();
                $usuario_logado = $request->user();    
                Log::insert([
                    'usuario_id' => $usuario_logado->cd_usuario,
                    'usuario_type' => get_class($usuario_logado),
                    'descricao' => 'Exclusao',
                    'modulo' => 'agendamento',
                    'rotina' => 'pep',
                    'registro_type' => 'App\Model\rpclinica\Oft_reserva_cirurgia',
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
