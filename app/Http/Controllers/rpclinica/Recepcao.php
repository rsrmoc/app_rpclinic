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
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\GuiaSituacao;
use App\Model\rpclinica\Usuario;
use Barryvdh\DomPDF\Facade\Pdf;

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

class Recepcao extends Controller
{

    public function index(Request $request)
    { 
         
  
        $agendas = Agenda::orderBy("nm_agenda")->whereRaw("sn_ativo='S'")
        ->orderBy("nm_agenda")->get();
        $profissionais = Profissional::whereRaw("sn_ativo='S'")->orderBy("nm_profissional")->get();
        $especialidades = Especialidade::whereRaw("sn_ativo='S'")->orderBy("nm_especialidade")->get();
        $convenios = Convenio::whereRaw("sn_ativo='S'")->orderBy("nm_convenio")->get();
        $procedimentos = Procedimento::whereRaw("sn_ativo='S'")->orderBy("nm_proc")->get();
        $localAtendimentos = LocalAtendimento::whereRaw("sn_ativo='S'")->orderBy("nm_local")->get();
        $exames = Exame::whereRaw("sn_ativo='S'")->orderBy("nm_exame")->get();
        $lista_guia = GuiaSituacao::orderBy('nm_situacao')->get();
        return view('rpclinica.recepcao.recepcao', compact('request','agendas','profissionais',
                                                           'especialidades','convenios','procedimentos',
                                                           'localAtendimentos','exames','lista_guia'));

    }
 
