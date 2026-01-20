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
use App\Model\rpclinica\AtendimentoItens;
use App\Model\rpclinica\Convenio;
use App\Model\rpclinica\Especialidade;
use App\Model\rpclinica\LocalAtendimento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\Procedimento;
use App\Model\rpclinica\Profissional; 
use App\Model\rpclinica\TipoAtendimento;  
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\DocumentoBoleto;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Marca;
use App\Model\rpclinica\ProcedimentoConvenio;
use App\Model\rpclinica\Setores;
use App\Model\rpclinica\Usuario;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable; 
use Illuminate\Support\Facades\DB;

class Tesouraria extends Controller
{

    public function index(Request $request)
    {  
     
        //dd($this->getConta(2423473));

        $agendas = Agenda::orderBy("nm_agenda")->whereRaw("sn_ativo='S'")
        ->orderBy("nm_agenda")->get();
        $profissionais = Profissional::whereRaw("sn_ativo='S'")->orderBy("nm_profissional")->get();
        $especialidades = Especialidade::whereRaw("sn_ativo='S'")->orderBy("nm_especialidade")->get();
        $convenios = Convenio::whereRaw("sn_ativo='S'")->orderBy("nm_convenio")->get();
        $procedimentos = Procedimento::whereRaw("sn_ativo='S'")->orderBy("nm_proc")->get();
        $localAtendimentos = LocalAtendimento::whereRaw("sn_ativo='S'")->orderBy("nm_local")->get();
        $exames = Exame::whereRaw("sn_ativo='S'")->orderBy("nm_exame")->get();
        $setores = Setores::all();
        $formasPagamento = FormaPagamento::all();
        $fornecedores = Fornecedor::all();
        $contasBancaria = ContaBancaria::all();
        $marcas = Marca::all();
        return view('rpclinica.recepcao.tesouraria', compact('request','agendas','profissionais','especialidades',
                    'convenios','procedimentos','localAtendimentos','exames',
                    'setores','formasPagamento','fornecedores','contasBancaria','marcas'));

    }
 
    public function show(Request $request)
    { 
 
        $retorno = RpclinicaAgendamento::Agendamentos($request)
        ->where("sn_atendimento","S")
        ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
        date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
        concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
        date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ") 
        ->orderby('data_horario')->get();  
        $RESUMO = $this->resumo($request,$retorno);
        
        return response()->json([ 'request'=>$request->toArray(),'retorno'=>$retorno,'resumo'=>$RESUMO ]);

    }

    public function liberacao(Request $request, RpclinicaAgendamento $agendamento)
    { 

        $retorno= DB::transaction(function() use ($agendamento,$request) {
            if($agendamento->recebido=='N'){
                $retorno=RpclinicaAgendamento::where('cd_agendamento',$agendamento->cd_agendamento)->update(['recebido'=>'S','dt_receb'=>date('Y-m-d H:i'),'usuario_receb'=>$request->user()->cd_usuario]);
                $dados['modulo']='FINAN';
                funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO LIBEROU ATENDIMENTO',$dados);
            }
            if($agendamento->recebido=='S'){ 
                $retorno=RpclinicaAgendamento::where('cd_agendamento',$agendamento->cd_agendamento)->update(['recebido'=>'N','dt_receb'=>date('Y-m-d H:i'),'usuario_receb'=>$request->user()->cd_usuario]);
                $dados['modulo']='FINAN';
                funcLogsAtendimentoHelpers($agendamento->cd_agendamento,'USUARIO BLOQUEOU ATENDIMENTO',$dados);
            }
 
            return $retorno;
        }); 
        return response()->json([ 'request'=>$request->toArray(),'agendamento'=>RpclinicaAgendamento::find($agendamento->cd_agendamento),'retorno' =>$retorno ]);

    }   
     
