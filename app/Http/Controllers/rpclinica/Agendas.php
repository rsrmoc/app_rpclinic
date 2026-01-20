<?php


namespace App\Http\Controllers\rpclinica;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\usuarios\EscalasRequest;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\AgendaConvenios;
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEscalaHorario;
use App\Model\rpclinica\AgendaEspecialidades;
use App\Model\rpclinica\AgendaExames;
use App\Model\rpclinica\AgendaExclusaoDatas;
use App\Model\rpclinica\AgendaIntervalo;
use App\Model\rpclinica\AgendaLocais;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoBloqueio;
use App\Model\rpclinica\AgendaProcedimentos;
use App\Model\rpclinica\AgendaProfissionais;
use App\Model\rpclinica\BloqueioAgendamento;
use App\Model\rpclinica\BloqueioAgendamentoGerado;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\Feriado;
use App\Model\rpclinica\FeriadoAgendamentoGerado;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\TipoAtendimento;
use App\Model\rpclinica\AgendaTipoAtendimento;
use App\Model\rpclinica\Exame;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\Else_;
use Throwable;

class Agendas extends Controller
{

    public function index(Request $request)
    {
      
        if ($request->query('b')) {
            $agendas = Agenda::with('profissional', 'especialidade', 'local')
                ->where('cd_agenda', $request->b)
                ->orWhere('nm_agenda', 'LIKE', "%{$request->b}%")
                ->get();
        } else {
            $agendas = Agenda::with('profissional', 'especialidade', 'local')->get();
        }

        return view('rpclinica.agenda.lista', compact('agendas'));
    }

