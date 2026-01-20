<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agenda; 
use App\Model\rpclinica\AgendaEscala;
use App\Model\rpclinica\AgendaEscalaHorario;
use App\Model\rpclinica\AgendaExames;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoItensHist;
use App\Model\rpclinica\AgendamentoSituacao;
use App\Model\rpclinica\AgendamentoSituacaoLog;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\TipoAtendimento;
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

class PreExame extends Controller
{

    public function index(Request $request)
    { 
 
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


        $parametros['profissionais'] = Profissional::where('sn_ativo','S')->orderBy('nm_profissional')->get(); 
        $parametros['local'] = LocalAtendimento::where('sn_ativo','S')->orderBy('nm_local')->get();
        $parametros['tipo'] = TipoAtendimento::where('sn_ativo','S')->orderBy('nm_tipo_atendimento')->get();
        $parametros['convenio'] = Convenio::where('sn_ativo','S')->orderBy('nm_convenio')->get();
        $parametros['situacao'] = AgendamentoSituacao::orderBy('nm_situacao')->get();


        return view('rpclinica.pre_exame.painel', compact('parametros','request'));
    }
  
    public function jsonPainel(Request $request)
    { 
        $request['not_situacao'] =array('bloqueado');
        $retorno=Agendamento::Agendamentos($request)
        ->get();  
        $retorno->load('auto_refracao','formularios_imagens_ceratometria_comp','ceratometria','itens.exame','itens.historico','itens.historico.usuario');
        return response()->json(['retorno' => $retorno->toArray(), 'request' => $request->toArray()]);
       
    }

    public function jsonFinalizar(Request $request, Agendamento $agendamento)
    { 
        $cd_paciente  =$agendamento->cd_paciente;
        if($agendamento->sn_pre_exame ==false){

            $situacao = AgendamentoSituacao::where('pre_exame','S')->first(); 
            if(!isset($situacao['cd_situacao'])){
                return response()->json(['errors' => array('O sistema não esta configurado para realizar o Pre-exame!')], 500);
            } 
            $agendamento->update(['sn_pre_exame'=>true,
                                  'dt_pre_exame'=>date('Y-m-d H:i'),
                                  'usuario_pre_exame'=> $request->user()->cd_usuario,
                                  'situacao'=>$situacao['cd_situacao']]);

                $agendamento->saveLog($request->user(),'Edicao',null,'pre-exame','pre-exame',
                array( 'agendamento'=>$agendamento->cd_agendamento,'paciente'=>$cd_paciente,'agendamento'=>null,'exame'=>null ) );

                AgendamentoSituacaoLog::create([
                    'cd_agendamento'=>$agendamento->cd_agendamento,
                    'situacao'=>$situacao->cd_situacao,
                    'cd_usuario'=>$request->user()->cd_usuario,
                ]);

        }else{

            $situacao = AgendamentoSituacao::where('atender','S')->first(); 
            if(!isset($situacao['cd_situacao'])){
                return response()->json(['errors' => array('O sistema não esta configurado para realizar o Pre-exame!')], 500);
            }
            $agendamento->update(['sn_pre_exame'=>false,
                                  'dt_pre_exame'=> null,
                                  'usuario_pre_exame'=> null,
                                  'situacao'=>$situacao['cd_situacao']]);

                $agendamento->saveLog($request->user(),'Edicao',null,'pre-exame','pre-exame',
                array( 'agendamento'=>$agendamento->cd_agendamento,'paciente'=>$cd_paciente,'agendamento'=>null,'exame'=>null ) );

                AgendamentoSituacaoLog::create([
                    'cd_agendamento'=>$agendamento->cd_agendamento,
                    'situacao'=>$situacao->cd_situacao,
                    'cd_usuario'=>$request->user()->cd_usuario,
                ]);
        } 
        
        return response()->json(['retorno' => $agendamento->toArray(), 'request' => $request->toArray()]);
       
    }
    
    
}
