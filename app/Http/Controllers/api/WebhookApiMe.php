<?php


namespace App\Http\Controllers\api;

use App\Bibliotecas\ApiWaMe;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoWhast;
use App\Model\rpclinica\ComunicacaoSend;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\WebhookErro;
use App\Model\rpclinica\WebhookMessage;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastReceive;
use App\Model\rpclinica\WhastRetornoAgenda;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\WhastSituacao;
use App\Model\rpclinica\WhastTag;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class WebhookApiMe extends Controller
{
 

    public function webhookMessage(Request $request, $key)
    {
        
        try {

            if(!$key=='b5d02e3d6d85'){
                return false;
            }
            
            //header('Content-Type: application/json; charset=UTF-8');
            $data = file_get_contents("php://input");

            $event = json_decode($data, true); 

            if((isset($event['type'])) && isset($event['data']['messageType'])){

                if( ($event['type']=='message') && ( ($event['data']['messageType']=='conversation') || ($event['data']['messageType']=='extendedTextMessage') || ($event['data']['messageType']=='messageContextInfo') ) ){
                    
                    //Verifica se o celular esta cadastrado na tabela agendamento_whast
                    if(isset($event['data']['remoteJid'])){
                        $celular=$event['data']['remoteJid'];
                        $dadosCelular=AgendamentoWhast::where('celular',$celular)->where('situacao','A')->first();
                        if(empty($dadosCelular)){
                            return false;
                        }
                    }else{
                        return false;
                    }
                    
                    //Verifica se a instancia é a mesma
                    $instance = $event['instance'];  
                    $empresa = Empresa::where('sn_ativo','s')->first();
                    if($empresa->key_whast<>$instance){
                        return false;
                    }

                    //Busca as tags cadastradas
                    $getOpcoes=WhastTag::where('tipo','confirmacao_agenda')->get();
                    $arrayTag=[];
                    foreach($getOpcoes as $tag){
                        $arrayTag[]=mb_strtoupper($tag->tag);
                        $dadosTag[mb_strtoupper($tag->tag)] = array('situacao'=>$tag->situacao);
                    }

                    $msg=null; 
                    if( ($event['type']=='message') && ($event['data']['messageType']=='conversation') ){
                        if(isset($event['data']['msgContent'])){
                            $msg=$event['data']['msgContent']['conversation']; 
                        }
                    } 
                    if( ($event['type']=='message') && ($event['data']['messageType']=='extendedTextMessage') ){
                        if(isset($event['data']['msgContent']['extendedTextMessage'])){
                            $msg=$event['data']['msgContent']['extendedTextMessage']['text']; 
                        }
                    } 
                    if( ($event['type']=='message') && ($event['data']['messageType']=='messageContextInfo') ){
                        if(isset($event['data']['msgContent']['reactionMessage'])){
                            $msg=$event['data']['msgContent']['reactionMessage']['text']; 
                        }
                    }
                    
                    if (in_array(mb_strtoupper($msg), $arrayTag)) {

                        $Agendamento=$dadosCelular->cd_agendamento;
                        $CodigoWhast = $dadosCelular->cd_agendamento_whast; 
                        $situacaoConfirmado=(isset($empresa->situacao_ag_confirm)) ? $empresa->situacao_ag_confirm : null;
                        $msgConfirmado= (isset($empresa->msg_ag_confirm)) ? utf8_decode($empresa->msg_ag_confirm) : null;
                        $situacaoCancelado= (isset($empresa->situacao_ag_cancel)) ? $empresa->situacao_ag_cancel : null;
                        $msgCancelado= (isset($empresa->msg_ag_cancel)) ? utf8_decode($empresa->msg_ag_cancel) : null; 
                        $dados=$dadosTag[mb_strtoupper($msg)];
                        

                        
                        if($dados['situacao']=='CA'){

                            if($Agendamento){   
                                
                                if($situacaoCancelado){
                                    
                                    if($empresa->sn_ag_cancel=='sim'){
            
                                        //Enviar Mensagem 
                                        $api = new ApiWaMe(); 
                                        $retorno = $api->sendTextMessage($celular,$msgCancelado,$empresa);
                                        if($retorno['retorno']==true){
                                            $Instance = json_decode($retorno['dados'], true);

                                            
                                            DB::transaction(function () use ($Agendamento,$celular,$Instance,$msgCancelado,$retorno,$situacaoCancelado,$msg,$CodigoWhast) {

                                                Agendamento::where('cd_agendamento', $Agendamento)->update([
                                                    'situacao'=>$situacaoCancelado,
                                                    'dt_resp_whast'=>date('Y-m-d H:i'),
                                                    'ds_whast'=>$msg,
                                                    'id_resp_whast'=>$Instance['data']['key']['id'],
                                                    'whast_resp'=>$celular,
                                                    'dt_resp_whast'=>date('Y-m-d H:i'),
                                                ]);

                                                if ($Instance['status']==200) {
                                                    WhastSend::create([
                                                        'status' => 200,
                                                        'tipo'=>'agenda',
                                                        'id' => $Instance['data']['key']['id'],
                                                        'nr_send' => $celular,
                                                        'conteudo' => $msgCancelado,
                                                        'retorno' => $retorno['dados'],
                                                        'dt_envio' => date('Y-m-d H:i'),
                                                        'from_me' => '1',
                                                        'cd_agendamento' => $Agendamento,
                                                        'cd_usuario' => 'rotina'
                                                    ]); 
                                                }

                                                AgendamentoWhast::where('cd_agendamento_whast',$CodigoWhast)
                                                ->update([
                                                    'situacao'=>'F',
                                                    'dt_finalizacao'=>date('Y-m-d H:i'),
                                                    'retorno'=>$msg
                                                ]);

                                            });
    
                                        }
                                    
                                    }
            
                                }else{
                                    throw new Exception('Não existe situação do tipo cancelado cadastrado!');  
                                }
                            }

                        }
                         
                        if($dados['situacao']=='CO'){
                            
                            if($Agendamento){
                                
                                if($situacaoConfirmado){
                                  
 
                                    if($empresa->sn_ag_confirm=='sim'){

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

                                        
                                        //Enviar Mensagem 
                                        $api = new ApiWaMe(); 
                                        $retorno = $api->sendTextMessage($celular,$msgConfirmado,$empresa);
                                        if($retorno['retorno']==true){
            
                                            $Instance = json_decode($retorno['dados'], true);

                                            DB::transaction(function () use ($Agendamento,$celular,$Instance,$msgConfirmado,$retorno,$situacaoConfirmado,$msg,$CodigoWhast) {
                                                
                                                $um=Agendamento::where('cd_agendamento', $Agendamento)->update([
                                                    'situacao'=>$situacaoConfirmado,
                                                    'dt_resp_whast'=>date('Y-m-d H:i'),
                                                    'ds_whast'=>$msg, 
                                                    'id_resp_whast'=>(isset($Instance['data']['key']['id']) ? $Instance['data']['key']['id'] : null),
                                                    'whast_resp'=>$celular,
                                                    'dt_resp_whast'=>date('Y-m-d H:i'), 
                                                ]);

                                                if ($Instance['status']==200) {
                                                    $dois=WhastSend::create([
                                                        'status' => 200,
                                                        'tipo'=>'agenda',
                                                        'id' =>  (isset($Instance['data']['key']['id']) ? $Instance['data']['key']['id'] : null),
                                                        'nr_send' => $celular,
                                                        'conteudo' => $msgConfirmado,
                                                        'retorno' => $retorno['dados'],
                                                        'dt_envio' => date('Y-m-d H:i'),
                                                        'from_me' => '1',
                                                        'cd_agendamento' => $Agendamento,
                                                        'cd_usuario' => 'rotina'
                                                    ]); 
                                                }

                                                $tres=AgendamentoWhast::where('cd_agendamento_whast',$CodigoWhast)
                                                ->update([
                                                    'situacao'=>'F',
                                                    'dt_finalizacao'=>date('Y-m-d H:i'),
                                                    'retorno'=>$msg
                                                ]);
                                               
                                            });
 
                                        }
                                        
                                    }
                                    
                                }else{ 
                                    throw new Exception('Não existe situação do tipo confirmado cadastrado!');   
                                }
                                
                                
                            }

                        }

                    }else{
                        return false;
                    }


                }else{
                    return false;
                }
    
            }else{
                echo "dsfdsf";
                return false;
            }

        } catch (Exception $e) {

            WebhookErro::create(['ds_erro'=>'WebhookApiMe.webhookMessage','dados'=>$e]);
   
        }
  
    }
 
    public function retornoAtendimento(Request $request, $key)
    {
 
        try {

            if(!$key=='b5d02e3d6d85'){
                return false;
            }
        
            $empresa = Empresa::where('sn_ativo','s')->first();
            $api = new ApiWaMe(); 
  
            //Finaliza todos os agendamentos menor que a data atual.
            AgendamentoWhast::where('agendamento_whast.situacao','A')
            ->join('agendamento','agendamento.cd_agendamento','agendamento_whast.cd_agendamento')
            ->whereRaw("dt_agenda<curdate()")->update(['agendamento_whast.situacao'=>'F','agendamento_whast.dt_finalizacao'=>date('Y-m-d H:i'),'agendamento_whast.retorno'=>'0']);
    

            //PACIENTE FALTOU
            if( ($empresa->sn_faltou=='sim') && ($empresa->situacao_faltou) && ($empresa->msg_faltou) ){
                $SITUACA_FALTOU=$empresa->situacao_faltou;
                $query=Agendamento::where('situacao',$SITUACA_FALTOU)
                ->whereRaw("dt_agenda = date_add(curdate(), interval -1 day)")
                ->with('paciente','profissional')->get();
    
                //dd($query->toArray());
                foreach($query as $val){
                    $agendamento=$val->cd_agendamento;
                    $TextoPadrao = utf8_decode($empresa->msg_faltou);
                    $Texto = HelperTextoFaltou($val->paciente->nm_paciente, $val->profissional->nm_profissional, $val->dt_agenda, $val->hr_agenda, $TextoPadrao);
                    $somenteNumeros = preg_replace('/[^0-9]/', '', $val->celular);
                    
                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros); 
                      
                    $retorno = $api->sendTextMessage($somenteNumeros,$Texto,$empresa);
                    //dd($retorno);
                    if($retorno['retorno']==true){
                        $Instance = json_decode($retorno['dados'], true);
                        if($Instance['status']==200) {
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Instance,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'faltou',
                                    'id' =>  $Instance['data']['key']['id'],
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }else{
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'faltou',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }

                    }else{
                        DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                            WhastSend::create([
                                'status' => 200,
                                'tipo' => 'faltou',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $Texto,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                            ]);
                        });
                    }
                }
    
            }
    
            //PESQUISA DE SATISFAÇÃO
            if( ($empresa->sn_pesquisa=='sim') && ($empresa->situacao_pesquisa) && ($empresa->pesquisa_satisfacao) ){
                $SITUACA_PESQUISA=$empresa->situacao_pesquisa;

                $query=Agendamento::where('situacao',$SITUACA_PESQUISA)
                ->whereRaw("dt_agenda = date_add(curdate(), interval -1 day)")
                ->with('paciente','profissional')->get();
    

                foreach($query as $val){
                    $agendamento=$val->cd_agendamento; 
                    $TextoPadrao = utf8_decode($empresa->pesquisa_satisfacao);
                    $Texto = HelperTextoPesquisaSatisfacao($val->paciente->nm_paciente, $val->profissional->nm_profissional, $TextoPadrao);
                    $somenteNumeros = preg_replace('/[^0-9]/', '', $val->celular);
                    
                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros); 
                     
                    if($empresa->logo_pesq_satisf){
                        $retorno=$api->sendMediaBase64($somenteNumeros,$Texto,$empresa->logo_pesq_satisf,$empresa->type_logo_pesq_satisf,$empresa);
                    }else{
                        $retorno = $api->sendTextMessage($somenteNumeros,$Texto,$empresa);
                    }
                                    
                    if($retorno['retorno']==true){
                        $Instance = json_decode($retorno['dados'], true);
                
                        if($Instance['status']==200) {
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Instance,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'pesquisa',
                                    'id' =>  $Instance['data']['key']['id'],
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }else{
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'faltou',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }

                    }else{
                        DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                            WhastSend::create([
                                'status' => 200,
                                'tipo' => 'faltou',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $Texto,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                            ]);
                        });
                    }

                }

            }

            // ANIVERSARIO (PACIENTE, MAE , PAI )
            if(($empresa->sn_niver=='sim') && ($empresa->msg_niver)){
                
                $TextoPadrao = utf8_decode($empresa->msg_niver);
            
                //PACIENTE
                $query=Paciente::where('sn_ativo','S')
                ->whereRaw(" date_format(dt_nasc,'%d%m') =date_format(curdate(),'%d%m') ")
                ->whereNotNull("celular")->get();
            
                foreach($query as $val){
                    $paciente=$val->cd_paciente; 
                    $Texto = HelperTextoNiver($val->nm_paciente , $TextoPadrao);
                    $somenteNumeros = preg_replace('/[^0-9]/', '', $val->celular);

                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros); 
                                         
                    $retorno = $api->sendTextMessage($somenteNumeros,$Texto,$empresa);
                    if($retorno['retorno']==true){
                        $Instance = json_decode($retorno['dados'], true);
                        if($Instance['status']==200) {
                            DB::transaction(function () use ($paciente,$somenteNumeros,$Instance,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'aniversario',
                                    'id' =>  $Instance['data']['key']['id'],
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_paciente' => $paciente,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }else{
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'faltou',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }

                    }else{
                        DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                            WhastSend::create([
                                'status' => 200,
                                'tipo' => 'faltou',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $Texto,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                            ]);
                        });
                    }

                }

                //MAE
                $query=Paciente::where('sn_ativo','S')
                ->whereRaw("  date_format(dt_nasc_mae,'%d%m')=date_format(curdate(),'%d%m')")
                ->whereNotNull("celular_mae")->get();
                
                foreach($query as $val){
                    $paciente=$val->cd_paciente; 
                    $Texto = HelperTextoNiver($val->nm_paciente , $TextoPadrao);
                    $somenteNumeros = preg_replace('/[^0-9]/', '', $val->celular_mae);

                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros); 
                     
                    $retorno = $api->sendTextMessage($somenteNumeros,$Texto,$empresa);
                    if($retorno['retorno']==true){
                        $Instance = json_decode($retorno['dados'], true);
                        if($Instance['status']==200) {
                            DB::transaction(function () use ($paciente,$somenteNumeros,$Instance,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'aniversario',
                                    'id' =>  $Instance['data']['key']['id'],
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_paciente' => $paciente,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }else{
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'faltou',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }

                    }else{
                        DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                            WhastSend::create([
                                'status' => 200,
                                'tipo' => 'faltou',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $Texto,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                            ]);
                        });
                    }

                }
                
                //PAI
                $query=Paciente::where('sn_ativo','S')
                ->whereRaw(" date_format(dt_nasc_pai,'%d%m')=date_format(curdate(),'%d%m') ")
                ->whereNotNull("celular_pai")->get();
                foreach($query as $val){
                    $paciente=$val->cd_paciente; 
                    $Texto = HelperTextoNiver($val->nm_paciente , $TextoPadrao);
                    $somenteNumeros = preg_replace('/[^0-9]/', '', $query->celular_pai);
    
                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros); 
                     
                    $retorno = $api->sendTextMessage($somenteNumeros,$Texto,$empresa);
                    if($retorno['retorno']==true){
                        $Instance = json_decode($retorno['dados'], true);
                        if($Instance['status']==200) {
                            DB::transaction(function () use ($paciente,$somenteNumeros,$Instance,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'aniversario',
                                    'id' =>  $Instance['data']['key']['id'],
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_paciente' => $paciente,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }else{
                            DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'faltou',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $Texto,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                                ]);
                            });
                        }

                    }else{
                        DB::transaction(function () use ($agendamento,$somenteNumeros,$Texto,$retorno,$request) {
                            WhastSend::create([
                                'status' => 200,
                                'tipo' => 'faltou',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $Texto,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : 'rotina'
                            ]);
                        });
                    }

                }

            }

        } catch (Exception $e) {

            WebhookErro::create(['ds_erro'=>'WebhookApiMe.retornoAtendimento','dados'=>$e]);
   
        }

    }

  
}
