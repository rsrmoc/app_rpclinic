<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\AgendaConvenios;
use App\Model\rpclinica\AgendaEspecialidades;
use App\Model\rpclinica\AgendaLocais;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Agendamento extends Controller
{
    public function index()
    {

  

        $agendas = Agenda::get();
        $profissionais = Profissional::all();
        $especialidades = Especialidade::all();
        $convenios = Convenio::all();
        $procedimentos = Procedimento::all();
        $localAtendimentos = LocalAtendimento::all();
        $procedimentosProfissional = Auth::user()->profissional?->procedimentos;
        $api_zap = WhastApi::find('WHAST');
        $ApiZap=$api_zap->sn_whast;
        return view('rpclinica.agendamento.calendario', compact(
            'agendas',
            'profissionais',
            'especialidades',
            'procedimentos',
            'convenios',
            'localAtendimentos',
            'procedimentosProfissional',
            'ApiZap'
        ));
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
        } , 'paciente', 'profissional', 'especialidade', 'procedimento', 'local', 'cid', 'documentos', 'convenio'])
            ->selectRaw("agendamento.*,DATE_FORMAT(data_horario, '%d/%m/%Y %H:%i') dt_hr,
            concat(' [ ',DATE_FORMAT(dt_presenca, '%d/%m/%Y %H:%i'),' ] ') dt_pres,
            DATE_FORMAT(dt_presenca, '%H:%i') hr_pres,
            ifnull(situacao_atende.sn_atende,'N') sn_atende, ifnull(situacao_atende.sn_prontuario,'N') sn_prontuario,
            concat(' [ Data do retorno: ',DATE_FORMAT(dt_resp_whast, '%d/%m/%Y %H:%i'),' - ',ds_whast,' ( <b>',whast_resp,'</b> ) ] ') retorno_whast ")
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
            'celular' => 'required|celular_com_ddd',
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

        if(empty($validated['sn_presenca'])){ 
            $validated['sn_presenca']=null; 
            $validated['dt_presenca']=null;    
        }else{
            if($DadosAgendamento['sn_presenca']==null){  
                $validated['dt_presenca']=date('Y-m-d H:i');  
                $validated['user_presenca']=$request->user()->cd_usuario;   
                $validated['situacao']='aguardando'; 
            } 
        }
        
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
            $agendamendo->update($agendamento);

            return response()->json(['message' => 'Horário excluído com sucesso!']);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao excluir o horário.' . $e->getMessage()], 500);
        }
    }

    public function bloquearHorario(Request $request)
    {
        $request->validate([
            'cd_agendamento' => 'required|integer|exists:agendamento,cd_agendamento'
        ]);

        try {
            $agendamendo = RpclinicaAgendamento::find($request->cd_agendamento);

            if ($agendamendo->situacao == 'bloqueado') {
                return response()->json(['message' => 'Horário já está bloqueado!'], 403);
            }

            if ($agendamendo->situacao != 'livre') {
                return response()->json(['message' => 'Você não pode bloquear este horário!'], 403);
            }

            $agendamendo->update([ 'situacao' => 'bloqueado' ]);

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


    public function jsonTeste(Request $request) {

        $agendamentos = RpclinicaAgendamento::with(['agenda', 'paciente', 'profissional', 'especialidade', 'procedimento', 'local' , 'cid', 'documentos', 'convenio'])
        ->selectRaw("agendamento.*,DATE_FORMAT(data_horario, '%d/%m/%Y %H:%i') dt_hr,
        ifnull(situacao_atende.sn_atende,'N') sn_atende")
        ->where('dt_agenda', '2023-04-28')
        ->leftJoin('situacao_atende','situacao_atende.cd_situacao_atend','agendamento.situacao')
        ->leftJoin('agenda','agenda.cd_agenda','agendamento.cd_agenda')
        ->leftJoin('local_atendimento','local_atendimento.cd_local','agenda.cd_local_atendimento')
        ->leftJoin('profissional','profissional.cd_profissional','agenda.cd_profissional')
        ->leftJoin('procedimento','procedimento.cd_proc','agenda.cd_proc')
        ->leftJoin('especialidade','especialidade.cd_especialidade','agenda.cd_especialidade')
        ->whereRaw('agendamento.cd_agenda=17')
        ->orderBy('data_horario')->get();
        dd($agendamentos->toArray());

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
                        "to" => ( $agendamento->celular ) ?  '55'.whast_formatPhone($agendamento->celular) : '0000000000' ,
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

}
