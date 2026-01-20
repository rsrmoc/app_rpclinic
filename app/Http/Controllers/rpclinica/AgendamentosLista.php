<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\app_rpclinic\Agendamento;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda; 
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEscalaHorario;
use App\Model\rpclinica\AgendaExames;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\AgendamentoBloqueio;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\AgendamentoSituacaoLog;
use App\Model\rpclinica\AtendimentoItens;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional; 
use App\Model\rpclinica\TipoAtendimento;  
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\Feriado;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Origem_paciente;
use App\Model\rpclinica\Profissional_externo;
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

class AgendamentosLista extends Controller
{

    public function index(Request $request)
    { 
 
            // dd($this->calculoCalendario('2025-02-24',['86']));
            $parametros['profissionais'] = Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get(); 
            $parametros['local'] = LocalAtendimento::where('sn_ativo','S')->orderBy('nm_local')->get();
            $parametros['tipo'] = TipoAtendimento::where('sn_ativo','S')->orderBy('nm_tipo_atendimento')->get();
            $parametros['convenio'] = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
            $parametros['situacao'] = AgendamentoSituacao::orderBy('nm_situacao')->get();
            $parametros['agenda'] = Agenda::where('sn_ativo','S')->where('sn_agenda_aberta','S')->orderBy('nm_agenda')->get();
            $parametros['confirmar'] = (AgendamentoSituacao::where('sn_ativo','S')->where('confirmar','S')->first())->cd_situacao;
            $parametros['cancelar'] = (AgendamentoSituacao::where('sn_ativo','S')->where('cancelar','S')->first())->cd_situacao;
            $parametros['situacao'] = AgendamentoSituacao::orderBy('nm_situacao')->get();
            $parametros['situacao-agend'] = AgendamentoSituacao::orderBy('nm_situacao')->where('agendamento','S')->get();
            $livre = (AgendamentoSituacao::where('sn_ativo','S')->where('livre','S')->first());
            $parametros['livre'] = $livre->icone.' '.$livre->nm_situacao;
            $parametros['class_livre'] = $livre->class;
            $convenios = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
            $empresa = Empresa::find($request->user()->cd_empresa);
            $request['obriga_cpf']=$empresa->obriga_cpf;
            return view('rpclinica.agendamento-lista.painel', 
                   compact('parametros','request','convenios'));
         
 
    }