    public function resumo($request,$retorno = null)
    { 

        if($retorno == null) {

            $retorno = RpclinicaAgendamento::Agendamentos($request)
            ->where("sn_atendimento","S")
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
            date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
            concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ") 
            ->orderby('data_horario')->get(); 

        } 
        $Conta= 0;
        $atConta= 0;
        $qtdConta= 0;
        $Financeiro= 0;
        $atFinanceiro= 0;
        $qtdFinanceiro= 0;
        $qtdPendente= 0;
        $Pendente= 0;

        foreach($retorno as $atends){
            $atConta= 0;
            $atFinanceiro= 0;
            $qtdConta= ( $qtdConta + 1 ); 

            foreach($atends->itens as $item){
                $Conta= ( $Conta + ( ($item->vl_item) ? $item->vl_item : 0 ) );
                $atConta= ( $atConta + ( ($item->vl_item) ? $item->vl_item : 0 ) );
            }   
            foreach($atends->boleto as $boleto){
                $Financeiro= ( $Financeiro + ( ($boleto->vl_pagrec) ? $boleto->vl_pagrec : 0 ) );
                $atFinanceiro= ( $atFinanceiro + ( ($boleto->vl_pagrec) ? $boleto->vl_pagrec : 0 ) );
            }
            if($atConta >$atFinanceiro){
                $qtdFinanceiro= ( $qtdFinanceiro + 1 ); 
            }
            if($atFinanceiro==0){ 
                $qtdPendente= ( $qtdPendente + 1 ); 
                $Pendente= ( $Pendente + ( ($atConta) ? $atConta : 0 ) ); 
            }
        }

        $RESUMO['Formas'] = RpclinicaAgendamento::where(DB::raw("date(dt_agenda)"),'=',trim($request->data))
        ->join('documento_boleto','documento_boleto.cd_agendamento','agendamento.cd_agendamento')
        ->join('forma_pagamento','documento_boleto.cd_forma','forma_pagamento.cd_forma_pag')
        ->selectRaw("forma_pagamento.cd_forma_pag,nm_forma_pag, sum(vl_pagrec) valor,count(*) qtde")
        ->groupBy("forma_pagamento.cd_forma_pag","nm_forma_pag")
        ->orderBy("nm_forma_pag")->get();

        $RESUMO['Conta']=$Conta; 
        $RESUMO['qtdConta']=$qtdConta;
        $RESUMO['Financeiro']=$Financeiro; 
        $RESUMO['qtdFinanceiro']=$qtdFinanceiro;
        $RESUMO['qtdPendente']=$qtdPendente;
        $RESUMO['Pendente']=$Pendente;

        return $RESUMO;

    }
  
    public function show_item(Request $request)
    { 
    
        $valorConta=0;
        foreach($request['itens'] as $item){ 
            $valorConta=($valorConta + $item['vl_item']);
        }

        $request['dadosConta']= $this->getConta($request['cd_agendamento']);
        $request['valorConta']= number_format($valorConta,2,",",".");
        return response()->json(['request'=>$request->toArray()]);

    }

    public function getConta($codigo)
    { 
        $retorno['reabrirConta'] = true;
        $retorno['liberarConta'] = false;
        $retorno['desconto'] = false;
        $retorno['vlDescontoRestante'] = 0; 
        $agendamento = RpclinicaAgendamento::find($codigo);
        $agendamento->load('user_descontos');
        $agendamento->load("boleto");
        $retorno['valorConta']=($agendamento->vl_conta) ? $agendamento->vl_conta : 0;
        $retorno['valorFinanceiro']=0;
        $retorno['vlDesconto'] = ($agendamento->vl_desconto) ? $agendamento->vl_desconto : 0;
        if(isset($agendamento->boleto)){
            foreach($agendamento->boleto as $boleto){
                $retorno['valorFinanceiro']=($retorno['valorFinanceiro'] + $boleto->vl_boleto);
                $retorno['reabrirConta'] = false;
            }
        }
        $VlTotal=($retorno['valorFinanceiro']+$retorno['vlDesconto']);
        $retorno['vl_restante'] = ( ( ($retorno['valorConta']) ? $retorno['valorConta'] : 0 ) - ( $VlTotal ) );
         
        if($VlTotal==$retorno['valorConta']){
            $retorno['liberarConta'] = true;
        }
        if($VlTotal<$retorno['valorConta']){
            $retorno['desconto'] = true;
            $retorno['vlDescontoRestante'] = ($retorno['valorConta']-$retorno['valorFinanceiro']);
        }

        $retorno['vDescontoRestante']= number_format($retorno['vlDescontoRestante'],2,",",".");
        $retorno['vRestante']= number_format($retorno['vl_restante'],2,",",".");
        $retorno['vFinanceiro']= number_format($retorno['valorFinanceiro'],2,",",".");
        $retorno['vConta']= number_format($retorno['valorConta'],2,",","."); 
        $retorno['vDesconto'] = $retorno['vlDesconto'];
        if($agendamento->vl_desconto){
            $retorno['relacao_desconto'] = '<code>Desconto Concedido pelo usuario <b>'.$agendamento->user_descontos?->nm_usuario.'</b> no valor de R$ '.number_format($agendamento->vl_desconto,2,",",".")."</code>"; 
        }else{
            $retorno['relacao_desconto'] = null;
        } 
        return $retorno;

    }

