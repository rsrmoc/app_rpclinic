<?php


namespace App\Http\Controllers\api;

use App\Bibliotecas\Kentro;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoWhast;
use App\Model\rpclinica\ComunicacaoSend;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\WebhookMessage;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastLogErro;
use App\Model\rpclinica\WhastReceive;
use App\Model\rpclinica\WhastRetornoAgenda;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\WhastSituacao;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class WhastKentro extends Controller
{


    public function confirAgendaKentroOficial(Request $request)
    {   
        
        try {
            
            $data= implode(', ',$request->toArray());

            /* Confirmado */
            if($request['retorno']==$request['button_confirmado']){

                $dadosCelular=AgendamentoWhast::where('celular',substr($request['telefone'], -8))->where('situacao','A')->first();
                
                if(isset($dadosCelular->cd_agendamento)){

                    $Agendamento=$dadosCelular->cd_agendamento;
                    $CodigoWhast=$dadosCelular->cd_agendamento_whast;
                    $empresa = Empresa::find($request['instancia']);

                    $situacaoConfirmado=(isset($empresa->situacao_ag_confirm)) ? $empresa->situacao_ag_confirm : null;
                    $situacaoCancelado= (isset($empresa->situacao_ag_cancel)) ? $empresa->situacao_ag_cancel : null;

                    Agendamento::where( 'cd_agendamento', $Agendamento)
                    ->update([ 
                        'situacao'=>$situacaoConfirmado,
                        'dt_resp_whast'=>date('Y-m-d H:i'),
                        'ds_whast'=>'Template '.$empresa->whast_temp_agenda,
                        'id_resp_whast'=>$empresa->whast_temp_agenda,
                        'whast_resp'=>$request['telefone'],
                        'dt_resp_whast'=>date('Y-m-d H:i'),
                    ]);
                    $api = new Kentro();
                    $somenteNumeros = $dadosCelular->celular_envio;
                    $retorno = $api->sendWaTemplateRetorno($somenteNumeros, $empresa->whast_temp_confirma, $empresa,null);
                    if ($retorno['retorno'] == true) {
                        $Instance = json_decode($retorno['dados'], true);
                        if ($Instance['message']=='success') {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo'=>'agenda',
                                    'id' => $empresa->whast_temp_confirma,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => 'Template '.$empresa->whast_temp_confirma,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_agendamento' => $Agendamento,
                                    'cd_usuario' => 'rotina'
                                ]);   
                        }
                    }
                    AgendamentoWhast::where('cd_agendamento_whast',$CodigoWhast)
                    ->update([
                        'situacao'=>'F',
                        'dt_finalizacao'=>date('Y-m-d H:i'),
                        'retorno'=>'Confirmado'
                    ]);
                } 
            }

            /* Cancelado */
            if($request['retorno']==$request['button_cancelado']){

                $dadosCelular=AgendamentoWhast::where('celular',substr($request['telefone'], -8))->where('situacao','A')->first();
                
                if(isset($dadosCelular->cd_agendamento)){

                    $Agendamento=$dadosCelular->cd_agendamento;
                    $CodigoWhast=$dadosCelular->cd_agendamento_whast;
                    $empresa = Empresa::find($request['instancia']);
 
                    $situacaoCancelado= (isset($empresa->situacao_ag_cancel)) ? $empresa->situacao_ag_cancel : null;

                    Agendamento::where( 'cd_agendamento', $Agendamento)
                    ->update([ 
                        'situacao'=>$situacaoCancelado,
                        'dt_resp_whast'=>date('Y-m-d H:i'),
                        'ds_whast'=>'Template '.$empresa->whast_temp_agenda,
                        'id_resp_whast'=>$empresa->whast_temp_agenda,
                        'whast_resp'=>$request['telefone'],
                        'dt_resp_whast'=>date('Y-m-d H:i'),
                    ]);
                    $api = new Kentro();
                    $somenteNumeros = $dadosCelular->celular_envio;
                    $retorno = $api->sendWaTemplateRetorno($somenteNumeros, $empresa->whast_temp_cancela, $empresa,null);
                    if ($retorno['retorno'] == true) {
                        $Instance = json_decode($retorno['dados'], true);
                        if ($Instance['message']=='success') {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo'=>'agenda',
                                    'id' => $empresa->whast_temp_cancela,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => 'Template '.$empresa->whast_temp_cancela,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_agendamento' => $Agendamento,
                                    'cd_usuario' => 'rotina'
                                ]);   
                        }
                    }
                    AgendamentoWhast::where('cd_agendamento_whast',$CodigoWhast)
                    ->update([
                        'situacao'=>'F',
                        'dt_finalizacao'=>date('Y-m-d H:i'),
                        'retorno'=>'Confirmado'
                    ]);
                } 
            }

        }

        catch (Throwable $th) {
            WhastLogErro::create([
                'dados'=>$data,
                'erro'=>$th->getMessage()
            ]);
            return response()->json(['message' => 'Houve um erro ao processar informação.'.$th->getMessage()], 500);
        }
       
        
	}

    
	public function confirAgendaRPclinic(Request $request,$tipo)
    {   
        
        try {
 
            header('Content-Type: application/json; charset=UTF-8');
            $data = file_get_contents("php://input");

            $DADOS = $Instance = json_decode($data, true);
            
            $dsCodigo=(isset($DADOS['codigo'])) ? $DADOS['codigo'] : null; 
            $msg=(isset($DADOS['msg'])) ? $DADOS['msg'] : null;
            $message_id=(isset($DADOS['message_id'])) ? $DADOS['message_id'] : null;
            $ArrayCodigo=explode('|',$dsCodigo);
            $ExtData=(isset($ArrayCodigo[0])) ? $ArrayCodigo[0] : null;
            $Agendamento= (isset($ArrayCodigo[1])) ? $ArrayCodigo[1] : null;
            $bd= (isset($ArrayCodigo[2])) ? $ArrayCodigo[2] : null;
            $fone= (isset($ArrayCodigo[3])) ? $ArrayCodigo[3] : null;
            $emp= (isset($ArrayCodigo[4])) ? $ArrayCodigo[4] : null; 
            $msg=(isset($DADOS['msg'])) ? $DADOS['msg'] : null;

            $database = DB::connection('master')->table('databases_clients')->select('*')->where('instancia',$bd)->first();

            if($database){
                config(['database.default' => 'mysql']);
                config(['database.connections.mysql.host' => $database->host]);
                config(['database.connections.mysql.username' => $database->username]);
                config(['database.connections.mysql.password' => $database->password]);
                config(['database.connections.mysql.database' => $database->database]); 
                config(['app.url' => request()->root()]);  
            }else{
                throw new Exception('Instancia não configurada!');  
            }
    
            
            $empresa = Empresa::where('cd_empresa', (($emp)?$emp:0) )->first(); 
            $situacaoConfirmado=(isset($empresa->situacao_ag_confirm)) ? $empresa->situacao_ag_confirm : null;
            $msgConfirmado= (isset($empresa->msg_ag_confirm)) ? utf8_decode($empresa->msg_ag_confirm) : null;
            $situacaoCancelado= (isset($empresa->situacao_ag_cancel)) ? $empresa->situacao_ag_cancel : null;
            $msgCancelado= (isset($empresa->msg_ag_cancel)) ? utf8_decode($empresa->msg_ag_cancel) : null;
            
            if($tipo=='CA'){
                if($Agendamento){ 
                    if($situacaoCancelado){

                        
                        if($empresa->sn_ag_cancel=='sim'){

                            //Enviar Mensagem
                            $api = new Kentro();  
                            $retorno = $api->enqueueMessageToSend($fone, $msgCancelado, null, null,  $empresa);
                            if($retorno['retorno']==true){
                                $Instance = json_decode($retorno['dados'], true);

                                DB::transaction(function () use ($Agendamento,$fone,$Instance,$msgCancelado,$retorno,$situacaoCancelado,$msg) {

                                    Agendamento::where('cd_agendamento', $Agendamento)->update([
                                        'situacao'=>$situacaoCancelado,
                                        'dt_resp_whast'=>date('Y-m-d H:i'),
                                        'ds_whast'=>$msg, 
                                    ]);

                                    if (isset($Instance['message'])) {
                                        WhastSend::create([
                                            'status' => 200,
                                            'tipo'=>'agenda',
                                            'id' => $Instance['enqueuedId'],
                                            'nr_send' => $fone,
                                            'conteudo' => $msgCancelado,
                                            'retorno' => $retorno['dados'],
                                            'dt_envio' => date('Y-m-d H:i'),
                                            'from_me' => '1',
                                            'cd_agendamento' => $Agendamento,
                                            'cd_usuario' => 'rotina'
                                        ]);
    
                                    }
                                });

                            }
                        
                        }

                    }else{
                        throw new Exception('Não existe situação do tipo cancelado cadastrado!');  
                    }
                    
                }
                
            }
            
            if($tipo=='CO'){

                
                if($Agendamento){
                     
                    if($situacaoConfirmado){

 
                        $query = Agendamento::find($Agendamento) ;
                        $query->load('paciente', 'agenda', 'profissional'); 
                        $variavel = array("@NOME","@PACIENTE","@PROFISSIONAL","@DATA","@HR_AGENDAMENTO");
                        $valores = array( ucfirst($query->paciente?->nm_paciente),
                                          ucfirst($query->paciente?->nm_paciente), 
                                          ucfirst($query->profissional?->nm_profissional), 
                                          date("d/m/Y", strtotime($query->dt_agenda)),
                                          $query->hr_agenda 
                                        ); 
                        $msgConfirmado = str_replace($variavel, $valores, $msgConfirmado);
                         
                        if($empresa->sn_ag_confirm=='sim'){

                            //Enviar Mensagem
                            $api = new Kentro();  
                            $retorno = $api->enqueueMessageToSend($fone, $msgConfirmado, null, null,  $empresa);
                            if($retorno['retorno']==true){

                                $Instance = json_decode($retorno['dados'], true);

                                DB::transaction(function () use ($Agendamento,$fone,$Instance,$msgConfirmado,$retorno,$situacaoConfirmado,$msg) {

                                    Agendamento::where('cd_agendamento', $Agendamento)->update([
                                        'situacao'=>$situacaoConfirmado,
                                        'dt_resp_whast'=>date('Y-m-d H:i'),
                                        'ds_whast'=>$msg, 
                                    ]);

                                    if (isset($Instance['message'])) {
                                        WhastSend::create([
                                            'status' => 200,
                                            'tipo'=>'agenda',
                                            'id' => $Instance['enqueuedId'],
                                            'nr_send' => $fone,
                                            'conteudo' => $msgConfirmado,
                                            'retorno' => $retorno['dados'],
                                            'dt_envio' => date('Y-m-d H:i'),
                                            'from_me' => '1',
                                            'cd_agendamento' => $Agendamento,
                                            'cd_usuario' => 'rotina'
                                        ]); 
                                    }
                                });


                            }
                            
                        }
                         

                    }else{ 
                        throw new Exception('Não existe situação do tipo confirmado cadastrado!');   
                    }
                    
                    
                }
                        
            

            }
           
  
           return response()->json(['message' => true,'tipo'=>$tipo]); 

        }
        catch (Throwable $th) {
            WhastLogErro::create([
                'dados'=>$data,
                'erro'=>$th->getMessage()
            ]);
            return response()->json(['message' => 'Houve um erro ao processar informação.'.$th->getMessage()], 500);
        }
       
        
	}
 
}
