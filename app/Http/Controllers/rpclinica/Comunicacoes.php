<?php


namespace App\Http\Controllers\rpclinica;

use App\Bibliotecas\ApiWaMe;
use App\Bibliotecas\Kentro;
use App\Bibliotecas\WhatsApp as BibliotecasWhatsApp;
use App\Http\Controllers\app_rpclinic\Agendamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento as RpclinicaAgendamento;
use App\Model\rpclinica\AgendamentoItens;
use App\Model\rpclinica\AgendamentoItensHist;
use App\Model\rpclinica\AgendamentoWhast;
use App\Model\rpclinica\ComunicacaoSend;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\WhastTag;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Route;
use App\Model\rpclinica\WhatsApp;
use Throwable;

class Comunicacoes extends Controller
{

    public function __construct()
    {
        set_time_limit(600);  
    }

    public function index(Request $request)
    {

        $empresa = Empresa::find($request->user()->cd_empresa);
        $data = null;

        if ($empresa->key_whast) {

            $data['tp_api'] = $empresa->api_whast;
            if ($empresa->api_whast == 'kentro') {
                $data['sn_api'] = 'S';
                $api = new Kentro();
                $dados = $api->getQueueStatus();
                //dd($dados);
                if ($dados['retorno'] == true) {
                    $Instance = json_decode($dados['dados'], true);
                    if (isset($Instance['enabled'])) {

                        $data['sn_api'] = 'S';
                        $data['phoneConnected'] = $Instance['authenticated'];
                        if ($Instance['enabled'] == false) {
                            $api->enableQueue();
                        }
                        if ($Instance['connected'] == false) {
                            $api->connectQueue();
                        }
                        if ($Instance['authenticated'] == false) {
                            $dados = $api->getQueueQrCode();
                            if ($dados['retorno'] == true) {
                                $data['image_qrcode'] = (isset($dados['dados']) ? $dados['dados'] : null);
                            }
                        } else {
                            $data['description'] = (isset($Instance['name'])) ? $Instance['name'] : null;
                            $data['name'] =   $Instance['authenticatedNumber'];
                            $data['img'] =    null;
                        }
                    } else {

                        $data['sn_api'] = 'E';
                        $data['msg'] = 'Erro ao carregar com API.';
                    }
                } else {
                    $data['sn_api'] = 'N';
                    $data['msg'] = 'API do WhatsApp não configurada no sistema.';
                }
            }

            if ($empresa->api_whast == 'api-wa.me') {

                $data['sn_api'] = 'S';
                $api = new ApiWaMe();
                $dados = $api->getInstance($empresa->key_whast, 'GET');

                if ($dados['retorno'] == true) {
                    $Instance = json_decode($dados['dados'], true);
                    if (isset($Instance['instance'])) {
                        $data['sn_api'] = 'S';
                        $Instance = json_decode($dados['dados'], true);
                        $data['phoneConnected'] = $Instance['instance']['phoneConnected'];

                        if ($Instance['instance']['phoneConnected'] == false) {
                            $dados = $api->getInstance($empresa->key_whast, 'POST');
                            $Instance = json_decode($dados['dados'], true);
                            $data['image_qrcode'] = (isset($Instance['image']) ? $Instance['image'] : null);
                        } else {


                            $data['description'] = (isset($Instance['businessProfile']['description'])) ? $Instance['businessProfile']['description'] : null;
                            $data['name'] = (isset($Instance['instance']['user']['name'])) ? $Instance['instance']['user']['name'] : null;
                            $data['img'] = (isset($Instance['instance']['user']['imageProfile'])) ? $Instance['instance']['user']['imageProfile'] : null;
                        }
                    } else {

                        $data['sn_api'] = 'E';
                        $data['msg'] = 'Erro ao carregar com API.';
                    }
                } else {
                    $data['sn_api'] = 'N';
                    $data['msg'] = 'API do WhatsApp não configurada no sistema.';
                }
            }
        }

        return view('rpclinica.comunicacao.tela', compact('empresa', 'data'));
    }