    public function addLancamento(Request $request) {

        $request['cd_empresa'] = $request->user()->cd_empresa;
        $request->validate([
            "cd_agendamento" => "required|integer|exists:agendamento,cd_agendamento",
            "cd_empresa" => "required|integer|exists:empresa,cd_empresa",
            "cd_categoria" => "required|integer|exists:categoria,cd_categoria",
            "cd_conta" => "required|integer|exists:conta_bancaria,cd_conta",
            "cd_forma" => "required|integer|exists:forma_pagamento,cd_forma_pag",
            "cd_fornecedor" => "required|integer|exists:fornecedor,cd_fornecedor",
            "cd_setor" => "nullable|integer|exists:setor,cd_setor",
            "cd_marca" => "nullable|integer|exists:marca,cd_marca",
            "cd_turma" => "nullable",
            "cd_evento" => "nullable",
            "descricao" => "required|string",
            "documento" => "nullable",
            "dt_emissao" => "nullable|date_format:Y-m-d",
            "tp_lancamento" => "required|string|in:despesa,receita",
            "data_vencimento" => "nullable|date_format:Y-m-d", 
            "valor" => "required|currency",
            "data_pagamento" => "nullable|date_format:Y-m-d",
            "valor_pago" => "nullable|currency",
            "parcelar" => "nullable|boolean",
            "dividir_parcelas" => "nullable|boolean",
            "periodicidade" => "nullable|string|in:mensal,quinzenal,semanal",
            "qtde_parcelas" => "nullable|integer",
            "parcelas" => "nullable|array",
            "parcelas.*.descricao" => "required|string",
            "parcelas.*.documento" => "required",
            "parcelas.*.data_vencimento" => "nullable|date_format:Y-m-d",
            "parcelas.*.data_compra" => "nullable|date_format:Y-m-d",
            "parcelas.*.data_pagamento" => "nullable|date_format:Y-m-d",
            "parcelas.*.valor" => "nullable|currency",
            "parcelas.*.valor_pago" => "nullable|currency",
        ],[
            "cd_agendamento.required"=>"O atendimento do Paciente não foi informado!",
            "cd_categoria.required"=>"A categoria  não foi informado, Favor verificar no cadastro do convênio [ ".$request['cd_convenio']." ] se  esta configurado o campo CATEGORIA.",
            "cd_fornecedor.required"=>"o fornecedor não foi informado, Favor verificar no cadastro do convênio [ ".$request['cd_convenio']." ] se esta configurado o campo FORNECEDOR."
        ]);

        try {

            DB::transaction(function() use ($request) {

                
                    $conta = ContaBancaria::find($request->cd_conta);  
                    $data = $request->only(
                        "cd_empresa", "cd_categoria", "cd_conta", "cd_forma", "cd_fornecedor", "cd_setor", "cd_marca",
                        "data_compra", "dt_emissao","cd_turma","cd_evento","cd_agendamento"
                    );
            
                    $dataParcela["dt_emissao"] = ($request["dt_emissao"])?$request["dt_emissao"]:null;
                    $dataParcela["cd_setor"] = ($request["cd_setor"])?$request["cd_setor"]:null;
                    $data["cd_usuario"] = $request->user()->cd_usuario;
                    $data["tipo"] = $request->tp_lancamento;
                    $data["hash_grupo"] = date("YmdHis")."-".\Illuminate\Support\Str::random(10);
                    $data['cd_agendamento']=$request['cd_agendamento'];
                    

                    if (($request->qtde_parcelas > 1) && is_array($request->parcelas) && count($request->parcelas) >= 1) {
                         
  
                        foreach($request->parcelas as $parcela) {
                               
                            $dataParcela = $data;
                            $dataParcela["ds_boleto"] = $parcela["descricao"];
                            $dataParcela["doc_boleto"] = (empty($parcela["documento"])) ? date('Ymd') : $parcela["documento"];
        
                            $dataParcela["dt_vencimento"] = $parcela["data_vencimento"];
                            $dataParcela["data_pagrec"] = $parcela["data_pagamento"];
                            $dataParcela["vl_boleto"] =  number_format($parcela["valor"],2);
                            $dataParcela["vl_pagrec"] =  number_format($parcela["valor_pago"],2);
                            $dataParcela["dt_pagrec"] = $parcela["data_pagamento"];
                            $dataParcela["situacao"] = $parcela["data_pagamento"] ? "QUITADO": "ABERTO";
                            $dataParcela["cd_usuario_pagrec"] = $parcela["data_pagamento"] ? $request->user()->cd_usuario: null; 
                            if(($dataParcela['vl_pagrec']>0) && (!$dataParcela['dt_pagrec'])){
                                return response()->json(["message" =>  "Data de Pag./Rec. não informada" ], 500);
                            }
                            if(($dataParcela['vl_pagrec']<=0) && ($dataParcela['dt_pagrec'])){
                                return response()->json(["message" =>  " Valor de Pag./Rec. não informado" ], 500); 
                            }
                            $dataParcela['tp_mov'] ='LC';
                              
                            DocumentoBoleto::create($dataParcela);
                        }
                        DB::commit();  
                        $request['dadosConta']= $this->getConta($request['cd_agendamento']);
                        return response()->json(["message" => "Cadastros realizados!",'request'=>$request->toArray()]);
                    }

                    $dataBoleto = $data;
                    $dataBoleto["ds_boleto"] = $request->descricao;
                    $dataBoleto['tp_mov'] ='LC';
                    $dataBoleto["doc_boleto"] =  (empty($request->documento)) ? date('Ymd') : $request->documento;
                    $dataBoleto["dt_vencimento"] = $request->data_vencimento;
                    $dataBoleto["dt_pagrec"] = $request->data_pagamento;
                    $dataBoleto["data_pagrec"] = $request->data_pagamento;
                    $dataBoleto["vl_boleto"] = $request->valor;
                    $dataBoleto["vl_pagrec"] = $request->valor_pago;
                    $dataBoleto["situacao"] = $request->data_pagamento ? "QUITADO": "ABERTO";
                    $dataBoleto["cd_usuario_pagrec"] = $request->data_pagamento ? $request->user()->cd_usuario: null;

                    if(($dataBoleto['vl_pagrec']>0) && (!$dataBoleto['dt_pagrec'])){
                        return response()->json(["message" =>  "Data de Pag./Rec. não informada" ], 500);
                    }
                    if(($dataBoleto['vl_pagrec']<=0) && ($dataBoleto['dt_pagrec'])){
                        return response()->json(["message" =>  " Valor de Pag./Rec. não informado" ], 500); 
                    }
                    
                    DocumentoBoleto::create($dataBoleto); 
         
            });


            $retorno = RpclinicaAgendamento::where('cd_agendamento',$request["cd_agendamento"])
            ->with('boleto.forma','boleto.usuario','boleto.conta','boleto.setor','boleto.marca')
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
            date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
            concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ")
            ->first(); 


            $retorno_atend = RpclinicaAgendamento::Agendamentos($request)
            ->where('cd_agendamento',$request["cd_agendamento"])
            ->where("sn_atendimento","S")
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
            date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
            concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ") 
            ->orderby('data_horario')->first(); 
 
            $RESUMO = $this->resumo($request);

            $request['dadosConta']= $this->getConta($request['cd_agendamento']);
 
            return response()->json(["message" => "Cadastro realizado!",
                                     "retorno" => $retorno,
                                     "retorno_atend" => $retorno_atend,
                                     "resumo" => $RESUMO,
                                     "request"=>$request->toArray()]);
        }

        catch (Exception $e) {
           
            return response()->json(["message" => "Não foi possivel realizar o cadastro! ".$e->getMessage()], 500);
        }
    }
 
