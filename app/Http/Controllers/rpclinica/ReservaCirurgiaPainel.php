<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda; 
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEscalaHorario;
use App\Model\rpclinica\AgendaExames;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\AgendamentoGuia;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\AtendimentoItens;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional; 
use App\Model\rpclinica\TipoAtendimento;  
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Oft_reserva_cirurgia;
use App\Model\rpclinica\Oft_reserva_cirurgia_hist;
use App\Model\rpclinica\Produto;
use App\Model\rpclinica\Usuario;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\DB;
 

class ReservaCirurgiaPainel extends Controller
{
    public function index(Request $request)
    {  
 

 
        $parametros['cirurgia'] = Exame::where('tp_item','CI')->where('sn_ativo','S')->orderBy('nm_exame')->get();
        $parametros['profissional'] = Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get();
        $parametros['opme'] = Produto::where('sn_ativo','S')->where('sn_opme','S')->orderBy('nm_produto')->get();
        $parametros['convenio'] = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();

        return view('rpclinica.reserva_cirurgia.painel', compact('parametros','request'));
    }

    public function jsonPainel(Request $request)
    {  

        $request['query'] = Oft_reserva_cirurgia::ReservasCrirugia($request)
        ->selectRaw("oft_reserva_cirurgia.*, DATE_FORMAT(created_at, '%d/%m/%Y') created_data,
        case when sn_negociado='S' then 'SIM' when sn_negociado='N' then 'NÃO' else ' NÃO ' end negociado
        ")->get();
        return $request->toArray();
 
    }

    public function addHist(Request $request) {
        try {

            $validator = Validator::make($request->all(), [
                'cd_reserva_cirurgia' => 'required|integer|exists:oft_reserva_cirurgia,cd_reserva_cirurgia',
                'ds_historico' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = $validator->validated();
            $dados['cd_usuario'] = $request->user()->cd_usuario;

            $return = Oft_reserva_cirurgia_hist::create($dados);

            $hist = Oft_reserva_cirurgia_hist::where('cd_reserva_cirurgia', '=', $request['cd_reserva_cirurgia'])
            ->join('usuarios', 'usuarios.cd_usuario', 'oft_reserva_cirurgia_hist.cd_usuario')
            ->selectRaw("oft_reserva_cirurgia_hist.*,date_format(oft_reserva_cirurgia_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
            ->selectRaw("usuarios.nm_usuario")
            ->get();

            return response()->json(['request' => $return,'hist' => $hist]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
 
    public function getHist($cd_reserva_cirurgia) {
        $hist = Oft_reserva_cirurgia_hist::where('cd_reserva_cirurgia', '=', $cd_reserva_cirurgia)
        ->join('usuarios', 'usuarios.cd_usuario', 'oft_reserva_cirurgia_hist.cd_usuario')
        ->selectRaw("oft_reserva_cirurgia_hist.*,date_format(oft_reserva_cirurgia_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
        ->selectRaw("usuarios.nm_usuario")
        ->get();

        return response()->json($hist);
    }

    public function addForm(Request $request) {
        try {
            
         
            $validator = Validator::make($request->all(), [
                'cd_reserva' => 'required|integer|exists:oft_reserva_cirurgia,cd_reserva_cirurgia' 
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
             
            $dados['situacao'] = $request->situacao;
            $dados['cd_convenio'] = $request->cd_convenio;
            $dados['valor'] = ($request->valor) ? trocar_ponto_virgula($request->valor) : null;
            $dados['sn_negociado'] = $request->sn_negociado;
            $dados['agendamento_reserva'] = $request->agendamento;
            $dados['dt_solicitacao'] = $request->dt_solicitacao;
            $dados['dt_autorizacao'] = $request->dt_autorizacao;
            $dados['guia'] = $request->guia;
            $dados['cd_opme'] = $request->opme;
            $dados['comentarios_form'] = $request->comentarios_form;
            $dados['cd_usuario_form'] = $request->user()->cd_usuario;

            $return = Oft_reserva_cirurgia::where('cd_reserva_cirurgia',$request->cd_reserva)->update($dados); 
         

            return response()->json(['request' => $request->toArray()]);

        } catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
} 