    public function create(Request $request)
    {
        $profissionais =  Profissional::whereRaw("sn_ativo='S'")->orderby("nm_profissional")->get();
        $locais =   LocalAtendimento::whereRaw("sn_ativo='S'")->orderby("nm_local")->get();
        $especialidades =   Especialidade::whereRaw("sn_ativo='S'")->orderby("nm_especialidade")->get();
        $tipos = TipoAtendimento::whereRaw("sn_ativo='S'")->orderby("nm_tipo_atendimento")->get();
        $convenios  = Convenio::whereRaw("sn_ativo='S'")->orderby("nm_convenio")->get();
        $intervalos = AgendaIntervalo::orderBy('mn_intervalo')->get();
        $empresas  = Empresa::whereRaw("sn_ativo='S'")->orderby("nm_empresa")->get();
        $exames =  Exame::whereRaw("sn_ativo='S'")->orderby("nm_exame")->get();

        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.agenda.add', compact('profissionais', 'tipos', 'especialidades', 'locais', 'convenios', 'intervalos','empresas','exames'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "descricao" => "required|string", 
            "tela_exec" => "required|string", 
            "profissional" => "sometimes|nullable|integer|exists:profissional,cd_profissional",
            "especialidade" => "sometimes|nullable|integer|exists:especialidade,cd_especialidade",
            "local_atendimento" => "sometimes|nullable|integer|exists:local_atendimento,cd_local",
            "observacao" => "nullable|string|max:1024",
            'tipo_atend' => 'sometimes|nullable|string|exists:tipo_atendimento,cd_tipo_atendimento',
            "tipo_atendimento-editavel" => "sometimes|boolean",
            "procedimento-editavel" => "sometimes|boolean",
            "especialidade-editavel" => "sometimes|boolean",
            "local_etendimento-editavel" => "sometimes|boolean",
            'cd_proc' => 'sometimes|nullable|string|exists:procedimento,cod_proc',
            'cd_exame' => 'nullable|array',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        try {
            $fields = [
                'nm_agenda' => $request->post('descricao'),
                'tela_exec' => $request->post('tela_exec'),
                'cd_local_atendimento' => $request->post('local_atendimento'),
                'cd_especialidade' => $request->post('especialidade'),
                'cd_profissional' => $request->post('profissional'),
                'obs' => $request->post('observacao'),
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_tipo_atend' => (trim($request->post('tipo_atend'))) ? trim($request->post('tipo_atend')) : null,
                'tipo_atend_editavel' => $request->post('tipo_atendimento-editavel'),
                'profissional_editavel' => $request->post('profissional-editavel'),
                'especialidade_editavel' => $request->post('especialidade-editavel'),
                'local_atendimento_editavel' => $request->post('local_etendimento-editavel'),
                'cd_empresa' => $request->user()->cd_empresa,
                'sn_agenda_aberta' => ($request['sn_agenda_aberta']=='S') ? 'S' : 'N',
                'sn_ativo' => 'S',
               

            ];
         

            $agenda = Agenda::create($fields);

            if ($request->cd_exame) {
                foreach ($request->cd_exame as $exame) {
                    AgendaExames::create([
                        'cd_agenda' => $agenda->cd_agenda,
                        'cd_exame' => $exame
                    ]);
                }
            }
 
            return redirect()->route('agenda.edit',['agenda' => $agenda->cd_agenda,'tab' => 'escala'])->with('success', 'Agenda cadastrada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a agenda. ' . $e->getMessage()]);
        }
    }

    public function storeEscalaManual(Request $request)
    {
        
        
        $validator = Validator::make($request->all(),[ 
                'data' => 'required|date_format:Y-m-d',
                'hr_inicio' => 'required|date_format:H:i',
                'hr_final' => 'required|date_format:H:i', 
                'intervalo' => 'required|integer', 
                'resource' => 'required|exists:agenda,cd_agenda'  
        ]  );
 
      
        try {
            
            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }

            $day=date('w', strtotime($request->data)); 
         
            if($request['sn_diario']=='S'){
                $Qtde=AgendaEscala::where('nr_dia',$day)->where('cd_agenda',$request['resource'])
                ->where('sn_ativo','S')->whereNull('escala_manual')->count();
                if($Qtde <= 0){
                    return response()->json(['message' => 'Atenção, Não é permitido criar uma escala diaria quando não existe escala criada, Nesse caso o usuario deverá criar uma escala Manual.'], 400);
                }
            }
 
            DB::beginTransaction(); 
            $request['cd_agenda']=$request['resource'];
            $request['hora_inicial']=$request['hr_inicio'];
            $request['hora_final']=$request['hr_final'];
            $ESCALA = $request['cd_escala'];  
            $diasemana = array(0=>'domingo', 1=>'segunda', 2=>'terca',3=>'quarta', 4=>'quinta', 5=>'sexta', 6=>'sabado');
            $Intervalo=AgendaIntervalo::find($request['intervalo']);
        
            //verifica Escala Normal
            $Escalas=AgendaEscala::where('cd_agenda',$request['cd_agenda'])
            ->where('nr_dia',$day)
            ->whereNull('escala_manual')
            ->where('sn_ativo','S')->get();
             
            if(empty($request['sn_diario'])){
                foreach($Escalas as $esc){
                    $dt1_1 = date("H:i", strtotime($request['hora_inicial']));
                    $dt1_2 = date("H:i", strtotime($request['hora_final']));
                    $dt2_1 = date("H:i", strtotime($esc->hr_inicial));
                    $dt2_2 = date("H:i", strtotime($esc->hr_final));
    
                    if(($dt1_1 < $dt2_2) && ($dt1_2 > $dt2_1)){
                        DB::rollback();
                        return response()->json(['message' => 'O Intervalo ('.$day.') [ '.$request['hora_inicial'].' - '.$request['hora_final'].' ]  esta conflitando com a escala ['.$esc->cd_escala_agenda.'] ' ], 400);
                    } 
                }
            }
            //verifica Escala Manual e Diaria
            $Escalas=AgendaEscala::where('cd_agenda',$request['cd_agenda'])
            ->where('nr_dia',$day)
            ->where('escala_manual','S')
            ->where('dt_inicial',$request['data'])
            ->where('sn_ativo','S')->get();
             
            if(empty($request['sn_diario'])){
                foreach($Escalas as $esc){
                    $dt1_1 = date("H:i", strtotime($request['hora_inicial']));
                    $dt1_2 = date("H:i", strtotime($request['hora_final']));
                    $dt2_1 = date("H:i", strtotime($esc->hr_inicial));
                    $dt2_2 = date("H:i", strtotime($esc->hr_final));
    
                    if(($dt1_1 < $dt2_2) && ($dt1_2 > $dt2_1)){
                        DB::rollback();
                        return response()->json(['message' => 'O Intervalo ('.$day.') [ '.$request['hora_inicial'].' - '.$request['hora_final'].' ]  esta conflitando com a escala ['.$esc->cd_escala_agenda.'] ' ], 400);
                    } 
                }
            }
            $intervalo = $request['intervalo'];
            $hr_inicial = substr($request['hora_inicial'],0,5);
            $cd_inicial = str_replace(':', '.', $request['hora_inicial']);
            $hr_final =  substr($request['hora_final'],0,5);
            $cd_final = str_replace(':', '.', $request['hora_final']);
            $QtdeProc=0;
            for ($hr = $cd_inicial; $hr <  $cd_final;) {
                $horaNova = strtotime("$hr_inicial + ".$intervalo." minutes"); 
                $horaNovaFormatada = date("H:i",$horaNova); 
                $hr = str_replace(':', '.', $horaNovaFormatada);
                $hr_inicial = $horaNovaFormatada; 
                $QtdeProc=($QtdeProc+1);
            }
            
            $Array=array(
                'cd_agenda'=>$request['cd_agenda'],
                'cd_dia'=> $diasemana[$day],
                'nr_dia'=> $day,
                'qtde_proc'=> $QtdeProc,
                'situacao'=>'Aberto',
                'dt_inicial'=> $request->data,
                'dt_fim'=> $request->data,
                'hr_inicial'=>$request['hora_inicial'],
                'hr_final'=>$request['hora_final'],
                'intervalo'=>$request['intervalo'],
                'nm_intervalo'=> $Intervalo['mn_intervalo'], 
                'sn_particular'=>'S',
                'escala_manual'=>'S',
                'escala_diaria'=>($request['sn_diario']=='S') ? $request['sn_diario'] : null,
                'sn_convenio'=>'S',
                'sn_sus'=>'S',
                'sn_ativo'=>'S'
            ); 
            
             $escala=AgendaEscala::create($Array);
             $request['cd_escala']=$escala->cd_escala_agenda;
             
            $hr_inicial = $request['hora_inicial'];
            $cd_inicial = str_replace(':', '.', $request['hora_inicial']); 
            $cd_final = str_replace(':', '.', $request['hora_final']); 
            $intervalo = $request['intervalo'];

           
            for ($hr = $cd_inicial; $hr <  $cd_final;) {

                $horaNova = strtotime("$hr_inicial + ".$intervalo." minutes"); 
                $horaNovaFormatada = date("H:i",$horaNova); 
                $HoraAgedamento = str_replace('.', ':', $hr); 
                $hr = str_replace(':', '.', $horaNovaFormatada);
                $hr_inicial = $horaNovaFormatada;
     
                $Array=array(
                 'cd_escala_agenda'=>$escala->cd_escala_agenda,
                 'cd_agenda'=>$escala->cd_agenda,
                 'cd_horario'=>$HoraAgedamento,
                 'cd_usuario'=>$request->user()->cd_usuario 
                );
                AgendaEscalaHorario::create($Array);
      
             }
                 
             DB::commit();
             

             return response()->json(['message'=>'Salvo com sucesso','request'=>$request->toArray()]);


        } catch (Exception $e) {
            DB::rollback(); 
            return response()->json(['message' => 'Não foi possivel cadastrar a agenda. ' . $e->getMessage()], 400);
        }

    }

    public function storeEscala(EscalasRequest $request)
    {

        try {


            if ($request->has('semana')) {

                DB::beginTransaction();
                
                $ESCALA = $request['cd_escala']; 
                $escalaAntiga = $request['cd_escala']; 
                
                if($ESCALA){
                    $dadosEscala=AgendaEscala::find($ESCALA);
                    if( ($dadosEscala->hr_inicial<>$request['hora_inicial'])  || ($dadosEscala->hr_final<>$request['hora_final']) || ($dadosEscala->intervalo<>$request['intervalo']) ){
                        
                        Agendamento::whereRaw("dt_agenda >=curdate()")->whereNull("cd_paciente")
                        ->where('cd_agenda',$request['cd_agenda'])->where('cd_escala',$request['cd_escala'])->delete();
    
                        Agendamento::whereRaw("dt_agenda >=curdate()") 
                        ->where('cd_agenda',$request['cd_agenda'])->where('cd_escala',$request['cd_escala'])->update(['cd_agenda_escala_horario'=> null]);
               
                        AgendaEscala::whereRaw("cd_escala_agenda=".$ESCALA)->update(['sn_ativo'=> 'N', 'usuario_up'=> $request->user()->cd_usuario]);
                        $ESCALA=null;
                        
                    }else{
                        
                        $Array=array(
                            'qtde_sessao'=>$request['qtde_sessao'],
                            'sn_sessao'=>$request['sn_sessao'],
                            'qtde_encaixe'=>$request['qtde_encaixe'],
                            'sn_particular'=>$request['particular'],
                            'sn_convenio'=>$request['convenio'],
                            'sn_sus'=>$request['sus']
                        );
                        $retorno=AgendaEscala::whereRaw("cd_escala_agenda = ".$ESCALA)
                        ->update($Array);

                        DB::commit();

                        return redirect()->route('agenda.edit', ['agenda' => $request['cd_agenda'],'tab' => 'escala'])->with('success', 'Agenda cadastrada com sucesso!');

                    }   


                }

                //dd($request->toArray());
                foreach ($request['semana'] as $day) {

                    $diasemana = array('domingo'=>0, 'segunda'=>1, 'terca'=>2, 'quarta'=>3, 'quinta'=>4, 'sexta'=>5, 'sabado'=>6);
                    $Intervalo=AgendaIntervalo::find($request['intervalo']);

                    $Escalas=AgendaEscala::where('cd_agenda',$request['cd_agenda'])
                    ->where('nr_dia',$diasemana[$day])
                    ->where('sn_ativo','S')->Get();
                    foreach($Escalas as $esc){
                        $dt1_1 = date("H:i", strtotime($request['hora_inicial']));
                        $dt1_2 = date("H:i", strtotime($request['hora_final']));
                        $dt2_1 = date("H:i", strtotime($esc->hr_inicial));
                        $dt2_2 = date("H:i", strtotime($esc->hr_final));

                        if(($dt1_1 <= $dt2_2) && ($dt1_2 >= $dt2_1)){
                            DB::rollback();
                            return redirect()->back()->withInput()->withErrors(['error' => 'O Intervalo ('.$day.') [ '.$request['hora_inicial'].' - '.$request['hora_final'].' ]  esta conflitando com a escala ['.$esc->cd_escala_agenda.'] ' ]);
                        } 
                    }

                    $intervalo = $request['intervalo'];
                    $hr_inicial = substr($request['hora_inicial'],0,5);
                    $cd_inicial = str_replace(':', '.', $request['hora_inicial']);
                    $hr_final =  substr($request['hora_final'],0,5);
                    $cd_final = str_replace(':', '.', $request['hora_final']);
                    $QtdeProc=0;
                    for ($hr = $cd_inicial; $hr <  $cd_final;) {
                        $horaNova = strtotime("$hr_inicial + ".$intervalo." minutes"); 
                        $horaNovaFormatada = date("H:i",$horaNova); 
                        $hr = str_replace(':', '.', $horaNovaFormatada);
                        $hr_inicial = $horaNovaFormatada; 
                        $QtdeProc=($QtdeProc+1);
                    }
                   

                    if(!$ESCALA){

                        $Array=array(
                            'cd_agenda'=>$request['cd_agenda'],
                            'cd_dia'=>$day,
                            'nr_dia'=>$diasemana[$day],
                            'qtde_proc'=> $QtdeProc,
                            'situacao'=>'Aberto',
                            'dt_inicial'=> ($request['data_inicial']) ? $request['data_inicial'] : '2000-01-01',
                            'dt_fim'=> ($request['data_final']) ? $request['data_final'] : '2050-12-31',
                            'hr_inicial'=>$request['hora_inicial'],
                            'hr_final'=>$request['hora_final'],
                            'intervalo'=>$request['intervalo'],
                            'nm_intervalo'=> $Intervalo['mn_intervalo'],
                            'qtde_sessao'=>$request['qtde_sessao'],
                            'sn_sessao'=>$request['sn_sessao'],
                            'qtde_encaixe'=>$request['qtde_encaixe'],
                            'sn_particular'=>$request['particular'],
                            'sn_convenio'=>$request['convenio'],
                            'sn_sus'=>$request['sus'],
                            'sn_ativo'=>'S'
                        );

                        $escala=AgendaEscala::create($Array);
                        $request['cd_escala']=$escala->cd_escala_agenda;

                        $hr_inicial = $request['hora_inicial'];
                        $cd_inicial = str_replace(':', '.', $request['hora_inicial']); 
                        $cd_final = str_replace(':', '.', $request['hora_final']); 
                        $intervalo = $request['intervalo'];
 
                        for ($hr = $cd_inicial; $hr <  $cd_final;) {
           
                            $horaNova = strtotime("$hr_inicial + ".$intervalo." minutes"); 
                            $horaNovaFormatada = date("H:i",$horaNova); 
                            $HoraAgedamento = str_replace('.', ':', $hr); 
                            $hr = str_replace(':', '.', $horaNovaFormatada);
                            $hr_inicial = $horaNovaFormatada;
                 
                            $Array=array(
                             'cd_escala_agenda'=>$escala->cd_escala_agenda,
                             'cd_agenda'=>$escala->cd_agenda,
                             'cd_horario'=>$HoraAgedamento,
                             'cd_usuario'=>$request->user()->cd_usuario 
                            );
                            AgendaEscalaHorario::create($Array);
                  
                        }

                        if($escalaAntiga){
                            Agendamento::whereRaw("dt_agenda >=curdate()") 
                            ->where('cd_agenda',$request['cd_agenda'])->where('cd_escala',$escalaAntiga)
                            ->update(['cd_escala'=> $request['cd_escala']]);
                        }



                    }else{
                        $Array=array(
                            'qtde_sessao'=>$request['qtde_sessao'],
                            'sn_sessao'=>$request['sn_sessao'],
                            'qtde_encaixe'=>$request['qtde_encaixe'],
                            'sn_particular'=>$request['particular'],
                            'sn_convenio'=>$request['convenio'],
                            'sn_sus'=>$request['sus']
                        );
                        AgendaEscala::whereRaw("cd_escala_agenda = ".$ESCALA)
                        ->update($Array);
                        $request['cd_escala']=$ESCALA;
                    }
  
                }

                DB::commit();

            }
            
            return redirect()->route('agenda.edit', ['agenda' => $request['cd_agenda'],'tab' => 'escala'])->with('success', 'Agenda cadastrada com sucesso!');

        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel cadastrar a agenda. ' . $e->getMessage()]);
        }

    }

    public function agendamentoEncaixe(Request $request, $escala)
    {
       
        $retorno=Agendamento::where('cd_escala',$escala)
        ->with(['paciente','situacao','profissional','horario_disponivel'])
        ->whereRaw(" dt_agenda >= curdate() ") 
        ->whereNull("cd_agenda_escala_horario")
        ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data")
        ->get();  
    
        return response()->json(['retorno' => $retorno]);
         
    }

    public function storeAgendamentoEncaixe(Request $request, Agendamento $agendamento, AgendaEscalaHorario $horario)
    {
        try {

            DB::beginTransaction(); 
            $qtde = Agendamento::where('dt_agenda',$agendamento->dt_agenda)->where('cd_escala',$agendamento->cd_escala)
            ->where('cd_agenda_escala_horario',$horario->cd_agenda_escala_horario)->count();
            if($qtde>0){
                return response()->json(['message' => 'Já existe agendamentos  cadastrados para essa Horario!'], 400);
            }
            $agendamento->update(['cd_agenda_escala_horario'=>$horario->cd_agenda_escala_horario]);
            DB::commit();

            $retorno=Agendamento::where('cd_escala',$agendamento->cd_escala)
            ->with(['paciente','situacao','profissional','horario_disponivel'])
            ->whereRaw(" dt_agenda >= curdate() ") 
            ->whereNull("cd_agenda_escala_horario")
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data")
            ->get();

        return response()->json(['retorno' => $retorno, 'message'=> 'Horario cadastrado com sucesso!']);
        } catch (Exception $e) {
            DB::rollback(); 
            return response()->json(['message' => 'Não foi possivel cadastrar o horario. ' . $e->getMessage()], 400);
        }
         
    }

    public function edit(Request $request, Agenda $agenda)
    {
 
        if(empty($request['tab'])){ $Tab='agenda'; }else{ $Tab='escala'; }

        if($agenda->profissional_editavel){
            $profissionaisEscala =  Profissional::find($agenda->cd_profissional);
            $profissionais =  Profissional::whereRaw("sn_ativo='S'")->orderby("nm_profissional")->get();
        }else{ 
            $profissionais =  Profissional::whereRaw("sn_ativo='S'")->orderby("nm_profissional")->get();
            $profissionaisEscala = $profissionais;
        }

        if($agenda->local_atendimento_editavel){
            $locaisEscala =  LocalAtendimento::find($agenda->cd_local_atendimento);
            $locais =   LocalAtendimento::whereRaw("sn_ativo='S'")->orderby("nm_local")->get();
        }else{ 
            $locais =   LocalAtendimento::whereRaw("sn_ativo='S'")->orderby("nm_local")->get();
            $locaisEscala = $locais;
        }
        
        if($agenda->especialidade_editavel){
            $especialidadesEscala =  Especialidade::find($agenda->cd_especialidade);
            $especialidades = Especialidade::whereRaw("sn_ativo='S'")->orderby("nm_especialidade")->get();
        }else{ 
            $especialidades = Especialidade::whereRaw("sn_ativo='S'")->orderby("nm_especialidade")->get();
            $especialidadesEscala = $especialidades;
        }

        if($agenda->tipo_atend_editavel){
            $tipoEscala =  TipoAtendimento::find($agenda->cd_tipo_atend);
            $tipo  = TipoAtendimento::whereRaw("sn_ativo='S'")->orderby("nm_tipo_atendimento")->get();
        }else{ 
            $tipo  = TipoAtendimento::whereRaw("sn_ativo='S'")->orderby("nm_tipo_atendimento")->get();
            $tipoEscala = $tipo;
        }
 
        $procedimentos = Procedimento::whereRaw("sn_ativo='S'")->orderby("nm_proc")->get();
        $convenios  = Convenio::whereRaw("sn_ativo='S'")->orderby("nm_convenio")->get();
        $intervalos = AgendaIntervalo::orderBy('mn_intervalo')->get(); 
        $exames =  Exame::with(['agenda_exame' => function ($q) use($agenda){
            $q->where('cd_agenda', $agenda->cd_agenda);
        }])->whereRaw("sn_ativo='S'")->orderby("nm_exame")->get();

  
        AgendaEscala::whereRaw("dt_inicial < curdate()")
        ->where('sn_ativo','S')->where('escala_manual','S')
        ->update(['sn_ativo'=>'N']);

        $escalas = AgendaEscala::whereRaw("cd_agenda=".$agenda->cd_agenda)
        ->where('sn_ativo','S')
        ->with(['escalaTipoAtend', 'escalaEspec', 'escalaLocal','escalaConv','escalaProf','agendamento.paciente','agendamento'=> function($q) use($request){ 
            $q->whereRaw(" dt_agenda >= curdate() ")
            ->whereRaw("cd_paciente is not null"); 
        },'agendamento_pendente'])
        ->orderByRaw("escala_manual,nr_dia,dt_inicial,hr_inicial")
        ->selectRaw("agenda_escala.*,date_format(dt_inicial,'%d/%m/%Y') data_inicial,
        date_format(dt_fim,'%d/%m/%Y') data_final, date_format(hr_inicial,'%H:%i') hora_inicial,
        date_format(hr_final,'%H:%i') hora_final,escala_manual ")->get();
        
        $agenda->load('profissionais', 'especialidades', 'procedimentos', 'locais', 'convenios');


        if(!$request->user()->cd_empresa){
            return redirect()->back()->withInput()->with('error', 'Não existe empresa Cadastrada para esse Usuario  !');  
        }

        return view('rpclinica.agenda.edit', compact('profissionais', 'profissionaisEscala' , 'locais','locaisEscala' , 'especialidades', 'especialidadesEscala' , 'procedimentos', 'convenios', 'agenda', 'intervalos', 'Tab', 'escalas','tipo','tipoEscala','exames'));
    }

    public function update(Request $request, Agenda $agenda)
    {

        $validator = Validator::make($request->all(), [
            "descricao" => "required|string",  
            "tela_exec" => "required|string", 
            "profissional" => "sometimes|nullable|integer|exists:profissional,cd_profissional",
            "especialidade" => "sometimes|nullable|integer|exists:especialidade,cd_especialidade",
            "local_atendimento" => "sometimes|nullable|integer|exists:local_atendimento,cd_local",
            "observacao" => "nullable|string|max:1024",
            'procedimento' => 'sometimes|nullable|integer|exists:procedimento,cd_proc',
            "profissional-editavel" => "sometimes|boolean",
            "procedimento-editavel" => "sometimes|boolean",
            "especialidade-editavel" => "sometimes|boolean",
            "local_etendimento-editavel" => "sometimes|boolean",
            'qtde_sus' => "sometimes|nullable|integer",
            'qtde_particular' => "sometimes|nullable|integer",
            'qtde_convenio' => "sometimes|nullable|integer",
            'qtde_sessao' => "sometimes|nullable|integer",
            'sn_sus' => "sometimes|boolean",
            'sn_particular' => "sometimes|boolean",
            'sn_convenio' => "sometimes|boolean",
            'sn_sessao' => "sometimes|boolean",
            'qtde_encaixe' => "sometimes|nullable|integer",
            'sn_agenda_manual' => "nullable|string"
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }


        try {
            $fields = [
                'nm_agenda' => $request->post('descricao'),
                'tela_exec' => $request->post('tela_exec'),
                'cd_local_atendimento' => $request->post('local_atendimento'),
                'cd_especialidade' => $request->post('especialidade'),
                'cd_profissional' => $request->post('profissional'),
                'obs' => $request->post('observacao'),
                'cd_usuario' => $request->user()->cd_usuario,
                'cd_proc' => $request->post('procedimento'),
                'procedimento_editavel' => $request->post('procedimento-editavel'),
                'profissional_editavel' => $request->post('profissional-editavel'),
                'especialidade_editavel' => $request->post('especialidade-editavel'),
                'local_atendimento_editavel' => $request->post('local_etendimento-editavel'),'cd_tipo_atend' => (trim($request->post('tipo_atend'))) ? trim($request->post('tipo_atend')) : null,
                'qtde_sus' => $request->post('qtde_sus'),
                'tipo_atend_editavel' => $request->post('tipo_atendimento-editavel'),
                'qtde_particular' => $request->post('qtde_particular'),
                'cd_empresa' => $request->user()->cd_empresa,
                'qtde_convenio' => $request->post('qtde_convenio'),
                'qtde_sessao' => $request->post('qtde_sessao'),
                'sn_sus' => $request->post('sn_sus') ? 'S' : 'N',
                'sn_particular' => $request->post('sn_particular') ? 'S' : 'N',
                'sn_convenio' => $request->post('sn_convenio') ? 'S' : 'N',
                'sn_sessao' => $request->post('sn_sessao') ? 'S' : 'N',
                'qtde_encaixe' => $request->post('qtde_encaixe'),
                'sn_agenda_manual' => (!$request->post('sn_agenda_manual')) ? 'NAO' : 'SIM',
                'sn_agenda_aberta' => ($request['sn_agenda_aberta']=='S') ? 'S' : 'N',
            ];
 
            $agenda->update($fields);
            AgendaExames::where('cd_agenda',$agenda->cd_agenda)->delete();
            if ($request->cd_exame) {
                foreach ($request->cd_exame as $exame) {
                    AgendaExames::create([
                        'cd_agenda' => $agenda->cd_agenda,
                        'cd_exame' => $exame
                    ]);
                }
            }

            return redirect()->route('agenda.listar')->with('success', 'Agenda atualizada com sucesso!');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Não foi possivel atualizar a agenda. ' . $e->getMessage()]);
        }
    }

    public function delete(Agenda $agenda)
    {
        try {
            $agenda->delete();
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function jsonAddEspecialidade(Request $request)
    {
        $validated = $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_especialidade' => 'required|integer|exists:especialidade,cd_especialidade',
        ]);

        return AgendaEspecialidades::create($validated);
    }

    public function jsonDeleteEspecialidade($cd_agenda_espec)
    {
        AgendaEspecialidades::find($cd_agenda_espec)->delete();

        return response()->json(['message' => 'Excluído com sucesso!']);
    }

    public function jsonAddProcedimento(Request $request)
    {
        $validated = $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_proc' => 'required|integer|exists:procedimento,cd_proc',
        ]);

        return AgendaProcedimentos::create($validated);
    }

    public function jsonDeleteProcedimento($cd_agenda_proc)
    {
        AgendaProcedimentos::find($cd_agenda_proc)->delete();

        return response()->json(['message' => 'Excluído com sucesso!']);
    }

    public function jsonDeleteEscala(Request $request, $cd_escala)
    {

        $Agendamentos = Agendamento::where('cd_escala',$cd_escala)->whereRaw('dt_agenda >= curdate() ')->count();
        if($Agendamentos>0){
            return response()->json(['message' => 'Existe agendamentos Futuros cadastrados para essa Escala!<br> Para exluir será necessário remover os agendamentos. '], 400);
        }
        $array['sn_ativo']='N';
        $array['usuario_up']=$request->user()->cd_usuario;
        AgendaEscala::whereRaw("cd_escala_agenda=".$cd_escala)->update($array);
        $escala = AgendaEscala::find($cd_escala);
        $Cod = $escala->cd_agenda;

        $escalas = AgendaEscala::whereRaw("cd_agenda=".$Cod)
        ->where('sn_ativo','S')
        ->with('escalaTipoAtend', 'escalaEspec', 'escalaLocal','escalaConv','escalaProf')
        ->orderByRaw("sn_ativo desc,dt_inicial desc,nr_dia,hr_inicial")
        ->selectRaw("agenda_escala.*,date_format(dt_inicial,'%d/%m/%Y') data_inicial,
        date_format(dt_fim,'%d/%m/%Y') data_final, date_format(hr_inicial,'%H:%i') hora_inicial,
        date_format(hr_final,'%H:%i') hora_final ")->get();


        return response()->json(['message' => 'Excluído com sucesso!',"escalas" => $escalas,"Cod" => $Cod]);
    }

    public function jsonAddProfissional(Request $request)
    {
        $validated = $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_profissional' => 'required|integer|exists:profissional,cd_profissional',
        ]);

        return AgendaProfissionais::create($validated);
    }

    public function jsonDeleteProfissional($cd_agenda_prof)
    {
        AgendaProfissionais::find($cd_agenda_prof)->delete();

        return response()->json(['message' => 'Excluído com sucesso!']);
    }

    public function jsonAddLocal(Request $request)
    {
        $validated = $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_local' => 'required|integer|exists:local_atendimento,cd_local',
        ]);

        return AgendaLocais::create($validated);
    }

    public function jsonDeleteLocal($cd_agenda_local)
    {
        AgendaLocais::find($cd_agenda_local)->delete();

        return response()->json(['message' => 'Excluído com sucesso!']);
    }

    public function jsonAddConvenio(Request $request)
    {
        $validated = $request->validate([
            'cd_agenda' => 'required|integer|exists:agenda,cd_agenda',
            'cd_convenio' => 'required|integer|exists:convenio,cd_convenio',
        ]);

        return AgendaConvenios::create($validated);
    }

    public function jsonDeleteConvenio($cd_agenda_conv)
    {
        AgendaConvenios::find($cd_agenda_conv)->delete();

        return response()->json(['message' => 'Excluído com sucesso!']);
    }

    public function horarios(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agenda' => 'required|integer|exists:agenda,cd_agenda',
            'escala' => 'required|integer|exists:agenda_escala,cd_escala_agenda'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $agenda = Agenda::find($request->agenda);
        $agenda->load('local', "bloqueiosGerados", "feriadosGerados");

        $escalas = AgendaEscala::find($request->escala);
        $escalas->load("bloqueiosGerados", "feriadosGerados","usuario");

        $horarios = [];

        $time1 = strtotime($escalas->hr_inicial);
        $time2 = strtotime($escalas->hr_final);
        $interval = $escalas->intervalo;

        $loopSections = ($time2 - $time1) / ($interval * 60);

        for ($i = 0; $i <= $loopSections; $i++) {
            array_push($horarios, [
                "horario" => date('H:i:s', $time1 + (($interval * 60) * $i))
            ]);
        }

        $feriados = Feriado::where('dt_feriado', '>=', $escalas->dt_inicial)
            ->where('dt_feriado', '<=', $escalas->dt_fim)
            ->get();

        return response()->json(compact('agenda', 'horarios', 'feriados', 'escalas'));
    }

    public function horarios_bkp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'agenda' => 'required|integer|exists:agenda,cd_agenda'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        $agenda = Agenda::find($request->agenda);
        $agenda->load('local', "bloqueiosGerados", "feriadosGerados");

        $horarios = [];

        $time1 = strtotime($agenda->hr_inicial);
        $time2 = strtotime($agenda->hr_final);
        $interval = $agenda->intervalo;

        $loopSections = ($time2 - $time1) / ($interval * 60);

        for ($i = 0; $i <= $loopSections; $i++) {
            array_push($horarios, [
                "horario" => date('H:i:s', $time1 + (($interval * 60) * $i))
            ]);
        }

        $feriados = Feriado::where('dt_feriado', '>=', $agenda->data_inicial)
            ->where('dt_feriado', '<=', $agenda->data_final)
            ->get();

        return response()->json(compact('agenda', 'horarios', 'feriados'));
    }

    public function gerarAgendamentos(Request $request)
    {

        $request->validate([
            "cd_agenda" => "required|integer|exists:agenda,cd_agenda",
            'cd_escala' => 'required|integer|exists:agenda_escala,cd_escala_agenda',
            "horarios_bloqueados" => "array",
            "feriados" => "array"
        ]);

        $AgendaEscala = AgendaEscala::find($request->cd_escala);
        $AgendaEscala->update(["situacao" => 'Gerado', "escala_gerada"=> true, "dt_geracao"=> date('Y-m-d H:i') ,"usuario_geracao"=> $request->user()->cd_usuario,'situacao'=>'Cancelado' ]);

        $bloqueioAgendamento = BloqueioAgendamentoGerado::create([
            "cd_agenda" => $request->cd_agenda,
            "cd_escala" => $request->cd_escala,
            "lista_horarios" => implode(",", $request->horarios_bloqueados)
        ]);

        $feriadoAgendamento = FeriadoAgendamentoGerado::create([
            "cd_agenda" => $request->cd_agenda,
            "cd_escala" => $request->cd_escala,
            "lista_datas" => implode(",", $request->feriados)
        ]);

        $dataLoop = Carbon::parse($AgendaEscala->dt_inicial);
        $datasAgenda = [];

        do {
            $datasAgenda[] = $dataLoop->format('Y-m-d');
            $dataLoop->addDay();
        } while (!$dataLoop->gt(Carbon::parse($AgendaEscala->dt_fim)));



        $countAgendamentosCriados = 0;

        foreach ($datasAgenda as $data) {
            $dayOfWeek = date('w', strtotime($data));

            if((int)trim($dayOfWeek)<>$AgendaEscala->nr_dia){
                continue;
            }

            if (in_array($data, $request->feriados)) {
                continue;
            }

            $time1 = strtotime($AgendaEscala->hr_inicial);
            $time2 = strtotime($AgendaEscala->hr_final);
            $interval = $AgendaEscala->intervalo;

            $loopSections = ($time2 - $time1) / ($interval * 60);

            for ($i = 0; $i <= $loopSections; $i++) {
                $horario = date('H:i:s', $time1 + (($interval * 60) * $i));

                Agendamento::create([
                    "cd_agenda" => $AgendaEscala->cd_agenda,
                    "cd_escala" => $AgendaEscala->cd_escala_agenda,
                    "tipo" => $AgendaEscala->tp_agenda,
                    "data_horario" => "{$data} {$horario}",
                    "cd_bloqueio_gerado" => $bloqueioAgendamento->cd_bloqueio,
                    "cd_feriado_gerado" => $feriadoAgendamento->cd_feriado_agendamento,
                    "situacao" => in_array($horario, $request->horarios_bloqueados) ? "bloqueado": "livre",
                    "dt_agenda" => $data,
                    "hr_agenda" => substr($horario, 0, 5),
                    "dia_semana" => $dayOfWeek
                ]);

                $countAgendamentosCriados++;
            }
        }

        $escalas = AgendaEscala::whereRaw("cd_agenda=".$AgendaEscala->cd_agenda)
        ->where('sn_ativo','S')
        ->with('escalaTipoAtend', 'escalaEspec', 'escalaLocal','escalaConv','escalaProf')
        ->orderByRaw("dt_inicial desc,nr_dia,hr_inicial")
        ->selectRaw("agenda_escala.*,date_format(dt_inicial,'%d/%m/%Y') data_inicial,
        date_format(dt_fim,'%d/%m/%Y') data_final, date_format(hr_inicial,'%H:%i') hora_inicial,
        date_format(hr_final,'%H:%i') hora_final ")->get();


        return response()->json(["message" => "{$countAgendamentosCriados} agendamentos gerados com sucesso!","escalas" => $escalas]);
    }

    public function pesquisaExclusao(Request $request) {
        $request->validate([
            "cd_agenda" => "required|integer|exists:agenda,cd_agenda",
            "cd_escala" => "required|integer|exists:agenda_escala,cd_escala_agenda",
            "data_inicial" => "required|date_format:Y-m-d",
            "data_final" => "required|date_format:Y-m-d"
        ]);

        $agendamentos = Agendamento::where('cd_agenda', $request->cd_agenda)
            ->where('cd_escala', $request->cd_escala)
            ->whereDate("data_horario", ">=", $request->data_inicial)
            ->whereDate("data_horario", "<=", $request->data_final)
            ->get();

        $datas = [];

        foreach ($agendamentos as $agendamento) {
            $dataAgendamento = substr($agendamento->data_horario, 0, 10);

            if (array_key_exists($dataAgendamento, $datas)) {
                if ($agendamento->situacao == "livre" || $agendamento->situacao == "bloqueado") {
                    $datas[$dataAgendamento]["livres"] += 1;
                    continue;
                }

                $datas[$dataAgendamento]["ocupados"] += 1;
                continue;
            }

            $datas[$dataAgendamento] = [
                "livres" => $agendamento->situacao == "livre" || $agendamento->situacao == "bloqueado" ? 1: 0,
                "ocupados" => $agendamento->situacao != "livre" && $agendamento->situacao != "bloqueado" ? 1: 0
            ];
        }

        return response()->json($datas);
    }

    public function excluirAgendamentosGerados(Request $request) {

        $validator = Validator::make($request->all(), [
            "cd_agenda" => "required|integer|exists:agenda,cd_agenda",
            "cd_escala" => "required|integer|exists:agenda_escala,cd_escala_agenda",
            "datas" => "required|array",
        ]);



        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        AgendaExclusaoDatas::create([
            "cd_agenda" => $request->cd_agenda,
            "cd_escala" => $request->cd_escala,
            "lista_datas" => implode(",", $request->datas),
            "cd_usuario" => $request->user()->cd_usuario
        ]);

        foreach($request->datas as $data) {
            $agendamentos = Agendamento::where("cd_agenda", $request->cd_agenda)
            ->where("cd_escala", $request->cd_escala)
                ->whereIn("situacao", ["livre","bloqueado"])
                ->whereDate("data_horario", $data);
            $agendamentos->delete();
        }

        return response()->json(["message" => "Agendamentos excluidos com sucesso!"]);
    }

 

}
