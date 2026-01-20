<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoAnexos;
use App\Model\rpclinica\AgendamentoDocumentos;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\TipoAtendimento;
use App\Model\rpclinica\Usuario;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Consultorio extends Controller {

    public function index(Request $request) {
 
        if($request['relacaoCampos']){
            $Campos=null;
            foreach($request['relacaoCampos'] as $ix => $campo){
                if($ix==0){ $Campos = $campo; }else{ $Campos = $Campos.','.$campo; }
            }
            if($Campos){
                Usuario::where('cd_usuario',$request->user()->cd_usuario)
                ->update(['consultorio_campos'=> $Campos]);
            }
            $request['Campos'] = explode(',',$Campos);

        }else{

            if(!$request->user()->consultorio_campos){
                $Campos = 'horario,espera,paciente,tipo,convenio,prof,situacao';
                $request['Campos'] = explode(',',$Campos);
            }else{
                $request['Campos'] = explode(',',$request->user()->consultorio_campos);
            }

        }


        $agendas = null;  
        $profissionais = Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get();
        $especialidades = Especialidade::where('sn_ativo','S')->orderBy('nm_especialidade')->get();
        $convenios = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $procedimentos = null;
        $empresa = Empresa::find($request->user()->cd_empresa);
        $tipoAtend = TipoAtendimento::where('sn_ativo','S')->orderBy('nm_tipo_atendimento')->get();
        $localAtend = LocalAtendimento::where('sn_ativo','S')->orderBy('nm_local')->get();
        $situacao = AgendamentoSituacao::where('sn_ativo','S')->orderBy('nm_situacao')
        ->whereRaw(" ifnull(bloqueado,'')<>'S' and ifnull(livre,'')<>'S' and ifnull(cancelar,'')<>'S' ")
        ->get();


        return view('rpclinica.consultorio.consultorio', compact('agendas', 'profissionais', 'especialidades', 'procedimentos', 'convenios','request','empresa','tipoAtend','localAtend','situacao'));
    }

    public function show(Request $request) {
         
        $request['not_situacao'] =array('bloqueado');
        $getStatus=null;
        $empresa = Empresa::find($request->user()->cd_empresa);
         
        $retorno=Agendamento::Agendamentos($request);
        if((($request->user()->sn_todos_agendamentos=='S') ? 'S' : 'N' ) == 'N'){
            $retorno=$retorno->where("cd_profissional",$request->user()->cd_profissional);
        } 

        if($request['status']=='A'){ 
            $arrayStatus= AgendamentoSituacao::where('finalizado','N')->get();
            foreach($arrayStatus as $val){
                $getStatus[]=$val->cd_situacao;
            }
        }
        if($request['status']=='F'){ 
            $arrayStatus= AgendamentoSituacao::where('finalizado','S')->get();
            foreach($arrayStatus as $val){
                $getStatus[]=$val->cd_situacao;
            }
        }
        $retorno=$retorno->join('agendamento_situacao','agendamento.situacao','agendamento_situacao.cd_situacao') 
        ->selectRaw("
            agendamento.*, 
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento,
            date_format(dt_atendimento,'%d/%m/%y %H:%i') data_atend,
            date_format(dt_finalizacao,'%d/%m/%Y %H:%i') data_finalizacao,
            case 
                when ( TIMESTAMPDIFF(DAY,dt_agenda,curdate()) > " . ( ($empresa->tempo_prontuario_dia) ? $empresa->tempo_prontuario_dia : 0 ) ." and sn_finalizado='S' ) then 'N'  
				when permite_atender<>'S' then 'N' 
            else 'S' end permite_atender ");
         
        if($getStatus){
            $retorno=$retorno->whereIn("situacao",$getStatus); 
        }
        if($request['ordenacao']=='presenca'){
            $retorno=$retorno->orderByRaw("ifnull(date_format(dt_atendimento,'%H%i'),'9999'),date_format(data_horario,'%H%i')");
            
        }
        if($request['ordenacao']=='agenda'){ 
            $retorno=$retorno->orderByRaw("hr_agenda");
        }
        $retorno=$retorno->get(); 
        //dd($request->user()->sn_todos_agendamentos,$retorno->toSql());
        $retorno->load('auto_refracao','formularios_imagens_ceratometria_comp','ceratometria','itens.exame', 
        'itens.historico','itens.historico.usuario','situacao_log.tab_situacao','situacao_log.tab_usuario','tab_situacao');

         
        return response()->json(['retorno' => $retorno->toArray(), 'request' => $request->toArray()]);
    }
  
    public function atendimento(Request $request) {
 
        $validator = Validator::make($request->all(), [
            'paciente' => 'required|exists:paciente,cd_paciente',
            'convenio' => 'required|exists:convenio,cd_convenio',
            'especialidade' => 'required|exists:especialidade,cd_especialidade',
            'tipo' => 'nullable|string',
            'local' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors(['error' => $validator->errors()->first()]);
        }

        if(!$request->user()->cd_profissional){
            return redirect()->back()->withInput()->withErrors(['error' => 'Usuario não possui profissional cadastrado!']);
        }
        $dia = date('w', strtotime(date('Y-m-d')));
        $Situacao = AgendamentoSituacao::where('em_atend','S')->first();

        if(!$Situacao->cd_situacao){
            return redirect()->back()->withInput()->withErrors(['error' => 'O sistema não esta configurado para atender esse tipo de atendimento!']);
        }
        $array=array(
            'sn_atend_avulso'=> 'S',
            'cd_paciente'=>$request['paciente'],
            'cd_convenio'=>$request['convenio'],
            'cd_especialidade'=>$request['especialidade'],
            'situacao'=>$Situacao->cd_situacao,
            'sn_atendimento'=>'S',
            'dt_atendimento'=>date('Y-m-d H:i'),
            'usuario_atendimento'=>$request->user()->cd_profissional,
            'tipo'=>$request['tipo'],
            'cd_local_atendimento'=>$request['local'],
            'data_horario'=>date('Y-m-d'),
            'dt_agenda'=>date('Y-m-d H:i'),
            'hr_agenda'=>date('H:i'),
            'dia_semana'=>$dia,
            'cd_profissional'=>$request->user()->cd_profissional
        );
        $Atend=Agendamento::create($array);

        return redirect()->route('consultorio_oftalmologia.show',$Atend->cd_agendamento)->with('success', 'Atendimento cadastrado com sucesso!');
    }

    public function pesquisaPacAtend(Request $request) {

        $validator = Validator::make($request->all(), [
            'paciente' => 'required',
            'mae' => 'nullable|string',
            'pai' => 'nullable|string',
            'nasc' => 'nullable|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            $Paciente = Paciente::whereRaw(" upper(nm_paciente) like '".mb_strtoupper($request['paciente'])."%'");
            if($request['mae']){
                $Paciente=$Paciente->whereRaw(" upper(nm_mae) like '".mb_strtoupper($request['mae'])."%'");
            }
            if($request['pai']){
                $Paciente=$Paciente->whereRaw(" upper(nm_pai) like '".mb_strtoupper($request['pai'])."%'");
            }
            if($request['nasc']){
                $Paciente=$Paciente->whereRaw(" dt_nasc = '".$request['nasc']."'");
            }
            $Paciente=$Paciente->orderBy("nm_paciente")->get();
            return response()->json($Paciente);
        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }
    }

    public function pesquisaPac(Request $request) {

        $validator = Validator::make($request->all(), [
            'dti' => 'required|date_format:Y-m-d',
            'dtf' => 'required|date_format:Y-m-d',
            'paciente' => 'nullable|string',
            'profissional' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            $query = Agendamento::whereRaw("date(data_horario) >= '".$request->dti."'")
            ->whereRaw("date(data_horario) <= '".$request->dtf."'")
            ->leftJoin('paciente','paciente.cd_paciente','agendamento.cd_paciente')
            ->leftJoin('profissional','profissional.cd_profissional','agendamento.cd_profissional')
            ->leftJoin('especialidade','especialidade.cd_especialidade','agendamento.cd_especialidade')
            ->selectRaw("cd_agendamento,date_format(dt_agenda,'%d/%m/%Y') dt_agenda,
                        nm_paciente,nm_profissional,agendamento.situacao,nm_especialidade")
            ->whereRaw("ifnull(agendamento.cd_paciente,'')<>'' ");
            if($request->user()->sn_todos_agendamentos<>'S'){
                if($request->user()->cd_profissional ){
                    $query = $query->where('agendamento.cd_profissional', $request->user()->cd_profissional );
                }else{
                    $query = $query->where('agendamento.cd_profissional', '0' );
                }
            }

            if($request['paciente']){
                $query = $query->whereRaw(" upper(nm_paciente) like '".mb_strtoupper($request['paciente'])."%'");
            }
            if($request['profissional']){
                $query = $query->whereRaw(" upper(nm_profissional) like '".mb_strtoupper($request['profissional'])."%'");
            }

            $query = $query->orderByRaw("dt_agenda desc,nm_paciente")->get();

           // dd($query);

            return response()->json($query);

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }


    }

    public function pesquisaAtend(Request $request) {

        $validator = Validator::make($request->all(), [
            'cod' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {
            
            $empresa = Empresa::find($request->user()->cd_empresa);

            $dados['agendamento'] = Agendamento::where('cd_agendamento',$request['cod'])
            ->join('paciente','paciente.cd_paciente','agendamento.cd_paciente')
            ->join('profissional','profissional.cd_profissional','agendamento.cd_profissional')
            ->leftJoin('especialidade','especialidade.cd_especialidade','agendamento.cd_especialidade')
            ->leftJoin('convenio','convenio.cd_convenio','agendamento.cd_convenio')
            ->leftJoin('procedimento','procedimento.cd_proc','agendamento.cd_procedimento')
            ->selectRaw("agendamento.*,paciente.*,date_format(dt_agenda,'%d/%m/%Y') dt_agendamento, nm_profissional,agendamento.situacao,nm_especialidade,nm_convenio,nm_proc,
            case when paciente.sexo='H' then 'MASCULINO' when paciente.sexo='M' then 'FEMININO' end ds_sexo, date_format(dt_nasc,'%d/%m/%Y') dt_nascimento,
            case when TIMESTAMPDIFF(DAY,dt_agenda,curdate()) <= " . ( ($empresa->tempo_prontuario_dia) ? $empresa->tempo_prontuario_dia : 0 ) ." then 'S' else 'N' end permite_atender
            ")->first();

            $dados['anexos'] = AgendamentoAnexos::where('cd_agendamento',$request['cod'])
            ->selectRaw("agendamento_anexos.*,date_format(created_at,'%d/%m/%Y %H:%i') dt_anexo")->get();
            $dados['doc'] = AgendamentoDocumentos::with(['formulario'])
            ->selectRaw("agendamento_documentos.*, date_format(created_at,'%d/%m/%Y %H:%i') dt_doc")
            ->where('cd_agendamento',$request['cod'])->get();

           return response()->json( $dados );

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }

    }

    public function pesquisaAtendimento(Request $request) {

        $validator = Validator::make($request->all(), [
            'cod' => 'required',
            'nome' => 'nullable',
            'dti' => 'nullable|date',
            'dtf' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

       try {

            $dados['historico'] = Paciente::whereRaw("paciente.cd_paciente = ".$request['cod'])
            ->join('agendamento','agendamento.cd_paciente','paciente.cd_paciente')
            ->whereRaw(" date(agendamento.dt_agenda) < curdate() ")
            ->leftJoin('tipo_atendimento','tipo_atendimento.cd_tipo_atendimento','agendamento.tipo')
            ->leftJoin('profissional','profissional.cd_profissional','agendamento.cd_profissional')            ->leftJoin('agendamento_situacao','agendamento_situacao.cd_situacao','agendamento.situacao')
            ->selectRaw("cd_agendamento, date_format(dt_agenda,'%d/%m/%Y') data_agenda, dt_agenda,hr_agenda,ifnull(hr_final,'--') hr_final,ifnull(nm_tipo_atendimento,'--') nm_tipo_atendimento,nm_profissional,nm_situacao,agendamento_situacao.icone ")
            ->orderByRaw("dt_agenda desc")->limit(20)->get();


            $dados['futuro'] = Paciente::whereRaw("paciente.cd_paciente = ".$request['cod'])
            ->join('agendamento','agendamento.cd_paciente','paciente.cd_paciente')
            ->whereRaw(" date(agendamento.dt_agenda) >= curdate() ")
            ->leftJoin('tipo_atendimento','tipo_atendimento.cd_tipo_atendimento','agendamento.tipo')
            ->leftJoin('profissional','profissional.cd_profissional','agendamento.cd_profissional')
            ->leftJoin('agendamento_situacao','agendamento_situacao.cd_situacao','agendamento.situacao')
            ->selectRaw("cd_agendamento, date_format(dt_agenda,'%d/%m/%Y') data_agenda, dt_agenda,hr_agenda,ifnull(hr_final,'--') hr_final,ifnull(nm_tipo_atendimento,'--') nm_tipo_atendimento,nm_profissional,nm_situacao,agendamento_situacao.icone ")
            ->orderBy("dt_agenda")->get();

            return response()->json( $dados );

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }


    }

    public function reabrirAtendimento(Request $request) {

        $validator = Validator::make($request->all(), [
            'atendimento' => 'required|integer|exists:agendamento,cd_agendamento',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        if(empty($request->user()->cd_profissional)){
            return response()->json(['message' => 'Esse profissional não tem permissão para reabrir esse atendimento!'], 400);
        }

       try {

        $atend = Agendamento::find($request['atendimento']);
        if(!$atend->cd_profissional==$request->user()->cd_profissional){
            return response()->json(['message' => 'Esse profissional não tem permissão para reabrir esse atendimento!'], 400);
        }        

        $arrayStatus= AgendamentoSituacao::where('em_atend','S')->get();

        if(!isset($arrayStatus->cd_situacao)){
            return response()->json(['message' => 'Sistema não condigurado para realizar essa reabertura!'], 400);
        }

        $dados=Agendamento::whereRaw(" cd_agendamento = ".$request['atendimento'])
            ->update(array('situacao'=>$arrayStatus->cd_situacao));

        funcLogsAtendimentoHelpers($request['atendimento'],'USUARIO REABRIU O ATENDIMENTO');

        return response()->json( $dados );

        }
        catch (Throwable $th) {
            return response()->json(['message' => 'Houve um erro ao processar informação.'], 200);
        }


    }
  
}