    public function addModal(Request $request)
    { 
 
        $validator = Validator::make($request->all(), [  
            'agenda' => 'required|integer|exists:agenda,cd_agenda', 
        ] );
        if ($validator->fails()) { 
            return response()->json(['message' => [$validator->errors()->first()]], 500);
        } 
        try {
            $retorno['agenda']=Agenda::find($request['agenda']);
            if($retorno['agenda']['profissional_editavel'] == true){
                if($retorno['agenda']['cd_profissional']){
                    $retorno['profissional'] = Profissional::
                    where('cd_profissional',$retorno['agenda']['cd_profissional'])
                    ->get();
                }else{
                    $retorno['profissional'] = null;
                } 
            }else{
                $retorno['profissional'] = Profissional::
                where('sn_ativo','S')
                ->orderBy('nm_profissional')
                ->get();
            }

            if($retorno['agenda']['local_atendimento_editavel'] == true){
                if($retorno['agenda']['cd_local_atendimento']){
                    $retorno['local'] = LocalAtendimento::
                    where('cd_local',$retorno['agenda']['cd_local_atendimento'])
                    ->get();
                }else{
                    $retorno['local'] = null;
                } 
            }else{
                $retorno['local'] = LocalAtendimento::
                where('sn_ativo','S')
                ->orderBy('nm_local')
                ->get();
            }
             
            if($retorno['agenda']['tipo_atend_editavel'] == true){
                if($retorno['agenda']['cd_tipo_atend']){
                    $retorno['tipo_atendimento'] = TipoAtendimento::
                    where('cd_tipo_atendimento',$retorno['agenda']['cd_tipo_atend'])
                    ->get();
                }else{
                    $retorno['tipo_atendimento'] = null;
                } 
            }else{
                $retorno['tipo_atendimento'] = TipoAtendimento::
                where('sn_ativo','S')
                ->orderBy('nm_tipo_atendimento')
                ->get();
            }

            if($retorno['agenda']['especialidade_editavel'] == true){
                if($retorno['agenda']['cd_especialidade']){
                    $retorno['especialidade'] = Especialidade::
                    where('cd_especialidade',$retorno['agenda']['cd_especialidade'])
                    ->get();
                }else{
                    $retorno['especialidade'] = null;
                } 
            }else{
                $retorno['especialidade'] = Especialidade::
                where('sn_ativo','S')
                ->orderBy('nm_especialidade')
                ->get();
            }

            $retorno['convenio'] = Convenio::where('sn_ativo','S')
            ->orderBy('nm_convenio')->get();
 
            return response()->json(['request'=>$request->toArray(),'retorno'=>$retorno]);

        }
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
 
    public function dadosModal(Request $request)
    { 
        
        $validator = Validator::make($request->all(), [ 
            'agenda' => 'required|array', 
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda', 
        ] );
        if ($validator->fails()) { 
            return response()->json(['message' => [$validator->errors()->first()]], 500);
        } 
        
        try {
             
            if($request['agenda']['profissional_editavel'] == true){
                if($request['agenda']['cd_profissional']){
                    $retorno['profissional'] = Profissional::
                    where('cd_profissional',$request['agenda']['cd_profissional'])
                    ->get();
                }else{
                    $retorno['profissional'] = null;
                } 
            }else{
                $retorno['profissional'] = Profissional::
                where('sn_ativo','S')
                ->orderBy('nm_profissional')
                ->get();
            }

            if($request['agenda']['local_atendimento_editavel'] == true){
                if($request['agenda']['cd_local_atendimento']){
                    $retorno['local'] = LocalAtendimento::
                    where('cd_local',$request['agenda']['cd_local_atendimento'])
                    ->get();
                }else{
                    $retorno['local'] = null;
                } 
            }else{
                $retorno['local'] = LocalAtendimento::
                where('sn_ativo','S')
                ->orderBy('nm_local')
                ->get();
            }
             
            if($request['agenda']['tipo_atend_editavel'] == true){
                if($request['agenda']['cd_tipo_atend']){
                    $retorno['tipo_atendimento'] = TipoAtendimento::
                    where('cd_tipo_atendimento',$request['agenda']['cd_tipo_atend'])
                    ->get();
                }else{
                    $retorno['tipo_atendimento'] = null;
                } 
            }else{
                $retorno['tipo_atendimento'] = TipoAtendimento::
                where('sn_ativo','S')
                ->orderBy('nm_tipo_atendimento')
                ->get();
            }

            if($request['agenda']['especialidade_editavel'] == true){
                if($request['agenda']['cd_especialidade']){
                    $retorno['especialidade'] = Especialidade::
                    where('cd_especialidade',$request['agenda']['cd_especialidade'])
                    ->get();
                }else{
                    $retorno['especialidade'] = null;
                } 
            }else{
                $retorno['especialidade'] = Especialidade::
                where('sn_ativo','S')
                ->orderBy('nm_especialidade')
                ->get();
            }

            $TpConvenio=null;
            if($request['sn_convenio']==1){ $TpConvenio[]='CO'; }
            if($request['sn_particular']==1){ $TpConvenio[]='PA'; }
            if($request['sn_sus']==1){ $TpConvenio[]='SUS'; }
            if($TpConvenio){
                $retorno['convenio'] = Convenio::whereIn('tp_convenio',$TpConvenio)
                ->where('sn_ativo','S')
                ->orderBy('nm_convenio')->get();
            }else{
                $retorno['convenio'] = null;
            }
            $retorno['convenio'] = Convenio::where('sn_ativo','S')
            ->orderBy('nm_convenio')->get();
            $retorno['itens'] = AgendaExames::where('cd_agenda',$request['cd_agenda'])
            ->with('exames')->get();

            $Itens=null;
          
            if(isset($request['agendamento']['cd_agendamento'])){
                $itens_agendamento = AgendamentoItens::where('cd_agendamento',$request['agendamento']['cd_agendamento'])
                ->select("cd_exame")->get();
                $Itens = null;
                foreach($itens_agendamento as $val){
                    $Itens[] = $val->cd_exame;
                } 
            } 
             
            $retorno['itens_agendamento'] = $Itens;

            $retorno['situacao_recep'] = AgendamentoSituacao::where('agendamento','S')
            ->selectRaw("agendamento_situacao.*,case when checkin='S' then 'Check-in' else nm_situacao end nome_situacao")
            ->orderBy('nm_situacao')->get();

            $retorno['itens'] = AgendaExames::where('cd_agenda',$request['cd_agenda'])
            ->with('exames')->get();

            return $retorno;

        }
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }

    }
     