    public function delete_parcela(Request $request,DocumentoBoleto $documento,  $agendamento)
    {
        try {
            $documento->delete();

            $retorno = RpclinicaAgendamento::where('cd_agendamento',$agendamento)
            ->with('boleto.forma','boleto.usuario','boleto.conta','boleto.setor','boleto.marca')
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
            date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
            concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ")
            ->first(); 

            $request['dadosConta']= $this->getConta($agendamento);
            return response()->json(['retorno'=>$retorno,'request'=>$request->toArray()]);
        }
        catch(Exception $e) {
            abort(500);
        }
    }

    public function delete_desconto(Request $request,RpclinicaAgendamento $agendamento)
    {
        try {
             
            $agendamento->update([  'vl_desconto'=> null,
                                    'dt_desconto'=> date('Y-m-d H:i'),
                                  'user_desconto'=> $request->user()->cd_usuario]);
            $dadosConta= $this->getConta($agendamento->cd_agendamento);
            $request['dadosConta']= $this->getConta($agendamento->cd_agendamento);
            return response()->json(['request'=>$request->toArray()]);

        }
        catch(Exception $e) {
            abort(500);
        }
    }

    
    public function recalcular(Request $request,RpclinicaAgendamento $agendamento)
    {
        try { 
             
            $agendamento->load('itens.exame.procedimento');
            $cd_convenio=$agendamento->cd_convenio;
            foreach($agendamento->itens as $item){
                $array=array(
                'vl_item'=> ($item->exame->cod_proc) ?  valorContaFaturamento($cd_convenio,$item->exame->cod_proc) : null,
                'sn_anomalia'=> ($item->exame->cod_proc) ? "N" : "S",
                'cd_procedimento'=> ($item->exame->cod_proc) ? $item->exame->cod_proc : null,
                'dt_valor'=>date('Y-m-d H:i'),
                'usuario_valor'=>$request->user()->cd_usuario,
                );
                AgendamentoItens::where('cd_agendamento_item',$item->cd_agendamento_item)
                ->update($array);
            }
            $dados=null;
            $retorno = RpclinicaAgendamento::with('agenda','paciente','profissional','especialidade','local',
            'itens','itens.img','itens.exame.procedimento','convenio','situacao','tipo_atend','escalas',
            'user_atendimento','user_pre_exame','guia.itens.exame.procedimento','convenio','origem',
            'itens.usuario','itens.historico','itens.historico.usuario','boleto.forma','boleto.usuario',
            'boleto.conta','boleto.setor','boleto.marca')
            ->where("sn_atendimento","S")
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
            date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
            concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ") 
            ->find($agendamento->cd_agendamento);  
            $request['dadosConta']= $this->getConta($agendamento->cd_agendamento);
            return response()->json(['retorno'=>$retorno,'agendamento'=>$agendamento,'request'=>$request->toArray()]);
             
        }

        catch(Exception $e) {
            return response()->json(["message" => "Não foi possivel realizar o cadastro! ".$e->getMessage()], 500);
        }
           
    }
 
