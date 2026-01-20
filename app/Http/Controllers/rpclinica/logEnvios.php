<?php

namespace App\Http\Controllers\rpclinica;

use App\Http\Controllers\Controller;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\AgendamentoItens; 
use App\Model\rpclinica\Profissional; 
use App\Model\rpclinica\SituacaoItem; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\rpclinica\Comunicacoes;
use App\Model\rpclinica\Agenda;
use App\Model\rpclinica\TipoAtendimento;
use App\Model\rpclinica\WhastRotina;
use App\Model\rpclinica\WhastSend;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Throwable;

class logEnvios extends Controller
{
 
    public function index(Request $request)
    { 

       
        $request['dti']= date("Y-m-d",strtotime('-5 day', strtotime(date('Y-m-d')))); 
        $request['dtf']=date('Y-m-d'); 
  
        $rotina=WhastRotina::find('laudo');
        $parametros['profissional'] = Profissional::where('sn_ativo', 'S')->orderBy('nm_profissional')->get();  
        $parametros['status'] = SituacaoItem::where('tipo', 'log_envio')->orderBy('nm_situacao_itens')->get(); 
        $parametros['agenda'] = Agenda::where('sn_ativo', 'S')->orderBy('nm_agenda')->get();  
        $parametros['tipo'] = TipoAtendimento::where('sn_ativo', 'S')->orderBy('nm_tipo_atendimento')->get();  
        $parametros['tp_rotina'] = WhastRotina::orderBy('nome')->get();  
      
        return view('rpclinica.log-envio.painel', compact('parametros', 'request', 'rotina'));

    }

    public function jsonPainel(Request $request): array
    {
        $situacao=AgendamentoItens::whereBetween('dt_laudo',array($request['dti'],$request['dtf']))
        ->where('sn_laudo',true)->selectRaw("cd_status_envio")->get();
        $aberta=0;$enviada=0;$erro=0;
        foreach($situacao as $linha){
            switch ($linha->cd_status_envio) {
                case 'A':
                    $aberta=($aberta+1);
                    break;
                case 'S':
                    $enviada=($enviada+1);
                    break;
                case 'E':
                    $erro=($erro+1);
                    break;
            } 
        }
        $request['aberta'] = $aberta; $request['enviada'] = $enviada; $request['erro'] = $erro;
        $request['tipo_painel']='log_envio';
        $itemsPerPage = ($request['itemsPerPage']) ? $request['itemsPerPage'] : 50;
        $request['query'] = AgendamentoItens::PainelCentralLaudos($request)
            ->where('sn_laudo',true)
            ->selectRaw("agendamento_itens.*,date_format(created_at,'%d/%m/%y') created_data,
            date_format(dt_envio,'%d/%m/%y %H:%i') data_envio,date_format(dt_laudo,'%d/%m/%Y') data_laudo ") 
            ->orderBy("created_at","desc")
            ->paginate($itemsPerPage)->appends($request->query());
      
        return $request->toArray();
    }


    public function jsonPainelAgendamento(Request $request)
    {
        $aberta=0;$enviada=0;$erro=0;
        $itemsPerPage = ($request['itemsPerPage']) ? $request['itemsPerPage'] : 50;
        $request['query'] =WhastSend::whereRaw(" date(dt_envio) >='".trim($request->dti)."'")
        ->whereRaw(" date(dt_envio) <='".trim($request->dtf)."'")
        ->selectRaw("whast_send.*, date_format(dt_envio,'%d/%m/%Y') data_envio")
        ->with(['tab_paciente','agendamento'=> function($q) use($request){  

            if($request['profissional']){
                $q->where("cd_profissional",$request['profissional']); 
            } 
            if($request['tipo_atend']){
                $q->where("tipo",$request['tipo_atend']); 
            }
            if($request['agenda']){
                $q->where("cd_agenda",$request['agenda']); 
            }
        },'agendamento.paciente' => function($q) use($request){
            if($request['paciente']){
                $q->where(DB::raw("upper(nm_paciente)"),'like',mb_strtoupper($request['paciente'].'%')); 
            } 
        },'agendamento.agenda','agendamento.profissional','agendamento.situacao','tipo']) 
        ->where('tipo',$request->tp_rotina);   

        if($request['paciente']){

            $request['query'] = $request['query']->whereHas('agendamento.paciente', function($q)  use($request) {
                if($request['paciente']){
                    $q->where(DB::raw("upper(nm_paciente)"),'like',mb_strtoupper($request['paciente'].'%')); 
                }  
            });

        }

        if(($request['profissional'])||($request['tipo_atend'])||($request['agenda'])){

            $request['query'] = $request['query']->whereHas('agendamento', function($q)  use($request) { 
                if($request['profissional']){
                    $q->where("cd_profissional",$request['profissional']); 
                } 
                if($request['tipo_atend']){
                    $q->where("tipo",$request['tipo_atend']); 
                }
                if($request['agenda']){
                    $q->where("cd_agenda",$request['agenda']); 
                }
            });

        }

        if($request->agendamento){
            $request['query'] = $request['query']->where("cd_agendamento",$request->agendamento);
        } 
        if($request->celular){
            $request['query'] = $request['query']->where("nr_send",'like','%'.preg_replace('/[^0-9]/', '', $request->celular).'%');
        } 
        $request['query'] = $request['query']->orderByRaw("dt_envio desc")
        ->paginate($itemsPerPage)->appends($request->query());

        return $request->toArray();
    }
  

    public function sendLote(Request $request)
    {
        $Campos = array( 
            'item' => 'required', 
        );  
        $validated = $request->validate($Campos);
  
        try {
            
            $comunicacao = new Comunicacoes();
             foreach($request['item'] as $item){
                
                $comunicacao->send_laudo($item);
             }
             
            return redirect()->route('logs.rotina.whast')->with('success', 'Atendimento cadastrado com sucesso!');
         
        } catch (Throwable $error) {  
            return redirect()->route('logs.rotina.whast')->with('error', 'Erro ao enviar mensagem! <br>'.$error->getMessage());
        
        }

    }

    public function historico(Request $request, AgendamentoItens $item)
    {  
            $item->load('whast_send');
            return response()->json(['historico'=>$item->toArray()]); 
 
    }
    
    public function getRotina(Request $request, AgendamentoItens $item)
    {
        $protocol = (isset($_SERVER['HTTP_CF_VISITOR'])) ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] . '/rpclinica/crontab-laudos/9a4dc32g5d6h8e';

        $curl = curl_init($protocol.$host);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, True);
        $return = curl_exec($curl);
        curl_close($curl); 
        return redirect()->route('logs.rotina.whast')->with('success', 'Rotina executa com sucesso!');

    }
 
}