    public function desconectar(Request $request)
    {
        $empresa = Empresa::find($request->user()->cd_empresa);
        if ($empresa->key_whast) {
            if ($empresa->api_whast == 'kentro') {
                $api = new Kentro();
                $dados = $api->logoutQueue();
                //dd($dados);
                $Instance = json_decode($dados['dados'], true);
                if (isset($Instance['message'])) {
                    if ($Instance['message'] == "ok") {
                        return redirect()->route('comunicacao.listar')->with('success', 'Desconectado com sucesso!');
                    } else {
                        return redirect()->route('comunicacao.listar')->with('error', 'Erro ao realizar operação!');
                    }
                } else {
                    return redirect()->route('comunicacao.listar')->with('error', 'Erro ao realizar operação!');
                }
            }
            if ($empresa->api_whast == 'api-wa.me') {
                $api = new ApiWaMe();
                $dados = $api->getDesconectar($empresa->key_whast);
                $Instance = json_decode($dados['dados'], true);
                if ($Instance['status'] == 200) {
                    return redirect()->route('comunicacao.listar')->with('success', 'Desconectado com sucesso!');
                } else {
                    return redirect()->route('comunicacao.listar')->with('error', 'Erro ao realizar operação!');
                }
            }
        }
    }

    public function CheckNumber(Request $request, $number)
    {
        $empresa = Empresa::find($request->user()->cd_empresa);
        $somenteNumeros = preg_replace('/[^0-9]/', '', $number);

        if ($empresa->api_whast == 'kentro') {
            $api = new Kentro();
            $dados = $api->checkIfUserExists($somenteNumeros);
            if ($dados['retorno'] == true) {
                $Instance = json_decode($dados['dados'], true);

                if (isset($Instance['exists'])) {
                    if ($Instance['exists'] == true) {
                        return response()->json(['retorno' => true, 'conectado' => true, 'dados' => $Instance['exists'], 'msg' => "Numero possui conta no WhatsApp!"]);
                    } else {
                        return response()->json(['retorno' => false, 'conectado' => true, 'dados' => false, 'msg' => "Numero não encontrado no WhatsApp!"]);
                    }
                } else {
                    if (isset($Instance['message'])) {
                        return response()->json(['retorno' => false, 'conectado' => false, 'dados' => false, 'msg' => $Instance['message']]);
                    }
                }
            }
        } 
        if ($empresa->api_whast == 'api-wa.me') {
             
            $api = new ApiWaMe();
            $dados = $api->getRegistered($somenteNumeros); 
            $Instance = json_decode($dados['dados'], true);
            
            if (isset($Instance['status'])) { 

                if ($Instance['status'] == 401) {
                    return response()->json(['retorno' => false, 'conectado' => false, 'dados' => false, 'msg' => "WhatsApp não conectado!"]);
                }

                if ($Instance['status'] == 500) {
                    return response()->json(['retorno' => false, 'conectado' => true, 'dados' => false, 'msg' => "Numero não encontrado no WhatsApp!"]);
                }
            }
            
            if (isset($Instance[0]['jid'])) { 
                return response()->json(['retorno' => true, 'conectado' => true, 'dados' => $Instance[0]['exists'], 'msg' => "Numero possui conta no WhatsApp!"]);
            }

        }

        return response()->json(['retorno' => false, 'conectado' => false, 'dados' => false, 'msg' => 'APi não esta configurado no Sistema!']);
    }


