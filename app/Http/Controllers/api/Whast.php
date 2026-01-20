<?php


namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\rpclinica\procedimento\ProcedimentosRequest;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\ComunicacaoSend;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\WebhookMessage;
use App\Model\rpclinica\WhastApi;
use App\Model\rpclinica\WhastReceive;
use App\Model\rpclinica\WhastRetornoAgenda;
use App\Model\rpclinica\WhastSend;
use App\Model\rpclinica\WhastSituacao;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class Whast extends Controller
{

    
    public function confirmaAgenda(Request $request)
    {

        $dados = WhastApi::find('WHAST');

        $text  = $dados->msg_agenda;

        $body = [
            "messageData" => [
                "to" => "553888281639",
                "text" => $text
            ]
        ];


        echo whast_getSendAgenda2('x86x5f7a7df7d29b917b8d101d80a2622bb1e156d8a0b18a0565584f231845645bc4', $body);
    }

    public function webhookMessage(Request $request)
    {
        $DADOS1 = null;
        header('Content-Type: application/json; charset=UTF-8');
        $data = file_get_contents("php://input");



        $event = json_decode($data, true);
        print_r($event);
        $conteudo = $data;
        $dados['conteudo'] = $data;
        $dados['tipo'] = 'webhookMessage';
        WebhookMessage::create($dados);

        if (isset($event)) {
            $TIPO_ACAO = $event['type'];
            $TIPO_MSG = $event['data']['messageType'];
            $NR_DA_MSG_ENVIO = $event['data']['jid'];
            $NM_DA_MSG_ENVIO = $event['data']['pushName'];
            $NR_DA_RESP = $event['data']['key']['remoteJid'];
            $ID_DA_RESP = $event['data']['key']['id'];

            if ($TIPO_ACAO == 'message') {

                $DADOS1 = $DADOS1 . "\n\n\n  MENSAGEM " . $ID_DA_RESP . " \n ";
                $DADOS1 = $DADOS1 . "\n TIPO_ACAO: " . $TIPO_ACAO;
                $DADOS1 = $DADOS1 . "\n TIPO_MSG: " . $TIPO_MSG;
                $DADOS1 = $DADOS1 . "\n NR_DA_MSG_ENVIO: " . $NR_DA_MSG_ENVIO;
                $DADOS1 = $DADOS1 . "\n NM_DA_MSG_ENVIO: " . $NM_DA_MSG_ENVIO;
                $DADOS1 = $DADOS1 . "\n ID_DA_RESP: " . $ID_DA_RESP;

                if ($TIPO_MSG == 'messageContextInfo') {

                    $ID_OP_SELECT = $event['data']['msgContent']['buttonsResponseMessage']['selectedButtonId'];
                    $DS_OP_SELECT = $event['data']['msgContent']['buttonsResponseMessage']['selectedDisplayText'];
                    $ID_ENVIO = $event['data']['msgContent']['buttonsResponseMessage']['contextInfo']['stanzaId'];
                    $NR_RESPOSTA = $event['data']['key']['remoteJid'];
                    $DATA_RESPOSTA = $event['data']['msgContent']['messageContextInfo']['deviceListMetadata']['recipientTimestamp'];

                    $DADOS1 = $DADOS1 . "\n ID_OP_SELECT: " . $ID_OP_SELECT;
                    $DADOS1 = $DADOS1 . "\n DS_OP_SELECT: " . $DS_OP_SELECT;
                    $DADOS1 = $DADOS1 . "\n ID_ENVIO: " . $ID_ENVIO;
                    $DADOS1 = $DADOS1 . "\n NR_RESPOSTA: " . $NR_RESPOSTA;
                    $DADOS1 = $DADOS1 . "\n DATA_RESPOSTA: " . $DATA_RESPOSTA;

                    $INSERT['tp_acao'] = $TIPO_ACAO;
                    $INSERT['tp_msg'] = $TIPO_MSG;
                    $INSERT['dt_msg'] = date('Y-m-d H:i:s');
                    $INSERT['id_msg'] = $ID_DA_RESP;
                    $INSERT['nr_envio'] = $NR_RESPOSTA;
                    $INSERT['nm_envio'] = NULL;
                    $INSERT['msg'] = NULL;
                    $INSERT['id_op_select'] = $ID_OP_SELECT;
                    $INSERT['ds_op_select'] = $DS_OP_SELECT;
                    $INSERT['id_envio'] = $ID_ENVIO;
                    $INSERT['nr_resposta'] = $NR_DA_MSG_ENVIO;

                    $RETORNO = WhastReceive::create($INSERT);

                    $API = WhastSituacao::where('valor', $ID_OP_SELECT)
                        ->where('tipo', 'CONF_AGENDA')->first();
                    if ($API->situacao) {

                        $UpDados['cd_whast_receive'] = $RETORNO->cd_whast_receive;
                        $UpDados['situacao'] = $API->situacao;
                        $UpDados['whast_resp'] = $ID_OP_SELECT;
                        $UpDados['dt_resp_whast'] = date('Y-m-d H:i:s');
                        $UpDados['ds_whast'] = 'RETORNO VIA WHATSAPP';
                        $UpDados['id_resp_whast'] = $ID_DA_RESP;
                        $x = Agendamento::where('whast_id', $ID_ENVIO)->update($UpDados);
                    }
                }

                if ($TIPO_MSG == 'conversation') {

                    $MSG = $event['data']['msgContent']['conversation'];

                    $snAgenda = WhastSend::where('nr_send', trim($NR_DA_RESP))
                        ->whereRaw(" situacao_atende.sn_whast='S' ")
                        ->selectRaw("agendamento.cd_agendamento, agendamento.situacao,whast_send.id,whast_send.nr_send")
                        ->join('agendamento', 'agendamento.cd_agendamento', 'whast_send.cd_agendamento')
                        ->join('situacao_atende', 'situacao_atende.cd_situacao_atend', 'agendamento.situacao')
                        ->whereRaw('agendamento.whast_id = whast_send.id')
                        ->orderByRaw("whast_send.created_at desc")->first();

                    if ($snAgenda->cd_agendamento) {

                        $snResultado = WhastSituacao::whereRaw(" upper(chave) = '" . trim($MSG) . "'")
                            ->join('whast_opcoes', 'whast_opcoes.cd_situacao', 'whast_situacao.situacao')
                            ->whereRaw(" whast_situacao.tipo ='CONF_AGENDA' ")
                            ->selectRaw("whast_situacao.situacao")->first();

                        if ($snResultado->situacao) {

                            $INSERT['tp_acao'] = $TIPO_ACAO;
                            $INSERT['tp_msg'] = $TIPO_MSG;
                            $INSERT['dt_msg'] = date('Y-m-d H:i:s');
                            $INSERT['id_msg'] = $ID_DA_RESP;
                            $INSERT['nr_envio'] = $snAgenda->nr_send;
                            $INSERT['id_envio'] = $snAgenda->id;
                            $INSERT['nm_envio'] = NULL;
                            $INSERT['msg'] = trim($MSG);
                            $INSERT['id_op_select'] = trim($MSG);
                            $INSERT['ds_op_select'] = $snResultado->situacao;
                            $INSERT['nr_resposta'] = $NR_DA_RESP;
                            $RETORNO = WhastReceive::create($INSERT);

                            $UpDados['cd_whast_receive'] = $RETORNO->cd_whast_receive;
                            $UpDados['situacao'] = $snResultado->situacao;
                            $UpDados['whast_resp'] = trim($MSG);
                            $UpDados['dt_resp_whast'] = date('Y-m-d H:i:s');
                            $UpDados['ds_whast'] = 'RETORNO VIA WHATSAPP';
                            $UpDados['id_resp_whast'] = $ID_DA_RESP;
                            $x = Agendamento::where('cd_agendamento', $snAgenda->cd_agendamento)->update($UpDados);

                            $retornoMsg = WhastRetornoAgenda::where('cd_retorno', $snResultado->situacao)->first();
                            if ($retornoMsg->msg) {
                                $dados = WhastApi::find('WHAST');
                                $body = [
                                    "messageData" => [
                                        "to" => substr(trim($NR_DA_RESP), 0, 12),
                                        "text" => $retornoMsg->msg
                                    ]
                                ];
                                $retornoApi = whast_getSendAgenda2($dados->key, $body);
                            }
                        }
                    }

                    $DADOS1 = $DADOS1 . "\n MSG: " . $MSG;

                    $INSERT['tp_acao'] = $TIPO_ACAO;
                    $INSERT['tp_msg'] = $TIPO_MSG;
                    $INSERT['dt_msg'] = date('Y-m-d H:i:s');
                    $INSERT['id_msg'] = $ID_DA_RESP;
                    $INSERT['nr_envio'] = $NR_DA_MSG_ENVIO;
                    $INSERT['nm_envio'] = NULL;
                    $INSERT['msg'] = $MSG;

                    WhastReceive::insert($INSERT);

                    $fileE = 'log_conversation.txt';
                    file_put_contents($fileE, $DADOS1, FILE_APPEND | LOCK_EX);

                    $conteudo = $DADOS1;
                    $fp = fopen("conversation.txt", "wb");
                    fwrite($fp, $conteudo);
                    fclose($fp);
                }

                if($TIPO_MSG=='extendedTextMessage'){
                    $MSG = $event['data']['msgContent']['extendedTextMessage']['text'];

                    $snAgenda = WhastSend::where('nr_send', trim($NR_DA_RESP))
                        ->whereRaw(" situacao_atende.sn_whast='S' ")
                        ->selectRaw("agendamento.cd_agendamento, agendamento.situacao,whast_send.id,whast_send.nr_send")
                        ->join('agendamento', 'agendamento.cd_agendamento', 'whast_send.cd_agendamento')
                        ->join('situacao_atende', 'situacao_atende.cd_situacao_atend', 'agendamento.situacao')
                        ->whereRaw('agendamento.whast_id = whast_send.id')
                        ->orderByRaw("whast_send.created_at desc")->first();

                        if ($snAgenda->cd_agendamento) {
                            echo "<br><br>FOIIIIIIIIIIIIIIIII<br>";
                            $snResultado = WhastSituacao::whereRaw(" upper(chave) = '" . trim($MSG) . "'")
                                ->join('whast_opcoes', 'whast_opcoes.cd_situacao', 'whast_situacao.situacao')
                                ->whereRaw(" whast_situacao.tipo ='CONF_AGENDA' ")
                                ->selectRaw("whast_situacao.situacao")->first();

                            if ($snResultado->situacao) {

                                    $INSERT['tp_acao'] = $TIPO_ACAO;
                                    $INSERT['tp_msg'] = $TIPO_MSG;
                                    $INSERT['dt_msg'] = date('Y-m-d H:i:s');
                                    $INSERT['id_msg'] = $ID_DA_RESP;
                                    $INSERT['nr_envio'] = $snAgenda->nr_send;
                                    $INSERT['id_envio'] = $snAgenda->id;
                                    $INSERT['nm_envio'] = NULL;
                                    $INSERT['msg'] = trim($MSG);
                                    $INSERT['id_op_select'] = trim($MSG);
                                    $INSERT['ds_op_select'] = $snResultado->situacao;
                                    $INSERT['nr_resposta'] = $NR_DA_RESP;
                                    $RETORNO = WhastReceive::create($INSERT);

                                    $UpDados['cd_whast_receive'] = $RETORNO->cd_whast_receive;
                                    $UpDados['situacao'] = $snResultado->situacao;
                                    $UpDados['whast_resp'] = trim($MSG);
                                    $UpDados['dt_resp_whast'] = date('Y-m-d H:i:s');
                                    $UpDados['ds_whast'] = 'RETORNO VIA WHATSAPP';
                                    $UpDados['id_resp_whast'] = $ID_DA_RESP;
                                    $x = Agendamento::where('cd_agendamento', $snAgenda->cd_agendamento)->update($UpDados);

                                    $retornoMsg = WhastRetornoAgenda::where('cd_retorno', $snResultado->situacao)->first();
                                    if ($retornoMsg->msg) {
                                        $dados = WhastApi::find('WHAST');
                                        $body = [
                                            "messageData" => [
                                                "to" => substr(trim($NR_DA_RESP), 0, 12),
                                                "text" => $retornoMsg->msg
                                            ]
                                        ];
                                        $retornoApi = whast_getSendAgenda2($dados->key, $body);
                                    }

                            }

                        }

                }

                /*
                if($TIPO_MSG=='imageMessage'){

                            $URL_IMG = $event['data']['msgContent']['imageMessage']['url'];
                            $TP_IMG = $event['data']['msgContent']['imageMessage']['mimetype'];
                            $SHA256_IMG = $event['data']['msgContent']['imageMessage']['fileSha256'];
                            $LENGTH = $event['data']['msgContent']['imageMessage']['height'];
                            $WIDTH = $event['data']['msgContent']['imageMessage']['width'];
                            $CONTEUDO = $event['data']['msgContent']['imageMessage']['jpegThumbnail'];
                            $ID_MSG = $event['data']['msgContent']['imageMessage']['mediaKey'];

                            $D = $DADOS1 . "\n URL_IMG: " . $URL_IMG;
                            $DADOS1 = $DADOS1 . "\n TP_IMG: " . $TP_IMG;
                            $DADOS1 = $DADOS1 . "\n SHA256_IMG: " . $SHA256_IMG;
                            $DADOS1 = $DADOS1 . "\n LENGTH: " . $LENGTH;
                            $DADOS1 = $DADOS1 . "\n CONTEUDO: " . $CONTEUDO;
                            $DADOS1 = $DADOS1 . "\n ID_MSG: " . $ID_MSG;

                            $fileE = 'log_imageMessage.txt';
                            file_put_contents($fileE, $DADOS1, FILE_APPEND | LOCK_EX);

                }

                if($TIPO_MSG=='extendedTextMessage'){
                    $MSG = $event['data']['msgContent']['extendedTextMessage']['text'];
                    $TYPE = $event['data']['msgContent']['extendedTextMessage']['previewType'];

                    $DADOS1 = $DADOS1 . "\n MSG: " . $MSG;
                    $DADOS1 = $DADOS1 . "\n TYPE: " . $TYPE;

                    $fileE = 'log_conversation.txt';
                    file_put_contents($fileE, $DADOS1, FILE_APPEND | LOCK_EX);
                }
                */
            }

            /*
            $file = 'webhookMessage.txt';
            file_put_contents($file, $data."\n \n \n \n ", FILE_APPEND | LOCK_EX);
            */
        }
    }

    public function webhookGroup(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data = file_get_contents("php://input");
        $event = json_decode($data, true);


        $dados['conteudo'] = $data;
        $dados['tipo'] = 'webhookGroup';
        WebhookMessage::create($dados);
    }

    public function webhookConnection(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data = file_get_contents("php://input");

        $event = json_decode($data, true);

        if ($event['data']['connection'] == 'open') {
            $dados_connection['phone_connected'] = '1';
            $dados_connection['phone']  = $event['data']['user']['id'];
            $dados_connection['phone_name']  = $event['data']['user']['name'];
        }
        if ($event['data']['connection'] == 'close') {
            $dados_connection['phone_connected'] = '0';
            $dados_connection['phone']  = null;
            $dados_connection['phone_name']  = null;
        }

        $dados['conteudo'] = $data;
        $dados['tipo'] = 'webhookConnection';
        WebhookMessage::create($dados);
        WhastApi::where('cd_api', 'WHAST')->update($dados_connection);
    }

    public function webhookQrCode(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data = file_get_contents("php://input");
        $event = json_decode($data, true);

        $dados['conteudo'] = $data;
        $dados['tipo'] = 'webhookQrCode';
        WebhookMessage::create($dados);
    }

    public function webhookMessageFromMe(Request $request)
    {
        header('Content-Type: application/json; charset=UTF-8');
        $data = file_get_contents("php://input");
        $event = json_decode($data, true);

        $dados['conteudo'] = $data;
        $dados['tipo'] = 'webhookMessageFromMe';
        WebhookMessage::create($dados);
    }

    public function sendAgenda(Request $request)
    {
        $Api = WhastApi::find('WHAST');
        dd($Api->toArray());
    }
}
