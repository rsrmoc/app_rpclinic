<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\AgendaConvenios;
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEspecialidades;
use App\Model\rpclinica\AgendaIntervalo;
use App\Model\rpclinica\AgendaLocais;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\AgendaProcedimentos;
use App\Model\rpclinica\AgendaProfissionais;
use App\Model\rpclinica\BloqueioAgendamento;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\WebhookMessage;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\TipoAtendimentoProc;
use App\Model\rpclinica\TipoAtendimento;


use App\Model\rpclinica\FaturamentoConta;
use App\Model\rpclinica\ProcedimentoConvenio;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Usuario;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Agendamento extends Controller
{

    
    public function index(Request $request)
    {
 
        $AgendaItens="";
        $nomesAgenda="";
        $Agendas="";
        if($request['param']=='S'){

            if(!$request['relacaoAgendas']){
                $itens = $request->user()->agendamento_agendas;
                $itens = explode(',',$itens);
                $request['relacaoAgendas']=$itens;
            }

            if($request['relacaoAgenda']){
                $request['relacaoAgendas']=explode(',',$request['relacaoAgenda']);
            }

            if (isset($request['relacaoAgendas'])) {
                foreach ($request['relacaoAgendas'] as $ix => $agenda) {
                    if ($ix == 0) {
                        $Agendas = $agenda;
                    } else {
                        $Agendas = $Agendas . ',' . $agenda;
                    }
                }
                if ($Agendas) {
                    Usuario::where('cd_usuario', $request->user()->cd_usuario)
                        ->update(['agendamento_agendas' => $Agendas, 'campos_intervalo' => $request['intervalo']]);
                }
                $AgendaItens = $Agendas;
            } else {
                $request['relacaoAgendas'] = array();
            }

        }else{

            $itens = $request->user()->agendamento_agendas;
            $itens = explode(',',$itens);
            $request['relacaoAgendas']=$itens;
            $request['intervalo']=($request->user()->campos_intervalo) ? $request->user()->campos_intervalo : '00:10';
            $AgendaItens = $request->user()->agendamento_agendas;
        }


        $resources = array();
        $businessHours = array();
        if(isset($request['relacaoAgendas'])){
            if(count($request['relacaoAgendas'])>0){

                $agenda = Agenda::with(['escalas'=> function($q){
                    $q->whereRaw("sn_ativo='S'");
                    $q->whereRaw("ifnull(escala_manual,'')=''");
                }])->whereRaw(" sn_ativo = 'S'")
                ->whereIn('cd_agenda',$request['relacaoAgendas'])
                ->get();
                $businessHours=null; $nomesAgenda=null;
                foreach($agenda as $idx => $row){
                    $Dia=null;
                    if($idx==0){  $nomesAgenda = 'Agenda(s): '. $row->nm_agenda; }else{ $nomesAgenda = $nomesAgenda.' , '.$row->nm_agenda; }
                    foreach($row->escalas as $escala){
                        $Dia=null;
                        $Dia=array( $escala->nr_dia );
                        $businessHours[] = array('dow'=>$Dia,'start' => $escala->hr_inicial,'end' => $escala->hr_final,'resources' => $escala->cd_agenda);
                    }

                }


                $request['intervalo']=null;
                $Intervalo=600;
                $resource=null;
                foreach($agenda as $row){
                    $resource=array(
                        'id' => $row->cd_agenda,
                        'title' => $row->nm_agenda,
                    );
                    $business =null;
                    foreach($row->escalas as $escala){
                        if($escala->intervalo<$Intervalo){
                            $Intervalo=$escala->intervalo;
                            $request['intervalo']=$escala->nm_intervalo;
                        }
                        $business[]=array(
                            'dow' => array($escala->nr_dia),
                            'start' => $escala->hr_inicial,
                            'end' => $escala->hr_final
                        );
                    }
                    $resource['businessHours']=$business;
                    $resources[] = $resource;
                }
                if($request['intervalo_campo']){
                    $request['intervalo']=$request['intervalo_campo'];
                }



            }
        }

        $Contas =ContaBancaria::whereRaw("sn_ativo='S'")->orderBy('nm_conta')->get();
        $Forma =FormaPagamento::whereRaw("sn_ativo='S'")->orderBy('nm_forma_pag')->get();
        $agendas = Agenda::orderBy("nm_agenda")->whereRaw("sn_ativo='S'")
        ->orderBy("nm_agenda")->get();
        $agendasSelect = Agenda::orderBy("nm_agenda")->whereIn('cd_agenda',$request['relacaoAgendas'])
        ->orderBy("nm_agenda")->get();
        $profissionais = Profissional::whereRaw("sn_ativo='S'")->orderBy("nm_profissional")->get();
        $especialidades = Especialidade::whereRaw("sn_ativo='S'")->orderBy("nm_especialidade")->get();
        $convenios = Convenio::whereRaw("sn_ativo='S'")->orderBy("nm_convenio")->get();
        $procedimentos = Procedimento::whereRaw("sn_ativo='S'")->orderBy("nm_proc")->get();
        $localAtendimentos = LocalAtendimento::whereRaw("sn_ativo='S'")->orderBy("nm_local")->get();
        $tipoAtendimentos =TipoAtendimento::whereRaw("sn_ativo='S'")->orderBy("nm_tipo_atendimento")->get();
        $procedimentosProfissional = Auth::user()->profissional?->procedimentos;
        $api_zap = WhastApi::find('WHAST');
        if(isset($api_zap)){
            $ApiZap=$api_zap->sn_whast;
        }else { $ApiZap='N'; }
        $intervalos = AgendaIntervalo::orderBy('mn_intervalo')->get();
        $empresa=Empresa::find(Auth::user()->cd_empresa);


        return view('rpclinica.agendamento.calendario', compact(
            'agendas',
            'profissionais',
            'especialidades',
            'procedimentos',
            'convenios',
            'localAtendimentos',
            'procedimentosProfissional',
            'ApiZap',
            'Contas',
            'Forma',
            'businessHours',
            'resources',
            'nomesAgenda',
            'request',
            'intervalos',
            'tipoAtendimentos',
            'agendasSelect',
            'AgendaItens',
            'empresa'
        ));
    }

    public function horarioAgendamento(Request $request)
    {

          $query['start'] = '10:00:00';
          $query['end'] = '13:00:00';
          $query['dow'] = array(0,1,2,3,5);
        return response()->json($query);
    }

    public function ShowAgendamento(Request $request)
    {
        if(empty($request['agendas'])){
            $request['agendas']=0;
        }

        $query = RpclinicaAgendamento::where('dt_agenda','>=',date('Y-m-d', $request['start']))
        ->leftJoin("tipo_atendimento","agendamento.tipo","tipo_atendimento.cd_tipo_atendimento")
        ->leftJoin("paciente","paciente.cd_paciente","agendamento.cd_paciente")
        ->leftJoin("convenio","convenio.cd_convenio","agendamento.cd_convenio")
        ->leftJoin("agendamento_situacao","agendamento_situacao.cd_situacao","agendamento.situacao")
        ->leftJoin("profissional","profissional.cd_profissional","agendamento.cd_profissional")
        ->where('dt_agenda','<=',date('Y-m-d', $request['end']))->whereRaw(" ifnull(cd_agenda,'')<>''")
        ->whereRaw("agendamento.cd_agenda in (".$request['agendas'].") ")
        ->whereRaw("agendamento.situacao <> 'livre'")
        ->selectRaw("'descricao' description,agendamento.cd_agendamento id,
        REPLACE(concat(dt_agenda,' ',hr_agenda),' ',' ')  start,nm_profissional,
        REPLACE(concat(dt_agenda,' ',hr_final),' ',' ') end, cd_agenda resourceId, ' ' title,
        concat(' | ',upper(nm_convenio),' | ',upper(nm_tipo_atendimento),' | ',upper(nm_paciente)) titulo ,  tipo_atendimento.cor className,agendamento_situacao.icone,nm_paciente,dt_nasc,agendamento.situacao,usuario_geracao, null rendering, null backgroundColor,'E' tipo, agendamento.obs, usuario_geracao ");

        $queryAll = AgendaEscala::where('dt_inicial','>=',date('Y-m-d', $request['start']))
        ->where('dt_inicial','<=',date('Y-m-d', $request['end']))
        ->whereRaw(" ifnull(escala_manual,'')='S'")
        ->whereRaw("cd_agenda in (".$request['agendas'].") ")
        ->unionAll($query)
        ->selectRaw("'descricao' description,cd_escala_agenda id,
        REPLACE(concat(dt_inicial,' ',hr_inicial),' ',' ')  start,'' nm_profissional,
        REPLACE(concat(dt_inicial,' ',hr_final),' ',' ') end, cd_agenda resourceId, ' ' title,
        concat(' Escala Manual ') titulo ,  null className,null icone,null nm_paciente, null dt_nasc,null situacao, cd_usuario usuario_geracao, 'background' rendering, '#30d7bb' backgroundColor,'M' tipo, '' obs, '' usuario_geracao ")
        ->get();

        return response()->json($queryAll);

    }

    public function getAgendamento(Request $request)
    {
        // Verifica se o cd_agendamento foi passado
        if (empty($request->query('cd_agendamento'))) {
            return response()->json(['error' => 'Agendamento ID is required'], 400);
        }

        // Consulta para pegar o agendamento específico pelo cd_agendamento
        $cd_agendamento = $request->query('cd_agendamento');
        $agendamento = RpclinicaAgendamento::where('cd_agendamento', $cd_agendamento)
            ->leftJoin("paciente", "paciente.cd_paciente", "agendamento.cd_paciente")
            ->select('paciente.cd_paciente')
            ->first();

        // Verifica se o agendamento foi encontrado
        if (!$agendamento) {
            return response()->json(['error' => 'Agendamento not found'], 404);
        }

        return response()->json(['cd_paciente' => $agendamento->cd_paciente]);
    }
       
    public function horarios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d',
            'agenda' => 'nullable|integer|exists:agenda,cd_agenda',
            'profissional' => 'nullable|integer|exists:profissional,cd_profissional',
            'especialidade' => 'nullable|integer|exists:especialidade,cd_especialidade',
            'situacao' => 'nullable|string|in:livre,agendado,confirmado,atendido,cancelado,aguardando'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $agendas = Agenda::where([
            ['data_inicial', '<=', $request->data],
            ['data_final', '>=', $request->data],
        ]);

        if ($request->has('agenda') && !empty($request->agenda)) {
            $agendas->where('cd_agenda', $request->agenda);
        }

        if ($request->has('profissional') && !empty($request->profissional)) {
            $agendas->where('cd_profissional', $request->profissional);
        }

        if ($request->has('especialidade') && !empty($request->especialidade)) {
            $agendas->where('cd_especialidade', $request->especialidade);
        }

        $horarios = [];

        foreach ($agendas->get() as $agenda) {
            $dayOfWeek = date('w', strtotime($request->data));

            if (($dayOfWeek == 0 && !$agenda->domingo) ||
                ($dayOfWeek == 1 && !$agenda->segunda) ||
                ($dayOfWeek == 2 && !$agenda->terca) ||
                ($dayOfWeek == 3 && !$agenda->quarta) ||
                ($dayOfWeek == 4 && !$agenda->quinta) ||
                ($dayOfWeek == 5 && !$agenda->sexta) ||
                ($dayOfWeek == 6 && !$agenda->sabado)
            ) {
                continue;
            }

            $time1 = strtotime($agenda->hr_inicial);
            $time2 = strtotime($agenda->hr_final);
            $interval = $agenda->intervalo;

            $loopSections = ($time2 - $time1) / ($interval * 60);

            for ($i = 0; $i <= $loopSections; $i++) {
                $horario = [
                    "agenda" => $agenda,
                    "profissional" => $agenda->profissional,
                    "especialidade" => $agenda->especialidade,
                    "procedimento" => $agenda->procedimento,
                    "local" => $agenda->local,
                    "data" => $request->data
                ];
                $horario["horario"] = date('H:i:s', $time1 + (($interval * 60) * $i));

                $agendamendo = RpclinicaAgendamento::firstWhere([
                    'data_horario' => $horario['data'] . ' ' . $horario['horario'],
                    'cd_agenda' => $agenda->cd_agenda
                ]);

                if ($agendamendo) {
                    $horario['profissional'] = $agendamendo->profissional;
                    $horario['especialidade'] = $agendamendo->especialidade;
                    $horario['procedimento'] = $agendamendo->procedimento;
                    $horario['local'] = $agendamendo->local;
                    $horario['agendamento'] = $agendamendo->toArray();
                    $horario['agendamento']['valor'] = formatCurrencyForFront($agendamendo->valor);
                    $horario['agendamento_procedimento'] = Procedimento::find($agendamendo->cd_procedimento);
                    $horario['agendamento_convenio'] = Convenio::find($agendamendo->cd_convenio);
                    $horario["situacao"] = ucfirst($agendamendo->situacao);
                    $horario["paciente"] = Paciente::with('convenio')->find($agendamendo->cd_paciente);

                    if ($horario["paciente"]) {
                        $horario['historico'] = RpclinicaAgendamento::with('agenda', 'profissional', 'paciente', 'especialidade', 'procedimento')
                            ->where('cd_paciente', $horario["paciente"]->cd_paciente)
                            ->orderBy('data_horario', 'desc')
                            ->get();

                        foreach ($horario['historico'] as $index => $agendamentoItem) {
                            $compartilha = $agendamentoItem->profissional->especialidades
                                ->where('cd_especialidade', $agendamentoItem->cd_especialidade)
                                ->where('sn_compartilha', 'N')
                                ->first();

                            if ($compartilha) {
                                unset($horario['historico'][$index]);
                            }
                        }
                    }
                } else {
                    $horario["situacao"] = "Livre";
                }

                $bloqueio = BloqueioAgendamento::firstWhere([
                    'data_horario' => $horario['data'] . ' ' . $horario['horario'],
                    'cd_agenda' => $agenda->cd_agenda
                ]);
                $horario['bloqueado'] = $bloqueio ? true : false;
                $horario['situacao'] = $bloqueio ? 'Bloqueado' : $horario['situacao'];

                array_push($horarios, $horario);
            }
        }

        if ($request->user()->sn_todos_agendamentos == "N" && $request->user()->admin == "N") {
            foreach ($horarios as $indice => $horario) {
                if ($horario['profissional']?->cd_profissional != $request->user()->cd_profissional) {
                    unset($horarios[$indice]);
                }
            }
        }

        if (!empty($request->situacao)) {
            foreach ($horarios as $indice => $horario) {
                if (strtolower($horario['situacao']) != $request->situacao) {
                    unset($horarios[$indice]);
                }
            }
        }

        return response()->json($horarios);
    }

    public function GetHorariosLivre(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'dtf_reagendamento' => 'required|date_format:Y-m-d',
            'dti_reagendamento' => 'required|date_format:Y-m-d',
            'cd_agenda' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $query = RpclinicaAgendamento::where("dt_agenda",">=",$request['dti_reagendamento'])
        ->where("dt_agenda","<=",$request['dtf_reagendamento'])
        ->where("agenda.cd_agenda",$request['cd_agenda'])
        ->join('agenda','agenda.cd_agenda','agendamento.cd_agenda')
        ->selectRaw("hr_agenda,nm_agenda,cd_agendamento,dia_semana,tipo,date_format(dt_agenda,'%d/%m/%Y') dt_agenda")
        ->where("situacao",'livre')->orderByRaw("dt_agenda,hr_agenda")->get();
        //dd($query->toArray());
        return response()->json($query);
    }

    public function Reagendamento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cd_reagendamento' => 'required|integer',
            'cd_agendamento' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 400);
        }

        $Agendamento = RpclinicaAgendamento::find($request['cd_agendamento']);

        if( ( $Agendamento['situacao']<>'agendado' ) && ( $Agendamento['situacao']<>'confirmado' ) ){
            return response()->json(['errors' => 'Situação do Agendamento não é permitido realizar o Reagendamento! <br><br> <i class="fa fa-asterisk"></i> Situações Permitidas <b>Agendado e Confirmado</b>'], 400);
        }

        $reagendamento = array(
            'cd_paciente' => $Agendamento['cd_paciente'],
            'cd_convenio' => $Agendamento['cd_convenio'],
            'cd_procedimento' => $Agendamento['cd_procedimento'],
            'cd_convenio' => $Agendamento['cd_convenio'],
            'situacao' => $Agendamento['situacao'],
            'valor' =>  str_replace(",",".",$Agendamento['valor']),
            'recebido' => $Agendamento['recebido'],
            'tipo' => $Agendamento['tipo'],
            'email' => $Agendamento['email'],
            'celular' => $Agendamento['celular'],
            'obs' => $Agendamento['obs'],
            'cd_profissional' => $Agendamento['cd_profissional'],
            'cd_especialidade' => $Agendamento['cd_especialidade'],
            'sms' => $Agendamento['sms'],
            'whatsapp' => $Agendamento['whatsapp'],
            'cd_local_atendimento' => $Agendamento['cd_local_atendimento'],
            'cd_reagendamento' => $Agendamento['cd_reagendamento'],
            'cartao' => $Agendamento['cartao'],
        );

        $agendamento = array(
            'cd_paciente' => null,
            'cd_convenio' => null,
            'cd_procedimento' => null,
            'cd_convenio' => null,
            'situacao' => 'livre',
            'valor' => null,
            'recebido' => null,
            //'tipo' => null,
            'email' => null,
            'celular' => null,
            'whast' => null,
            'dt_whast' => null,
            'obs' => null,
            'cd_profissional' => null,
            'conduta'=> null,
            'cd_especialidade' => null,
            'sms' => null,
            'whatsapp' => null,
            'cd_local_atendimento' => null,
            'cd_reagendamento' => null,
            'cartao' => null,
            'conduta'=> null,
            'hipotese_diagnostica'=> null,
            'exame_fisico'=> null,
            'anamnese'=> null,
            'informacoes_adicionais'=> null,
            'sn_presenca'=> null,
            'dt_presenca'=> null,
            'user_presenca'=> null,
            'user_presenca'=> null,
            'user_presenca'=> null,
            'whast_id'=> null,
            'whast_resp'=> null,
            'dt_resp_whast'=> null,
            'cd_whast_receive'=> null,
            'ds_whast'=> null,
            'whast'=> null

        );


        $retorno = RpclinicaAgendamento::where('cd_agendamento',$request['cd_reagendamento'])
        ->update($reagendamento);

        RpclinicaAgendamento::where('cd_agendamento',$request['cd_agendamento'])
        ->update($agendamento);

        return response()->json($request);
    }

    public function ReagendamentoManual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cd_agendamento' => 'required|integer',
            'dt_reagend' => 'required|date_format:Y-m-d',
            'hr_reagend' => 'required|date_format:H:i',
            'cd_agenda' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->first()], 400);
        }

        $Agendamento = RpclinicaAgendamento::find($request['cd_agendamento']);

        if( ( $Agendamento['situacao']<>'agendado' ) && ( $Agendamento['situacao']<>'confirmado' ) ){
            return response()->json(['errors' => 'Situação do Agendamento não é permitido realizar o Reagendamento! <br><br> <i class="fa fa-asterisk"></i> Situações Permitidas <b>Agendado e Confirmado</b>'], 400);
        }

        $reagendamento = array(
            'cd_paciente' => $Agendamento['cd_paciente'],
            'cd_convenio' => $Agendamento['cd_convenio'],
            'cd_procedimento' => $Agendamento['cd_procedimento'],
            'cd_convenio' => $Agendamento['cd_convenio'],
            'situacao' => $Agendamento['situacao'],
            'valor' =>  str_replace(",",".",$Agendamento['valor']),
            'recebido' => $Agendamento['recebido'],
            'tipo' => $Agendamento['tipo'],
            'email' => $Agendamento['email'],
            'celular' => $Agendamento['celular'],
            'obs' => $Agendamento['obs'],
            'cd_profissional' => $Agendamento['cd_profissional'],
            'cd_especialidade' => $Agendamento['cd_especialidade'],
            'sms' => $Agendamento['sms'],
            'whatsapp' => $Agendamento['whatsapp'],
            'cd_local_atendimento' => $Agendamento['cd_local_atendimento'],
            'cd_reagendamento' => $Agendamento['cd_reagendamento'],
            'cartao' => $Agendamento['cartao'],
        );

        $agendamento = array(
            'cd_paciente' => null,
            'cd_convenio' => null,
            'cd_procedimento' => null,
            'cd_convenio' => null,
            'situacao' => 'livre',
            'valor' => null,
            'recebido' => null,
            //'tipo' => null,
            'email' => null,
            'celular' => null,
            'whast' => null,
            'dt_whast' => null,
            'obs' => null,
            'cd_profissional' => null,
            'conduta'=> null,
            'cd_especialidade' => null,
            'sms' => null,
            'whatsapp' => null,
            'cd_local_atendimento' => null,
            'cd_reagendamento' => null,
            'cartao' => null,
            'conduta'=> null,
            'hipotese_diagnostica'=> null,
            'exame_fisico'=> null,
            'anamnese'=> null,
            'informacoes_adicionais'=> null,
            'sn_presenca'=> null,
            'dt_presenca'=> null,
            'user_presenca'=> null,
            'user_presenca'=> null,
            'user_presenca'=> null,
            'whast_id'=> null,
            'whast_resp'=> null,
            'dt_resp_whast'=> null,
            'cd_whast_receive'=> null,
            'ds_whast'=> null,
            'whast'=> null

        );


        $retorno = RpclinicaAgendamento::where('cd_agendamento',$request['cd_reagendamento'])
        ->update($reagendamento);

        RpclinicaAgendamento::where('cd_agendamento',$request['cd_agendamento'])
        ->update($agendamento);

        return response()->json($request);
    }

    public function GetHorariosSessao(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'qtde_sessao' => 'nullable|integer',
            'cd_agendamento' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        $Agendamento= RpclinicaAgendamento::find($request['cd_agendamento']);

        $query = RpclinicaAgendamento::where([
            'cd_agenda'=>$Agendamento['cd_agenda'],
            'dia_semana'=>$Agendamento['dia_semana'],
            'hr_agenda'=>$Agendamento['hr_agenda']
        ])->where('data_horario','>',$Agendamento['data_horario'])
        ->orderBy('data_horario')->limit( ($request['qtde_sessao']) ? $request['qtde_sessao'] : 0 )->get();

        return response()->json($query);

    }

    public function horariosAgendamentos(Request $request)
    {
  
        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d',
            'agenda' => 'nullable|integer|exists:agenda,cd_agenda',
            'profissional' => 'nullable|integer|exists:profissional,cd_profissional',
            'especialidade' => 'nullable|integer|exists:especialidade,cd_especialidade',
            'situacao' => 'nullable|string|in:livre,agendado,confirmado,atendido,cancelado,aguardando'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }


        $agendamentos = RpclinicaAgendamento::with(['agenda'  => function($q) use($request){
            $q->selectRaw(" agenda.*, nm_especialidade, nm_proc, nm_profissional, nm_local ")
            ->leftJoin('local_atendimento','local_atendimento.cd_local','agenda.cd_local_atendimento')
            ->leftJoin('profissional','profissional.cd_profissional','agenda.cd_profissional')
            ->leftJoin('procedimento','procedimento.cd_proc','agenda.cd_proc')
            ->leftJoin('especialidade','especialidade.cd_especialidade','agenda.cd_especialidade');
        } , 'paciente', 'profissional', 'especialidade', 'procedimento', 'local', 'cid', 'documentos', 'convenio','tipo_atend'])
            ->selectRaw("agendamento.*,DATE_FORMAT(data_horario, '%d/%m/%Y %H:%i') dt_hr,
            concat(' [ ',DATE_FORMAT(dt_presenca, '%d/%m/%Y %H:%i'),' ] ') dt_pres,
            DATE_FORMAT(dt_presenca, '%H:%i') hr_pres,

            case when ifnull(dt_inicio,'')<>'' then
                    DATE_FORMAT(TIMEDIFF(DATE_FORMAT(dt_inicio, '%H:%i'),  DATE_FORMAT(dt_presenca, '%H:%i')), '%HHs %iMin')
		        when ifnull(dt_inicio,'')='' and ifnull(dt_presenca,'') <> '' then
				    DATE_FORMAT(TIMEDIFF('".date('H:i:s')."', DATE_FORMAT(dt_presenca, '%H:%i')), '%HHs %iMin')
            end tempo,

            ifnull(situacao_atende.sn_atende,'N') sn_atende, ifnull(situacao_atende.sn_prontuario,'N') sn_prontuario,
            concat(' [ Data do retorno: ',DATE_FORMAT(dt_resp_whast, '%d/%m/%Y %H:%i'),' - ',ds_whast,' ( <b>',whast_resp,'</b> ) ] ') retorno_whast,
            concat(' [ Foi confirmado a presença do paciente as ',' ( <b>',DATE_FORMAT(dt_presenca, '%d/%m/%Y %H:%i'),  whast_resp,'</b> ) ] ') retorno_presenca ")
            ->where('dt_agenda', $request->data)
            ->leftJoin('agenda','agenda.cd_agenda','agendamento.cd_agenda')
            ->leftJoin('situacao_atende','situacao_atende.cd_situacao_atend','agendamento.situacao');

        if (empty($request->ordenar) || $request->ordenar=='age') {
            $agendamentos->orderBy('data_horario');
        }
        if($request->ordenar=='con'){
            $agendamentos->orderByRaw('ifnull(dt_presenca,ADDDATE(data_horario, INTERVAL 2 DAY) )');
        }

        if ($request->has('agenda') && !empty($request->agenda)) {
            $agendamentos->where('agenda.cd_agenda', $request->agenda);
        }
        $dados['data_agenda']=array();



        if($request['tela']=='agendamento'){
            if (!empty($request->agenda)) {
                $dados['data_agenda']=Agenda::where('agenda.cd_agenda',$request->agenda)->first();
            }

            if ($request->has('profissional') && !empty($request->profissional)) {
                $agendamentos->where('agenda.cd_profissional', $request->profissional);
            }

            if ($request->has('especialidade') && !empty($request->especialidade)) {
                $agendamentos->where('agenda.cd_especialidade', $request->especialidade);
            }
        }

        if($request['tela']=='consultorio'){
            if (!empty($request->agenda)) {
                $dados['data_agenda']=Agenda::where('cd_agenda',$request->agenda)->first();
            }

            if ($request->has('profissional') && !empty($request->profissional)) {
                $agendamentos->where('agendamento.cd_profissional', $request->profissional);
            }

            if ($request->has('especialidade') && !empty($request->especialidade)) {
                $agendamentos->where('agendamento.cd_especialidade', $request->especialidade);
            }
        }

        if ($request->has('situacao') && !empty($request->situacao)) {
            $agendamentos->where('agendamento.situacao', $request->situacao);
        }
        $xx=null;
        if($request['tela']=='consultorio'){
            $agendamentos->where('sn_atende', 'S');
            if($request->user()->sn_todos_agendamentos<>'S'){
                if($request->user()->cd_profissional ){
                    $agendamentos->where('agendamento.cd_profissional', $request->user()->cd_profissional );
                }else{
                    $agendamentos->where('agendamento.cd_profissional', '0' );
                }
            }
        }

        $dados['header']= RpclinicaAgendamento::where('dt_agenda', $request->data)
        ->selectRaw("
        sum(case when situacao='livre' then 1 else 0 end) livre,
        sum(case when situacao='agendado' then 1 else 0 end) agendado,
        sum(case when situacao='confirmado' then 1 else 0 end) confirmado,
        sum(case when situacao='aguardando' then 1 else 0 end) aguardando,
        sum(case when situacao='atendido' then 1 else 0 end) atendido,
        sum(case when situacao='cancelado' then 1 else 0 end) cancelado
        ")->get();

        $dados['dados']=$agendamentos->get();
        $dados['tela']=$request->user()->sn_todos_agendamentos.' - '.$request->user()->cd_profissional.' - '.$xx;
        $dados['request']=$request->data;
        return response()->json($dados);

    }

    public function horariosAgendaAvanc(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dti' => 'required|date_format:Y-m-d',
            'dtf' => 'required|date_format:Y-m-d'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }
        $whereHasPac=0; $whereHasAgen=0;
        $agendamentos = RpclinicaAgendamento::with([
        'paciente' => function($q) use($request){
            $q->select('nm_paciente', 'cd_paciente');
            if($request->paciente){
                $q->whereRaw(" upper(nm_paciente) like '".mb_strtoupper($request->paciente)."%'");
            }
        },
        'agenda'=> function($q) use($request){
            $q->selectRaw('agenda.*');
            if($request->espec){
                $q->where("cd_especialidade",$request->espec);
            }
            if($request->proc){
                $q->where("cd_proc",$request->proc);
            }
            if($request->prof){
                $q->where("cd_profissional",$request->prof);
            }
            if($request->convenio){
                $q->where("cd_convenio",$request->convenio);
            }
            if($request->tp_sus==1){
                $q->where("sn_sus",'S');
            }
            if($request->tp_part==1){
                $q->where("sn_particular",'S');
            }
            if($request->tp_conv==1){
                $q->where("sn_convenio",'S');
            }
        }])
        ->selectRaw("
            cd_agenda,dt_agenda,date_format(dt_agenda,'%d/%m/%Y') data,
            sum(case when situacao='livre' then 1 else 0 end) livre,
            sum(case when situacao='agendado' then 1 else 0 end) agendado,
            sum(case when situacao='confirmado' then 1 else 0 end) confirmado,
            sum(case when situacao='aguardando' then 1 else 0 end) aguardando,
            sum(case when situacao='atendido' then 1 else 0 end) atendido,
            sum(case when situacao='cancelado' then 1 else 0 end) cancelado
        ")
        ->whereBetween("dt_agenda",[ $request['dti'] , $request['dtf']]);

        if ($request->has('paciente') && !empty($request->paciente)) {
            $agendamentos = $agendamentos->whereHas('paciente',function($q) use($request) {
                $q->whereRaw(" upper(nm_paciente) like '".mb_strtoupper($request->paciente)."%'");
            } );
        }

        if($request->espec){
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("cd_especialidade",$request->espec);
            } );
        }

        if($request->prof){
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("cd_profissional",$request->prof);
            } );
        }

        if($request->convenio){
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("cd_convenio",$request->convenio);
            } );
        }

        if($request->tp_sus){
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("sn_sus",'S');
            } );
        }

        if($request->tp_part){
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("sn_particular",'S');
            } );
        }

        if($request->tp_conv){
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("sn_convenio",'S');
            } );
        }

        if ($request->proc) {
            $agendamentos = $agendamentos->whereHas('agenda',function($q) use($request) {
                $q->where("cd_proc",$request->proc);
            } );
        }

        if ($request->has('agenda') && !empty($request->agenda)) {
            $agendamentos = $agendamentos->where('cd_agenda', $request->agenda);
        }

        if ($request->has('situacao') && !empty($request->situacao)) {
            $agendamentos = $agendamentos->where('situacao', $request->situacao);
        }

        if ($request->dia>='0') {
            $agendamentos = $agendamentos->where('dia_semana', $request->dia);
        }

        if ($request->has('convenio') && !empty($request->convenio)) {
            $agendamentos = $agendamentos->where('cd_convenio', $request->convenio);
        }

        $agendamentos = $agendamentos->groupByRaw("cd_agenda,dt_agenda,date_format(dt_agenda,'%d/%m/%Y')")
        ->orderBy('dt_agenda')->get();
        return response()->json($agendamentos);

    }

    public function agendamentoManual(Request $request)
    {

        $validated = $request->validate([

            'cd_escala' => 'required|integer|exists:agenda_escala,cd_escala_agenda',
            'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
            'cd_local_atendimento' => 'required|integer|exists:local_atendimento,cd_local',
            'cd_especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
            'cd_procedimento' => 'nullable|integer|exists:procedimento,cd_proc',
            'cd_convenio' => 'required|integer|exists:convenio,cd_convenio',
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'tipo' => 'required|string',
            'dt_agenda' => 'required|date_format:Y-m-d',
            'hr_inicio' => 'required|date_format:H:i',
            'hr_fim' => 'required|date_format:H:i',
            'email' => 'nullable|email',
            'celular' => 'required',
            'cartao' => 'nullable|string',
            'validade' => 'nullable|string',
            'rg' => 'nullable|string',
            'cpf' => 'nullable|string',
            'dt_nasc' => 'nullable|string',
            'obs' => 'nullable|string|max:255',
            'sms' => 'sometimes|boolean',
            'whatsapp' => 'sometimes|boolean',
        ], [
            'valor.currency' => 'O valor não é um número valido.'
        ]);

 

        if ($request->has('cd_paciente')) {
            $paciente = Paciente::firstOrCreate(
                ['cd_paciente' => $request->cd_paciente],
                [
                    'nm_paciente' => $request->cd_paciente,
                    'sn_ativo' => 'S',
                    'cd_usuario' => $request->user()->cd_usuario
                ]
            );

            if ($request->cd_convenio) {
                $paciente->cd_categoria = $request->cd_convenio;
                $paciente->save();
            }

            if ($request->cartao) {
                $paciente->cartao = $request->cartao;
                $paciente->save();
            }

            if ($request->dt_nasc) {
                $paciente->dt_nasc = $request->dt_nasc;
                $paciente->save();
            }

            if ($request->profissao) {
                $paciente->profissao = $request->profissao;
                $paciente->save();
            }
            
            if ($request->validade) {
                $paciente->dt_validade = $request->validade;
                $paciente->save();
            }

            if ($request->rg) {
                $paciente->rg = $request->rg;
                $paciente->save();
            }

            if ($request->cpf) {
                $paciente->cpf = $request->cpf;
                $paciente->save();
            }

            if ($request->email) {
                $paciente->email = $request->email;
                $paciente->save();
            }

            if ($request->celular) {
                $paciente->celular = $request->celular;
                $paciente->save();
            }

            $validated['cd_paciente'] = $paciente->cd_paciente;
        }

        try {

            $Dia= $diasemana_numero = date('w', strtotime($request->dt_agenda));
            $escala=AgendaEscala::whereRaw("'".$request->dt_agenda."' between dt_inicial and dt_fim and  cd_agenda=".$request->cd_agenda." and sn_ativo='S' and nr_dia= '".$Dia."'")->first();
            $NmIntervalo=null;
            if(isset($escala->intervalo)){
                $Inter=AgendaIntervalo::whereRaw(" cd_intervalo=".$escala->intervalo)->first();
                $NmIntervalo=$Inter->nm_intervalo;
            }

            if(empty($NmIntervalo)){
                $NmIntervalo= gmdate('H:i:s', abs( strtotime($request->dt_agenda ) - strtotime( $request->hr_inicio ) ) );
            }

            unset($validated['data'], $validated['horario']);

            $situacao = AgendamentoSituacao::where('agendar','S')->first();
            if(!$situacao->cd_situacao){
                return response()->json(['errors' => 'O sistema não esta configurado para realizar agendamento!'], 500);
            }
            $Convenio = Convenio::find($request->cd_convenio);

            
            $array['cd_agenda'] = $request->cd_agenda;
            $array['cd_escala'] = (isset($request->cd_escala)) ? $request->cd_escala :null;
            $array['dt_agenda'] = $request->dt_agenda;
            $array['hr_agenda'] = $request->hr_inicio;
            $array['hr_final'] = $request->hr_fim;
            $array['intervalo'] =  (isset($NmIntervalo)) ? $NmIntervalo :null;
            $array['dia_semana'] = $Dia;
            $array['cd_paciente'] = $validated['cd_paciente'];
            $array['cd_convenio'] = $request->cd_convenio;
            $array['situacao'] = 'agendado';
            $array['data_horario'] = trim($request->dt_agenda).' '.trim($request->hr_inicio);
            $array['email'] = $request->email;
            $array['celular'] = $request->celular;
            $array['whast'] = null;
            $array['obs'] = $request->obs;
            $array['tipo'] = $request->tipo;
            $array['cd_profissional'] = $request->cd_profissional;
            $array['cd_especialidade'] = $request->cd_especialidade;
            if($Convenio->tp_conveino=='PA'){
                $array['cartao'] = null;
            }else{
                $array['cartao'] = $request->cartao;
            } 
            $array['dt_validade'] = $request->validade;
            $array['cd_local_atendimento'] = $request->cd_local_atendimento;
            $array['usuario_geracao']= $request->user()->cd_usuario;
             
             

            if(!$request['cd_agendamento']){
                $Agendamento = RpclinicaAgendamento::create($array);
                if($request->tipo){
                    $TipoAtendProc = TipoAtendimentoProc::where('cd_tipo_atendimento',$request->tipo)
                    ->get();

                    foreach($TipoAtendProc as $proc){

                        $TabProc = ProcedimentoConvenio::whereRaw('cd_procedimento='.$proc->cd_proc)
                        ->where('cd_convenio',$Agendamento->cd_convenio)->first();
                        if($TabProc){

                            $Pac = Paciente::find($Agendamento->cd_paciente);
                            $Prof = Profissional::find($Agendamento->cd_profissional);
                            $Conv = Convenio::find($Agendamento->cd_convenio);
                            $Proced = Procedimento::find($proc->cd_proc);

                            $ArrayConta['cd_conta']=$Agendamento->cd_agendamento;
                            $ArrayConta['cd_agendamento']=$Agendamento->cd_agendamento;
                            $ArrayConta['cd_atendimento']=$Agendamento->cd_agendamento;
                            $ArrayConta['cd_profissional']=$Agendamento->cd_profissional;
                            $ArrayConta['nm_profissional']=$Prof->nm_profissional;
                            $ArrayConta['cd_prof_exec']=$Agendamento->cd_profissional;
                            $ArrayConta['nm_prof_exec']=$Prof->nm_profissional;
                            $ArrayConta['cd_convenio']=$Agendamento->cd_convenio;
                            $ArrayConta['nm_convenio']=$Conv->nm_convenio;
                            $ArrayConta['dt_conta']=$Agendamento->dt_agenda;
                            $ArrayConta['cd_paciente']=$Agendamento->cd_paciente;
                            $ArrayConta['nm_paciente']=$Pac->nm_paciente;
                            $ArrayConta['cd_proc']=$proc->cd_proc;
                            $ArrayConta['cod_proc']=$proc->cod_proc;
                            $ArrayConta['ds_proc']=$Proced->nm_proc;
                            $ArrayConta['vl_unitario']=$TabProc->valor;
                            $ArrayConta['vl_total']=( ( $TabProc->valor ? $TabProc->valor : 0 ) * 1 );
                            $ArrayConta['qtde']=1;
                            $ArrayConta['situacao']='ABERTA';

                            FaturamentoConta::create($ArrayConta);

                        }

                    }

                }

                funcLogsAtendimentoHelpers($Agendamento['cd_agendamento'],'USUARIO CADASTROU AGENDAMENTO');

                return $Agendamento;
            }else{

                funcLogsAtendimentoHelpers($request['cd_agendamento'],'USUARIO ATUALIZOU AGENDAMENTO');
                return RpclinicaAgendamento::where('cd_agendamento',$request['cd_agendamento'])->update($array);
            }

        }
        catch (Throwable $error) {
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
    }

    public function agendamentoManual_BKP(Request $request)
    {
        $validated = $request->validate([
            // 'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
            'cd_local_atendimento' => 'required|integer|exists:local_atendimento,cd_local',
            'cd_especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
            'cd_procedimento' => 'nullable|integer|exists:procedimento,cd_proc',
            'cd_convenio' => 'required|integer|exists:convenio,cd_convenio',
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'situacao' => 'required|string|in:livre,agendado,confirmado,aguardando,cancelado,atendido',
            'valor' => 'nullable|currency',
            'tipo' => 'nullable|string',
            'data' => 'required|date_format:Y-m-d',
            'horario' => 'required|date_format:H:i',
            'email' => 'nullable|email',
            'celular' => 'required',
            'cartao' => 'nullable|string',
            'obs' => 'nullable|string|max:255',
            'sms' => 'sometimes|boolean',
            'whatsapp' => 'sometimes|boolean',
        ], [
            'valor.currency' => 'O valor não é um número valido.'
        ]);

        // if (BloqueioAgendamento::firstWhere(['cd_agenda' => $request->cd_agenda, 'data_horario' => $request->data_horario])) {
        //     return response()->json(['errors' => ['Horário bloqueado!']], 403);
        // }

        if ($request->has('cd_paciente')) {
            $paciente = Paciente::firstOrCreate(
                ['cd_paciente' => $request->cd_paciente],
                [
                    'nm_paciente' => $request->cd_paciente,
                    'sn_ativo' => 'S',
                    'cd_usuario' => $request->user()->cd_usuario
                ]
            );

            if ($paciente->cd_categoria != $request->cd_convenio) {
                $paciente->cd_categoria = $request->cd_convenio;
                $paciente->save();
            }

            if ($paciente->cartao != $request->cartao && !empty($request->cartao)) {
                $paciente->cartao = $request->cartao;
                $paciente->save();
            }

            $validated['cd_paciente'] = $paciente->cd_paciente;
        }

        try {
            unset($validated['data'], $validated['horario']);

            $validated['valor'] = formatCurrencyForDB($validated['valor']);
            $validated['data_horario'] = "{$request->data} {$request->horario}";
            $validated['dt_agenda'] = $request->data;
            $validated['hr_agenda'] = $request->horario;
            $validated['dia_semana'] = date('w', strtotime($request->data));

            // if (RpclinicaAgendamento::firstWhere(['data_horario' => $validated['data_horario'], 'cd_agenda' => $validated['cd_agenda']])) {
            //     return response()->json(['errors' => ['Já existe um agendamento para o horário selecionado.']], 403);
            // }

            return RpclinicaAgendamento::create($validated);
        }
        catch (Throwable $error) {
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
    }

    public function updateAgendamento(Request $request)
    {
        $validated = $request->validate([
            'cd_agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
            'cd_profissional' => 'nullable|integer|exists:profissional,cd_profissional',
            'cd_local_atendimento' => 'nullable|integer|exists:local_atendimento,cd_local',
            'cd_especialidade' => 'nullable|integer|exists:especialidade,cd_especialidade',
            'cd_procedimento' => 'nullable|integer|exists:procedimento,cd_proc',
            'cd_convenio' => 'required|integer|exists:convenio,cd_convenio',
            'situacao' => 'required|string|in:livre,agendado,confirmado,atendido,cancelado,aguardando',
            'valor' => 'nullable|currency',
            'tipo' => 'nullable|string',
            'data_horario' => 'required|date_format:Y-m-d H:i:s',
            'email' => 'nullable|email',
            'celular' => 'required|celular_com_ddd',
            'cartao' => 'nullable|string',
            'obs' => 'nullable|string|max:255',
            'sms' => 'sometimes|boolean',
            'whatsapp' => 'sometimes|boolean',
            'recebido' => 'sometimes|boolean',
            'sn_presenca' => 'sometimes|boolean',
        ], [
            'valor.currency' => 'O valor não é um número valido.'
        ]);
        $DadosAgendamento = RpclinicaAgendamento::find($request->cd_agendamento);
        if(empty($validated['recebido'])){ $validated['recebido']=null; }
        if(empty($validated['whatsapp'])){ $validated['whatsapp']=null; }
        if(empty($validated['sms'])){ $validated['sms']=null; }

        /*
        if(empty($validated['sn_presenca'])){
            $validated['sn_presenca']=null;
            $validated['dt_presenca']=null;
            $validated['user_presenca']=null;
        }else{
            if($DadosAgendamento['sn_presenca']==null){
                $validated['dt_presenca']=date('Y-m-d H:i');
                $validated['user_presenca']=$request->user()->cd_usuario;
                $validated['situacao']='aguardando';
            }
        }
        
        if($DadosAgendamento['sn_presenca']){
            if(empty($validated['sn_presenca'])){
                $validated['sn_presenca']=null;
                $validated['dt_presenca']=null;
                $validated['user_presenca']=null;
            } 
        }
         */


        if (BloqueioAgendamento::firstWhere([
            'cd_agenda' => $request->cd_agenda,
            'data_horario' => $request->data_horario
        ])) {
            return response()->json(['errors' => ['Horário bloqueado!']], 403);
        }

        if ($request->has('cd_paciente')) {
            $paciente = Paciente::firstOrCreate(
                ['cd_paciente' => $request->cd_paciente],
                [
                    'nm_paciente' => $request->cd_paciente,
                    'nm_pai' => $request->nm_pai,
                    'nm_mae' => $request->nm_mae,
                    'dt_nasc' => $request->dt_nasc,
                    'sn_ativo' => 'S',
                    'celular' => $request->celular,
                    'cartao' => $request->cartao,
                    'email' => $request->email,
                    'cd_categoria' => $request->cd_convenio,
                    'cd_usuario' => $request->user()->cd_usuario
                ]
            );

            if ($paciente->cd_categoria != $request->cd_convenio) {
                $paciente->cd_categoria = $request->cd_convenio;
                $paciente->save();
            }

            if ($paciente->cartao != $request->cartao && !empty($request->cartao)) {
                $paciente->cartao = $request->cartao;
                $paciente->save();
            }

            $validated['cd_paciente'] = $paciente->cd_paciente;
        }

        try {
            $validated['valor'] = formatCurrencyForDB($validated['valor']);

            unset($validated['cd_agendamento']);
            if($validated['celular']){
                if($validated['celular']==$request['foneWhast']){
                    $validated['whast'] = $request['SituacaoWhast'];
                    $validated['dt_whast'] = date('Y-m-d H:i');
                }else{
                    $validated['whast'] = null;
                    $validated['dt_whast'] = null;
                }
            }
            return RpclinicaAgendamento::find($request->cd_agendamento)->update($validated);

            funcLogsAtendimentoHelpers($request['cd_agendamento'],'USUARIO ATUALIZOU O AGENDAMENTO');

        } catch (Throwable $error) {
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
    }

    public function destroyAgendamento($agendamendo)
    {
        try {
            $agendamendo = RpclinicaAgendamento::find($agendamendo);

            /*
            if ($agendamendo->situacao != 'livre') {
                return response()->json(['message' => 'Você só pode excluir um horário se ele estiver livre.'], 403);
            }
            */
            $agendamento = array(
                'cd_paciente' => null,
                'cd_convenio' => null,
                'cd_procedimento' => null,
                'cd_convenio' => null,
                'situacao' => 'livre',
                'valor' => null,
                'recebido' => null,
                //'tipo' => null,
                'email' => null,
                'celular' => null,
                'whast' => null,
                'dt_whast' => null,
                'obs' => null,
                'cd_profissional' => null,
                'conduta'=> null,
                'cd_especialidade' => null,
                'sms' => null,
                'whatsapp' => null,
                'cd_local_atendimento' => null,
                'cd_reagendamento' => null,
                'cartao' => null,
                'conduta'=> null,
                'hipotese_diagnostica'=> null,
                'exame_fisico'=> null,
                'anamnese'=> null,
                'informacoes_adicionais'=> null,
                'sn_presenca'=> null,
                'dt_presenca'=> null,
                'user_presenca'=> null,
                'user_presenca'=> null,
                'user_presenca'=> null,
                'whast_id'=> null,
                'whast_resp'=> null,
                'dt_resp_whast'=> null,
                'cd_whast_receive'=> null,
                'ds_whast'=> null,
                'whast'=> null
            );
            $agendamendo->delete();

            funcLogsAtendimentoHelpers($agendamendo['cd_agendamento'],'USUARIO DELETOU AGENDAMENTO');

            return response()->json(['message' => 'Horário excluído com sucesso!']);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao excluir o horário.' . $e->getMessage()], 500);
        }
    }

    public function alterarHorario(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:agendamento,cd_agendamento',
            'data_start' => 'required|date_format:Y-m-d',
            'hr_start' => 'required|date_format:H:i',
            'hr_end' => 'required|date_format:H:i'
        ]);
        try {

            $Array['usuario_geracao']=$request->user()->cd_usuario;
            $Array['dt_agenda']=$request['data_start'];
            $Array['hr_agenda']=$request['hr_start'];
            $Array['hr_final']=$request['hr_end'];
            $Array['data_horario']=trim($request['data_start']).' '.$request['hr_start'];

            RpclinicaAgendamento::where('cd_agendamento',$request['id'])->update($Array);
            funcLogsAtendimentoHelpers($request['id'],'USUARIO ATUALIZOU O HORARIO DO AGENDAMENTO');


            return response()->json(['message' => 'Horário alterado com sucesso!']);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao altrar o horário. ' . $th->getMessage()], 500);
        }

    }

 
     public function bloquearHorario(Request $request)
    {
        $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'data' => 'required|date_format:Y-m-d',
            'hr_inicio' => 'required|date_format:H:i',
            'hr_fim' => 'required|date_format:H:i'
        ]);

        try {
            /*
            $agendamendo = RpclinicaAgendamento::find($request->cd_agendamento);

            if ($agendamendo->situacao == 'bloqueado') {
                return response()->json(['message' => 'Horário já está bloqueado!'], 403);
            }

            if ($agendamendo->situacao != 'livre') {
                return response()->json(['message' => 'Você não pode bloquear este horário!'], 403);
            }
            */
            $Array['situacao']='bloqueado';
            $Array['usuario_geracao']=$request->user()->cd_usuario;
            $Array['cd_agenda']=$request['cd_agenda'];
            $Array['dt_agenda']=$request['data'];
            $Array['hr_agenda']=$request['hr_inicio'];
            $Array['hr_final']=$request['hr_fim'];
            $Array['obs']=$request['obs'];
            $Array['data_horario']=trim($request['data']).' '.$request['hr_agenda'];

            $bloqueado = RpclinicaAgendamento::create($Array);

            funcLogsAtendimentoHelpers($bloqueado['cd_agendamento'],'USUARIO BLOQUEOU HOARARIO');

            return response()->json(['message' => 'Horário bloqueado com sucesso!']);
        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao bloquear o horário. ' . $th->getMessage()], 500);
        }
    }

    public function desbloquearHorario(Request $request)
    {
        $request->validate([
            'cd_agendamento' => 'required|integer|exists:agendamento,cd_agendamento'
        ]);

        try {
            $agendamendo = RpclinicaAgendamento::find($request->cd_agendamento);

            $agendamendo->update(['situacao' => 'livre']);

            return response()->json(['message' => 'Horário desbloqueado com sucesso!']);
        } catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao desbloquear o horário. ' . $th->getMessage()], 500);
        }
    }

    public function agendamentoSessao(Request $request) {
        $request->validate([
            "cd_agendamento" => "required|integer|exists:agendamento,cd_agendamento",
            "cds_agendamento_sessao" => "required|array",
            "cds_agendamento_sessao.*" => "integer|exists:agendamento,cd_agendamento"
        ]);

        $agendamento = RpclinicaAgendamento::find($request->cd_agendamento);

        if (empty($agendamento->cd_paciente) || $agendamento->situacao == "livre") {
            return response()->json(['message' => 'Você não pode criar sessões sem um paciente ou com a situação livre!'], 403);
        }

        try {
            foreach($request->cds_agendamento_sessao as $cd_agendamento_sessao) {
                $agendamento_sessao = RpclinicaAgendamento::find($cd_agendamento_sessao);

                $agendamento_sessao->update([
                    "cd_paciente" => $agendamento->cd_paciente,
                    "cd_profissional" => $agendamento->cd_profissional,
                    "cd_local_atendimento" => $agendamento->cd_local_atendimento,
                    "cd_procedimento" => $agendamento->cd_procedimento,
                    "cd_convenio" => $agendamento->cd_convenio,
                    "cd_especialidade" => $agendamento->cd_especialidade,
                    "cd_cid" => $agendamento->cd_cid,
                    "cd_classificacao" => $agendamento->cd_classificacao,
                    "situacao" => "agendado",
                    "valor" => formatCurrencyForDB($agendamento->valor),
                    "recebido" => $agendamento->recebido,
                    "tipo" => $agendamento->tipo,
                    "data_horario" => $agendamento->data_horario,
                    "email" => $agendamento->email,
                    "celular" => $agendamento->celular,
                    "cartao" => $agendamento->cartao,
                    "cd_reagendamento" => $agendamento->cd_agendamento,
                ]);
            }

            return response()->json(['message' => 'Sessões agendadas com sucesso!']);
        }
        catch(Exception $e) {
            return response()->json(['message' => 'Houve um erro ao criar as sessões! '.$e->getMessage()], 403);
        }

    }

    public function agendamentoDadosMes($data) {
        $dateCarbon = Carbon::parse($data)->startOfMonth();
        $dias = [];

        do {
            $agendamentos = RpclinicaAgendamento::where('dt_agenda', $dateCarbon->format('Y-m-d'))->get()->toArray();

            $dias[$dateCarbon->format('d')] = [
                'livre' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'livre') ),
                'agendado' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'agendado') ),
                'confirmado' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'confirmado') ),
                'atendido' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'atendido') ),
                'bloqueado' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'bloqueado') ),
                'cancelado' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'cancelado') ),
                'aguardando' => count( array_filter($agendamentos, fn($agendamento) => $agendamento['situacao'] == 'aguardando') )
            ];
            $dateCarbon->addDay();
        } while (!$dateCarbon->gt(Carbon::parse($data)->endOfMonth()));

        return response()->json($dias);
    }
 

    public function jsonShowAgenda(Request $request) {

        $request->validate([
            "cd_agenda" => "required|integer|exists:agenda,cd_agenda"
        ]);


        try {
            $dados['agendamento'] = Agenda::find($request->cd_agenda);
            //local_atendimento
            $locais = AgendaLocais::where('cd_agenda',$request->cd_agenda)->get();
            $qtdeLocais = $locais->count();
            if($qtdeLocais==0){
                $dados['locais'] = LocalAtendimento::where('sn_ativo','S')
                ->selectRaw('nm_local,cd_local');
                if($dados['agendamento']->local_atendimento_editavel=='1' &&  $dados['agendamento']->cd_local_atendimento){
                    $dados['locais'] = $dados['locais']->where('local_atendimento.cd_local',$dados['agendamento']->cd_local_atendimento);
                }
                $dados['locais'] = $dados['locais']->orderBy('nm_local')->get();
            }
            else {
                $dados['locais'] = AgendaLocais::where('cd_agenda',$request->cd_agenda)
                ->join('local_atendimento','local_atendimento.cd_local','agenda_locais.cd_local');
                if($dados['agendamento']->local_atendimento_editavel=='1' &&  $dados['agendamento']->cd_local_atendimento){
                    $dados['locais'] = $dados['locais']->where('local_atendimento.cd_local',$dados['agendamento']->cd_local_atendimento);
                }
                $dados['locais'] = $dados['locais']->selectRaw('nm_local,local_atendimento.cd_local')->orderBy('nm_local')->get();
            }
            //convenios
            $convenios = AgendaConvenios::where('cd_agenda',$request->cd_agenda)->get();
            $qtdeConvenios = $convenios->count();
            if($qtdeConvenios==0)
                $dados['convenios'] = Convenio::where('sn_ativo','S')
                ->selectRaw('nm_convenio,cd_convenio')->orderBy('nm_convenio')->get();
            else
                $dados['convenios'] = AgendaConvenios::where('cd_agenda',$request->cd_agenda)
                ->join('convenio','convenio.cd_convenio','agenda_convenios.cd_convenio')
                ->selectRaw('nm_convenio,agenda_convenios.cd_convenio')->orderBy('nm_convenio')->get();
            //procedimentos
            $procs = AgendaProcedimentos::where('cd_agenda',$request->cd_agenda)->get();
            $qtdeProcs = $procs->count();
            if($qtdeProcs==0) {
                $dados['procedimentos'] = Procedimento::where('sn_ativo','S');
                if($dados['agendamento']->procedimento_editavel=='1' &&  $dados['agendamento']->cd_proc){
                    $dados['procedimentos'] = $dados['procedimentos']->where('procedimento.cd_proc',$dados['agendamento']->cd_proc);
                }
                $dados['procedimentos'] = $dados['procedimentos']->selectRaw('nm_proc,procedimento.cd_proc')->orderBy('nm_proc')->get();
            }else {
                $dados['procedimentos'] = AgendaProcedimentos::where('cd_agenda',$request->cd_agenda)
                ->join('procedimento','procedimento.cd_proc','agenda_procedimentos.cd_proc')
                ->selectRaw('nm_proc,procedimento.cd_proc');
                if($dados['agendamento']->procedimento_editavel=='1' &&  $dados['agendamento']->cd_proc){
                    $dados['procedimentos'] = $dados['procedimentos']->where('procedimento.cd_proc',$dados['agendamento']->cd_proc);
                }
                $dados['procedimentos'] = $dados['procedimentos']->orderBy('nm_proc')->get();
            }
            //especialidades
            $espec = AgendaEspecialidades::where('cd_agenda',$request->cd_agenda)->get();
            $qtdeEspec = $espec->count();
            if($qtdeEspec==0) {
                $dados['especialidades'] = Especialidade::where('sn_ativo','S');
                if($dados['agendamento']->especialidade_editavel=='1' &&  $dados['agendamento']->cd_especialidade){
                    $dados['especialidades'] = $dados['especialidades']->where('especialidade.cd_especialidade',$dados['agendamento']->cd_especialidade);
                }
                $dados['especialidades'] = $dados['especialidades']->selectRaw('nm_especialidade,especialidade.cd_especialidade')->orderBy('nm_especialidade')->get();
            }else {
                $dados['especialidades'] = AgendaEspecialidades::where('cd_agenda',$request->cd_agenda)
                ->join('especialidade','especialidade.cd_especialidade','agenda_especialidades.cd_especialidade')
                ->selectRaw('nm_especialidade,especialidade.cd_especialidade');
                if($dados['agendamento']->especialidade_editavel=='1' &&  $dados['agendamento']->cd_especialidade){
                    $dados['especialidades'] = $dados['especialidades']->where('especialidade.cd_especialidade',$dados['agendamento']->cd_especialidade);
                }
                $dados['especialidades'] = $dados['especialidades']->orderBy('nm_especialidade')->get();
            }
             //profissionais
             $prof = AgendaProfissionais::where('cd_agenda',$request->cd_agenda)->get();
             $qtdeProf = $prof->count();
             if($qtdeProf==0) {
                 $dados['profissionais'] = Profissional::where('sn_ativo','S');
                 if($dados['agendamento']->profissional_editavel=='1' &&  $dados['agendamento']->cd_profissional){
                    $dados['profissionais'] = $dados['profissionais']->where('profissional.cd_profissional',$dados['agendamento']->cd_profissional);
                 }
                 $dados['profissionais'] = $dados['profissionais']->selectRaw('nm_profissional,profissional.cd_profissional')->orderBy('nm_profissional')->get();
             }else {
                 $dados['profissionais'] = AgendaProfissionais::where('cd_agenda',$request->cd_agenda)
                 ->join('profissional','profissional.cd_profissional','agenda_profissionais.cd_profissional')
                 ->selectRaw('nm_profissional,profissional.cd_profissional');
                 if($dados['agendamento']->profissional_editavel=='1' &&  $dados['agendamento']->cd_profissional){
                    $dados['profissionais'] = $dados['profissionais']->where('profissional.cd_profissional',$dados['agendamento']->cd_profissional);
                 }
                 $dados['profissionais'] = $dados['profissionais']->orderBy('nm_profissional')->get();
             }

            return response()->json($dados);
        }
        catch(Exception $e) {
            return response()->json(['message' => 'Houve um erro ao criar as sessões! '.$e->getMessage()], 403);
        }

    }

    public function getHorConf(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d',
            'cd_agenda' => 'required',
            'situacao' => 'required',
            'tp_envio' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }



        $query = RpclinicaAgendamento::with(['agenda', 'paciente', 'profissional',  'convenio'])
        ->selectRaw("agendamento.*, date_format(dt_agenda,'%d/%m/%Y') data_agenda ")
        ->where('cd_agenda',$request['cd_agenda'])
        ->where('situacao',$request['situacao'])
        ->whereRaw("date(dt_agenda) = '".$request['data']."'")
        ->orderBy('data_horario')->get();

        return response()->json($query);

    }

    public function sendHorConf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cd_agendamento' => "required|array"
        ]);

        try {

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $dados = WhastApi::find('WHAST');
            if($dados->ds_button1)
                $Array[] = array('title'=>$dados->ds_button1, 'id' => $dados->cd_button1);
            if($dados->ds_button2)
                $Array[] = array('title'=>$dados->ds_button2, 'id' => $dados->cd_button2);
            if($dados->ds_button3)
                $Array[] = array('title'=>$dados->ds_button3, 'id' => $dados->cd_button3);
            if($dados->ds_button4)
                $Array[] = array('title'=>$dados->ds_button4, 'id' => $dados->cd_button4);

            $text  = $dados->msg_agenda;

            foreach($request->cd_agendamento as $linha){

                $agendamento = RpclinicaAgendamento::with([  'paciente', 'profissional' ])
                ->selectRaw("agendamento.*, date_format(dt_agenda,'%d/%m/%Y') data_agenda")
                ->find($linha);

                $text  = $dados->msg_agenda;
                $Mascaras = array("*NOME_CLIENTE*", "*NOME_PROFISIONAL*", "*DATA_AGENDA*", "*HORA_AGENDA*");
                $Valores   = array("*".mb_strtoupper($agendamento->paciente?->nm_paciente)."*" ,
                ($agendamento->profissional?->nm_profissional) ? ' com *'.mb_strtoupper($agendamento->profissional?->nm_profissional).'*' : '' ,"*".$agendamento->data_agenda."*" , "*".$agendamento->hr_agenda."*" );
                $textFormat = str_replace($Mascaras, $Valores, $text);
                 /*
                $body = [
                    "to" => ( $agendamento->celular ) ?  '55'.whast_formatPhone($agendamento->celular) : '0000000000' ,
                    "data" => [
                        "text" => $textFormat,
                        "buttons" => $Array,
                        "footerText" => $dados->msg_agenda_footer
                    ]
                  ];
                  $retornoApi = whast_getSendAgenda($dados->key,$body);
                  */

                  $body=[
                    "messageData"=> [
                        /*
                        "to" => ( $agendamento->celular ) ?  '55'.whast_formatPhone($agendamento->celular) : '0000000000' ,
                        */
                        "to"=> '553888281639',
                        "text"=> $textFormat
                    ]
                  ];


                $retornoApi = whast_getSendAgenda2($dados->key,$body);

                  $retorno =json_decode($retornoApi);


                  if(isset($retorno->status)){

                    $insert['status'] = $retorno->status;
                    if($insert['status'] == 200){
                        $insert['nr_send'] = $retorno->data->key->remoteJid;
                        $insert['id'] = $retorno->data->key->id;
                        $insert['from_me'] = $retorno->data->key->fromMe;
                        $insert['dt_envio'] =  date('Y-m-d H:i:s', $retorno->data->messageTimestamp);
                        $Update = array('whast_id'=>$retorno->data->key->id );
                        RpclinicaAgendamento::where('cd_agendamento',$linha)->update($Update);
                    }
                    if($insert['status'] == 401){
                      $insert['message'] = $retorno->message . ' [ Número não cadastrado no Whatsapp ] ';
                      $Update['whast']='false';
                      $Update['dt_whast']=date('Y-m-d H:i');
                      $Update['obs']=$agendamento->obs.' [ Número não cadastrado no Whatsapp -> '.date('Y-m-d H:i').' ] ';
                      RpclinicaAgendamento::where('cd_agendamento',$linha)->update($Update);
                    }
                    if($insert['status'] == 500){
                      $insert['message'] = $retorno->message;
                    }
                    if(isset($retorno->message)){
                      $insert['message'] = $retorno->message;
                    }

                  }
                  $insert['conteudo'] = $textFormat;
                  $insert['cd_agendamento'] = $linha;
                  $insert['retorno'] = $retornoApi;
                  $insert['cd_usuario'] = $request->user()->cd_usuario;
                  WhastSend::create($insert);


            }




            return response()->json($insert);

        }
        catch(Exception $e) {
            return response()->json(['message' => 'Houve um erro ao enviar mensagem! '.$e->getMessage()], 403);
        }
    }

    public function logsAgendamento(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cd_agendamento' => "required"
        ]);

        try {
            $logs = DB::select(" select * from (
                select 'ENVIO' tipo, id,nr_send nr_send,date_format(dt_envio,'%d/%m/%Y %H:%i')  dt_envio,cd_usuario,cd_agendamento,'' tp_acao, '' tp_msg,
                '' msg,'' id_envio,'' nr_resposta
                from whast_send
                union all
                select 'RETORNO' tipo, id_msg id,nr_envio nr_send, date_format(dt_msg,'%d/%m/%Y %H:%i') dt_envio,'' cd_usuario,
                cd_agendamento,  tp_acao,
                case when tp_msg='conversation' then 'CONVERSA'
                         when tp_msg='messageContextInfo' then 'RESPOSTA' else tp_msg end tp_msg,
                case when tp_msg='conversation' then msg
                         when tp_msg='messageContextInfo' then ds_op_select else tp_msg end msg,
                id_envio,nr_resposta
                 from whast_receive
                inner join agendamento on agendamento.cd_whast_receive=whast_receive.cd_whast_receive
             ) query_principal
            where cd_agendamento = ".$request['cd_agendamento']."
            order by 4 ");

            return response()->json($logs);

        }
        catch(Exception $e) {
            return response()->json(['message' => 'Houve um erro ao criar as sessões! '.$e->getMessage()], 403);
        }
    }

    public function jsonShowTable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codigo' => "required",
            'tipo' => "required",
            'dt_hr' => 'nullable',
            'data' => 'nullable'

        ]);

        try {

            $query=null; $hr_final=null;

            if($request['tipo']=='cad_agenda'){

                $query=Agenda::with(['profissional','especialidade','local','tp_agendamento'])
                ->find($request['codigo']);

            }

            if($request['tipo']=='agenda'){

                $diasemana_numero = date('w', strtotime($request['data']));
                $escala = AgendaEscala::
                where(function ($query) use ($diasemana_numero,$request) {
                    $query->whereRaw("nr_dia=".$diasemana_numero)
                    ->whereRaw("cd_agenda=".$request['codigo'])
                    ->whereRaw("sn_ativo='S'")
                    ->whereRaw("ifnull(escala_manual,'')=''");
                })->orWhere(function ($query) use ($request) {
                    $query->whereRaw("ifnull(escala_manual,'')='S'")
                    ->whereRaw("cd_agenda=".$request['codigo'])
                    ->whereRaw("sn_ativo='S'")
                    ->whereRaw("dt_inicial='".$request['data']."'");
                })->first();

                if(!$escala){
                    return response()->json(['tp_error'=>'escala','error'=>true,'message' => 'Não existe Escala para essa agenda! ']);
                }

                $query=Agenda::with(['profissional','especialidade','local',
                'escalas'=> function($q) use($diasemana_numero){

                    $q->whereRaw("nr_dia=".$diasemana_numero)
                    ->whereRaw("sn_ativo='S'");
                    $q->whereRaw("ifnull(escala_manual,'')=''");

                },
                'escalas_manual'=> function($q) use($diasemana_numero,$request){

                    $q->whereRaw("nr_dia=".$diasemana_numero)
                    ->whereRaw("sn_ativo='S'")
                    ->whereRaw("ifnull(escala_manual,'')='S'")
                    ->where('dt_inicial',$request['data']);

                }])->find($request['codigo']);

                if(isset($query->escalas[0])){
                    $query['escalass'] = $query->escalas[0]->toArray();
                }else{
                    $query['escalass'] = null;
                }
                $query['escala'] =$escala->toArray();

                if(isset($query->escalass->nm_intervalo)){
                    $data = $request['data'].' '.$request['hr_start'];
                    $duracao = $query->escalass->nm_intervalo;
                    $v = explode(':', $duracao);
                    $query['termino'] =  date('H:i', strtotime("{$data} + {$v[0]} hours {$v[1]} minutes {$v[2]} seconds"));
                }
                if($request['info']=='rapido'){
                    $query2=RpclinicaAgendamento::with(['convenio','profissional','especialidade',
                    'local','agenda','paciente','contas','escalas','tipo_atend'])
                    ->whereRaw("cd_agenda=".$request['codigo'])
                    ->whereRaw("dt_agenda='".$request['data']."'")
                    ->selectRaw("agendamento.*,date_format(dt_agenda, '%d/%m/%Y') data_agenda ")
                    ->orderBy('dt_agenda')->get();
                    $query['agendamento'] = $query2->toArray();
                }

            }

            if($request['tipo']=='agendamento'){
                $query=RpclinicaAgendamento::with(['convenio','profissional','especialidade',
                'convenio.procedimentosConvenio' => function($q) use($request){
                    $q->selectRaw("procedimento_convenio.*,nm_proc,cod_proc");
                    $q->join('procedimento','procedimento.cd_proc','procedimento_convenio.cd_procedimento')->orderBy('nm_proc');
                },'local','agenda','paciente','contas','escalas'])->find($request['codigo']);

                if($query->cd_paciente){
                    $historico=RpclinicaAgendamento::with(['convenio','profissional','especialidade'])
                    ->whereRaw("cd_paciente=".$query->cd_paciente)
                    ->orderBy('dt_agenda')->get();
                    $query['historico']=$historico->toArray();
                }else{
                    $query['historico']=null;
                }

                if($query->dt_presenca){
                    $query['retorno_presenca']= '[ Foi confirmado a presença do paciente as ( <b> '. $query->dt_presenca.' </b> ) ] ';
                }else{
                    $query['retorno_presenca']=null;
                }


            }

            if($request['tipo']=='lista_agenda'){
                $diasemana_numero = date('w', strtotime($request['data']));
                $query = AgendaEscala::whereRaw("nr_dia=".$diasemana_numero)
                ->whereRaw("agenda_escala.sn_ativo='S'")
                ->selectRaw(" distinct(agenda.cd_agenda) cd_agenda, nm_agenda")
                ->Join('agenda','agenda.cd_agenda','agenda_escala.cd_agenda')
                ->whereRaw("agenda.sn_ativo='S'")->get();
            }

            if($request['tipo']=='historico'){
                $query = RpclinicaAgendamento::with(['convenio','profissional','especialidade'])
                ->whereRaw("cd_paciente=".$request['codigo'])
                ->orderBy('dt_agenda')->get();
            }


            return response()->json($query);

        }
        catch(Exception $e) {
            return response()->json(['message' => 'Houve um erro ao pesquisar as sessões! '.$e->getMessage()], 403);
        }

    }

    public function jsonShowResources(Request $request){

        $agenda=Agenda::with(['escalas' => function($q){
            // $q->whereRaw("sn_ativo='S'");
             $q->selectRaw(" group_concat(nr_dia separator ',') as nr_dia, hr_inicial,hr_final,cd_agenda  ");
             $q->groupBy('hr_inicial','hr_final','cd_agenda');
         }])->get();
         $valores=null;

         foreach($agenda as $ag){
             $valores['id']=$ag->cd_agenda;
             $valores['title']=$ag->nm_agenda;
             /*
             $valoresbusines=null;
             foreach($ag->escalas as $escala){
                 $dia=explode(',',$escala->nr_dia);
                 $dias = null;
                 foreach($dia as $nr){
                     $dias[]=$nr;
                 }
                 $valoresbusines[]= array('dow'=>$dias,'start'=>$escala->hr_inicial,'end'=>$escala->hr_final);
             }
             $valores['businessHours']=$valoresbusines;
             */
            // $ArrayCompleto[]=$valores;
         }

         $valores['id']='a';
         $valores['title']='fsdfsdf';
         $ArrayCompleto[]=$valores;
         $valores['id']='b';
         $valores['title']='fsdfsdf sadadasds';
         $ArrayCompleto[]=$valores;

         return response()->json($ArrayCompleto);
    }

    public function addProcConta(Request $request)
    {

        $request->validate([
            'proc' => 'required|integer|exists:procedimento,cd_proc',
            'qtde' => 'required|integer',
            'agendamento' => 'required|integer|exists:agendamento,cd_agendamento'
        ]);

        try {

            $this->funcAddConta($request['proc'], $request['qtde'], $request['agendamento']);

            $query = FaturamentoConta::whereRaw('cd_agendamento='.$request['agendamento'])
            ->get();
            return response()->json(['message' => 'Procedimento adicionado com sucesso!','query'=>$query,'request'=>$request->toArray()]);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao altrar o horário. ' . $th->getMessage()], 500);
        }

    }

    function funcAddConta($procCod, $Qtde, $codAgendamento)
    {

        try {

            $Agendamento = RpclinicaAgendamento::find($codAgendamento);
            $TabProc = ProcedimentoConvenio::whereRaw('cd_procedimento='.$procCod)
            ->where('cd_convenio',$Agendamento->cd_convenio)->first();
            if($TabProc){

                $Pac = Paciente::find($Agendamento->cd_paciente);
                $Prof = Profissional::find($Agendamento->cd_profissional);
                $Conv = Convenio::find($Agendamento->cd_convenio);
                $Proced = Procedimento::find($procCod);

                $ArrayConta['cd_conta']=$Agendamento->cd_agendamento;
                $ArrayConta['cd_agendamento']=$Agendamento->cd_agendamento;
                $ArrayConta['cd_atendimento']=$Agendamento->cd_agendamento;
                $ArrayConta['cd_profissional']=$Agendamento->cd_profissional;
                $ArrayConta['nm_profissional']=$Prof->nm_profissional;
                $ArrayConta['cd_prof_exec']=$Agendamento->cd_profissional;
                $ArrayConta['nm_prof_exec']=$Prof->nm_profissional;
                $ArrayConta['cd_convenio']=$Agendamento->cd_convenio;
                $ArrayConta['nm_convenio']=$Conv->nm_convenio;
                $ArrayConta['dt_conta']=$Agendamento->dt_agenda;
                $ArrayConta['cd_paciente']=$Agendamento->cd_paciente;
                $ArrayConta['nm_paciente']=$Pac->nm_paciente;
                $ArrayConta['cd_proc']=$Proced->cd_proc;
                $ArrayConta['cod_proc']=$Proced->cod_proc;
                $ArrayConta['ds_proc']=$Proced->nm_proc;
                $ArrayConta['vl_unitario']=$TabProc->valor;
                $ArrayConta['situacao']='ABERTA';
                $ArrayConta['vl_total']=( ( $TabProc->valor ? $TabProc->valor : 0 ) * ( ($Qtde) ? $Qtde : 1 ) );
                $ArrayConta['qtde']=($Qtde) ? $Qtde : 1;

                FaturamentoConta::create($ArrayConta);

                return true;

            }

        }
        catch (Throwable $error) {

            return false;

        }

    }

    public function recebimento(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'valor' => 'required',
            'desconto' => 'nullable',
            'acrescimo' => 'nullable',
            'agendamento' => 'required|integer|exists:agendamento,cd_agendamento'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }


        try {

            $valor = str_replace('.', '', $request->valor);
            $valor = str_replace(',', '.', $valor);

            $desconto = str_replace('.', '', $request->desconto);
            $desconto = str_replace(',', '.', $desconto);

            $acrescimo = str_replace('.', '', $request->acrescimo);
            $acrescimo = str_replace(',', '.', $acrescimo);

            $dados['recebido']=($valor) ? true : false;
            $dados['valor']=($valor) ? $valor : null;
            $dados['vl_desconto']=($desconto) ? $desconto : null;
            $dados['vl_acrescimo']=($acrescimo) ? $acrescimo : null;
            $dados['usuario_receb']=$request->user()->cd_usuario;
            $dados['dt_receb']=date('Y-m-d H:i');

            $agenda = RpclinicaAgendamento::find($request->agendamento);
            $agenda->update($dados);

            return response()->json(['message' => 'Recebimento realizado com sucesso!','query'=>$agenda]);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao realizar o recebimento. ' . $th->getMessage()], 500);
        }

    }

    public function destroyProcConta($Codigo)
    {

        try {

            $Conta=FaturamentoConta::find($Codigo);
            $Agendamento = $Conta->cd_agendamento;
            $Conta->delete();

            $query = FaturamentoConta::whereRaw('cd_agendamento='.$Agendamento)
            ->get();

            return response()->json(['message' => 'Procedimento excluído com sucesso!','query'=> $query]);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao excluir o Procedimento. ' . $th->getMessage()], 500);
        }

    }

    public function confereProcConta(Request $request){

        try {

            $Conta=FaturamentoConta::find($request['id_conta']);
            if(empty($Conta->sn_confere)){
                $array['sn_confere']='S';
                $array['dt_confere']=date('Y-m-d H:i:s');
                $array['usuario_confere']=$request->user()->cd_usuario;
            }else{
                $array['sn_confere']=null;
                $array['dt_confere']=date('Y-m-d H:i:s');
                $array['usuario_confere']=$request->user()->cd_usuario;
            }
            $Conta->update($array);

            $query = FaturamentoConta::whereRaw('cd_agendamento='.$request['cd_agendamento'])
            ->get();

            return response()->json(['message' => 'Procedimento conferido com sucesso!','query'=> $query]);


        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao excluir o Procedimento. ' . $th->getMessage()], 500);
        }
    }



    public function viewEvento(Request $request){

        return view('rpclinica.recepcao.agendamento.eventos',compact('request'))->render();

    }

    public function viewTipoAtend(Request $request){

        $validator = Validator::make($request->all(), [
            'codigo' => 'required|exists:tipo_atendimento,cd_tipo_atendimento',
            'data' => 'required',
            'hora' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $termino = null;
            $Contas =TipoAtendimento::find($request['codigo']);

            if(isset($Contas->tempo)){
                $intervalo =AgendaIntervalo::find($Contas['tempo']);

                if(isset($intervalo->mn_intervalo)){
                    $data = trim($request['data']).' '.trim($request['hora']);
                    $duracao = $intervalo->mn_intervalo.':00';
                    $v = explode(':', $duracao);
                    $termino =  date('H:i', strtotime("{$data} + {$v[0]} hours {$v[1]} minutes {$v[2]} seconds"));
                }
            }


            return response()->json(['termino' => $termino]);

        }

        catch (Throwable $th) {
            return response()->json(['message' => 'Houve ao calcular o tempo do tipo de atendimento. ' . $th->getMessage()], 500);
        }



    }

    public function escalaManual(Request $request){

        $validator = Validator::make($request->all(), [
            'agenda' => 'required|exists:agenda,cd_agenda',
            'dt_agenda' => 'required|date',
            'hr_inicio' => 'required',
            'hr_fim' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $nr_dia = date('w', strtotime($request['dt_agenda']));
            $cd_dia = CODIGO_DIA_DA_SEMANA[$nr_dia];
            $dados['cd_agenda']=$request['agenda'];
            $dados['cd_dia']=$cd_dia;
            $dados['nr_dia']=$nr_dia;
            $dados['dt_inicial']=$request['dt_agenda'];
            $dados['dt_fim']=$request['dt_agenda'];
            $dados['hr_inicial']=$request['hr_inicio'].':00';
            $dados['hr_final']=$request['hr_fim'].':00';
            $dados['intervalo']=30;
            $dados['nm_intervalo']='00:30:00';
            $dados['situacao']='Aberto';
            $dados['sn_ativo']='S';
            $dados['escala_manual']='S';
            $dados['cd_usuario']=$request->user()->cd_usuario;
            $escala = AgendaEscala::create($dados);

            return response()->json(['escala' => $escala]);
        }

        catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao gravar escala. -->> ' . $th->getMessage()], 500);
        }

    }

    public function destroyEscalaManual($escala){

        try {
            $Escala = AgendaEscala::where('cd_escala_agenda',$escala)->first();
            $Agendamento = RpclinicaAgendamento::where('cd_agenda',$Escala->cd_agenda)
            ->where('dt_agenda',$Escala->dt_inicial)->count();

            if($Agendamento>0){
                return response()->json(['message' => 'Erro! Existe evento cadastrado para essa data.'], 400);
            }
            $retorno = $Escala->delete();
            if($retorno == true){
                return response()->json(['message' => 'Escala exluida com sucesso!']);
            }else{
                return response()->json(['message' => 'Erro ao excluir escala. -->> '], 500);
            }

        }

        catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao excluir escala. -->> ' . $th->getMessage()], 500);
        }

    }

    

    public function storeAgendaRapido(Request $request){


        $validator = Validator::make($request->all(), [
            'agenda' => 'required|exists:agenda,cd_agenda',
            'cd_escala' => 'required|exists:agenda_escala,cd_escala_agenda',
            'convenio' => 'required|exists:convenio,cd_convenio',
            'tp_atendimento' => 'required|exists:tipo_atendimento,cd_tipo_atendimento',
            'local' => 'required|exists:local_atendimento,cd_local',
            'especialidade' => 'required|exists:especialidade,cd_especialidade',
            'profissional' => 'required|exists:profissional,cd_profissional',
            'paciente' => 'required|exists:paciente,cd_paciente',
            'dt_agenda' => 'required|date',
            'hr_inicio' => 'required',
            'hr_fim' => 'required',
            'rg' => 'nullable',
            'cpf' => 'nullable',
            'dt_nasc' => 'required',
            'celular' => 'nullable',
            'email' => 'nullable',
            'cartao' => 'nullable',
            'validade' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            return response()->json(['request' => $request->toArray()]);

        }

        catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao salvar agendamento. -->> ' . $th->getMessage()], 500);
        }

    }


    
    public function envioConfirmacao(Request $request){
        
        $validator = Validator::make($request->all(), [
            'agendamento' => 'required|array',
            'agendamento.*' => 'required|exists:agendamento,cd_agendamento'
        ]); 
        try {
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            return response()->json(['request' => $request->toArray()]);

        } 
        catch (Throwable $th) {
            return response()->json(['message' => 'Erro ao salvar agendamento. -->> ' . $th->getMessage()], 500);
        }
    }




}
