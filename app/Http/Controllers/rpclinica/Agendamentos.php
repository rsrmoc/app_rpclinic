<?php

namespace App\Http\Controllers\rpclinica;

use App\Bibliotecas\ApiWaMe;
use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda; 
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEscalaHorario;
use App\Model\rpclinica\AgendaExames;
use App\Model\rpclinica\AgendaIntervalo;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
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
use App\Bibliotecas\apiAgendamento;
use App\Model\rpclinica\AgendamentoAnexos;
use App\Model\rpclinica\AgendamentoDocumentos;

class Agendamentos extends Controller
{

    public function index(Request $request)
    { 
    
 
        $user=Usuario::find($request->user()->cd_usuario);
        $user->load('perfil');  
 
        if($user->perfil?->tp_agenda=='lista'){
            return redirect()->to('/rpclinica/agendamentos-lista');
        }
                    
        $request['edita_horario']=$user->perfil?->ag_editar_horario;
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

                $itens = $request['relacaoAgendas']; 
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

                $horariosPadrao = AgendaEscala::where("sn_ativo","S")
                ->whereIn('cd_agenda',$request['relacaoAgendas'])->min('nm_intervalo'); 

            } else {
                $request['relacaoAgendas'] = array();

                $itens = $request->user()->agendamento_agendas;
                $itens = explode(',',$itens);
                $horariosPadrao = AgendaEscala::where("sn_ativo","S")
                ->whereIn('cd_agenda',$itens)->min('nm_intervalo'); 

            }

        }else{ 
            $itens = $request->user()->agendamento_agendas;
            $itens = explode(',',$itens);
            $request['relacaoAgendas']=$itens;
            $request['intervalo']=($request->user()->campos_intervalo) ? $request->user()->campos_intervalo : '00:10';
            $AgendaItens = $request->user()->agendamento_agendas;

            $horariosPadrao = AgendaEscala::where("sn_ativo","S")
            ->whereIn('cd_agenda',$itens)->min('nm_intervalo'); 

        }

        $selectAgenda=null; 
        foreach($itens as $item){
            $selectAgenda[$item]=$item;
        }

        $resources = array();
        $businessHours = array();
        if(isset($request['relacaoAgendas'])){
            if(count($request['relacaoAgendas'])>0){

                $agenda = Agenda::with(['escalas'=> function($q){
                    $q->whereRaw("sn_ativo='S'")
                    ->whereNull('escala_manual'); 
                }])->whereRaw(" sn_ativo = 'S'")
                ->whereIn('cd_agenda',$request['relacaoAgendas'])
                ->get();
                $businessHours=null;  
                foreach($agenda as $idx => $row){
                    $Dia=null; 
                    foreach($row->escalas as $escala){
                        $Dia=null;
                        $Dia=array( $escala->nr_dia );
                        $businessHours[] = array('dow'=>$Dia,'start' => $escala->hr_inicial,'end' => $escala->hr_final,'resources' => $escala->cd_agenda);
                    } 
                }
  
                $Intervalo=600;
                $resource=null;
                foreach($agenda as $row){
                    $resource=array(
                        'id' => $row->cd_agenda,
                        'title' => $row->nm_agenda,
                    );
                    $business =null;
                    foreach($row->escalas as $escala){
                      
                        $business[]=array(
                            'dow' => array($escala->nr_dia),
                            'start' => $escala->hr_inicial,
                            'end' => $escala->hr_final
                        );
                    }
                    $resource['businessHours']=$business;
                    $resources[] = $resource;
                }  
            }
        }

        if($request['intervalo_campo']){ $horariosPadrao = $request['intervalo_campo']; }else{ $horariosPadrao = '00:15:00'; }
 
         
        $Horarios = AgendaEscala::selectRaw("distinct(substring(nm_intervalo,1,5))  intervalo, nm_intervalo")
        ->where("sn_ativo","S")->orderByRaw("1")->get(); 
        $Contas = ContaBancaria::whereRaw("sn_ativo='S'")->orderBy('nm_conta')->get();
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
        $exames = Exame::whereRaw("sn_ativo='S'")->orderBy("nm_exame")->get();
        $situacoes = AgendamentoSituacao::orderBy('nm_situacao')->get();
        $empresa = Empresa::find($request->user()->cd_empresa);
        $DiasAgenda=null;
        
        if(!$empresa->segunda){ $DiasAgenda[] = 1; } 
        if(!$empresa->terca){ $DiasAgenda[] = 2; } 
        if(!$empresa->quarta){ $DiasAgenda[] = 3; } 
        if(!$empresa->quinta){ $DiasAgenda[] = 4; } 
        if(!$empresa->sexta){ $DiasAgenda[] = 5; } 
        if(!$empresa->sabado){ $DiasAgenda[] = 6; } 
        if(!$empresa->domingo){ $DiasAgenda[] = 0; } 

        //dd($DiasAgenda,$request->user()->cd_empresa);

        $request['obriga_cpf']=$empresa->obriga_cpf;
        $intervalos = AgendaIntervalo::orderBy('mn_intervalo')->get(); 
        
        return view('rpclinica.recepcao.agendamento.agenda', compact(
            'agendas',
            'intervalos',
            'profissionais',
            'especialidades',
            'procedimentos',
            'convenios',
            'localAtendimentos',  
            'Contas',
            'Forma',
            'exames',
            'businessHours',
            'resources',
            'nomesAgenda',
            'request', 
            'tipoAtendimentos',
            'agendasSelect',
            'AgendaItens', 
            'situacoes',
            'empresa',
            'DiasAgenda',
            'horariosPadrao',
            'Horarios',
            'selectAgenda'
        ));
    }
     
    public function ShowAgendamento(Request $request)
    {
 
        if(isset($request['tipo'])=='bloqueio'){
            if($request['start']<date('Y-m-d')){
                return response()->json(['message' => 'Data do Bloqueio menor que a data atual!' ], 500);
            }
            $request['start'] = date('Y-m-d',strtotime('@' . strtotime($request['start']) . '- 1 day'));
            $request['start']=strtotime($request['start']);
            $request['end']=strtotime($request['end']); 
            $request['di'] = $request['start'];
            $request['df'] = $request['end']; 
        }else{
 
            $request['di'] = $request['start'];
            $request['df'] = $request['end']; 
        }

        
        $apiAgendamento = new apiAgendamento(); 
        $retorno = $apiAgendamento->principal($request);

        if($retorno['retorno']==true){
            return response()->json($retorno['dados']);
        }else{
            return response()->json(['errors' => [$retorno['dados']]], 500);
        }
        return $retorno;
              
    }
    
    public function ShowAgendamento_antigo2(Request $request)
    { 
 
        if(isset($request['tipo'])=='bloqueio'){
            if($request['start']<date('Y-m-d')){
                return response()->json(['message' => 'Data do Bloqueio menor que a data atual!' ], 500);
            }
            $request['start']=strtotime($request['start']);
            $request['end']=strtotime($request['end']);
            $di = $request['start'];
            $df = $request['end']; 
        }else{
            $di = $request['start'];
            $df = $request['end']; 
        }

        $situacaoLivre=AgendamentoSituacao::where('livre','S')->first();
        $IconeLivre=$situacaoLivre->icone;
        $ArrayDias=[];
        $listaAgendamentoManual=[];
        $listaAgendamento=[]; 
        $arrayFeriados=[];
        $retorno=[];
        $Escala=[];
        $EscalaDiaria=[];
        if(empty($request['agendas'])){
            $request['agendas']=0;
            $Agendas = array(0);
        }else{
            $Agendas = explode(',',$request['agendas']);
        }
        $request['start'] = date('Y-m-d', $request['start']);
        $request['end'] = date('Y-m-d', $request['end']);
         
        $request['agendas']=$Agendas;
        $DiasEscalaManual=null; 

        if($di<=$df){   
            $dt = date('Y-m-d',$di);

            $CountEscalaDiaria=AgendaEscala::where('dt_inicial',$dt)
            ->where('sn_ativo','S')->where('escala_manual','S')
            ->whereIn('cd_agenda',$request['agendas']) 
            ->where('escala_diaria','S')->count();  

            $ArrayDias[]=['data'=>date('Y-m-d',$di),'dia'=> date('w', strtotime($dt)),'diario'=> (($CountEscalaDiaria>0) ? 'S' : 'N') ];  
            $DiasEscalaManual[]=['data'=>date('Y-m-d',$di),'dia'=> date('w', strtotime($dt))];  
 
            while($di<$df){ 
                $di = strtotime('@'.$di.'+ 1 day'); 
                $dt = date('Y-m-d',$di);
 
                $CountEscalaDiaria=AgendaEscala::where('dt_inicial',$dt)
                ->where('sn_ativo','S')->where('escala_manual','S')
                ->whereIn('cd_agenda',$request['agendas']) 
                ->where('escala_diaria','S')->count(); 

                $ArrayDias[]=['data'=>date('Y-m-d',$di),'dia'=> date('w', strtotime($dt)),'diario'=> (($CountEscalaDiaria>0) ? 'S' : 'N') ];  
                $DiasEscalaManual[]=['data'=>date('Y-m-d',$di),'dia'=> date('w', strtotime($dt))];

            }
        } 
  
        foreach($ArrayDias as $key => $dia){
            $dia= $dia['data'];
            $Numero[$key]=  date('w', strtotime($dia));   
        }
        
        $Agendamentos=RpclinicaAgendamento::where('dt_agenda',">=",$request['start'])->where('dt_agenda',"<=",$request['end'])
        ->with('local','convenio','profissional','especialidade','paciente','tipo_atend','agenda','tab_situacao')
        ->whereIn('cd_agenda',$request['agendas'])->get(); 
        foreach($Agendamentos as $Agendamento){
            if($Agendamento->cd_agenda_escala_horario){
                $listaAgendamento[$Agendamento->dt_agenda][$Agendamento->cd_agenda_escala_horario][] = $Agendamento->toArray();
            }else{
                $listaAgendamentoManual[$Agendamento->dt_agenda][] = $Agendamento->toArray();
            } 
        }
          
        $feriados = Feriado::where('dt_feriado',">=",$request['start'])->where('dt_feriado',"<=",$request['end'])
        ->where('sn_bloqueado','S')->get();
        $empresa = Empresa::find($request->user()->cd_empresa); 
        foreach($feriados as $val){ 
            $arrayFeriados[$val->dt_feriado]=$val->dt_feriado; 
            foreach($request['agendas'] as $ag){
                    $retorno[]  = array(
                        "tp"=> 1,    
                        "cd_agendamento"=> null,    
                        "Tipo"=> 'BLO',
                        "cd_horario"=>null,
                        "description"=> null,
                        "encaixe"=> '',
                        "especialidade" => null,
                        "local" =>  null,
                        "convenio" => null, 
                        "id"=> null,
                        "start"=> $val->dt_feriado. ' ' .( ($empresa->hr_inicial) ? substr($empresa->hr_inicial,0,5) : '08:00' ),
                        "ds_start"=> ( ($empresa->hr_inicial) ? substr($empresa->hr_inicial,0,5) : '08:00' ),
                        "nm_profissional"=> null,
                        "end"=> $val->dt_feriado. ' ' .( ($empresa->hr_final) ? substr($empresa->hr_final,0,5) : '18:00' ),
                        "ds_end"=> ( ($empresa->hr_final) ? substr($empresa->hr_final,0,5) : '08:00' ),
                        "resourceId"=>$ag,
                        "resource"=> $ag,
                        "nm_resource"=> ucfirst($val->nm_feriado),
                        "escalaId"=> $val->cd_feriado,
                        "escalaIntervalo"=> null,
                        "escalaNmIntervalo"=> null,
                        "title"=> null,
                        "cd_dia"=>null,
                        "titulo"=> 'Bloqueado - '. ucfirst(strtolower($val->nm_feriado)),
                        "className"=> 'event-livre',
                        "icone"=> null,
                        "nm_paciente"=> null,
                        "dt_nasc"=> null,
                        "situacao"=>  'bloqueado',
                        "usuario_geracao"=> "",
                        "rendering"=> null,
                        "backgroundColor"=> null, 
                        "icone_livre"=>$IconeLivre,
                        "obs"=> "",
                        "tipo_dados"=> "feriados"
                    );  
            } 
        }

        /* Gerar Escala Normal */
        $query=AgendaEscala::whereIn('cd_agenda',$request['agendas'])
        ->with('agenda.profissional','agenda.especialidade','escala_horarios')
        ->whereIn('nr_dia',$Numero)
        ->where('sn_ativo','S')
        ->whereNull('escala_manual')->get(); 
        foreach($query as $keyLinha => $linha) {  
            foreach($linha->escala_horarios as $keyHorarios=> $horario) {
                $horaNova = strtotime("$horario->cd_horario + ".$linha->intervalo." minutes"); 
                $horaNovaFormatada = date("H:i",$horaNova); 
                
                $Escala[$linha->nr_dia][$linha->cd_agenda][$horario->cd_agenda_escala_horario]  = array(
                    "tp"=> 1,    
                    "cd_agendamento"=> null,    
                    "Tipo"=> 'LI',
                    "cd_horario"=>$horario->cd_agenda_escala_horario,
                    "description"=> $linha->agenda->nm_agenda,
                    "encaixe"=> '',
                    "especialidade" => null,
                    "local" =>  null,
                    "convenio" => null,
                    "id"=> null,
                    "start"=> $horario->cd_horario,
                    "nm_profissional"=> null,
                    "end"=> trim($horaNovaFormatada),
                    "resourceId"=>$linha->cd_agenda,
                    "resource"=> $linha->cd_agenda,
                    "nm_resource"=> $linha->agenda->nm_agenda,
                    "escalaId"=> $linha->cd_escala_agenda,
                    "escalaIntervalo"=> $linha->intervalo,
                    "escalaNmIntervalo"=> $linha->nm_intervalo,
                    "title"=> null,
                    "cd_dia"=> $linha->nr_dia,
                    "titulo"=> 'Livre',
                    "className"=> 'event-livre',
                    "icone"=> null,
                    "nm_paciente"=> null,
                    "dt_nasc"=> null,
                    "situacao"=>  'livre',
                    "usuario_geracao"=> "",
                    "rendering"=> null,
                    "backgroundColor"=> null, 
                    "icone_livre"=>$IconeLivre,
                    "obs"=> "",
                    "tipo_dados"=> "Escala_Normal"
                );  
            }
        } 

        /* Gerar Escala Diaria */
        $query=AgendaEscala::whereIn('cd_agenda',$request['agendas'])
        ->with('agenda.profissional','agenda.especialidade','escala_horarios')
        ->where('dt_inicial','>=',$request['start'])
        ->where('dt_inicial','<=',$request['end'])
        ->where('sn_ativo','S')
        ->where('escala_manual','S')
        ->where('escala_diaria','S')->get(); 
        foreach($query as $keyLinha => $linha) {  
            foreach($linha->escala_horarios as $keyHorarios=> $horario) {
                $horaNova = strtotime("$horario->cd_horario + ".$linha->intervalo." minutes"); 
                $horaNovaFormatada = date("H:i",$horaNova); 
                
                $EscalaDiaria[$linha->dt_inicial][$linha->cd_agenda][$horario->cd_agenda_escala_horario]  = array(
                    "tp"=> 1,    
                    "cd_agendamento"=> null,    
                    "Tipo"=> 'LI',
                    "cd_horario"=>$horario->cd_agenda_escala_horario,
                    "description"=> $linha->agenda->nm_agenda,
                    "encaixe"=> '',
                    "especialidade" => null,
                    "local" =>  null,
                    "convenio" => null,
                    "id"=> null,
                    "start"=> $horario->cd_horario,
                    "nm_profissional"=> null,
                    "end"=> trim($horaNovaFormatada),
                    "resourceId"=>$linha->cd_agenda,
                    "resource"=> $linha->cd_agenda,
                    "nm_resource"=> $linha->agenda->nm_agenda,
                    "escalaId"=> $linha->cd_escala_agenda,
                    "escalaIntervalo"=> $linha->intervalo,
                    "escalaNmIntervalo"=> $linha->nm_intervalo,
                    "title"=> null,
                    "cd_dia"=> $linha->nr_dia,
                    "titulo"=> 'Livre',
                    "className"=> 'event-livre',
                    "icone"=> null,
                    "nm_paciente"=> null,
                    "dt_nasc"=> null,
                    "situacao"=>  'livre',
                    "usuario_geracao"=> "",
                    "rendering"=> null,
                    "backgroundColor"=> null, 
                    "icone_livre"=>$IconeLivre,
                    "obs"=> "",
                    "tipo_dados"=> "Escala_Diaria"
                );  
            }
        } 

  
        foreach($ArrayDias as $keyDIA => $DIA) { 
            $NR_DIA = $DIA['dia'];
            $DATA = $DIA['data']; 
            $SN_DIARIO = $DIA['diario']; 
            $Visualizar = 'N'; 

            if($DATA>=date('Y-m-d')){
                $Visualizar = 'S';
            } 
            if(isset($arrayFeriados[$DATA])){
                $Visualizar='N';
            }
            
            //Escala Manual
            $escalaManual=AgendaEscala::whereIn('cd_agenda',$request['agendas'])
            ->with('agenda.profissional','agenda.especialidade','escala_horarios')
            ->where('escala_manual','S')
            ->whereNull('escala_diaria')
            ->where('sn_ativo','S')
            ->where('dt_inicial','>=',$DATA)
            ->where('dt_inicial','<=',$DATA)->get(); 
            foreach($escalaManual as $Ag => $age) { 
                if(isset($age->escala_horarios)){
                    foreach($age->escala_horarios as $Ho => $Time) { 
                        $horaNova = strtotime("$Time->cd_horario + ".$age->intervalo." minutes"); 
                        $horaNovaFormatada = date("H:i",$horaNova); 
                        $cd_horario=$Time->cd_agenda_escala_horario;
                        $Horario=$Time->cd_horario;
                        $agendamento =  (isset($listaAgendamento[$DATA][$cd_horario][0])) ? $listaAgendamento[$DATA][$cd_horario][0] : null; 
                        if(isset($agendamento['hr_agenda'])){
                            if($agendamento['hr_agenda']){
                                $Horario=$agendamento['hr_agenda'];
                            }
                        }
                        if(isset($agendamento['hr_final'])){
                            if($agendamento['hr_final']){
                                $horaNovaFormatada=$agendamento['hr_final'];
                            }
                        }
                        if($Visualizar == 'S'){
                            $retorno[]  = array(
                                "tp"=> 1,    
                                "tipo"=> 'E',   
                                "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,   
                                "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                "cd_horario"=>$cd_horario,
                                "description"=> $Time->agenda->nm_agenda,
                                "encaixe"=> '',
                                "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                                "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                                "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                                "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                                "start"=> trim($DATA) . ' ' . trim($Horario),  
                                "ds_start"=>  trim($Horario), 
                                "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                                "end"=> trim($DATA) . ' ' . trim($horaNovaFormatada),
                                "ds_end"=> trim($horaNovaFormatada),
                                "resourceId"=> $age->agenda->cd_agenda,
                                "resource"=> $age->agenda->cd_agenda,
                                "nm_resource"=> $age->agenda->nm_agenda,
                                "escalaId"=> $Time->cd_escala_agenda,
                                "escalaIntervalo"=> $age->intervalo,
                                "escalaNmIntervalo"=> $age->nm_intervalo,
                                "title"=> "", //(isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                "cd_dia"=> $age->nr_dia,
                                "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'Livre',
                                "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                "icone"=> "", //(isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : null,
                                "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                                "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                "usuario_geracao"=> "",
                                "rendering"=> null,
                                "backgroundColor"=> null, 
                                "icone_livre"=>$IconeLivre,
                                "obs"=> "",
                                "tipo_dados"=> "Escala_Manual"
                            ); 

                        }else{
                            if($agendamento){
        
                                $retorno[]  = array(
                                    "tp"=> 1,    
                                    "tipo"=> 'E',   
                                    "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,   
                                    "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                    "cd_horario"=>$cd_horario,
                                    "description"=> $Time->agenda->nm_agenda,
                                    "encaixe"=> '',
                                    "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                                    "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                                    "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                                    "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                                    "start"=> trim($DATA) . ' ' . trim($Horario),  
                                    "ds_start"=>  trim($Horario), 
                                    "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                                    "end"=> trim($DATA) . ' ' . trim($horaNovaFormatada),
                                    "ds_end"=> trim($horaNovaFormatada),
                                    "resourceId"=> $age->agenda->cd_agenda,
                                    "resource"=> $age->agenda->cd_agenda,
                                    "nm_resource"=> $age->agenda->nm_agenda,
                                    "escalaId"=> $Time->cd_escala_agenda,
                                    "escalaIntervalo"=> $age->intervalo,
                                    "escalaNmIntervalo"=> $age->nm_intervalo,
                                    "title"=> "", // (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                    "cd_dia"=> $age->nr_dia,
                                    "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                    "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                    "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                    "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                    "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                                    "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                    "usuario_geracao"=> "",
                                    "rendering"=> null,
                                    "backgroundColor"=> null, 
                                    "icone_livre"=>$IconeLivre,
                                    "obs"=> "",
                                   "tipo_dados"=> "Escala_Manual"
                                ); 
                            }
        
                        }

                    }
                }
 
            }

            if($SN_DIARIO=='N'){
              
                //Escala Normal
                $ateManual =  (isset($listaAgendamentoManual[$DATA])) ? $listaAgendamentoManual[$DATA] : null;
                if(isset($Escala[$NR_DIA])){

                    foreach($Escala[$NR_DIA] as $cd_agenda => $agenda) { 
                        foreach($agenda as $cd_horario => $horario) { 

                            $agendamento =  (isset($listaAgendamento[$DATA][$cd_horario][0])) ? $listaAgendamento[$DATA][$cd_horario][0] : null; 
                            if(isset($agendamento['hr_agenda'])){
                                if($agendamento['hr_agenda']){
                                    $horario['start']=$agendamento['hr_agenda'];
                                }
                            }
                            if(isset($agendamento['hr_final'])){
                                if($agendamento['hr_final']){
                                    $horario['end']=$agendamento['hr_final'];
                                }
                            }

                            if($Visualizar == 'S'){
                                $retorno[]  = array(
                                    "tp"=> 1,  
                                    "tipo"=> 'E',     
                                    "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,   
                                    "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                    "cd_horario"=>$horario['cd_horario'],
                                    "description"=> ($horario['description']) ? $horario['description'] : '',
                                    "encaixe"=> '',
                                    "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                                    "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                                    "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                                    "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                                    "start"=> trim($DATA) . ' ' . trim($horario['start']),  
                                    "ds_start"=>  trim($horario['start']), 
                                    "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                                    "end"=> trim($DATA) . ' ' . trim($horario['end']),
                                    "ds_end"=> trim($horario['end']),
                                    "resourceId"=> $horario['resourceId'],
                                    "resource"=> $horario['resource'],
                                    "nm_resource"=> $horario['nm_resource'],
                                    "escalaId"=> $horario['escalaId'],
                                    "escalaIntervalo"=> $horario['escalaIntervalo'],
                                    "escalaNmIntervalo"=> $horario['escalaNmIntervalo'],
                                    "title"=> "", //(isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                    "cd_dia"=> $horario['cd_dia'],
                                    "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                    "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                    "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                    "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                    "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                                    "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                    "usuario_geracao"=> "",
                                    "rendering"=> null,
                                    "backgroundColor"=> null, 
                                    "icone_livre"=>$IconeLivre,
                                    "obs"=> "",
                                   "tipo_dados"=> "Escala_Normal2"
                                ); 
                            }else{
                                if($agendamento){
                                    
                                    $retorno[]  = array(
                                        "tp"=> 1,     
                                        "tipo"=> 'E',   
                                        "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',   
                                        "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                        "cd_horario"=>$horario['cd_horario'],
                                        "description"=> ($horario['description']) ? $horario['description'] : '',
                                        "encaixe"=> '',
                                        "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                                        "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                                        "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                                        "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                                        "start"=> trim($DATA) . ' ' . trim($horario['start']),  
                                        "ds_start"=>  trim($horario['start']), 
                                        "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                                        "end"=> trim($DATA) . ' ' . trim($horario['end']),
                                        "ds_end"=> trim($horario['end']),
                                        "resourceId"=> $horario['resourceId'],
                                        "resource"=> $horario['resource'],
                                        "nm_resource"=> $horario['nm_resource'],
                                        "escalaId"=> $horario['escalaId'],
                                        "escalaIntervalo"=> $horario['escalaIntervalo'],
                                        "escalaNmIntervalo"=> $horario['escalaNmIntervalo'],
                                        "title"=> "", //(isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                                        "cd_dia"=> $horario['cd_dia'],
                                        "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                        "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                        "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                        "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                                        "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                                        "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                        "usuario_geracao"=> "",
                                        "rendering"=>  '',
                                        "backgroundColor"=> '',
                                        "icone_livre"=>$IconeLivre,
                                        "obs"=> "",
                                        "tipo_dados"=> "Escala_Normal2"
                                    ); 
        
                            
                                } 
                            }
                        } 
                    }

                    if($ateManual){
                        foreach($ateManual as $agendamento){
                            $retorno[]  = array(
                                "tp"=> 1,     
                                "tipo"=> 'E',   
                                "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',   
                                "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                "cd_horario"=>null,
                                "description"=> $agendamento['agenda']['nm_agenda'],
                                "encaixe"=> '&nbsp;&nbsp; <code><b> ENCAIXE </b></code>',
                                "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                                "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                                "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                                "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                                "start"=> trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_agenda'],0,5)),   
                                "ds_start"=> trim($agendamento['hr_agenda']),
                                "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                                "end"=> trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_final'],0,5)),  
                                "ds_end"=> trim($agendamento['hr_final']),
                                "resourceId"=> $agendamento['cd_agenda'],
                                "resource"=> $agendamento['cd_agenda'],
                                "nm_resource"=> $agendamento['agenda']['nm_agenda'],
                                "escalaId"=> $agendamento['cd_escala'],
                                "escalaIntervalo"=> $agendamento['intervalo'],
                                "escalaNmIntervalo"=> null,
                                "title"=> "", // (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                                "cd_dia"=> $agendamento['dia_semana'],
                                "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                                "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                                "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                "usuario_geracao"=> "",
                                "rendering"=>  '',
                                "backgroundColor"=> '',
                                "icone_livre"=>$IconeLivre,
                                "obs"=> "",
                                "tipo_dados"=> "Escala_Atend_Manual"
                            );
                            
                        }
                        
                    }

                }  

            }else{

                //Escala Normal
                $ateManual =  (isset($listaAgendamentoManual[$DATA])) ? $listaAgendamentoManual[$DATA] : null;
                if(isset($Escala[$NR_DIA])){
                    foreach($EscalaDiaria[$DATA] as $cd_agenda => $agenda) { 
                        foreach($agenda as $cd_horario => $horario) { 

                            $agendamento =  (isset($listaAgendamento[$DATA][$cd_horario][0])) ? $listaAgendamento[$DATA][$cd_horario][0] : null; 

                            if(isset($agendamento['hr_agenda'])){
                                if($agendamento['hr_agenda']){
                                    $horario['start']=$agendamento['hr_agenda'];
                                }
                            }
                            if(isset($agendamento['hr_final'])){
                                if($agendamento['hr_final']){
                                    $horario['end']=$agendamento['hr_final'];
                                }
                            }

                            if($Visualizar == 'S'){
                                $retorno[]  = array(
                                    "tp"=> 1,    
                                    "tipo"=> 'E',   
                                    "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,   
                                    "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                    "cd_horario"=>$horario['cd_horario'],
                                    "description"=> ($horario['description']) ? $horario['description'] : '',
                                    "encaixe"=> '',
                                    "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : null,
                                    "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : null,
                                    "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : null,
                                    "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : null,
                                    "start"=> trim($DATA) . ' ' . trim($horario['start']),  
                                    "ds_start"=>  trim($horario['start']), 
                                    "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : null,
                                    "end"=> trim($DATA) . ' ' . trim($horario['end']),
                                    "ds_end"=> trim($horario['end']),
                                    "resourceId"=> $horario['resourceId'],
                                    "resource"=> $horario['resource'],
                                    "nm_resource"=> $horario['nm_resource'],
                                    "escalaId"=> $horario['escalaId'],
                                    "escalaIntervalo"=> $horario['escalaIntervalo'],
                                    "escalaNmIntervalo"=> $horario['escalaNmIntervalo'],
                                    "title"=> "", // (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                    "cd_dia"=> $horario['cd_dia'],
                                    "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                    "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                    "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                    "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : null,
                                    "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : null,
                                    "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                    "usuario_geracao"=> "",
                                    "rendering"=> null,
                                    "backgroundColor"=> null, 
                                    "icone_livre"=>$IconeLivre,
                                    "obs"=> "",
                                    "tipo_dados"=> "Escala_Atend_Manual2"
                                ); 
                            }else{
                                if($agendamento){
                                    
                                    $retorno[]  = array(
                                        "tp"=> 1,     
                                        "tipo"=> 'E',   
                                        "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',   
                                        "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                        "cd_horario"=>$horario['cd_horario'],
                                        "description"=> ($horario['description']) ? $horario['description'] : '',
                                        "encaixe"=> '',
                                        "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                                        "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                                        "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                                        "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                                        "start"=> trim($DATA) . ' ' . trim($horario['start']),  
                                        "ds_start"=>  trim($horario['start']), 
                                        "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                                        "end"=> trim($DATA) . ' ' . trim($horario['end']),
                                        "ds_end"=> trim($horario['end']),
                                        "resourceId"=> $horario['resourceId'],
                                        "resource"=> $horario['resource'],
                                        "nm_resource"=> $horario['nm_resource'],
                                        "escalaId"=> $horario['escalaId'],
                                        "escalaIntervalo"=> $horario['escalaIntervalo'],
                                        "escalaNmIntervalo"=> $horario['escalaNmIntervalo'],
                                        "title"=> "", // (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                                        "cd_dia"=> $horario['cd_dia'],
                                        "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                        "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                        "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                        "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                                        "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                                        "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                        "usuario_geracao"=> "",
                                        "rendering"=>  '',
                                        "backgroundColor"=> '',
                                        "icone_livre"=>$IconeLivre,
                                        "obs"=> "",
                                        "tipo_dados"=> "Escala_Atend_Manual2"
                                    ); 
        
                            
                                } 
                            }

                        }
                    }

                    if($ateManual){
                        foreach($ateManual as $agendamento){
                            $retorno[]  = array(
                                "tp"=> 1,     
                                "tipo"=> 'E',   
                                "cd_agendamento"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',   
                                "Tipo"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'LI',
                                "cd_horario"=>null,
                                "description"=> $agendamento['agenda']['nm_agenda'],
                                "encaixe"=> '&nbsp;&nbsp; <code><b> ENCAIXE </b></code>',
                                "especialidade" => (isset($agendamento['especialidade']['nm_especialidade'])) ? $agendamento['especialidade']['nm_especialidade'] : '',
                                "local" =>  (isset($agendamento['local']['nm_local'])) ? $agendamento['local']['nm_local'] : '',
                                "convenio" => (isset($agendamento['convenio']['nm_convenio'])) ? $agendamento['convenio']['nm_convenio'] : '',
                                "id"=> (isset($agendamento['cd_agendamento'])) ? $agendamento['cd_agendamento'] : '',
                                "start"=> trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_agenda'],0,5)),   
                                "ds_start"=> trim($agendamento['hr_agenda']),
                                "nm_profissional"=> (isset($agendamento['profissional']['nm_profissional'])) ? $agendamento['profissional']['nm_profissional'] : '',
                                "end"=> trim($agendamento['dt_agenda']) . ' ' . trim(substr($agendamento['hr_final'],0,5)),  
                                "ds_end"=> trim($agendamento['hr_final']),
                                "resourceId"=> $agendamento['cd_agenda'],
                                "resource"=> $agendamento['cd_agenda'],
                                "nm_resource"=> $agendamento['agenda']['nm_agenda'],
                                "escalaId"=> $agendamento['cd_escala'],
                                "escalaIntervalo"=> $agendamento['intervalo'],
                                "escalaNmIntervalo"=> null,
                                "title"=> "", // (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] : '',
                                "cd_dia"=> $agendamento['dia_semana'],
                                "titulo"=> (isset($agendamento['tab_situacao']['nm_situacao'])) ? $agendamento['tab_situacao']['nm_situacao'] : 'livre',
                                "className"=> (isset($agendamento['tipo_atend']['cor'])) ? $agendamento['tipo_atend']['cor'] : 'event-livre',
                                "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ',
                                "nm_paciente"=> (isset($agendamento['paciente']['nm_paciente'])) ? $agendamento['paciente']['nm_paciente'] :  '',
                                "dt_nasc"=>  (isset($agendamento['paciente']['dt_nasc'])) ? $agendamento['paciente']['dt_nasc'] : '',
                                "situacao"=> (isset($agendamento['situacao'])) ? $agendamento['situacao'] : 'livre',
                                "usuario_geracao"=> "",
                                "rendering"=>  '',
                                "backgroundColor"=> '',
                                "icone_livre"=>$IconeLivre,
                                "obs"=> "",
                                "tipo_dados"=> "Escala_Atend_Manual3"
                            );
                            
                        }
                        
                    }
                }

            }
 
        }
   
        return response()->json($retorno);

    }
 
    public function ShowAgendamento_antigo(Request $request)
    { 
        
        if(empty($request['agendas'])){
            $request['agendas']=0;
            $Agendas = array(0);
        }else{
            $Agendas = explode(',',$request['agendas']);
        }
        $start = date('Y-m-d', $request['start']);
        $end = date('Y-m-d', $request['end']);
        
        $array_lista=null; 
        $ArrayDias=[]; 
        $di = $request['start'];
        $df = $request['end']; 
        if($di<=$df){  
            $ArrayDias[]=date('Y-m-d',$di);
            while($di<$df){
            $di = strtotime('@'.$di.'+ 1 day'); 
            $ArrayDias[]=date('Y-m-d',$di); 
            }
        } 
 
        $escala = Agenda::whereIn('cd_agenda',$Agendas)
        ->with(['escalas.escala_horarios'  => function($query) {  
            $query->selectRaw('*');
        }])->get(); 
        
        foreach($escala as $ArrayAgendas){
            $agenda = $ArrayAgendas->cd_agenda;
            $hr = null;
            foreach($ArrayAgendas->escalas as $linha){
                $hr = null;
                $dia = $linha->nr_dia;
                foreach($linha->escala_horarios as $horarios){
                    $hr[$horarios->cd_horario] = array(
                        'cd_agenda_escala_horario' => $horarios->cd_agenda_escala_horario,
                        'cd_horario' => $horarios->cd_horario,
                    );
                }

                $array_lista[$dia][$agenda][$linha->cd_escala_agenda]=array(
                    'cd_escala_agenda'=>$linha->cd_escala_agenda,
                    'cd_agenda'=>$linha->cd_agenda,
                    'nm_agenda'=>$linha->agenda->nm_agenda,
                    'cd_dia'=>$linha->cd_dia,
                    'nr_dia'=>$linha->nr_dia,
                    'intervalo'=>$linha->intervalo,
                    'nm_intervalo'=>$linha->nm_intervalo,
                    'horarios'=>$hr, 
                );   
                 
            } 

        }
  

        $Array = null;
        //ArrayDIA
        foreach($ArrayDias as $Dia){ 
            $NrDia = date('w', strtotime($Dia));  
            //ArrayAGENDA
            if(isset($array_lista[$NrDia])){ 
                foreach($array_lista[$NrDia] as $escalas){ 

                    //ArrayESCALA
                    foreach($escalas  as $agendas){
                     
                        $cd_escala_agenda = $agendas['cd_escala_agenda'];
                        $cd_agenda = $agendas['cd_agenda'];
                        $nm_agenda = $agendas['nm_agenda'];
                        $cd_dia = $agendas['cd_dia'];
                        $nr_dia = $agendas['nr_dia'];
                        $intervalo = $agendas['intervalo'];
                        $nm_intervalo = $agendas['nm_intervalo'];
                     
                        //ArrayHORARIOS
                        foreach($agendas['horarios'] as $DadosHorarios ){ 
  
                            $cd_agenda_escala_horario = $DadosHorarios['cd_agenda_escala_horario'];
                            $cd_horario = $DadosHorarios['cd_horario']; 

                            $horaNova = strtotime("$cd_horario + ".$intervalo." minutes"); 
                            $horaNovaFormatada = date("H:i",$horaNova); 

                            $agendamento = RpclinicaAgendamento::where("dt_agenda",$Dia)
                            ->where('cd_agenda',$cd_agenda)
                            ->where('cd_escala',$cd_escala_agenda)
                            ->where('dia_semana',$NrDia)
                            ->where('sn_atend_avulso','N')
                            ->whereRaw(" TIME_FORMAT(hr_agenda, '%H:%i') = '".$cd_horario."'")->first();
                            $Visualizar = 'N';
                            
                            $Titulo = '';
                            if(isset($agendamento->cd_agendamento)){
                                $agendamento->load('paciente','profissional','especialidade','local','convenio','tipo_atend');
                                $Titulo = $agendamento->paciente?->nm_paciente;
                                $Visualizar = 'S';
                            }  
                            $icone="";
                            if(isset($agendamento->situacao)){
                                $icone=HelperIconesAgendamento($agendamento->situacao);
                            }
                            if($Dia>=date('Y-m-d')){
                                $Visualizar = 'S';
                            }    
                            if($Visualizar == 'S'){
                                $Ag = (isset($agendamento->cd_agendamento)) ? " TEM " : " *** ";
                                
                                $Array[]  = array(
                                    "tp"=> 1,    
                                    "Tipo"=> (isset($agendamento->situacao)) ? $agendamento->situacao : 'LI',
                                    "cd_horario"=>$cd_agenda_escala_horario,
                                    "description"=> $nm_agenda,
                                    "encaixe"=> '',
                                    "especialidade" => (isset($agendamento->especialidade->nm_especialidade)) ? $agendamento->especialidade->nm_especialidade : null,
                                    "local" =>  (isset($agendamento->local->nm_local)) ? $agendamento->local->nm_local : null,
                                    "convenio" => (isset($agendamento->convenio->nm_convenio)) ? $agendamento->convenio->nm_convenio : null,
                                    "id"=> (isset($agendamento->cd_agendamento)) ? $agendamento->cd_agendamento : null,
                                    "start"=> (trim($Dia)." ".trim($cd_horario)),
                                    "nm_profissional"=> (isset($agendamento->profissional?->nm_profissional)) ? $agendamento->profissional?->nm_profissional : null,
                                    "end"=> trim($Dia)." ".trim($horaNovaFormatada),
                                    "resourceId"=> $cd_agenda,
                                    "resource"=> $cd_agenda,
                                    "nm_resource"=> $nm_agenda,
                                    "escalaId"=> $cd_escala_agenda,
                                    "escalaIntervalo"=> $intervalo,
                                    "escalaNmIntervalo"=> $nm_intervalo,
                                    "title"=> $Titulo,
                                    "cd_dia"=> $nr_dia,
                                    "titulo"=> (isset($agendamento->situacao)) ? $agendamento->situacao : 'livre',
                                    "className"=> (isset($agendamento->tipo_atend?->cor)) ? $agendamento->tipo_atend?->cor : 'event-livre',
                                    "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ', //$icone,
                                    "nm_paciente"=> (isset($agendamento->paciente?->nm_paciente)) ? $agendamento->paciente?->nm_paciente : null,
                                    "dt_nasc"=> null,
                                    "situacao"=> (isset($agendamento->situacao)) ? $agendamento->situacao : 'livre',
                                    "usuario_geracao"=> "",
                                    "rendering"=> null,
                                    "backgroundColor"=> null, 
                                    "obs"=> ""
                                );
    
                            }

                        }

                    }
                      
                } 

            }

            $agendamentos = RpclinicaAgendamento::
            where('sn_atend_avulso','S')
            ->where("dt_agenda",$Dia)
            ->whereIn('cd_agenda',$Agendas)
            ->orderBy("hr_agenda")->get();

            $agendamentos->load('paciente','profissional','especialidade','local','convenio','tipo_atend');
            foreach($agendamentos as $agendamento){
                $codAgenda = Agenda::find($agendamento->cd_agenda);
                $codEscalaAgenda = AgendaEscala::find($agendamento->cd_escala);
                $icone= null;
                if(isset($agendamento->situacao)){
                    $icone=HelperIconesAgendamento($agendamento->situacao);
                }
                $Array[]  = array(
                    "tp"=> 1,    
                    "Tipo"=> (isset($agendamento->situacao)) ? $agendamento->situacao : 'LI',
                    "cd_horario"=>$agendamento->cd_agenda_escala_horario,
                    "description"=> $codAgenda->nm_agenda,
                    "encaixe"=> '',
                    "especialidade" => ( (isset($agendamento->especialidade->nm_especialidade)) ? $agendamento->especialidade->nm_especialidade : null  ) . ' <code>encaixe</code>' ,
                    "local" =>  (isset($agendamento->local->nm_local)) ? $agendamento->local->nm_local : null,
                    "convenio" => (isset($agendamento->convenio->nm_convenio)) ? $agendamento->convenio->nm_convenio : null,
                    "id"=> (isset($agendamento->cd_agendamento)) ? $agendamento->cd_agendamento : null,
                    "start"=> (trim($Dia)." ".trim($agendamento->hr_agenda)),
                    "nm_profissional"=> (isset($agendamento->profissional?->nm_profissional)) ? $agendamento->profissional?->nm_profissional : null,
                    "end"=> trim($Dia)." ".trim($agendamento->hr_final),
                    "resourceId"=> $agendamento->cd_agenda,
                    "resource"=> $agendamento->cd_agenda,
                    "nm_resource"=> $codAgenda->nm_agenda,
                    "escalaId"=> $codEscalaAgenda->cd_escala_agenda,
                    "escalaIntervalo"=> $codEscalaAgenda->intervalo,
                    "escalaNmIntervalo"=> $codEscalaAgenda->nm_intervalo,
                    "title"=> $Titulo,
                    "cd_dia"=> $nr_dia,
                    "titulo"=> (isset($agendamento->situacao)) ? $agendamento->situacao : 'livre',
                    "className"=> (isset($agendamento->tipo_atend?->cor)) ? $agendamento->tipo_atend?->cor : 'event-livre',
                    "icone"=> (isset($agendamento['tab_situacao']['icone'])) ? $agendamento['tab_situacao']['icone'] : ' ', //$icone,
                    "nm_paciente"=> (isset($agendamento->paciente?->nm_paciente)) ? $agendamento->paciente?->nm_paciente : null,
                    "dt_nasc"=> null,
                    "situacao"=> (isset($agendamento->situacao)) ? $agendamento->situacao : 'livre',
                    "usuario_geracao"=> "",
                    "rendering"=> null,
                    "backgroundColor"=> null, 
                    "obs"=> ""
                );
            }
 
        }
 
        return response()->json($Array);

    }
  
    public function ShowAgendamentoAvanc(Request $request)
    { 
       
        $escalas = AgendaEscala::whereRaw("agenda_escala.sn_ativo = 'S'")
        ->selectRaw("agenda.cd_agenda,nm_agenda,agenda_escala.intervalo,agenda_escala.nm_intervalo,agenda_escala.nr_dia,agenda_escala.cd_escala_agenda,
        agenda_escala.cd_dia,agenda_escala.cd_dia, case when nr_dia = 1 then 'Segunda-Feira' when nr_dia = 2 then 'Terça-Feira' when nr_dia = 3 then 'Quarta-Feira'
        when nr_dia = 4 then 'Quinta-Feira' when nr_dia = 5 then 'Sexta-Feira' when nr_dia = 6 then 'Sabado' when nr_dia = 7 then 'Domingo' else 'Não Encontrado' end nm_dia,
        time_format(agenda_escala.hr_inicial,'%H:%i') hi, time_format(agenda_escala.hr_final,'%H:%i') hf ")
        ->join('agenda','agenda.cd_agenda','agenda_escala.cd_agenda');
        if($request['agenda']){
            $escalas =  $escalas->whereIn('agenda.cd_agenda',$request['agenda']);
        } 
        if($request['exame']){
            $escalas =  $escalas->whereIn('agenda.cd_exame',$request['exame']);
        } 
        if($request['local']){
            $escalas =  $escalas->whereRaw("agenda_escala.cd_escala_agenda in ( select cd_escala from agenda_locais where cd_local = ". $request['local'] ." )");
        } 
        if($request['profissional']){
            $escalas =  $escalas->whereRaw("agenda_escala.cd_escala_agenda in ( select cd_escala from agenda_profissionais where cd_profissional  = ". $request['profissional'] ." )");
        } 
        if($request['especialidade']){
            $escalas =  $escalas->whereRaw("agenda_escala.cd_escala_agenda in ( select cd_escala from agenda_especialidades where cd_especialidade ". $request['especialidade'] ." )");
        } 
        $escalas =  $escalas->with(['escala_horarios'])->get();
        
        foreach($escalas as $linha){
            $dia = $linha->nr_dia;
            $agenda = $linha->cd_agenda;
            $hr = null;
 
            foreach($linha->escala_horarios as $horarios){
                $hr[] = array(
                    'cd_agenda_escala_horario' => $horarios->cd_agenda_escala_horario,
                    'cd_horario' => $horarios->cd_horario
                );
            }

            $array_lista[$dia][$agenda]=array(
                'cd_escala_agenda'=>$linha->cd_escala_agenda,
                'cd_agenda'=>$linha->cd_agenda,
                'nm_agenda'=>$linha->nm_agenda,
                'cd_dia'=>$linha->cd_dia,
                'nr_dia'=>$linha->nr_dia,
                'intervalo'=>$linha->intervalo,
                'nm_intervalo'=>$linha->nm_intervalo,
                'nm_dia'=>$linha->nm_dia,
                'hi'=>$linha->hi,
                'hf'=>$linha->hf,
                'horarios'=>$hr, 
            );   
 
        }

        $di =  strtotime($request['dti']);
        $df = strtotime($request['dtf']);
        if($di<=$df){  
            $ArrayDias[]=date('Y-m-d',$di);
            while($di<$df){
            $di = strtotime('@'.$di.'+ 1 day'); 
            $ArrayDias[]=date('Y-m-d',$di); 
            }
        }
     
        $Array = null;
        $ArraydoDia = null;
        foreach($ArrayDias as $Dia){
            $NrDia = date('w', strtotime($Dia)); 
            $DataDia = date('d/m/Y', strtotime($Dia));


            if(isset($array_lista[$NrDia])){ 
                 
                $ArraydaAgenda = null;
                foreach($array_lista[$NrDia] as $agendas){

                    $cd_escala_agenda = $agendas['cd_escala_agenda'];
                    $cd_agenda = $agendas['cd_agenda'];
                    $nm_agenda = $agendas['nm_agenda'];
                    $cd_dia = $agendas['cd_dia'];
                    $nr_dia = $agendas['nr_dia'];
                    $intervalo = $agendas['intervalo'];
                    $nm_dia = $agendas['nm_dia'];
                    $hi = $agendas['hi'];
                    $hf = $agendas['hf'];
                    $nm_intervalo = $agendas['nm_intervalo']; 
                    $Horarios = ($agendas['horarios']) ? (array)$agendas['horarios'] : [];
                    $ArraydoHorario=null;  
                    foreach($Horarios as $DadosHorarios ){

                        $cd_agenda_escala_horario = $DadosHorarios['cd_agenda_escala_horario'];
                        $cd_horario = $DadosHorarios['cd_horario'];


                        $agendamento = RpclinicaAgendamento::where("dt_agenda",$Dia)
                        ->where('cd_agenda',$cd_agenda)
                        ->where('cd_escala',$cd_escala_agenda)
                        ->where('dia_semana',$NrDia)
                        ->where('sn_atend_avulso','N')
                        ->whereRaw(" TIME_FORMAT(hr_agenda, '%H:%i') = '".$cd_horario."'")->first();
                        $Visualizar = 'S'; 
                        if(isset($agendamento->cd_agendamento)){ 
                            $Visualizar = 'N';
                        }   
                        if($Dia<date('Y-m-d')){
                            $Visualizar = 'N';
                        }   

                        if($Visualizar == 'S'){
 
                            $horaNova = strtotime("$cd_horario + ".$intervalo." minutes"); 
                            $horaNovaFormatada = date("H:i",$horaNova);

                            $ArraydoHorario[]=  array(
                                    "tp"=> 2,    
                                    "Tipo"=>  'LI',
                                    "cd_horario"=>$cd_agenda_escala_horario,
                                    "description"=> $nm_agenda,
                                    "encaixe"=> '',
                                    "especialidade" =>   null,
                                    "local" =>  null,
                                    "convenio" =>   null,
                                    "id"=>   null,
                                    "start"=> (trim($Dia)." ".trim($cd_horario)),
                                    "dt_start"=> (trim($DataDia)." ".trim($cd_horario)),
                                    "hr_start"=> trim($cd_horario),
                                    "nm_horario"=> trim($cd_horario),
                                    "data_start"=> trim($Dia),
                                    "nm_profissional"=>   null,
                                    "end"=> trim($Dia)." ".trim($horaNovaFormatada),
                                    "dt_end"=> (trim($DataDia)." ".trim($horaNovaFormatada)),
                                    "data_end"=> trim($Dia),
                                    "hr_end"=> trim($horaNovaFormatada),
                                    "resourceId"=> $cd_agenda,
                                    "resource"=> $cd_agenda,
                                    "nm_resource"=> $nm_agenda,
                                    "escala"=> $cd_escala_agenda,
                                    "escalaIntervalo"=> $intervalo,
                                    "intervalo"=> $intervalo,
                                    "escalaNmIntervalo"=> $nm_intervalo,
                                    "nm_intervalo"=> $nm_intervalo,
                                    "title"=> null,
                                    "cd_dia"=> $nr_dia,
                                    "titulo"=>  'livre',
                                    "className"=> 'event-livre',
                                    "icone"=> null,
                                    "nm_paciente"=>  null,
                                    "dt_nasc"=> null,
                                    "situacao"=>  'livre',
                                    "usuario_geracao"=> "",
                                    "rendering"=> null,
                                    "backgroundColor"=> null, 
                                    "obs"=> "" 
                            );

                        }
                        
                    }
                        if($ArraydoHorario){
                            $TituloAgenda = '<i class="fa fa-calendar"></i>&nbsp;&nbsp;&nbsp;&nbsp;'. ucwords(mb_strtolower($nm_agenda)).' &nbsp;&nbsp; [ [ '.$hi.' - '.$hf.' ] ] &nbsp;&nbsp;'.substr($nm_intervalo,0,5);
                            $ArraydaAgenda[]=array(
                                'cd_agenda'=>$cd_agenda,
                                'nm_agenda'=>$nm_agenda,
                                'hi'=>$hi,
                                'hf'=>$hf, 
                                'class'=>'',
                                'titulo_agenda'=>$TituloAgenda,
                                'nm_intervalo'=>substr($nm_intervalo,0,5),
                                'horarios'=>$ArraydoHorario
                            );
                        }

 
                }
                $Titulo = '<i class="fa fa-sign-out"></i>&nbsp;&nbsp;&nbsp;&nbsp;'.$DataDia.' &nbsp;&nbsp;&nbsp; [ [ '.$nm_dia.' ] ]';
                $ArraydoDia[]=array(
                    'data'=>$DataDia,
                    'nm_dia'=>$nm_dia,
                    'titulo'=>$Titulo, 
                    'agendas'=>$ArraydaAgenda
                );

            }

        }
        /*
        foreach($ArrayDias as $Dia){

            $NrDia = date('w', strtotime($Dia)); 
            if(isset($array_lista[$NrDia])){ 

                foreach($array_lista[$NrDia] as $agendas){ 
                    $cd_escala_agenda = $agendas['cd_escala_agenda'];
                    $cd_agenda = $agendas['cd_agenda'];
                    $nm_agenda = $agendas['nm_agenda'];
                    $cd_dia = $agendas['cd_dia'];
                    $nr_dia = $agendas['nr_dia'];
                    $intervalo = $agendas['intervalo'];
                    $nm_dia = $agendas['nm_dia'];
                    $hi = $agendas['hi'];
                    $hi = $agendas['hi'];
                    $nm_intervalo = $agendas['nm_intervalo'];

                    foreach($agendas['horarios'] as $DadosHorarios ){ 
                        $cd_agenda_escala_horario = $DadosHorarios['cd_agenda_escala_horario'];
                        $cd_horario = $DadosHorarios['cd_horario'];
 
                        $horaNova = strtotime("$cd_horario + ".$intervalo." minutes"); 
                        $horaNovaFormatada = date("H:i",$horaNova); 

                        $agendamento = RpclinicaAgendamento::where("dt_agenda",$Dia)
                        ->where('cd_agenda',$cd_agenda)
                        ->where('cd_escala',$cd_escala_agenda)
                        ->where('dia_semana',$NrDia)
                        ->whereRaw(" TIME_FORMAT(hr_agenda, '%H:%i') = '".$cd_horario."'")->first();
                        $Visualizar = 'S';
                        $Titulo = '';
                        if(isset($agendamento->cd_agendamento)){ 
                            $Visualizar = 'N';
                        }   
                        if($Dia<date('Y-m-d')){
                            $Visualizar = 'N';
                        }   

                        if($Visualizar == 'S'){
                            
                            $DataHorario = $date = date('d/m/Y', strtotime($Dia));
                            $Titulo =  $DataHorario.' [[ '.trim($cd_horario).' - '.trim($horaNovaFormatada).' ]] - '.substr($nm_intervalo,0,5);
                            $Array[$Titulo][$cd_agenda][]  = array(
                                "tp"=> 1,    
                                "Tipo"=>  'LI',
                                "cd_horario"=>$cd_agenda_escala_horario,
                                "description"=> $nm_agenda,
                                "encaixe"=> null,
                                "especialidade" =>   null,
                                "local" =>  null,
                                "convenio" =>   null,
                                "id"=>   null,
                                "start"=> (trim($Dia)." ".trim($cd_horario)),
                                "nm_profissional"=>   null,
                                "end"=> trim($Dia)." ".trim($horaNovaFormatada),
                                "resourceId"=> $cd_agenda,
                                "resource"=> $cd_agenda,
                                "nm_resource"=> $nm_agenda,
                                "escalaId"=> $cd_escala_agenda,
                                "escalaIntervalo"=> $intervalo,
                                "escalaNmIntervalo"=> $nm_intervalo,
                                "title"=> null,
                                "cd_dia"=> $nr_dia,
                                "titulo"=>  'livre',
                                "className"=> 'event-livre',
                                "icone"=> null,
                                "nm_paciente"=>  null,
                                "dt_nasc"=> null,
                                "situacao"=>  'livre',
                                "usuario_geracao"=> "",
                                "rendering"=> null,
                                "backgroundColor"=> null, 
                                "obs"=> ""
                            );
 
                        }

                    }
                     
                } 

            }

        }
        */
        return response()->json($ArraydoDia);

    }
 
    public function ShowAgendamentoConfirm(Request $request)
    { 
        $validator = Validator::make($request->all(), [
            'data' => 'required|date_format:Y-m-d', 
            'agenda' => 'nullable|integer|exists:agenda,cd_agenda', 
            'situacao' => 'nullable|integer|exists:agendamento_situacao,cd_situacao', 
        ]);

        try {

            $retorno = RpclinicaAgendamento::whereRaw("date(dt_agenda)='".$request['data']."'")
            ->whereNotNull("cd_paciente");
            if($request['situacao']){
                $retorno = $retorno->where('situacao',$request['situacao']);
            }
            if($request['agenda']){
                $retorno = $retorno->where('cd_agenda',$request['agenda']);
            }
            if($request['profissional']){
                $retorno = $retorno->where('cd_profissional',$request['profissional']);
            }
            $retorno = $retorno->with('paciente','agenda','especialidade','convenio','situacao','tab_whast_send','profissional') 
            ->selectRaw("agendamento.*,date_format(data_horario,'%d/%m/%Y %H:%i') data, case when whast = 1 then 'class_enviado' else 'class_aguardando' end class_envio")
            ->orderByRaw("cd_agenda , data_horario")->get();
            return response()->json($retorno);

        }
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }

    }
  
    public function modal(Request $request)
    {  
        $qtde_encaixe=0;
        if($request['tp'] == "1"){ 
            $validator = Validator::make($request->all(), [
                'data_end' => 'required|date_format:Y-m-d',
                'hr_start' => 'required|date_format:H:i',
                'hr_end' => 'required|date_format:H:i',
                'resource' => 'required|integer|exists:agenda,cd_agenda',
                'escala' => 'required|integer|exists:agenda_escala,cd_escala_agenda',
                'id' => 'nullable|integer|exists:agendamento,cd_agendamento', 
            ]);    
        }

        if($request['tp'] == "AVULSO"){
            
            $validator = Validator::make($request->all(), [
                'data_end' => 'required|date_format:Y-m-d',
                'hr_start' => 'required|date_format:H:i',
                'hr_end' => 'nullable|date_format:H:i',
                'resource' => 'nullable|integer|exists:agenda,cd_agenda',
                'escala' => 'nullable|integer|exists:agenda_escala,cd_escala_agenda',
                'id' => 'nullable|integer|exists:agendamento,cd_agendamento', 
            ]);    
            $request['resource']=0;
            $request['escala']=0;
        }

        

        if($request['tp'] == "2"){


            $NrDia = date('w', strtotime($request['data_end']));
            $dt = strtotime($request['data_end']);
            $escala = AgendaEscala::where('cd_agenda',$request['resource'])
            ->where("sn_ativo","S")
            ->where("nr_dia",$NrDia)
            ->selectRaw("min(abs(UNIX_TIMESTAMP(hr_final)-'".$dt."')) valor, cd_escala_agenda")
            ->where("dt_inicial","2000-01-01")
            ->groupBy("cd_escala_agenda")
            ->first();
            if(isset($escala->cd_escala_agenda)){ 
                $request['escala']=$escala->cd_escala_agenda;
                $escala=AgendaEscala::find($escala->cd_escala_agenda);
                $request["situacao"] = "livre";
                $request["intervalo"] = $escala->intervalo;
                $request["nm_intervalo"] = $escala->nm_intervalo;
                $request['cd_dia'] = $NrDia; 
                $qtde_encaixe= $escala->qtde_encaixe;

            }


            $validator = Validator::make($request->all(), [
                'data_end' => 'required|date_format:Y-m-d',
                'hr_start' => 'required|date_format:H:i',
                'hr_end' => 'required|date_format:H:i',
                'resource' => 'required|integer|exists:agenda,cd_agenda',
                'escala' => 'nullable|integer|exists:agenda_escala,cd_escala_agenda',
                'id' => 'nullable|integer|exists:agendamento,cd_agendamento', 
            ]);        
        
        }

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if($request['tp'] == "2"){
           
            $QtdeAgenda=RpclinicaAgendamento::where('cd_escala',$request['escala'])
            ->where('sn_atend_avulso','S')
            ->where('dt_agenda',$request->data_end)->count();  
            $QtdeAgenda= ($QtdeAgenda) ? $QtdeAgenda : 0;
            if($QtdeAgenda >= $qtde_encaixe){
                return response()->json(['message' =>'Não possivél realizar enxaixe! <br>Limite configurado: [ '.( ($qtde_encaixe) ? $qtde_encaixe : '0' ).' ]'], 400);
            }
        }


        try {
               
            $escala=AgendaEscala::where('cd_escala_agenda',$request['escala'])
            ->with('escalaTipoAtend','agenda', 'escalaLocal', 'escalaEspec', 'escalaConv', 'escalaProf')->first();
             
              
            $request['origem']= Origem_paciente::orderBy("nm_origem")->get(); 
            $request['prof_ext']= Profissional_externo::orderBy("nm_profissional_externo")->get(); 
            $request['escala'] = $escala;
            if($request['resource'])
                $request['agenda'] = Agenda::with('itens')->find($request['resource']);
            else 
                $request['agenda'] = null;

            $request['itens_pendente'] = AgendamentoItens::whereNull('cd_agendamento_guia')
            ->where('cd_agendamento',$request['id'])
            ->orderBy('created_at')->with('exame')->get();
            $request['historico']=[];

            if($request['id']){ 

                $agedamento = RpclinicaAgendamento::selectRaw("agendamento.*, date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atend")
                ->find($request['id']);
                $agedamento->load('paciente','itens','guia','tab_situacao','user_atendimento');
                $agedamento->itens->load('exame','usuario');
                $agedamento->guia->load('usuario','situacao_guia','itens.exame'); 
                 
                $request['historico'] = RpclinicaAgendamento::with('agenda', 'tab_situacao', 'profissional', 'convenio', 'paciente', 'especialidade', 'procedimento')
                ->where('cd_paciente', $agedamento->cd_paciente)
                ->orderBy('data_horario', 'desc')
                ->get();
                 

                $request['itens_agenda'] = Exame::
                join(DB::raw("(
                select distinct(cd_exame) cd_exame from (
                select cd_exame from agenda_exames where cd_agenda=".$request['resource']."
                union all
                select cd_exame from agendamento_itens where cd_agendamento=".$request['id']."
                ) consulta ) exames_agenda"),'exames_agenda.cd_exame','exames.cd_exame')
                ->leftJoin(DB::raw("(select * from agendamento_itens where cd_agendamento=".$request['id'].") agendamento_itens"),"agendamento_itens.cd_exame","exames.cd_exame")
                ->selectRaw("exames.cd_exame,nm_exame,cd_agendamento_item")
                ->get()->toArray();

               $itens_agendamento = AgendamentoItens::where('cd_agendamento',$request['id'])
               ->select("cd_exame")->get();
               $Itens = null;
               foreach($itens_agendamento as $val){
                   $Itens[] = $val->cd_exame;
               }
               $request['itens_agendamento'] = $Itens;
               
            }else{

                $agedamento = null;  
                $request['itens_agenda'] = Exame::
                join(DB::raw("(
                    select cd_exame from agenda_exames where cd_agenda=".$request['resource']." 
                ) exames_agenda"),'exames_agenda.cd_exame','exames.cd_exame')
                ->selectRaw("exames.cd_exame,nm_exame,null cd_agendamento_item")
                ->get()->toArray();
            }

            $request['itens_agenda'] = Exame::where('sn_ativo','S')
            ->selectRaw("exames.cd_exame,nm_exame,null cd_agendamento_item")
            ->get()->toArray();
            if(isset($agedamento->paciente)){
                $agedamento['paciente']['idade'] = idadeAluno($agedamento->paciente->dt_nasc);  

            }
            $request['agendamento'] = $agedamento;
            if(isset($agedamento->situacao)){
                $request['situacao_agendamento'] = AgendamentoSituacao::where('agendamento','S')
                ->whereNotIn('cd_situacao',[$agedamento->situacao])
                ->where('sn_ativo','S')
                ->orderBy('order_agendamento')->get();
    
                $request['situacao_recepcao'] = AgendamentoSituacao::where('recepcao','S')
                ->whereNotIn('cd_situacao',[$agedamento->situacao])
                ->where('sn_ativo','S')
                ->orderBy('order_recepcao')->get();

            }else{
                $request['situacao_agendamento'] = null;
                $request['situacao_recepcao'] = null;
            }

            if($escala->agenda?->tipo_atend_editavel==true){
                if($escala->agenda?->cd_tipo_atend){
                    $OpTip = null;
                    $OpTip[]=$escala->agenda?->cd_tipo_atend; 
                    if(isset($agedamento)){
                        $OpTip[]=$agedamento->tipo;
                    }
                    $request['tipo_atend']=TipoAtendimento::whereIn('cd_tipo_atendimento',$OpTip)
                    ->where('sn_ativo','S')
                    ->orderBy("nm_tipo_atendimento")->get(); 
                }else{
                    $request['tipo_atend']=TipoAtendimento::where('sn_ativo','S')
                    ->orderBy("nm_tipo_atendimento")->get(); 
                }
            }else{
                $request['tipo_atend']=TipoAtendimento::where('sn_ativo','S')
                ->orderBy("nm_tipo_atendimento")->get(); 
            }


            if($escala->agenda?->profissional_editavel==true){
                if($escala->agenda?->cd_profissional){
                    $OpTip = null;
                    $OpTip[]=$escala->agenda?->cd_profissional; 
                    if(isset($agedamento)){
                        $OpTip[]=$agedamento->cd_profissional;
                    }
                    $request['profissionais']=Profissional::whereIn('cd_profissional',$OpTip)
                    ->where('sn_ativo','S')
                    ->orderBy("nm_profissional")->get(); 
                }else{
                    $request['profissionais']=Profissional::where('sn_ativo','S')
                    ->orderBy("nm_profissional")->get(); 
                }
            }else{
                $request['profissionais']=Profissional::where('sn_ativo','S')
                ->orderBy("nm_profissional")->get(); 
            }
 
            if($escala->agenda?->local_atendimento_editavel==true){
                if($escala->agenda?->cd_local_atendimento){
                    $OpTip = null;
                    $OpTip[]=$escala->agenda?->cd_local_atendimento; 
                    if(isset($agedamento)){
                        $OpTip[]=$agedamento->cd_local_atendimento;
                    }
                    $request['locais']=LocalAtendimento::whereIn('cd_local',$OpTip)
                    ->where('sn_ativo','S')
                    ->orderBy("nm_local")->get();  
                }else{
                    $request['locais']=LocalAtendimento::where('sn_ativo','S')
                    ->orderBy("nm_local")->get(); 
                }
            }else{
                $request['locais']=LocalAtendimento::where('sn_ativo','S')
                ->orderBy("nm_local")->get(); 
            }

            if($escala->agenda?->especialidade_editavel==true){
                if($escala->agenda?->cd_especialidade){
                    $OpTip = null;
                    $OpTip[]=$escala->agenda?->cd_especialidade; 
                    if(isset($agedamento)){
                        $OpTip[]=$agedamento->cd_especialidade;
                    }
                    $request['especialidades']=Especialidade::whereIn('cd_especialidade',$OpTip)
                    ->where('sn_ativo','S')
                    ->orderBy("nm_especialidade")->get(); 
                }else{
                    $request['especialidades']= Especialidade::where('sn_ativo','S')
                    ->orderBy("nm_especialidade")->get(); 
                }
            }else{
                $request['especialidades']= Especialidade::where('sn_ativo','S')
                ->orderBy("nm_especialidade")->get(); 
            }

            if($escala->sn_convenio==true){ $OpConv[]='CO';}
            if($escala->sn_particular==true){ $OpConv[]='PA';}
            if($escala->sn_sus==true){ $OpConv[]='SUS';}
            $request['convenios']= Convenio::where('sn_ativo','S');
            if(isset($OpConv)){
                $request['convenios']=$request['convenios']->whereIn('tp_convenio',$OpConv);
            }

            $request['convenios']=$request['convenios']->orderBy("nm_convenio")->get(); 
       
  
            return response()->json($request->toArray());
 
         
        }
        
        catch (Throwable $error) {
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
         
       
    }

    public function storeItemAgendamento(Request $request, RpclinicaAgendamento $agendamento)
    {
        
        $validated = $request->validate([ 
            'exame' => 'required|integer|exists:exames,cd_exame',   
            ] , [
                'exame.required' => 'Exame não informado.',
                'exame.integer' => 'Exame tem quer integer.',
                'exame.exists' => 'Exame Invalido.', 
                'qtde.required' => 'Qtde não informado.',
            ] 
        );

        try {

            $qtdeExame=AgendamentoItens::where("cd_agendamento",$agendamento['cd_agendamento'])
            ->where('cd_exame',$request['exame'])->count();
            if($qtdeExame>0){
                return response()->json(['message' => 'Já existe esse Item no Agendamento.'], 500);
            }

            DB::beginTransaction();
 
            $exame =Exame::find($request['exame']);
            if(isset($exame->cod_proc)){
                $valor = valorContaFaturamento($agendamento['cd_convenio'],$exame->cod_proc);
            }else{
                $valor = null;
            }  
            $dados=[
                'cd_agendamento'=>$agendamento['cd_agendamento'],
                'cd_exame'=>$request['exame'],
                'vl_item'=>$valor,
                'qtde'=>($request['qtde']) ? $request['qtde'] : 1,
                'sn_anomalia'=> ($valor) ?  'N' : 'S',
                'dt_valor'=>date('Y-m-d H:i'),
                'usuario_valor'=>$request->user()->cd_usuario,
                'cd_usuario'=>$request->user()->cd_usuario
            ];
            $retorno=AgendamentoItens::create($dados);

            $retorno->saveLog($request->user(),'Cadastro',null,'agendamento','agendamento',
            array( 'agendamento'=>$request['cd_agendamento'],'paciente'=>$agendamento['cd_paciente'],'item'=>$retorno->cd_agendamento_item,'exame'=>$request['exame'] ),$_SERVER['REQUEST_URI']);
 
            DB::commit();

            if($retorno){
 
                $agendamento = RpclinicaAgendamento::find($agendamento['cd_agendamento']);
                $agendamento->load('paciente','itens','guia');
                $agendamento->itens->load('exame','usuario');
                $agendamento->guia->load('usuario','situacao_guia','itens.exame'); 

                $itens = AgendamentoItens::whereNull("cd_agendamento_guia")
                ->where('cd_agendamento',$agendamento['cd_agendamento'])
                ->orderBy('created_at')->with('exame')->get();
 
                return response()->json(['message'=>'Salvo com sucesso','retorno'=>$agendamento,'itens'=> $itens]);

            }else{
                return response()->json(['message' => ['Erro ao salvar Item']], 500);
            }
            
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
         
    }
    
    public function StoreAgendamento(Request $request)
    {
         
        try { 
            $empresa = Empresa::find($request->user()->cd_empresa);
            $validaCpf=$empresa->valida_cpf;
            $obrigaCpf=$empresa->obriga_cpf;
            if($request['cd_escala']){
                $cd_escala = AgendaEscala::find($request['cd_escala']);
                $request['cd_dia']=(int)$cd_escala['cd_dia'];
                $request['nm_intervalo']=$cd_escala['nm_intervalo'];
                $request['intervalo']=$cd_escala['intervalo'];
                $qtde_encaixe = ($cd_escala['qtde_encaixe']) ? $cd_escala['qtde_encaixe'] : 0;
            }
            if($empresa->sn_item_agendamento == 'S'){ $tipo='required'; }else{ $tipo='nullable'; }
             
            $validator = Validator::make($request->all(),[

                    'cd_horario' => 'nullable|integer|exists:agenda_escala_horario,cd_agenda_escala_horario',
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
                    'cd_dia' => 'required|integer',
                    'intervalo' => 'required|integer',
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
                    'item_agendamento' => $tipo.'|array|min:1|exists:exames,cd_exame'
               
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
 
            if(!$request['cd_horario']){
                $QtdeAgenda=RpclinicaAgendamento::where('cd_escala',$request['cd_escala'])
                ->where('sn_atend_avulso','S')
                ->where('dt_agenda',$request->dt_agenda)->count();
                $QtdeAgenda= ($QtdeAgenda) ? $QtdeAgenda : 0; 
                if($QtdeAgenda >= $qtde_encaixe){
                    return response()->json(['errors' => ['Não possivél realizar enxaixe!']], 400);
                }
            }

         
            DB::beginTransaction();
  
            if ($request->has('cd_paciente')) {
                $paciente = Paciente::firstOrCreate(
                    ['cd_paciente' => $request->cd_paciente],
                    [
                        'nm_paciente' => $request->cd_paciente,
                        /*
                        'dt_nasc'=> $request->dt_nasc,
                        'cpf'=> preg_replace('/[^0-9]/', '',$request->cpf ), 
                        'rg'=> $request->rg,
                        'profissao'=> $request->ds_profissao,
                        'email'=> $request->email,
                        'celular'=>  preg_replace('/[^0-9]/', '', $request->celular),
                        */
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
  
    public function viewEvento(Request $request){

      
        return view('rpclinica.recepcao.agendamento.eventos',compact('request'))->render();

    }
 
    public function destroyAgendamento(Request $request, $agendamendo)
    {
        try {

            $qtd = AgendamentoDocumentos::where('cd_agendamento',$agendamendo)->count();
            if($qtd>0){
                return response()->json(['errors' => array('<b>Atenção!!!<b> <br>Esse atendimento possui Documentos de prontuario criado!')], 500);
            }
            $qtd = AgendamentoAnexos::where('cd_agendamento',$agendamendo)->count();
            if($qtd>0){
                return response()->json(['errors' => array('<b>Atenção!!!<b> <br>Esse atendimento possui Anexos importados!')], 500);
            }
            $qtd = AgendamentoItens::where('cd_agendamento',$agendamendo)->where('sn_laudo','1')->count();
            if($qtd>0){
                return response()->json(['errors' => array('<b>Atenção!!!<b> <br>Esse atendimento possui exames Laudados!')], 500);
            }
            $qtd = RpclinicaAgendamento::where('cd_agendamento',$agendamendo)->whereNotNull('dt_anamnese')->count();
            if($qtd>0){
                return response()->json(['errors' => array('<b>Atenção!!!<b> <br>Esse atendimento possui anamnese realiada!')], 500);
            }
            $qtd = RpclinicaAgendamento::where('cd_agendamento',$agendamendo)->where('sn_finalizado','S')->count();
            if($qtd>0){
                return response()->json(['errors' => array('<b>Atenção!!!<b> <br>Esse atendimento encontrasse finalizado!')], 500);
            }

            DB::transaction(function () use ($agendamendo,$request) {
                AgendamentoItens::where("cd_agendamento",$agendamendo)->delete();
                $agendamendo = RpclinicaAgendamento::find($agendamendo); 
                $agendamendo->delete(); 

                $agendamendo->saveLog($request->user(),'Exclusao',null,'agendamento','agendamento',
                array( 'agendamento'=>$request['cd_agendamento'],'paciente'=>$agendamendo['cd_paciente'],'item'=>$agendamendo->cd_agendamento_item,'exame'=>$request['exame'] ) ,$_SERVER['REQUEST_URI']);
      
                funcLogsAtendimentoHelpers($agendamendo['cd_agendamento'],'USUARIO DELETOU AGENDAMENTO');
            });   

            return response()->json(['message' => 'Horário excluído com sucesso!']);

        }
        catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao excluir o horário.' . $e->getMessage()], 500);
        }
    }

    public function deleteItemAgendamento(AgendamentoItens $item)
    {
        try { 

            if($item->cd_agendamento_guia){
                return response()->json(['message' => 'Erro! Esse item esta vinculado a uma Guia.'], 500);
            }

            $cdAgendamento=$item->cd_agendamento;
            $item->delete();

            $agedamento = RpclinicaAgendamento::find($cdAgendamento);
            $agedamento->load('paciente','itens');
            $agedamento->itens->load('exame','usuario');

            return response()->json(['message' => 'Item excluído com sucesso!','retorno'=>$agedamento]);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'Houve um erro ao excluir o horário.' . $e->getMessage()], 500);
        }
    }
     
    public function bloquearHorario(Request $request)
    {
        $validator = Validator::make($request->all(),[
       // $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_escala' => 'required|integer|exists:agenda_escala,cd_escala_agenda',
            'cd_horario' => 'required|integer|exists:agenda_escala_horario,cd_agenda_escala_horario',
            'dt_agenda' => 'required|date_format:Y-m-d',
            'hr_inicio' => 'required|date_format:H:i',
            'hr_fim' => 'required|date_format:H:i',
            'nm_intervalo' => 'required',
            'cd_dia' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 400);
        }

        try {
            DB::beginTransaction();
            $situacaoBloqueado= AgendamentoSituacao::where('bloqueado','S')->first();
            if(!$situacaoBloqueado){
                return response()->json(['errors' => ['Situação não configurada para essa ação!']], 400);
            }
            $Array['situacao']=$situacaoBloqueado->cd_situacao;
            $Array['usuario_geracao']=$request->user()->cd_usuario;
            $Array['cd_agenda']=$request['cd_agenda'];
            $Array['dt_agenda']=$request['dt_agenda'];
            $Array['hr_agenda']=$request['hr_inicio'];
            $Array['hr_final']=$request['hr_fim'];
            $Array['cd_escala']=$request['cd_escala'];
            $Array['intervalo']=$request['nm_intervalo'];
            $Array['cd_agenda_escala_horario']=$request['cd_horario']; 
            $Array['dia_semana']=$request['cd_dia'];
            $Array['data_horario']=trim($request['dt_agenda']).' '.$request['hr_inicio'];

            $bloqueado = RpclinicaAgendamento::create($Array); 
            funcLogsAtendimentoHelpers($bloqueado['cd_agendamento'],'USUARIO BLOQUEOU HOARARIO');
            DB::commit();

            return response()->json(['message' => 'Horário bloqueado com sucesso!']);
        }
        catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Houve um erro ao bloquear o horário. ' . $th->getMessage()], 500);
        }
    }
 
    public function bloquearHorarioModal(Request $request)
    {
        $situacaoBloqueado= AgendamentoSituacao::where('bloqueado','S')->first();
        if(!$situacaoBloqueado){
            return response()->json(['errors' => ['Situação não configurada para essa ação!']], 400);
        }
        
        
        try {
 
            foreach($request['cd_agendamento_sessao'] as $val){
                $dados = json_decode($val, true); 
                //$teste[]=$dados;
    
                DB::beginTransaction();
    
                $Array['situacao']=$situacaoBloqueado->cd_situacao;
                $Array['usuario_geracao']=$request->user()->cd_usuario;
                $Array['cd_agenda']=$dados['resource'];
                $Array['dt_agenda']=$dados['start'];
                $Array['hr_agenda']=substr($dados['ds_start'],0,5);
                $Array['hr_final']=substr($dados['ds_end'],0,5);
                $Array['cd_escala']=$dados['escalaId'];
                $Array['intervalo']=$dados['escalaNmIntervalo'];
                $Array['cd_agenda_escala_horario']=$dados['cd_horario']; 
                $Array['dia_semana']=$dados['cd_dia'];
                $Array['data_horario']=trim($dados['start']);
    
                $bloqueado = RpclinicaAgendamento::create($Array); 
                funcLogsAtendimentoHelpers($bloqueado['cd_agendamento'],'USUARIO BLOQUEOU HOARARIO');
                DB::commit();
    
            }
            return response()->json(['request' => $request->toArray(),'message' => 'Horario bloqueado com sucesso!']);
        }
        catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Houve um erro ao gerar escala. ' . $th->getMessage()], 500);
        }
    }

    public function desbloquearHorario(Request $request)
    {
 
        $request->validate([
            'id' => 'required|integer|exists:agendamento,cd_agendamento', 
        ]);

        try {
            DB::beginTransaction();
            RpclinicaAgendamento::where('cd_agendamento',$request['id'])->delete();
            funcLogsAtendimentoHelpers($request['id'],'USUARIO DESBLOQUEOU HOARARIO');
            DB::commit();
            return response()->json(['message' => 'Horário desbloqueado com sucesso!']);
        }
        catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Houve um erro ao desbloquear o horário. ' . $th->getMessage()], 500);
        }
    }

    public function StoreConfirmacao(Request $request, RpclinicaAgendamento $agendamento, AgendamentoSituacao $situacao)
    {
        try {

            DB::beginTransaction();

            $agendamento->update([
                'situacao'=>$situacao->cd_situacao
            ]); 

            $agendamento->saveLog($request->user(),'Cadastro',null,'agendamento','confirmacao_status',
            array( 'agendamento'=>$request['cd_agendamento'],'paciente'=>$agendamento['cd_paciente'],'item'=>null,'exame'=>null ) ,$_SERVER['REQUEST_URI']);
 
            AgendamentoSituacaoLog::create([
                'cd_agendamento'=>$agendamento->cd_agendamento,
                'situacao'=>$situacao->cd_situacao,
                'cd_usuario'=>$request->user()->cd_usuario,
            ]);

            DB::commit();

            $retorno = RpclinicaAgendamento::whereRaw("date(dt_agenda)='".$request['data']."'")->whereRaw("cd_agenda=".$request['agenda']);
            if($request['situacao']){
                $retorno = $retorno->where('situacao',$request['situacao']);
            }
            $retorno = $retorno->with('paciente','agenda','especialidade','convenio','situacao')->orderBy('data_horario')
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data")->get();

            return response()->json(['message' => 'Agendamento alterado com sucesso!','retorno' => $retorno]);

        }
        catch (Throwable $th) {
            DB::rollback();
            return response()->json(['message' => 'Houve um erro ao desbloquear o horário. ' . $th->getMessage()], 500);
        }
    }
    
    public function updateStatus(Request $request){

        $validator = Validator::make($request->all(), [
            'cd_agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
            'situacao' => 'required|exists:agendamento_situacao,cd_situacao' 
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $Array['situacao']=$request['situacao'];
            $Situacoes=AgendamentoSituacao::find($request['situacao']);
            if($Situacoes->gerar_atend=='S'){
                $Array['sn_atendimento']='S';
                $Array['dt_atendimento']=date('Y-m-d H:i');
                $Array['usuario_atendimento']=$request->user()->cd_usuario; 
                $Array['dt_presenca']=date('Y-m-d H:i');
                $Array['sn_presenca']=true;
                $Array['user_presenca']=$request->user()->cd_usuario; 
            }
            $presenca['data']=date('d/m/Y H:i');
            $presenca['user']=$request->user()->cd_usuario;
           
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

            return response()->json(['message' => 'Agendamento atualizado com sucesso!','query'=> $query, 'retorno'=> $retorno, 'presenca'=>$presenca]);
            
        }

        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao excluir o Procedimento. ' . $th->getMessage()], 500);
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

   
 
}