    public function send_laudo($item, $emp = null, $rotina = 'N')
    {
        try {
         
            $item = AgendamentoItens::find($item);
            $item->load('atendimento.paciente', 'atendimento.profissional', 'exame');


            if($rotina=='N'){
                $hist = AgendamentoItensHist::where('cd_agendamento_item', '=', $item->cd_agendamento_item)
                    ->join('usuarios', 'usuarios.cd_usuario', 'agendamento_item_hist.cd_usuario')
                    ->selectRaw("agendamento_item_hist.*,date_format(agendamento_item_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
                    ->selectRaw("usuarios.nm_usuario")
                    ->orderBy("agendamento_item_hist.created_at", "desc")
                    ->get();

                if (empty(Auth::user()->cd_empresa)) {
                    $codEmpresa = $emp;
                } else {
                    $codEmpresa = Auth::user()->cd_empresa;
                }
                $empresa = Empresa::find($codEmpresa);
                if (!$item->atendimento->celular) {
                    return response()->json(['message' => 'Numero Invalido!'], 400);
                }
            }else{

                $codEmpresa = $emp;
                $empresa = Empresa::find($codEmpresa);
                if (!$item->atendimento->celular) {

                    $dadosItens['cd_status_envio'] = 'E';
                    $dadosItens['cd_usuario_envio'] = (empty(Auth::user()->cd_usuario)) ? 'rotina' : Auth::user()->cd_usuario;
                    $dadosItens['dt_envio'] = date('Y-m-d H:i');
                    $item->update($dadosItens); 
                    return false;

                }
            }


            $whatsapp = new ApiWaMe();
            $fone = $whatsapp->formatPhone($item->atendimento->celular);
            $nm_paciente = mb_strtoupper($item->atendimento->paciente->nm_paciente);
            $nm_exame = mb_strtoupper($item->exame->nm_exame);
            $nm_profissional = mb_strtoupper($item->atendimento->profissional->nm_profissional);
            $Texto = str_replace(
                ['@PACIENTE', '@PROFISSIONAL', '@EXAME', '@NOME_FANTASIA'],
                [mb_convert_case($nm_paciente, MB_CASE_TITLE, "UTF-8"),  mb_convert_case($nm_profissional, MB_CASE_TITLE, "UTF-8"), mb_convert_case($nm_exame, MB_CASE_TITLE, "UTF-8"), $empresa->nm_empresa],
                $empresa->msg_laudo
            );

            $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https://' : 'http://';
            $host = $_SERVER['HTTP_HOST'] . '/rpclinica/laudo-paciente/'; 
            $body['to'] = "55" . $fone;
            //$body['to'] = "5538988281639";
            $body['url'] = $protocol . $host . $item->cd_agendamento_item . '/' . $item->key; 
            $body['mimetype'] = "application/pdf";
            $body['fileName'] = 'Laudo de Exame';
            $body['caption'] = $Texto;
              
            $dados = $whatsapp->sendDocumentoMessage($body, $codEmpresa);

            if (isset($dados['retorno']))
                if ($dados['retorno'] == true) {

                    $Instance = json_decode($dados['dados'], true);

                    if (isset($Instance['status']))
                        if ($Instance['status'] == 200) {

                            DB::beginTransaction();

                            $insert['cd_agendamento'] = $item->cd_agendamento;
                            $insert['cd_agendamento_item'] = $item->cd_agendamento_item;
                            $insert['cd_usuario'] = (empty(Auth::user()->cd_usuario)) ? 'rotina' : Auth::user()->cd_usuario;
                            $insert['status'] = $Instance['data']['status'];
                            $insert['dt_envio'] = date('Y-m-d H:i', $Instance['data']['messageTimestamp']);
                            $insert['key_laudo'] = $item->key;
                            $insert['tipo'] = 'laudo';
                            $insert['url'] = $body['url'];
                            if (isset($Instance['data']['key'])) {
                                $insert['id'] = $Instance['data']['key']['id'];
                                $insert['nr_send'] = $Instance['data']['key']['remoteJid'];
                                $insert['from_me'] = $Instance['data']['key']['fromMe'];
                            }
                            if (isset($Instance['data']['message']['documentMessage'])) {
                                $insert['conteudo'] = $Instance['data']['message']['documentMessage']['caption'];
                            }

                            $send = WhastSend::create($insert);

                            $dadosItens['cd_status_envio'] = 'S';
                            $dadosItens['cd_usuario_envio'] = (empty(Auth::user()->cd_usuario)) ? 'rotina' : Auth::user()->cd_usuario;
                            $dadosItens['dt_envio'] = date('Y-m-d H:i');
                            $item->update($dadosItens);

                            $dadosHist['cd_agendamento_item'] = $item->cd_agendamento_item;
                            $dadosHist['ds_historico'] = "Mensagem enviada! \r\n[ ID: " . $insert['id'] . " ]\r\n[ FONE: " . $insert['nr_send'] . " ]";
                            $dadosHist['cd_usuario'] = (empty(Auth::user()->cd_usuario)) ? 'rotina' : Auth::user()->cd_usuario;
                            $dadosHist['cd_whast_send'] = $send->cd_whast_send;
                            AgendamentoItensHist::create($dadosHist);

                            DB::commit();

                            $hist = AgendamentoItensHist::where('cd_agendamento_item', '=', $item->cd_agendamento_item)
                                ->join('usuarios', 'usuarios.cd_usuario', 'agendamento_item_hist.cd_usuario')
                                ->selectRaw("agendamento_item_hist.*,date_format(agendamento_item_hist.created_at,'%d/%m/%Y %H:%i:%s') created_data ")
                                ->selectRaw("usuarios.nm_usuario")
                                ->orderBy("agendamento_item_hist.created_at", "desc")
                                ->get();

                            return response()->json(['retorno' => true, 'hist' => $hist]);
                        } else {


                            $dadosItens['cd_status_envio'] = 'E';
                            $dadosItens['cd_usuario_envio'] = (empty(Auth::user()->cd_usuario)) ? 'rotina' : Auth::user()->cd_usuario;
                            $dadosItens['dt_envio'] = date('Y-m-d H:i');
                            $item->update($dadosItens);

                            $insert['nr_send'] = $body['to'];
                            $insert['cd_agendamento'] = $item->cd_agendamento;
                            $insert['cd_agendamento_item'] = $item->cd_agendamento_item;

                            $insert['cd_usuario'] = (empty(Auth::user()->cd_usuario)) ? 'rotina' : Auth::user()->cd_usuario;
                            $insert['status'] = 'ERRO';
                            $insert['dt_envio'] = date('Y-m-d H:i');
                            $insert['key_laudo'] = $item->key;
                            $insert['tipo'] = 'laudo';
                            $insert['retorno'] = $dados['dados'];
                            $insert['conteudo'] = '<code>' . ($Instance['message']) . '</code>';
                            $insert['url'] = $body['url'];
                            $send = WhastSend::create($insert);

                            $msg = (isset($Instance['message'])) ? $Instance['message'] : null;
                            if($rotina=='N'){
                                return response()->json(['retorno' => false, 'msg' => $msg]);
                            }else{
                                return true;
                            }
                        }
                }
        } catch (Throwable $error) {

            DB::rollback();
            return response()->json(['retorno' => false, 'message' => 'Erro no envio da mensagem!  ' . $error->getMessage()], 400);
        }
    }

    public function confirmaAgendamentoManual(Request $request)
    {
        $empresa = Empresa::find($request->user()->cd_empresa);
        $TextoPadrao = utf8_decode($empresa->msg_agendamento);

        if (empty($empresa->instancia)) {
            return response()->json(['message' => 'Instancia não esta configurada para confirmação da agenda. '], 500);
        }
        $bancoDeDados = $empresa->instancia;

        if ($empresa->sn_agendamento <> 'sim') {
            return response()->json(['message' => 'A API não foi configurada para envio. '], 500);
        }

        if (empty($empresa->msg_agendamento)) {
            return response()->json(['message' => 'Texto da Mensagem não condigurado! { Campo: Confirmação de Agendamento } '], 500);
        }

        if ($empresa->api_whast == 'kentro') {
            if ((!$empresa->key_whast) || (!$empresa->url_whast) || (!$empresa->fila_whast)) {
                return response()->json(['message' => 'Erro na configuração da API. '], 500);
            }
        }

        if($request['agendamento'])
        foreach ($request['agendamento'] as $agendamento) {
            $query = RpclinicaAgendamento::find($agendamento);
            $query->load('paciente', 'agenda', 'profissional');
            $query->profissional['como_ser_chamado'] = (($query->profissional->sexo == 'F') ? 'Dra.' : ((($query->profissional->sexo == 'M') ? 'Dr.' : 'Dr(a).')));
            $query->profissional['do_da'] = (($query->profissional->sexo == 'F') ? 'da' : ((($query->profissional->sexo == 'M') ? 'do' : ' ')));

            $variavel = array("@PACIENTE", "@PROFISSIONAL", "@DATA", "@HR_AGENDAMENTO", "@DO_DA", "@DR_DRA");
            $valores = array(
                ucfirst($query->paciente->nm_paciente),
                ucfirst($query->profissional->nm_profissional),
                date("d/m/Y", strtotime($query->dt_agenda)),
                $query->hr_agenda,
                (isset($query->profissional->do_da)) ? $query->profissional->do_da : '',
                (isset($query->profissional->do_da)) ? $query->profissional->como_ser_chamado : ''

            );
            $TextoMsg = str_replace($variavel, $valores, $TextoPadrao);
            $somenteNumeros = preg_replace('/[^0-9]/', '', $query->celular);

            if ($empresa->api_whast == 'kentro') {

                if ($empresa->whast_oficial == 'SIM') {
                    
                    if (empty($empresa->whast_temp_agenda)) {
                        return response()->json(['message' => 'Template do Agendamento não condigurado!'], 500);
                    }

                    $api = new Kentro();
                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros);
                     
                    $valores = array('*'.mb_strtoupper($query->paciente->nm_paciente).'*', '*'.mb_strtoupper($query->profissional->nm_profissional).'*', '*'.date("d/m/Y", strtotime($query->dt_agenda)).'*', '*'.$query->hr_agenda.'*');
                    $retorno = $api->sendWaTemplate($somenteNumeros, $valores, $empresa);
                    if ($retorno['retorno'] == true) {
                        $Instance = json_decode($retorno['dados'], true);
                        if (isset($Instance['message'])) {
                            if ($Instance['message'] == 'success') {

                                AgendamentoWhast::updateOrCreate(['cd_agendamento' => $agendamento],
                                [
                                    'cd_usuario'=> (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null,
                                    'celular' => substr($somenteNumeros, -8),
                                    'celular_envio' => $somenteNumeros,
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'situacao' => 'A', 
                                    'cd_agendamento' =>$agendamento,
                                    'cd_paciente'=>$query->cd_paciente
                                ]);

                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'agenda',
                                    'id' => $empresa->whast_temp_agenda,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => implode(', ', $valores),
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                                ]);
                                $query->update(['whast' => true, 'dt_whast' => date('Y-m-d H:i')]);

                            }else{

                                WhastSend::create([
                                    'status' => 404,
                                    'tipo' => 'agenda',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => implode(', ', $valores),
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                                ]);

                            }

                        } else {

                            WhastSend::create([
                                'status' => 500,
                                'tipo' => 'agenda',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => implode(', ', $valores),
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                            ]);

                        } 

                    }  

                } else { 

                    $api = new Kentro();
                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros);
                    $api = new Kentro();
                    $somenteNumeros = '55' . $api->formatPhone($somenteNumeros);
                    $Xdata = 'confirAgendaRPclinic|' . $query->cd_agendamento . '|' . $bancoDeDados . '|' . $somenteNumeros . '|' . $request->user()->cd_empresa;
                    $retorno = $api->enqueueMessageToSend($somenteNumeros, $TextoMsg, $request->user()->cd_empresa, $Xdata);
                    if ($retorno['retorno'] == true) {
                        $Instance = json_decode($retorno['dados'], true);
                        if (isset($Instance['message'])) {

                            if ($Instance['message'] == 'success') {
                                $idMsg = $Instance['enqueuedId'];

                                WhastSend::create([
                                    'status' => 200,
                                    'tipo' => 'agenda',
                                    'id' => $Instance['enqueuedId'],
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $TextoMsg,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '1',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                                ]);
                                $query->update(['whast' => true, 'dt_whast' => date('Y-m-d H:i')]);
                            } else {
                                WhastSend::create([
                                    'status' => 404,
                                    'tipo' => 'agenda',
                                    'id' => null,
                                    'nr_send' => $somenteNumeros,
                                    'conteudo' => $TextoMsg,
                                    'retorno' => $retorno['dados'],
                                    'dt_envio' => date('Y-m-d H:i'),
                                    'from_me' => '0',
                                    'cd_agendamento' => $agendamento,
                                    'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                                ]);
                            }
                        } else {
                            WhastSend::create([
                                'status' => 500,
                                'tipo' => 'agenda',
                                'id' => null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $TextoMsg,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '0',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                            ]);
                        }
                    }
                }
            }


            if ($empresa->api_whast == 'api-wa.me') {
                $api = new ApiWaMe(); 
                $somenteNumeros = '55' . $api->formatPhone($somenteNumeros); 
                $retorno = $api->sendTextMessage($somenteNumeros,$TextoMsg,$empresa);
                if($retorno['retorno']==true){
                    $Instance = json_decode($retorno['dados'], true);
                    if($Instance['status']==200) {
                        DB::transaction(function () use ($agendamento,$somenteNumeros,$Instance,$TextoMsg,$retorno,$request,$query) {
                            WhastSend::create([
                                'status' => 200,
                                'tipo' => 'agenda',
                                'id' =>  (isset($Instance['data']['key']['id'])) ? $Instance['data']['key']['id'] : null,
                                'nr_send' => $somenteNumeros,
                                'conteudo' => $TextoMsg,
                                'retorno' => $retorno['dados'],
                                'dt_envio' => date('Y-m-d H:i'),
                                'from_me' => '1',
                                'cd_agendamento' => $agendamento,
                                'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                            ]);
                            $query->update(['whast' => true, 'dt_whast' => date('Y-m-d H:i')]);
                            $remoteId=explode('@',$Instance['data']['key']['remoteJid']);
                            if(!isset($remoteId[0])){
                                return response()->json(['message' => 'Numero de telefone não retornado corretamente!'], 500);
                            }
                            AgendamentoWhast::where('cd_agendamento',$agendamento)->where('celular',$remoteId[0])
                            ->where('situacao','A')->delete();
                            AgendamentoWhast::create([
                                'cd_usuario'=> (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null,
                                'celular' => $remoteId[0],
                                'celular_envio' => $somenteNumeros,
                                'dt_envio' => date('Y-m-d H:i'),
                                'situacao' => 'A', 
                                'cd_agendamento' =>$agendamento,
                                'cd_paciente'=>$query->cd_paciente
                            ]);
                            RpclinicaAgendamento::where('cd_agendamento',$agendamento)
                            ->update([
                                'whast_id'=> (isset($Instance['data']['key']['id'])) ? $Instance['data']['key']['id'] : null
                            ]);
                        });


                    }else{
                        WhastSend::create([
                            'status' => 404,
                            'tipo' => 'agenda',
                            'id' => null,
                            'nr_send' => $somenteNumeros,
                            'conteudo' => $TextoMsg,
                            'retorno' => $retorno['dados'],
                            'dt_envio' => date('Y-m-d H:i'),
                            'from_me' => '0',
                            'cd_agendamento' => $agendamento,
                            'cd_usuario' => (isset($request->user()->cd_usuario)) ? $request->user()->cd_usuario : null
                        ]);
                    }

                } 
                 
            }
 
        }
        return response()->json(['message' => "Rotina executada com sucesso!", 'request' => $request->toArray()]);
    }

    public function atualizarRetornos(Request $request)
    {
        try {

            //Busca as tags cadastradas
            $getOpcoes=WhastTag::where('tipo','confirmacao_agenda')->get();
            $arrayTag=[];
            foreach($getOpcoes as $tag){
                $arrayTag[]=mb_strtoupper($tag->tag);
                $dadosTag[mb_strtoupper($tag->tag)] = array('situacao'=>$tag->situacao);
            }

            $api = new ApiWaMe(); 
            $empresa = Empresa::find($request->user()->cd_empresa);

            foreach ($request['agendamento'] as $agendamento) {

                $retorno = AgendamentoWhast::where('cd_agendamento',$agendamento)
                ->where('situacao','A')->with('tab_agendamento')->get();

                foreach($retorno as $val){

                    $DataCorte=strtotime($val->dt_envio);
                    $retorno=$api->listMessage($val->celular,$empresa); 
                    $Instance = json_decode($retorno['dados'], true);
                    if($retorno['retorno']==true){

                            $Instance = json_decode($retorno['dados'], true);
                            $messages = $Instance['messages'];

                            foreach($messages as $msg){

                                $dtMsg=$msg['content']['messageTimestamp'];
                                if($dtMsg >= $DataCorte){
                                    $Msg=(isset($msg['content']['message']['conversation'])) ? $msg['content']['message']['conversation'] : null;
                                    $idMsg=(isset($msg['msgId'])) ? $msg['msgId'] : null;

                                    if (in_array(mb_strtoupper($Msg), $arrayTag)) {

                                        $Agendamento=$val->cd_agendamento;
                                        $CodigoWhast = $val->cd_agendamento_whast; 
                                        $situacaoConfirmado=(isset($empresa->situacao_ag_confirm)) ? $empresa->situacao_ag_confirm : null; 
                                        $situacaoCancelado= (isset($empresa->situacao_ag_cancel)) ? $empresa->situacao_ag_cancel : null; 
                                        $dados=$dadosTag[mb_strtoupper($Msg)];

                                        if($dados['situacao']=='CA'){

                                            DB::transaction(function () use ($Agendamento,$situacaoCancelado,$Msg,$CodigoWhast,$idMsg) { 
                                                RpclinicaAgendamento::where('cd_agendamento', $Agendamento)->update([
                                                    'situacao'=>$situacaoCancelado,
                                                    'dt_resp_whast'=>date('Y-m-d H:i'),
                                                    'ds_whast'=>$Msg, 
                                                    'whast_id'=> $idMsg
                                                ]);

                                                AgendamentoWhast::where('cd_agendamento_whast',$CodigoWhast)
                                                ->update([
                                                    'situacao'=>'F',
                                                    'dt_finalizacao'=>date('Y-m-d H:i'),
                                                    'retorno'=>$Msg
                                                ]); 
                                            });
    
                                        }

                                        if($dados['situacao']=='CO'){

                                            DB::transaction(function () use ($Agendamento,$situacaoConfirmado,$Msg,$CodigoWhast,$idMsg) {

                                                RpclinicaAgendamento::where('cd_agendamento', $Agendamento)->update([
                                                    'situacao'=>$situacaoConfirmado,
                                                    'dt_resp_whast'=>date('Y-m-d H:i'),
                                                    'ds_whast'=>$Msg,  
                                                    'whast_id'=> $idMsg
                                                ]); 

                                                AgendamentoWhast::where('cd_agendamento_whast',$CodigoWhast)
                                                ->update([
                                                    'situacao'=>'F',
                                                    'dt_finalizacao'=>date('Y-m-d H:i'),
                                                    'retorno'=>$Msg
                                                ]); 

                                            });

                                        }

                                    }

                                }

                            }

                    }

                }
            }
            return response()->json(['retorno' => true, 'message' => 'Rotina Atualizada com sucesso!']);

        } catch (Throwable $error) {

            DB::rollback();
            return response()->json(['retorno' => false, 'message' => 'Erro no envio da mensagem!  ' . $error->getMessage()], 400);
        }

    }
    
}