    public function show(Request $request)
    { 
 
        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d', 
            'agenda' => 'nullable|integer|exists:agenda,cd_agenda', 
            'profissional' => 'nullable|integer|exists:profissional,cd_profissional', 
            'cd_local' => 'nullable|integer|exists:local_atendimento,cd_local', 
            'cd_tipo' => 'nullable|integer|exists:tipo_atendimento,cd_tipo_atendimento',  
            'situacao' => 'nullable|integer|exists:agendamento_situacao,cd_situacao', 
        ] );
        if ($validator->fails()) { 
            return response()->json(['message' => [$validator->errors()->first()]], 500);
        }

        try {

           
            $request['nr_dia']=date('w', strtotime($request['data']));

            $request['query'] = RpclinicaAgendamento::where('dt_agenda',$request['data']) 
            ->with('profissional','especialidade','local','tab_situacao','convenio','tipo_atend','itens.exame','paciente','agenda',
            'user_agendamento','user_atendimento','user_presenca')
            ->selectRaw("agendamento.*,date_format(dt_presenca,'%d/%m/%Y %H:%i') data_presenca,date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento,
            FORMAT(valor, 2, 'de_DE') valor_recebido ");
            if($request->agenda){
                $request['query']=$request['query']->where('cd_agenda',$request->agenda);
            }
            if($request->profissional){
                $request['query']=$request['query']->where('cd_profissional',$request->profissional);
            }
            if($request->cd_local){
                $request['query']=$request['query']->where('cd_local_atendimento',$request->cd_local);
            }
            if($request->cd_tipo){
                $request['query']=$request['query']->where('tipo',$request->cd_tipo);
            }
            if($request->situacao){
                $request['query']=$request['query']->where('situacao',$request->situacao);
            }
            $request['query']=$request['query']->orderBy("hr_agenda")->get(); 

           
            
            if($request['data']){
                $header=RpclinicaAgendamento::where('dt_agenda',$request['data'])
                ->join('agendamento_situacao','agendamento.situacao','agendamento_situacao.cd_situacao')
                ->selectRaw("sum(case when checkin='S' then 1 else 0 end) q_checkin, sum(case when confirmar='S' then 1 else 0 end) q_confirmar,
                sum(case when atender='S' then 1 else 0 end) q_atender, sum(case when agendar='S' then 1 else 0 end) q_agendar");

                if($request->agenda){
                    $header=$header->where('agendamento.cd_agenda',$request->agenda);
                }
                if($request->profissional){
                    $header=$header->where('agendamento.cd_profissional',$request->profissional);
                }
                if($request->cd_local){
                    $header=$header->where('agendamento.cd_local_atendimento',$request->cd_local);
                }
                if($request->cd_tipo){
                    $header=$header->where('agendamento.tipo',$request->cd_tipo);
                }
                if($request->situacao){
                    $header=$header->where('agendamento.situacao',$request->situacao);
                }
                
                $header=$header->first();
                 
                
                $request['header'] = array(
                    'agendado'=> ( isset($header->q_agendar) ) ? $header->q_agendar : 0,
                    'confirmado'=> ( isset($header->q_confirmar) ) ? $header->q_confirmar : 0,
                    'aguardando'=> ( isset($header->q_checkin) ) ? $header->q_checkin : 0,
                    'atendido'=> ( isset($header->q_atender) ) ? $header->q_atender : 0,

                ); 
                 
            }else{
                $request['header']=[];
                $request['calendario']=[];
            }
 
            $request['feriado'] = Feriado::where('dt_feriado',$request['data'])
            ->where('sn_bloqueado','S')->first();

            return response()->json($request->toArray());
         
        }
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
    }

 
    public function StoreAgendamento(Request $request)
    {
          
        try { 
   
            $empresa = Empresa::find($request->user()->cd_empresa);
            $validaCpf=$empresa->valida_cpf;
            $obrigaCpf=$empresa->obriga_cpf;

            if(($request->dt_agenda)&&($request->hr_inicio)&&($request->hr_fim)){
                $request['data_horario']=date('Y-m-d H:i',strtotime(trim($request->dt_agenda).' '.trim($request->hr_inicio)));
                $request['cd_dia']=date('w', strtotime($request->dt_agenda));

                $inicio = $request->hr_inicio.':00';
                $fim = $request->hr_fim.':00';
                
                $inicio = DateTime::createFromFormat('H:i:s', $inicio); 
                $fim = DateTime::createFromFormat('H:i:s', $fim); 
                $intervalo = $inicio->diff($fim);

                $request['nm_intervalo']= $intervalo->format('%H:%I:%S');   

            }
             
            $validator = Validator::make($request->all(),[ 
                    'cd_horario' => 'nullable|integer|exists:agenda_escala_horario,cd_agenda_escala_horario',
                    'cd_escala' => 'nullable|integer|exists:agenda_escala,cd_escala_agenda',
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
                    'cd_dia' => 'required|integer',
                    //'intervalo' => 'required|integer',
                    'nm_intervalo' => 'required|date_format:H:i:s',
                    'data_horario' => 'required|date_format:Y-m-d H:i',
                    'email' => 'nullable|email',
                    'celular' => 'nullable',
                    'cartao' => 'nullable|string',
                    'validade' => 'nullable|string',
                    'rg' => 'nullable|string',
                    'cpf' => 'nullable|string',
                    'dt_nasc' => 'nullable|date_format:Y-m-d',
                    'obs' => 'nullable|string|max:255',
                    'sms' => 'sometimes|boolean',
                    'whatsapp' => 'sometimes|boolean',
                    'cd_agendamento' => 'nullable|integer|exists:agendamento,cd_agendamento',
                    'item_agendamento' => 'nullable|array|min:1|exists:exames,cd_exame' 
            ] , [
                    'cd_dia.required' => 'Dia não informado.',
                    'cd_dia.integer' => 'Dia tem quer integer.',
                    'dt_agenda.required' => 'Data da Agenda não informada.',
                    'dt_agenda.date_format' => 'Data da Agenda não informada.',
                    'hr_inicio.date_format' => 'Data da Agenda não informada.',
                    'hr_inicio.required' => 'Data da Agenda não informada.',
                    'hr_fim.date_format' => 'Data da Agenda não informada.',
                    'hr_fim.required' => 'Data da Agenda não informada.',
                    'cd_especialidade.required' => 'Especialidade não informada.',
                    'cd_especialidade.integer' => 'Especialidade não informada.',
                    'cd_especialidade.exists' => 'Especialidade não informada.',
            ] 
            );
 
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()], 400);
            }

            if(($validaCpf=='sim') && ($request['cpf'])){
                if(HelperValidaCPF($request['cpf'])==false){
                    return response()->json(['errors' => ['CPF Invalido!']], 400);
                }
            }

            if(($obrigaCpf=='sim') && (!trim($request['cpf']))){
                return response()->json(['errors' => ['CPF não informado!']], 400);
            }
 
            $Feriado = Feriado::where('dt_feriado',$request['dt_agenda'])
            ->where('sn_bloqueado','S')->first();
            if(isset($Feriado->nm_feriado)){
                return response()->json(['errors' => ['Atenção!<br>Data Bloqueada.<br><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span> <b>Feriado: '.$Feriado->nm_feriado.'</b>']], 400);
            }

            
            $bloqueios = AgendamentoBloqueio::where('cd_profissional',$request['cd_profissional'])
            ->whereRaw("'".$request['dt_agenda']."' between dt_inicio and dt_final")->count();
            if($bloqueios>0){
                return response()->json(['errors' => ['Atenção!<br>Data bloqueada para esse Profissional.']], 400);
            }

            if(trim($request['cpf'])){
                $NrCpf=Paciente::whereRaw( "REGEXP_REPLACE(cpf,'(([^0-9]))', '') = '". preg_replace('/[^0-9]/', '',$request->cpf ) ."'")
                ->where('cd_paciente','<>',$request->cd_paciente)
                ->count();

                 
                if($NrCpf>0){
                    $Texto="";
                    $pac=Paciente::whereRaw( "REGEXP_REPLACE(cpf,'(([^0-9]))', '') = '". preg_replace('/[^0-9]/', '',$request->cpf ) ."'")
                    ->where('cd_paciente','<>',$request->cd_paciente)
                    ->get();
                    foreach($pac as $val){
                        $Texto = $Texto . "<br><b>[ Codigo: " . $val->cd_paciente . " ]</b> - ". $val->nm_paciente . " [ CPF: ".$val->cpf." ]";
                    }

                    return response()->json(['errors' => ['Esse CPF já esta cadastrado no Sistema!'.$Texto ]], 400);
                }
            }
 
            
            DB::beginTransaction();
  
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
    
                if ($request->validade) {
                    $paciente->dt_validade = $request->validade;
                    $paciente->save();
                }
    
                if ($request->rg) {
                    $paciente->rg = $request->rg;
                    $paciente->save();
                }
    
                if ($request->cpf) {
                    $paciente->cpf = preg_replace('/[^0-9]/', '',$request->cpf ); 
                    $paciente->save();
                }
    
                if ($request->email) {
                    $paciente->email = $request->email;
                    $paciente->save();
                }
    
                if ($request->celular) {
                    $paciente->celular = preg_replace('/[^0-9]/', '',$request->celular );
                    $paciente->save();
                }
                if ($request->profissao) {
                    $paciente->profissao = $request->profissao;
                    $paciente->save();
                }
                if ($request->sexo) {
                    $paciente->sexo = $request->sexo;
                    $paciente->save();
                }
                if ($request->nm_mae) {
                    $paciente->nm_mae = $request->nm_mae;
                    $paciente->save();
                }
                if ($request->dt_nasc_mae) {
                    $paciente->dt_nasc_mae = $request->dt_nasc_mae;
                    $paciente->save();
                }
                if ($request->celular_mae) {
                    $paciente->celular_mae = $request->celular_mae;
                    $paciente->save();
                }
                if ($request->nm_pai) {
                    $paciente->nm_pai = $request->nm_pai;
                    $paciente->save();
                }
                if ($request->dt_nasc_pai) {
                    $paciente->dt_nasc_pai = $request->dt_nasc_pai;
                    $paciente->save();
                }
                if ($request->celular_pai) {
                    $paciente->celular_pai = $request->celular_pai;
                    $paciente->save();
                }
     
                $validated['cd_paciente'] = $paciente->cd_paciente;
            }

            $Convenio = Convenio::find($request->cd_convenio);

            $array['cd_agenda'] = $request->cd_agenda;
            $array['cd_escala'] = (isset($request->cd_escala)) ? $request->cd_escala :null;
            $array['cd_agenda_escala_horario'] = (isset($request->cd_horario)) ? $request->cd_horario :null;
            $array['dt_agenda'] = $request->dt_agenda;
            $array['hr_agenda'] = $request->hr_inicio;
            $array['hr_final'] = $request->hr_fim;
            $array['intervalo'] = $request->nm_intervalo;
            $array['dia_semana'] = date('w', strtotime($request->dt_agenda));  
            $array['cd_paciente'] = $validated['cd_paciente'];
            $array['cd_convenio'] = $request->cd_convenio;
            $array['data_horario'] = trim($request->dt_agenda).' '.trim($request->hr_inicio);
            $array['email'] = $request->email;
            $array['celular'] = $request->celular;
            $array['whast'] = null;
            $array['obs'] = $request->obs;
            $array['tipo'] = $request->tipo;
            $array['cd_profissional'] = $request->cd_profissional;
            $array['cd_especialidade'] = $request->cd_especialidade;
            $array['recebido'] = ($request['recebido']) ? $request['recebido'] : null;
            $array['valor'] = ($request['valor']) ? ( str_replace(',', '.', str_replace('.', '', $request['valor']) ) ) : null;

            if($Convenio->tp_conveino=='PA'){
                $array['cartao'] = null;
            }else{
                $array['cartao'] = $request->cartao;
            }
             
            $array['dt_validade'] = $request->validade;
            $array['cd_local_atendimento'] = $request->cd_local_atendimento;
            $array['usuario_geracao']= $request->user()->cd_usuario;
            $array['sn_atendimento'] = 'N';
 
            if(empty($request['cd_agendamento'])){

                if(!$request['cd_horario']){
                    $array['sn_atend_avulso'] = 'S';
                }
                $situacao = AgendamentoSituacao::where('agendar','S')->first(); 
                if(!isset($situacao['cd_situacao'])){
                    return response()->json(['errors' => array('O sistema não esta configurado para realizar agendamento!')], 500);
                }
                
                if($request->cd_horario){
                    $ret=RpclinicaAgendamento::where('cd_agenda_escala_horario',$request->cd_horario)
                    ->where('dt_agenda',$request->cd_agenda)->where('cd_escala',$request->cd_escala)
                    ->count();
                    if($ret > 0){
                        $desc=RpclinicaAgendamento::where('cd_agenda_escala_horario',$request->cd_horario)
                        ->with('paciente')
                        ->where('dt_agenda',$request->cd_agenda)->where('cd_escala',$request->cd_escala)
                        ->whereRaw("cd_agendamento <> '".$request['cd_agendamento']."'")->first();

                        $paciente= $desc->paciente->nm_paciente.' { '.$desc->hr_agenda.' } [ '.$desc->agendamento . ' ] ';
                        return response()->json(['errors' => array('Já existe paciente cadastrado para esse Horario!<br>'.$paciente)], 500);
                    }
                }

                $array['situacao'] = $situacao->cd_situacao; 
                $Agendamento = RpclinicaAgendamento::create($array);  
                $Agendamento->saveLog($request->user(),'Cadastro',null,'agendamento','agendamento',
                array( 'agendamento'=>$request['cd_agendamento'],'paciente'=>$array['cd_paciente'],'agendamento'=>null,'exame'=>null ) ,$_SERVER['REQUEST_URI']);

                AgendamentoSituacaoLog::create([
                    'cd_agendamento'=>$Agendamento->cd_agendamento,
                    'situacao'=>$situacao->cd_situacao,
                    'cd_usuario'=>$request->user()->cd_usuario,
                ]);
                
                if(isset($request->item_agendamento))
                foreach($request->item_agendamento as $exames){

                    $exame =Exame::find($exames);
                    if(isset($exame->cod_proc)){
                        $valor = valorContaFaturamento($request->cd_convenio,$exame->cod_proc);
                    }else{
                        $valor = null;
                    } 
                    AgendamentoItens::create([
                        'cd_agendamento'=>$Agendamento['cd_agendamento'],
                        'cd_exame'=>$exames,
                        'vl_item'=>$valor,
                        'sn_anomalia'=> ($valor) ?  'N' : 'S',
                        'dt_valor'=>date('Y-m-d H:i'),
                        'usuario_valor'=>$request->user()->cd_usuario,
                        'cd_usuario'=>$request->user()->cd_usuario
                    ]);
                }

                $request['ds_retorno'] = 'Agendamento Cadastrado com sucesso!'; 

            }else{ 
                  
                if($request->cd_horario){
                    $ret=RpclinicaAgendamento::where('cd_agenda_escala_horario',$request->cd_horario)
                    ->where('dt_agenda',$request->cd_agenda)->where('cd_escala',$request->cd_escala)
                    ->whereRaw("cd_agendamento <> '".$request['cd_agendamento']."'")->count();
                    if($ret > 0){
                        $desc=RpclinicaAgendamento::where('cd_agenda_escala_horario',$request->cd_horario)
                        ->with('paciente')
                        ->where('dt_agenda',$request->cd_agenda)->where('cd_escala',$request->cd_escala)
                        ->whereRaw("cd_agendamento <> '".$request['cd_agendamento']."'");

                        $paciente= $desc->paciente->nm_paciente.' { '.$desc->hr_agenda.' } [ '.$desc->agendamento . ' ] ';
                        return response()->json(['errors' => array('Já existe paciente cadastrado para esse Horario!<br>'.$paciente)], 500);
                    }
                }

                $Agendamento = RpclinicaAgendamento::find($request['cd_agendamento']);
                $Agendamento->update($array);
                $Agendamento->saveLog($request->user(),'Edicao',null,'agendamento','agendamento',
                                      array( 'agendamento'=>$request['agendamento'],'paciente'=>$array['cd_paciente'],'agendamento'=>null,'exame'=>null ) ,$_SERVER['REQUEST_URI']);
                
                $arraySalvo = null;
                if(isset($request->item_agendamento))
                    foreach($request->item_agendamento as $item){
                        $arraySalvo[]=$item;
                    }  

                if($arraySalvo){ 
                    AgendamentoItens::where('cd_agendamento',$request['cd_agendamento'])->whereNotIn('cd_exame',$arraySalvo)->delete();
                }

                if(isset($request->item_agendamento))
                    foreach($request->item_agendamento as $exames){
                        $exame =Exame::find($exames);
                        if(isset($exame->cod_proc)){
                            $valor = valorContaFaturamento($request->cd_convenio,$exame->cod_proc);
                        }else{
                            $valor = null;
                        } 
                    
                        AgendamentoItens::updateOrCreate(
                            ['cd_agendamento'=>$request['cd_agendamento'],'cd_exame'=> trim($exames)],
                            [
                                'cd_agendamento'=>$Agendamento['cd_agendamento'],
                                'cd_exame'=>trim($exames),
                                'vl_item'=>$valor,
                                'sn_anomalia'=> ($valor) ?  'N' : 'S',
                                'dt_valor'=>date('Y-m-d H:i'),
                                'usuario_valor'=>$request->user()->cd_usuario,
                                'cd_usuario'=>$request->user()->cd_usuario
                            ]);
                    }
                 

                $request['ds_retorno'] = 'Agendamento Atualizado com sucesso!'; 

            }
            
            $request['agendamento'] = $Agendamento->toArray();
            $request['retorno'] = true; 
            DB::commit();

            return $request->toArray();
           

        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
        
         
    }
 
    public function calculoCalendario($data,$agenda)
    { 
            $retorno=null;
            $dt=explode('-',$data);
            $nr_dia=date('w', strtotime($dt[2].'-'.$dt[1].'-'.$dt[0]));
            $ultimoDia = date("t", mktime(0,0,0, $dt[1],'01', $dt[0])); 
            $dti=$dt[0].'-'.$dt[1].'-01';
            $dtf=$dt[0].'-'.$dt[1].'-'.$ultimoDia;
            $DIA=$dt[2];
            $COMP=$dt[1].'-'.$dt[0];
            if($dti < date('Y-m-d')){ 
                if($dtf < date('Y-m-d')){
                    $Pdti=null; 
                    $PdiaI=0; 
                }else{
                    $Pdti=date('Y-m-d'); 
                    $PdiaI=date('d'); 
                }

            }else{ 
                $Pdti=$dti; 
                $PdiaI=explode('-',$dti)[2]; 
            }
 
            if($dtf < date('Y-m-d')){ $Pdtf=null; $PdiaF=0; }else{ $Pdtf=$dtf; $PdiaF=explode('-',$dtf)[2]; }
 
            if(($Pdti) and ($Pdtf)){

                $agendamentos=RpclinicaAgendamento::where('dt_agenda','>=',$Pdti)->where('dt_agenda','<=',$Pdtf)
                ->join("agendamento_situacao","agendamento.situacao","agendamento_situacao.cd_situacao")
                ->join("agenda_escala_horario","agendamento.cd_agenda_escala_horario","agenda_escala_horario.cd_agenda_escala_horario")
                ->whereIn("agenda_escala_horario.cd_agenda",$agenda)
                ->selectRaw("dt_agenda,date_format(dt_agenda,'d') dia, 
                sum(case when status_agendamento = 'agendado' then 1 else 0 end) qtde_agendado, 
                sum(case when status_agendamento = 'confirmado' then 1 else 0 end) qtde_confirmado,
                sum(case when status_agendamento = 'cancelado' then 1 else 0 end) qtde_cancelado,
                count(*) qtde")
                ->groupByRaw("dt_agenda,date_format(dt_agenda,'d')")->get();
            
                $agen=null;
                foreach($agendamentos as $val){
                    $dt=explode('-',$val->dt_agenda);
                    $nr=date('w', strtotime($dt[2].'-'.$dt[1].'-'.$dt[0]));
                    $agen[$dt[2]]=array('nr_dia'=>$nr,'dt_agenda'=>$val->dt_agenda,'qtde'=>$val->qtde,
                                                                                'qtde_agendado'=>$val->qtde_agendado,
                                                                                'qtde_confirmado'=>$val->qtde_confirmado,
                                                                                'qtde_cancelado'=>$val->qtde_cancelado);
                }
                
                $Horarios=AgendaEscala::whereIn("agenda_escala.cd_agenda",$agenda)->whereRaw("agenda_escala.sn_ativo='S'")
                ->join("agenda_escala_horario","agenda_escala.cd_escala_agenda","agenda_escala_horario.cd_escala_agenda")
                ->selectRaw("nr_dia,
                    count(*) qtde")
                ->groupByRaw("nr_dia")->get();
             
                $dias=null;
                foreach($Horarios as $val){
                    $dias[$val->nr_dia]=array('nr_dia'=>$val->nr_dia,'qtde'=>$val->qtde);
                }

                for ($x = $PdiaI; $x <= $PdiaF; $x++) {
                    $nr=date('w', strtotime($x.'-'.$COMP));
                    $totalAgenda= (isset($dias[$nr]['qtde'])) ? $dias[$nr]['qtde'] : 0 ;
                    $totalAgendado= (isset($agen[$x]['qtde'])) ? $agen[$x]['qtde'] : 0;
                    $totalCancelado= (isset($agen[$x]['qtde_cancelado'])) ? $agen[$x]['qtde_cancelado'] : 0;
                    $totalCancelado=($totalCancelado) ? $totalCancelado : 0;
                    if($totalAgenda>0){
                        
                        $Total=$totalAgenda-($totalAgendado-$totalCancelado);

                        if($Total>0){ $class='text-success';}else{ $class='text-danger'; }   
                        $retorno['calendario'][str_pad($x , 2 , '0' , STR_PAD_LEFT)]=$class; 
                    }   
                }


            }else{
                $retorno['calendario']=null;
            }

            if($data>=date('Y-m-d')){
                
                $retorno['header']['total']=(isset($dias[$nr_dia]['qtde'])) ? $dias[$nr_dia]['qtde'] : 0;
                $retorno['header']['agendado']=(isset($agen[$DIA]['qtde_agendado'])) ? $agen[$DIA]['qtde_agendado'] : 0;
                $retorno['header']['confirmado']=(isset($agen[$DIA]['qtde_confirmado'])) ? $agen[$DIA]['qtde_confirmado'] : 0;
                $retorno['header']['cancelado']=(isset($agen[$DIA]['qtde_cancelado'])) ? $agen[$DIA]['qtde_cancelado'] : 0;    
                
            } else {

                $agendamentos=RpclinicaAgendamento::where('dt_agenda',$data)
                ->join("agendamento_situacao","agendamento.situacao","agendamento_situacao.cd_situacao")
                ->join("agenda_escala_horario","agendamento.cd_agenda_escala_horario","agenda_escala_horario.cd_agenda_escala_horario")
                ->whereIn("agenda_escala_horario.cd_agenda",$agenda)
                ->selectRaw("status_agendamento,dt_agenda,date_format(dt_agenda,'d') dia, 
                sum(case when status_agendamento = 'agendado' then 1 else 0 end) qtde_agendado, 
                sum(case when status_agendamento = 'confirmado' then 1 else 0 end) qtde_confirmado,
                sum(case when status_agendamento = 'cancelado' then 1 else 0 end) qtde_cancelado,
                count(*) qtde")
                ->groupByRaw("status_agendamento,dt_agenda,date_format(dt_agenda,'d')")->first();

                $retorno['header']['total']=' -- ';
                $retorno['header']['agendado']=(isset($agendamentos->qtde_agendado)) ? $agendamentos->qtde_agendado : 0;
                $retorno['header']['confirmado']=(isset($agendamentos->qtde_confirmado)) ? $agendamentos->qtde_confirmado : 0;
                $retorno['header']['cancelado']=(isset($agendamentos->qtde_cancelado)) ? $agendamentos->qtde_cancelado : 0; 
            }                           
         
            return $retorno;

    }

 
    public function updateStatus(Request $request){

 
        $validator = Validator::make($request->all(), [
            'cd_agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            if($request['status']=='CA'){ 
                $status = AgendamentoSituacao::where('cancelar','S')->first();
                if(!$status){
                    return response()->json(['message' => 'Situação não configurada para essa ação!'], 400);
                }
                $Array['situacao']=$status->cd_situacao;
            }
             
            if($request['status']=='CO'){ 
                $status = AgendamentoSituacao::where('confirmar','S')->first();
                if(!$status){
                    return response()->json(['message' => 'Situação não configurada para essa ação!'], 400);
                }
                $Array['situacao']=$status->cd_situacao;
            }
           
            $agendamento = RpclinicaAgendamento::find($request['cd_agendamento']);
            if($agendamento->situacao == $Array['situacao']){
                return response()->json(['message' =>'Ação ja realizada!'], 400);
            } 

            $query=RpclinicaAgendamento::where('cd_agendamento',$request['cd_agendamento'])
            ->update($Array);
  
 
            AgendamentoSituacaoLog::create([
                'cd_agendamento'=>$request['cd_agendamento'],
                'situacao'=>$Array['situacao'],
                'cd_usuario'=>$request->user()->cd_usuario,
            ]);

            $retorno=RpclinicaAgendamento::find($request['cd_agendamento']);
            if($retorno->dt_presenca){
                $retorno['retorno_presenca']= '[ Foi confirmado a presença do paciente as ( <b> '. $retorno->dt_presenca.' </b> ) ] ';
            }else{
                $retorno['retorno_presenca']=null;
            }

            funcLogsAtendimentoHelpers($request['cd_agendamento'],'USUARIO ALTEROU STATUS AGENDAMENTO');

            return response()->json(['message' => 'Agendamento atualizado com sucesso!','query'=> $query, 'retorno'=> $retorno]);
             
        }

        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao excluir o Procedimento. ' . $th->getMessage()], 500);
        }
         
    }
 
    
    public function getBloqueio(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            'profissional' => 'required|integer|exists:profissional,cd_profissional' 
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try { 

            $retorno=AgendamentoBloqueio::whereRaw("cd_profissional=".$request['profissional'])
            ->selectRaw("agendamento_bloqueio.*,date_format(dt_inicio,'%d/%m/%Y') data_inicio,date_format(dt_final,'%d/%m/%Y') data_final")
            ->with('tab_profissional')
            ->orderByRaw("dt_inicio desc")
            ->limit(50)
            ->get();

            return response()->json(['request'=> $request->toArray(),'retorno'=>$retorno->toArray()]);
            
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
        
         
    }

    
    public function storeBloqueio(Request $request)
    {
         
        $validator = Validator::make($request->all(), [
            'profissional' => 'required|integer|exists:profissional,cd_profissional',
            'dti' => 'required|date_format:Y-m-d',
            'dtf' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try { 

            DB::beginTransaction();

            AgendamentoBloqueio::create([
                'cd_profissional'=>$request['profissional'],
                'dt_inicio'=>$request['dti'],
                'dt_final'=>$request['dtf'],
                'cd_usuario'=>$request->user()->cd_usuario
            ]);

            DB::commit();

            $retorno=AgendamentoBloqueio::whereRaw("cd_profissional=".$request['profissional'])
            ->selectRaw("agendamento_bloqueio.*,date_format(dt_inicio,'%d/%m/%Y') data_inicio,date_format(dt_final,'%d/%m/%Y') data_final")
            ->with('tab_profissional')
            ->orderByRaw("dt_inicio desc")
            ->limit(50)
            ->get();

            return response()->json(['request'=> $request->toArray(),'retorno'=>$retorno->toArray()]);
            
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
         
    }
     
    public function deleteBloqueio(Request $request, AgendamentoBloqueio $bloqueio)
    {
           
        try { 
            $profissional=$bloqueio->cd_profissional;

            DB::beginTransaction(); 
            $bloqueio->delete(); 
            DB::commit();

            $retorno=AgendamentoBloqueio::whereRaw("cd_profissional=".$profissional)
            ->selectRaw("agendamento_bloqueio.*,date_format(dt_inicio,'%d/%m/%Y') data_inicio,date_format(dt_final,'%d/%m/%Y') data_final")
            ->with('tab_profissional')
            ->orderByRaw("dt_inicio desc")
            ->limit(50)
            ->get();

            return response()->json(['request'=> $request->toArray(),'retorno'=>$retorno->toArray()]);
            
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
        
         
    }

}