    public function fechar_conta(Request $request,RpclinicaAgendamento $agendamento, $tipo)
    {
        try {
            
            if($tipo=='A'){
                if($agendamento->situacao_conta=='F'){
                    $agendamento->update(['vl_conta'=>null,'situacao_conta'=>'A','user_conta'=>$request->user()->cd_usuario,'dt_conta'=>date('Y-m-d H:i')]);
                }else{
                    return response()->json(["message" => "A conta já encontrasse Aberta! "], 500);
                }
            }
            $itens_anomalia=null;
            if($tipo=='F'){
                if($agendamento->situacao_conta=='A'){
                    $itens_anomalia=AgendamentoItens::where('cd_agendamento',$agendamento->cd_agendamento)
                    ->where('sn_anomalia',"S")->count();
                    
                    $valores = AgendamentoItens::where('cd_agendamento',$agendamento->cd_agendamento)
                    ->selectRaw("sum(vl_item)  vl_item")->first();
                    $vlConta = (isset($valores->vl_item)) ? $valores->vl_item : 0;
                  
                    if($itens_anomalia>0){
                        return response()->json(["message" => "A conta possui itens com anomalias! ".$itens_anomalia], 500);
                    }else{
                        $agendamento->update(['vl_conta'=>$vlConta,'situacao_conta'=>'F','user_conta'=>$request->user()->cd_usuario,'dt_conta'=>date('Y-m-d H:i')]);
                    }
                   
                }else{
                    return response()->json(["message" => "A conta já encontrasse Fechada! "], 500);
                }
            }

            $dados=null;
            $retorno = RpclinicaAgendamento::with('agenda','paciente','profissional','especialidade','local',
            'itens','itens.img','itens.exame.procedimento','convenio','situacao','tipo_atend','escalas',
            'user_atendimento','user_pre_exame','guia.itens.exame.procedimento','convenio','origem',
            'itens.usuario','itens.historico','itens.historico.usuario','boleto.forma','boleto.usuario',
            'boleto.conta','boleto.setor','boleto.marca')
            ->where("sn_atendimento","S")
            ->selectRaw("agendamento.*,date_format(dt_agenda,'%d/%m/%Y') data,date_format(data_horario,'%Y-%m-%d %H:%i') start,date_format(data_horario,'%d/%m/%Y %H:%i') dt_start,
            date_format(data_horario,'%Y-%m-%d') data_start_end, concat(date_format(data_horario,'%Y-%m-%d'),' ',hr_final) end, 
            concat(date_format(data_horario,'%d/%m/%Y'),' ',hr_final) dt_end,
            date_format(dt_atendimento,'%d/%m/%Y %H:%i') data_atendimento, date_format(created_at,'%d/%m/%Y %H:%i') data_agendamento ") 
            ->find($agendamento->cd_agendamento);  
            $request['dadosConta']= $this->getConta($agendamento->cd_agendamento);
            return response()->json(['retorno'=>$retorno,'request'=>$request->toArray()]);
            
        }

        catch(Exception $e) {
            return response()->json(["message" => "Não foi possivel realizar o cadastro! ".$e->getMessage()], 500);
        }
           
    }


    public function desconto(Request $request,RpclinicaAgendamento $agendamento)
    {
        try {

            $dadosConta= $this->getConta($agendamento->cd_agendamento);

            if(($dadosConta['vlDescontoRestante']<=0)){
                return response()->json(["message" =>  "Erro no desconto!<br> Valor R$ 0,00" ], 500); 
            }
            $agendamento->update(['vl_desconto'=> $dadosConta['vlDescontoRestante'],
                                  'dt_desconto'=>date('Y-m-d H:i'),
                                  'user_desconto'=> $request->user()->cd_usuario]);
            $request['dadosConta']= $this->getConta($agendamento->cd_agendamento);
            return response()->json(['request'=>$request->toArray()]);
        }

        catch(Exception $e) {
            return response()->json(["message" => "Não foi possivel realizar o cadastro! ".$e->getMessage()], 500);
        }
           
    }
}