    public function show(Request $request)
    {

        $request['not_situacao'] =array('bloqueado');
        $retorno = RpclinicaAgendamento::Agendamentos($request)
        ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
        date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
        concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
        date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ") 
        ->orderby('data_horario')->get();
        return response()->json(['request'=>$request->toArray(),'retorno'=>$retorno]);

    }
    public function storeAtend(Request $request, RpclinicaAgendamento $agendamento, Paciente $paciente )
    {
 
        try {

            DB::beginTransaction(); 
            $sn_atendimento = $agendamento->sn_atendimento;

            $situacao = AgendamentoSituacao::where('atendimento','S')->first(); 
            if(!isset($situacao['cd_situacao'])){
                return response()->json(['errors' => array('O sistema não esta configurado para realizar agendamento!')], 500);
            }

            $dados['sn_atendimento']='S';
            $dados['dt_atendimento']=date('Y-m-d H:i');
            $dados['usuario_atendimento']=$request->user()->cd_usuario;
            $dados['carater']=$request['carater'];
            $dados['cd_origem']=$request['cd_origem'];
            $dados['cd_prof_solicitante']=$request['cd_prof_solicitante'];
            $dados['cartao']=$request['cartao'];
            $dados['cd_convenio']=$request['cd_convenio'];
            $dados['cd_especialidade']=$request['cd_especialidade'];
            $dados['cd_local_atendimento']=$request['cd_local'];
            $dados['tipo']=$request['cd_tipo'];
            $dados['dt_validade']=$request['dt_validade'];
            $dados['obs']=$request['obs']; 
            $dados['dt_presenca']=date('Y-m-d H:i');
            $dados['sn_presenca']=true;
            $dados['user_presenca']=$request->user()->cd_usuario;
            
            if($sn_atendimento=='N'){

                $dados['situacao']=$situacao['cd_situacao'];
            } 
            $agendamento->update($dados);

            $agendamento->saveLog($request->user(),'Exclusao',null,'recepcao','atendimento',
            array( 'agendamento'=>$request['cd_agendamento'],'paciente'=>$agendamento['cd_paciente'],'item'=>null,'exame'=>null ) );
  
            if($sn_atendimento=='N'){   
                AgendamentoSituacaoLog::create([
                    'cd_agendamento'=>$request['cd_agendamento'],
                    'situacao'=>$situacao['cd_situacao'],
                    'cd_usuario'=>$request->user()->cd_usuario,
                ]);
            }

            
            $pac['cpf']=$request['cpf'];
            $pac['rg']=$request['rg'];
            $pac['email']=$request['email']; 
            $pac['cd_categoria']=$request['cd_convenio'];
            $pac['dt_validade']=$request['dt_validade'];
            $pac['dt_nasc']=$request['dt_nasc'];
            $paciente->update($pac); 

            $paciente->saveLog($request->user(),'Edicao',null,'recepcao','paciente',
            array( 'agendamento'=>null,'paciente'=>$paciente->cd_paciente,'item'=>null,'exame'=>null ) );
  
            DB::commit();

            $retorno = RpclinicaAgendamento::where('cd_agendamento',$agendamento->cd_agendamento)
            ->with('agenda','paciente','profissional','especialidade','local','itens','itens.exame.procedimento',
            'convenio','situacao','tipo_atend','escalas','user_agendamento','user_atendimento','user_pre_exame',
            'guia.itens.exame.procedimento')
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,
            date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,date_format(data_horario,'%Y-%m-%d') data_start_end, 
            concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end,concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento,date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento")->first();

            return response()->json(['request'=>$request->toArray(),'retorno'=>$retorno]);

        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['errors' => [$error->getMessage()]], 500);
        }
    }

    public function storeGuia(Request $request, RpclinicaAgendamento $agendamento )
    {

        $validator = Validator::make($request->all(), [
            'dt_solicitacao' => 'required|date_format:Y-m-d',
            'nr_guia' => 'required',
            'senha' => 'required',
            'tipo' => 'required',
            'situacao' => 'required', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            DB::beginTransaction(); 
            $array =array(
                'cd_agendamento'=>$agendamento['cd_agendamento'],
                'dt_solicitacao'=>$request['dt_solicitacao'],
                'nr_guia'=>$request['nr_guia'],
                'senha'=>$request['senha'],
                'tp_guia'=>$request['tipo'],
                'situacao'=>$request['situacao'],
                'cd_usuario'=>$request->user()->cd_usuario
            );
            $guia=AgendamentoGuia::create($array);

            foreach($request['itens'] as $itens){
                AgendamentoItens::find($itens)->update(['cd_agendamento_guia'=>$guia->cd_agendamento_guia]);
            }

            DB::commit();

            $retorno = AgendamentoItens::whereNull("cd_agendamento_guia")
            ->where('cd_agendamento',$agendamento['cd_agendamento'])
            ->orderBy('created_at')->with('exame')->get();

            $agendamento = RpclinicaAgendamento::find($agendamento['cd_agendamento']);
            $agendamento->load('paciente','itens','guia');
            $agendamento->itens->load('exame','usuario');
            $agendamento->guia->load('usuario','situacao_guia','itens.exame'); 
           
            return response()->json(['request'=>$request->toArray(),'retorno'=>$retorno,'guias'=>$agendamento]);
        
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }


    public function updateGuia(Request $request )
    {

        $validator = Validator::make($request->all(), [
            'cd_agendamento' => 'required|integer|exists:agendamento,cd_agendamento',
            'cd_agendamento_guia' => 'required|integer|exists:agendamento_guias,cd_agendamento_guia',
            'situacao' => 'required',
        ]);

        if ($validator->fails()) { 
            return response()->json(['message' => $validator->errors()->first()], 400);
        }

        try {

            DB::beginTransaction(); 
            if($request['situacao']=='excluir'){
                AgendamentoItens::where('cd_agendamento_guia',$request['cd_agendamento_guia'])
                ->update(['cd_agendamento_guia'=>null]);
                AgendamentoGuia::where('cd_agendamento_guia',$request['cd_agendamento_guia'])
                ->delete();
            }else{
                AgendamentoGuia::where('cd_agendamento_guia',$request['cd_agendamento_guia'])
                ->update(['situacao'=>$request['situacao']]);
            }

            DB::commit();

            $agendamento = RpclinicaAgendamento::find($request['cd_agendamento']);
            $agendamento->load('paciente','itens','guia');
            $agendamento->itens->load('exame','usuario');
            $agendamento->guia->load('usuario','situacao_guia','itens.exame'); 

            $retorno = AgendamentoItens::whereNull('cd_agendamento_guia')
            ->where('cd_agendamento',$request['cd_agendamento'])
            ->orderBy('created_at')->with('exame')->get();

            return response()->json(['request'=>$request->toArray(),'retorno'=>$retorno,'agendamento'=>$agendamento]);
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }

    }
    
    public function CarregaGuiaItens(Request $request , RpclinicaAgendamento $agendamento )
    {
 
        try {

            $agendamento->load('paciente','itens','guia');
            $agendamento->itens->load('exame','usuario');
            $agendamento->guia->load('usuario','situacao_guia','itens.exame'); 

            $itens = AgendamentoItens::whereNull("cd_agendamento_guia")
            ->where('cd_agendamento',$agendamento['cd_agendamento'])
            ->orderBy('created_at')->with('exame')->get();

            return response()->json(['retorno'=>$agendamento, 'itens'=>$itens]);

        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }

    
    public function etiqueta(Request $request, RpclinicaAgendamento $agendamento )
    {
        try {
            $layout =  'P';
            $dados=$agendamento->load('paciente','profissional_externo','profissional','convenio','itens_pedente.exame','tipo_atend');
            $empresa = Empresa::find($request->user()->cd_empresa);
            $pdf = Pdf::loadView('rpclinica.recepcao.etiqueta', compact('dados','empresa')); 
            return $pdf->setPaper([0,0,350,100], $layout)->stream('etiqueta.pdf');
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
      
   
    public function ficha(Request $request, RpclinicaAgendamento $agendamento )
    {
        try {
         
            $layout =  'P';
            $dados=$agendamento->load('paciente','profissional_externo','profissional','convenio','itens_pedente.exame','usuario_portal');
            $empresa = Empresa::find($request->user()->cd_empresa);
            //dd($dados->usuario_portal->toArray());
            switch ($empresa->recibo_atendimento) {
                case 'oftalmo':
                    $pdf = Pdf::loadView('rpclinica.recepcao.ficha_atendimento_oftalmo', compact('dados','empresa')); 
                    break;
                case 'geral':
                    $pdf = Pdf::loadView('rpclinica.recepcao.ficha_atendimento_geral', compact('dados','empresa')); 
                    break;
            }
           
            return $pdf->setPaper('a4', $layout)->stream('ficha_Atend_'.$agendamento->cd_agendamento.'.pdf');
        }
        catch (Throwable $error) {
            DB::rollback();
            return response()->json(['message' => [$error->getMessage()]], 500);
        }
    }
}
