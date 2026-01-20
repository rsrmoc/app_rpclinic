<?php

namespace App\Http\Controllers\rpclinica;
 
use App\Http\Controllers\Controller;
use App\Model\rpclinica\CartaoCredito;
use App\Model\rpclinica\CartaoFatura;
use App\Model\rpclinica\Categoria;
use App\Model\rpclinica\ConfigGeral;
use App\Model\rpclinica\ContaBancaria;
use App\Model\rpclinica\DocumentoBoleto;
use App\Model\rpclinica\Empresa;
use App\Model\rpclinica\Evento;
use App\Model\rpclinica\FinanceiroConfig;
use App\Model\rpclinica\FormaPagamento;
use App\Model\rpclinica\Fornecedor;
use App\Model\rpclinica\Marca;
use App\Model\rpclinica\Motivo as RpclinicaMotivo;
use App\Model\rpclinica\Setores;
use App\Model\rpclinica\Turma;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class Financeiro extends Controller
{
 
    public function index(Request $request) {
     
        $categorias = Categoria::selectRaw("categoria.cd_empresa,categoria.cd_categoria,nm_categoria,case when sn_lancamento ='S' then false else true end lanc,cod_estrutural ")
        ->whereRaw("categoria.deleted_at is null")
        ->orderByRaw("RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0')")->get();
        $setores = Setores::orderBy('nm_setor')->get();
        $formasPagamento = FormaPagamento::orderBy('nm_forma_pag')->get();
        $fornecedores = Fornecedor::orderBy('nm_fornecedor')->get();
        $contasBancaria = ContaBancaria::orderBy('nm_conta')->get();
        $contasBancariaTransf = ContaBancaria::where('tp_conta','<>','CA')->orderBy('nm_conta')->get();
        $marcas = Marca::orderBy('nm_marca')->get(); 
        return view("rpclinica.financeiro.lista", compact("categorias", "setores", "formasPagamento", "fornecedores", 
                                                 "contasBancaria", "marcas"));
        
    }

    public function add(Request $request) {
        $empresas = Empresa::all();
        $categorias = Categoria::selectRaw("cd_empresa,categoria.cd_categoria,nm_categoria,case when sn_lancamento ='S' then false else true end lanc,cod_estrutural,tp_lancamento,
        cd_forma,cd_marca,cd_setor,cd_conta,cd_fornecedor,descricao ")
        ->whereRaw("categoria.deleted_at is null")
        ->orderByRaw("RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0')")->get();
  
        $empresaUsuario = $request->user()->cd_empresa;
        $setores = Setores::orderBy('nm_setor')->get();    
        $contasBancaria = ContaBancaria::orderBy('nm_conta')->get();
        $formasPagamento = FormaPagamento::orderBy('nm_forma_pag')->get();
        $fornecedores = Fornecedor::orderBy('nm_fornecedor')->get();
        $contasBancaria = ContaBancaria::orderBy('nm_conta')->get();
        $marcas = Marca::orderBy('nm_marca')->get();
        $contasBancariaTransf = ContaBancaria::where('tp_conta','<>','CA')->orderBy('nm_conta')->get();
        return view("rpclinica.financeiro.add", compact(
            "empresas", "categorias", "setores", "formasPagamento", "marcas", "fornecedores",
            "contasBancariaTransf","contasBancaria","empresaUsuario"
        ));
    }

    public function addLancamento(Request $request) {
        $request->validate([
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
        ]);

        try {

            DB::beginTransaction();

            $conta = ContaBancaria::find($request->cd_conta);
            $tpMov='LC';
            if ($conta->tp_conta == "CA") { 
                $tpMov='CA';
                $FaturaCartao=$this->fatura_cartao($request->data_vencimento,$request->cd_conta);
                if($FaturaCartao['situacao']==false) {
                    return response()->json(["message" =>' [ '.$request["data_vencimento"].' ] '.$FaturaCartao['descricao']], 403);
                } 
                if ($request->parcelar && is_array($request->parcelas) && count($request->parcelas) >= 1) {
                    foreach($request->parcelas as $parcela) {

                        $FaturaCartao=$this->fatura_cartao($parcela["data_vencimento"],$request->cd_conta);
                        if($FaturaCartao['situacao']==false) {
                            return response()->json(["message" => '[ '.$parcela["data_vencimento"].' ] '.$FaturaCartao['descricao']], 403);
                        } else {
                            $parcela["CdFatura"]=$FaturaCartao['codigo'];
                        }
                    } 
                    
                }
      
            }

            $data = $request->only(
                "cd_empresa", "cd_categoria", "cd_conta", "cd_forma", "cd_fornecedor", "cd_setor", "cd_marca",
                "data_compra", "dt_emissao","cd_turma","cd_evento"
            );
            
            $dataParcela["dt_emissao"] = ($request["dt_emissao"])?$request["dt_emissao"]:null;
            $dataParcela["cd_setor"] = ($request["cd_setor"])?$request["cd_setor"]:null;
            $data["cd_usuario"] = $request->user()->cd_usuario;
            $data["tipo"] = $request->tp_lancamento;
            $data["hash_grupo"] = date("YmdHis")."-".\Illuminate\Support\Str::random(10);

 

            if ($request->parcelar && is_array($request->parcelas) && count($request->parcelas) >= 1) {
                foreach($request->parcelas as $parcela) {
                    $dataParcela = $data;
                    $dataParcela["ds_boleto"] = $parcela["descricao"];
                    $dataParcela["doc_boleto"] = (empty($parcela["documento"])) ? date('Ymd') : $parcela["documento"];
                    $dataParcela["data_compra"] = $parcela["data_compra"];
                    $dataParcela["dt_vencimento"] = $parcela["data_vencimento"];
                    $dataParcela["data_pagrec"] = $parcela["data_pagamento"];
                    $dataParcela["vl_boleto"] = $parcela["valor"];
                    $dataParcela["vl_pagrec"] = $parcela["valor_pago"];
                    $dataParcela["dt_pagrec"] = $parcela["data_pagamento"];
                    $dataParcela["situacao"] = $parcela["data_pagamento"] ? "QUITADO": "ABERTO";
                    $dataParcela["cd_usuario_pagrec"] = $parcela["data_pagamento"] ? $request->user()->cd_usuario: null;
                    $dataParcela['tp_mov'] =$tpMov;
                    if(($dataParcela['vl_pagrec']>0) && (!$dataParcela['dt_pagrec'])){
                        return response()->json(["message" =>  "Data de Pag./Rec. não informada" ], 500);
                    }
                    if(($dataParcela['vl_pagrec']<=0) && ($dataParcela['dt_pagrec'])){
                        return response()->json(["message" =>  " Valor de Pag./Rec. não informado" ], 500); 
                    }

                    if ($conta->tp_conta == "CA") { 
                        $dataParcela["sn_cartao"] = 'S';
                        $FaturaCartao=$this->fatura_cartao($dataParcela["dt_vencimento"],$request->cd_conta);
                        if($FaturaCartao['situacao']==true){
                            $dataParcela["cd_fatura_cartao"] = $FaturaCartao['codigo'];
                            $dataParcela["dt_vencimento"] = $FaturaCartao["vencimento_fatura"];
                        }else{
                            return response()->json(["message" => $FaturaCartao['descricao']]);
                        }    
                    }


                    DocumentoBoleto::create($dataParcela);
                }
                DB::commit();  
                return response()->json(["message" => "Cadastros realizados!"]);
            }

            $dataBoleto = $data;
            $dataBoleto["ds_boleto"] = $request->descricao;
            $dataBoleto['tp_mov'] =$tpMov;
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

            if ($conta->tp_conta == "CA") {
                $dataBoleto["data_compra"] = $request->data_vencimento;
                $dataBoleto["sn_cartao"] = 'S'; 

                $FaturaCartao=$this->fatura_cartao($dataBoleto["dt_vencimento"],$request->cd_conta);
                if($FaturaCartao['situacao']==true){
                    $dataBoleto["cd_fatura_cartao"] = $FaturaCartao['codigo'];
                    $dataBoleto["dt_vencimento"] = $FaturaCartao["vencimento_fatura"];
                }else{
                    return response()->json(["message" => $FaturaCartao['descricao']]);
                }
                    
            }

            DocumentoBoleto::create($dataBoleto);

            if ($request->valor_restante) {
                $dataValorRestante = $data;
                $dataValorRestante["ds_boleto"] = $request->valor_restante["parcela_descricao"] ? $request->descricao." / valor restante" : $request->descricao;
                $dataValorRestante["doc_boleto"] = $request->documento;
                $dataValorRestante["dt_vencimento"] = $request->valor_restante["data_vencimento"];
                $dataValorRestante["vl_boleto"] = $request->valor_restante["valor_restante"];
                $dataValorRestante["situacao"] = "ABERTO";
                if ($conta->tp_conta == "CA") {
                    $dataValorRestante["data_compra"] = $dataValorRestante["dt_vencimento"];
                    $dataValorRestante["sn_cartao"] = 'S'; 
    
                    $FaturaCartao=$this->fatura_cartao($dataBoleto["dt_vencimento"],$request->cd_conta);
                    if($FaturaCartao['situacao']==true){
                        $dataValorRestante["cd_fatura_cartao"] = $FaturaCartao['codigo'];
                        $dataValorRestante["dt_vencimento"] = $FaturaCartao["vencimento_fatura"];
                    }else{
                        return response()->json(["message" => $FaturaCartao['descricao']]);
                    }
                }else{
                    $dataValorRestante["sn_cartao"] = null;
                    $dataValorRestante["cd_fatura_cartao"] = null;
                    $dataValorRestante["data_compra"] = null;
                }
                DocumentoBoleto::create($dataValorRestante);
            }

            DB::commit();  
            return response()->json(["message" => "Cadastro realizado!"]);
        }
        catch (Exception $e) {
            //DB::rollback();
            return response()->json(["message" => "Não foi possivel realizar o cadastro! ".$e->getMessage()], 500);
        }
    }

    public function addLancamentoTransferencia(Request $request) {
        $request->validate([
            "cd_empresa_origem" => "required|integer|exists:empresa,cd_empresa",
            "cd_conta_origem" => "required|integer|exists:conta_bancaria,cd_conta",
            "cd_empresa_destino" => "required|integer|exists:empresa,cd_empresa",
            "cd_conta_destino" => "required|integer|exists:conta_bancaria,cd_conta",
            "descricao" => "required|string",
            "data" => "required|date_format:Y-m-d",
            "valor" => "required|currency"
        ]);

        $cdCategoria = FinanceiroConfig::where('cd_usuario', 'LIKE', $request->user()->cd_usuario)->first();

        if (!$cdCategoria) {
            return response()->json(["message" => "Você precisa configurar a categoria de transferência!"], 403);
        }

        $cdCategoria = $cdCategoria['cd_categoria_transf'];

        try {
            $data = [
                "cd_categoria" => $cdCategoria,
                "ds_boleto" => $request->descricao,
                "vl_boleto" => $request->valor,
                "vl_pagrec" => $request->valor,
                "dt_vencimento" => $request->data,
                "doc_boleto" => date('Ymd'),
                "dt_pagrec" => $request->data,
                "data_pagrec" => $request->data,
                "hash_grupo" => date("YmdHis")."-".\Illuminate\Support\Str::random(10),
                "situacao" => "QUITADO",
                "cd_usuario" => $request->user()->cd_usuario
            ];

            DocumentoBoleto::create(array_merge($data, [
                "cd_empresa" => $request->cd_empresa_origem,
                "cd_conta" => $request->cd_conta_origem,
                "sn_transferencia" => 'S',
                "tp_mov" => 'TR',
                "tipo" => "despesa"
            ]));

            DocumentoBoleto::create(array_merge($data, [
                "cd_empresa" => $request->cd_empresa_destino,
                "cd_conta" => $request->cd_conta_destino,
                "sn_transferencia" => 'S',
                "tp_mov" => 'TR',
                "tipo" => "receita",
            ]));

            return response()->json(["message" => "Transferência realizada!"]);
        }
        catch (Exception $e) {
            return response()->json(["message" => "Não foi possivel realizar a transferência! ".$e->getMessage()], 500);
        }
    }

    public function edit(Request $request,DocumentoBoleto $documentoBoleto) {
       
        $documentoBoleto->load('conta');
        $empresas = Empresa::all(); 
        $categorias = Categoria::selectRaw("categoria.cd_empresa,categoria.cd_categoria,nm_categoria,case when sn_lancamento ='S' then false else true end lanc,cod_estrutural ")
        ->whereRaw("categoria.deleted_at is null")
        ->orderByRaw("RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0')") ->get();
        $setores = Setores::all();
        $formasPagamento = FormaPagamento::all();
        $marcas = Marca::all();
        $fornecedores = Fornecedor::all();
        $contasBancaria = ContaBancaria::all();
 
        $parcelas = DocumentoBoleto::where("hash_grupo", $documentoBoleto->hash_grupo)
        ->selectRaw("documento_boleto.*")
        ->selectRaw(" FORMAT(vl_boleto, 2, 'de_DE')  valor_boleto")
        ->selectRaw(" FORMAT(vl_pagrec, 2, 'de_DE') valor_pagrec")
        ->selectRaw("
            case
				when situacao='ABERTO' and dt_vencimento>=CURDATE() and tipo='despesa' then 'A vencer'
				when situacao='ABERTO' and dt_vencimento<CURDATE()   then 'Vencido'
				when situacao='ABERTO' and dt_vencimento>=CURDATE() and tipo='receita' then 'A receber'
				when situacao='QUITADO' and tipo='receita' then 'Recebido'
				when situacao='QUITADO' and tipo='despesa' then 'Pago' else  situacao 
            end statuss ")
        ->orderByRaw('cd_documento_boleto,dt_vencimento')->get();

        return view("rpclinica.financeiro.edit", compact(
            "empresas", "categorias", "setores", "formasPagamento", "marcas", "fornecedores",
            "contasBancaria", "documentoBoleto", "parcelas","request" 
        ));
    }

    public function updateLancamento(Request $request) {

         
        $validated = $request->validate([
            "id" => "required|integer|exists:documento_boleto,cd_documento_boleto",
            "cd_categoria" => "required|integer|exists:categoria,cd_categoria",
            "cd_conta" => "required|integer|exists:conta_bancaria,cd_conta",
            "cd_forma" => "required|integer|exists:forma_pagamento,cd_forma_pag",
            "cd_fornecedor" => "required|integer|exists:fornecedor,cd_fornecedor",
            "cd_setor" => "nullable",
            "cd_marca" => "nullable",
            "cd_turma" => "nullable",
            "cd_evento" => "nullable",
            "ds_boleto" => "required|string", 
            "dt_emissao" => "nullable|date_format:Y-m-d",
            "tipo" => "required|string|in:despesa,receita",
            "dt_vencimento" => "nullable|date_format:Y-m-d",
            "data_compra" => "nullable|date_format:Y-m-d",
            "vl_boleto" => "required|currency", 
            "dt_pagrec" => ($request['vl_pagrec'] ? 'required|date_format:Y-m-d' : 'nullable'),
            "vl_pagrec" => ($request['dt_pagrec'] ? 'required|currency' : 'nullable'),
        ]);
     
        try {

            DB::beginTransaction();

            unset($validated["id"]);

            if (isset($validated["dt_pagrec"])) {
                $validated["situacao"] = "QUITADO";
            }

            $Conta = ContaBancaria::find($request->cd_conta);
            $tpMov='LC'; 
            if ($Conta->tp_conta == "CA") { 
                $tpMov='CA';
                unset($validated["dt_pagrec"]);
                unset($validated["vl_pagrec"]);
                $FaturaCartao=$this->fatura_cartao($validated["data_compra"],$request->cd_conta);
                if($FaturaCartao['situacao']==true){
                    $validated["cd_fatura_cartao"] = $FaturaCartao['codigo'];
                    $validated["sn_cartao"] = 'S'; 
                    $validated["dt_vencimento"] = $FaturaCartao['vencimento_fatura'];  
                }
            }else{
                $validated["cd_fatura_cartao"] = null;
                $validated["sn_cartao"] = null;
                $validated["data_compra"] = null;
            }

            $validated["tp_mov"] =  $tpMov;
            $boleto = DocumentoBoleto::find($request->id);
            $boleto->update($validated);

            if ($request->valor_restante) {
                unset($validated["dt_pagrec"]);
                unset($validated["vl_pagrec"]);
                $dataValorRestante = $validated;
                $dataValorRestante["cd_empresa"] = $boleto->cd_empresa;
                $dataValorRestante["hash_grupo"] = $boleto->hash_grupo;
                $dataValorRestante["cd_usuario"] = $request->user()->cd_usuario;
                $dataValorRestante["ds_boleto"] = $request->valor_restante["parcela_descricao"] ? $request->descricao." / valor restante" : $request->descricao;
                $dataValorRestante["doc_boleto"] = ($request->documento) ? $request->documento : date('Ymd');
                $dataValorRestante["dt_vencimento"] = $request->valor_restante["data_vencimento"];
                $dataValorRestante["vl_boleto"] = $request->valor_restante["valor_restante"];
                $dataValorRestante["situacao"] = "ABERTO";
                $dataValorRestante["tp_mov"] =  $tpMov;
                
                if ($Conta->tp_conta == "CA") {
                    $FaturaCartao=$this->fatura_cartao($dataValorRestante["dt_vencimento"],$request->cd_conta);
                    if($FaturaCartao['situacao']==true){
                        $dataValorRestante["cd_fatura_cartao"] = $FaturaCartao['codigo'];
                        $dataValorRestante["sn_cartao"] = 'S';
                        $dataValorRestante["data_compra"] = date('Y-m-d');
                    }else{
                        return response()->json(["message" => $FaturaCartao['descricao']]);
                    }
                }else{
                    $dataValorRestante["cd_fatura_cartao"] = null;
                    $dataValorRestante["sn_cartao"] = null;
                    $dataValorRestante["data_compra"] = null;
                }

                DocumentoBoleto::create($dataValorRestante);
            }

            DB::commit(); 
            return response()->json([
                "message" => "Parcela atualizada!",
                "boleto" => $boleto->load("empresa", "fornecedor")
            ]);
        }
        catch(Exception $e) {
            DB::rollback();
            return response()->json(["message" => "Não foi possivel atualizar a parcela. ".$e->getMessage()], 500);
        }
    }

    public function relacao(Request $request) {
        
        
        $saldo = DocumentoBoleto::PainelSaldo($request)->first();  
        $saldo = (isset($saldo->saldo)) ? $saldo->saldo : 0;
         


        $boletos = DocumentoBoleto::PainelFinanceiro($request)
        ->selectRaw("documento_boleto.*,
            case
				when situacao='ABERTO' and tipo='receita'  then vl_boleto 
				when situacao='QUITADO' and tipo='receita'  then vl_pagrec 
            end vl_receita,
            case
				when situacao='ABERTO' and tipo='despesa'  then vl_boleto 
				when situacao='QUITADO' and tipo='despesa'  then vl_pagrec 
            end vl_despesa,
            case
				when situacao='ABERTO' and dt_vencimento>=CURDATE() and tipo='despesa' then 'A vencer'
				when situacao='ABERTO' and dt_vencimento<CURDATE()   then 'Vencido'
				when situacao='ABERTO' and dt_vencimento>=CURDATE() and tipo='receita' then 'A receber'
				when situacao='QUITADO' and tipo='receita' then 'Recebido'
				when situacao='QUITADO' and tipo='despesa' then 'Pago' else  situacao end statuss ")
        ->get();

        $vl_receita = array_sum(array_column($boletos->toArray(),'vl_receita'));
        $vl_despesa = array_sum(array_column($boletos->toArray(),'vl_despesa'));


 


        $request['mesInicial'] = '01';
        $request['mesFinal'] =  12;
        $request['tipoAgrupamento'] = 'CAT'; 
        $ultimo_dia = 31;   
        $request['dti'] = '2024-'. $request['mesInicial']. '-01';
        $request['dtf'] = '2024-'. $request['mesFinal']. '-'. $ultimo_dia; 
        $di = strtotime($request['dti']);
        $df=strtotime($request['dtf']);
          
        $query = DocumentoBoleto::where('situacao','QUITADO')->where('dt_pagrec','<',$request['dti'])
        ->select(DB::raw(" sum( if(tipo='despesa', (vl_pagrec*(-1)) , vl_pagrec ) ) valor  "))->first();
        $saldoInicial = (isset($query->valor)) ? $query->valor : 0; 

        while($di<$df){  

            $ArrayComp[date('m',$di)] = MES_ANO[date('m',$di)];
        
            $queryMov = DocumentoBoleto::where('situacao','QUITADO')
            ->whereBetween('dt_pagrec',[date('Y-m',$di).'-01' , date('Y-m-',$di). date("t", mktime(0,0,0,date('m',$di),'01',date('Y',$di))) ])
            ->selectRaw("
                sum( if(tipo='despesa' , vl_pagrec, 0 )  ) valor_despesa,
                sum( if(tipo='receita' , vl_pagrec, 0 ) ) valor_receita,
                sum( case when tp_mov='TR' and tipo='despesa' then vl_pagrec*(-1) when tp_mov='TR' and tipo='receita' then vl_pagrec else 0 end  ) valor_tranf
            ")->first(); 

            $receita=(isset($queryMov->valor_receita)) ? $queryMov->valor_receita : 0 ;
            $ArrayReceita[]=$receita;
            $despesa=(isset($queryMov->valor_despesa)) ? $queryMov->valor_despesa : 0;
            $ArrayDespesa[date('m',$di)]=$despesa;
            $saldo_ope=$receita-$despesa;
            $ArraySaldoOpe[date('m',$di)]=$saldo_ope;
            $transferencia = (isset($queryMov->valor_tranf)) ? $queryMov->valor_tranf : 0;
            $ArrayTransferencia[date('m',$di)]=$transferencia;
            $ArraySaldoFinal[date('m',$di)]=( $saldoInicial + $receita - $despesa - $transferencia );
            if($di<>strtotime($request['dti'])){
                $diAnterior = strtotime('@'.$di.'- 1 month'); 
                $query = DocumentoBoleto::where('situacao','QUITADO')->whereBetween('dt_pagrec',[date('Y-m',$diAnterior).'-01' , date('Y-m',$diAnterior).'-'.date("t", mktime(0,0,0,date('m',$diAnterior),'01',date('Y',$diAnterior))) ])
                ->select(DB::raw(" sum( if(tipo='despesa', (vl_pagrec*(-1)) , vl_pagrec ) ) valor  "))->first();
                $saldoComp = (isset($query->valor)) ? $query->valor : 0;
                $saldoInicial = ($saldoInicial+$saldoComp);
            } 

            $di = strtotime('@'.$di.'+ 1 month'); 

        }

        

        return response()->json([
            "saldo_anterior" => $saldo,
            "saldo_receita" => $vl_receita,
            "saldo_despesa" => $vl_despesa,
            "boletos" => $boletos,
            'request'=>$request->toArray(), 
            'retorno' =>[
                'receita'=>$ArrayReceita,
                'despesa'=>$ArrayDespesa,
                'saldo_ope'=>$ArraySaldoOpe,
                'transferencia'=>$ArrayTransferencia,
                'saldo_final'=>$ArraySaldoFinal,
                'comp'=>$ArrayComp,
                'teste'=>$ArrayComp
            ]
        ]);
    }

    public function excluirParcela($cdDocumentoBoleto) {
        try {

            $Doc = DocumentoBoleto::find($cdDocumentoBoleto);
            if($Doc->tp_mov=='TR'){
                DocumentoBoleto::where('hash_grupo',$Doc->hash_grupo)->delete();
                return response()->json(["message" => "Transferencia excluida com sucesso!"]);
            }
            if(($Doc->tp_mov=='CA') and ($Doc->situacao=='QUITADO')){
                return response()->json(["message" => "Não foi possivel exluir CARTÃO com status Quitado "], 500);
            }
            DocumentoBoleto::find($cdDocumentoBoleto)->delete();

            return response()->json(["message" => "Boleto excluido!"]);
        }
        catch (Exception $e) {
            return response()->json(["message" => "Não foi possivel realizar a exclusãoo! ".$e->getMessage()], 500);
        }
    }

    public function estornarParcela($cdDocumentoBoleto) {
        try {

            $Doc = DocumentoBoleto::find($cdDocumentoBoleto);
            if(($Doc->tp_mov=='CA') or ($Doc->tp_mov=='TR')){
                return response()->json(["message" => "Não permitido estorno desse documento."], 500);
            }

            DocumentoBoleto::find($cdDocumentoBoleto)->update([
                'dt_pagrec'=>null,
                'data_pagrec'=>null,
                'vl_pagrec'=>null,
                'situacao'=>'ABERTO',
                'cd_usuario_pagrec'=>null
            ]);
 
            return response()->json(["message" => "Parcela estornada com sucesso!"]);
        }
        catch (Exception $e) {
            return response()->json(["message" => "Não foi possivel realizar o estorno! ".$e->getMessage()], 500);
        }
    }

    public function updateLancamentoParcelas(Request $request) {

        

        $request->validate([
            "cd_documento_boleto" => "required|integer|exists:documento_boleto,cd_documento_boleto",
            "cd_empresa" => "required|integer|exists:empresa,cd_empresa",
            "cd_categoria" => "required|integer|exists:categoria,cd_categoria",
            "cd_conta" => "required|integer|exists:conta_bancaria,cd_conta",
            "cd_forma" => "required|integer|exists:forma_pagamento,cd_forma_pag",
            "cd_fornecedor" => "required|integer|exists:fornecedor,cd_fornecedor",
            "cd_setor" => "nullable|integer|exists:setor,cd_setor",
            "cd_marca" => "nullable|integer|exists:marca,cd_marca",
            "descricao" => "required|string",
            "documento" => "required",
            "tp_lancamento" => "required|string|in:despesa,receita",
            "parcelas" => "nullable|array",
            "parcelas.*.cd_documento_boleto" => "required|integer|exists:documento_boleto,cd_documento_boleto",
            "parcelas.*.ds_boleto" => "required|string",
            "parcelas.*.doc_boleto" => "required|string",
            "parcelas.*.dt_vencimento" => "nullable|date_format:Y-m-d",
            "parcelas.*.vl_boleto" => "required|currency",
            "parcelas.*.dt_pagrec" => "nullable|date_format:Y-m-d",
            "parcelas.*.vl_pagrec" => "nullable|currency" 
        ]);

        try {

            
            DB::beginTransaction();
 
            //$data = $request->only(["cd_empresa", "cd_categoria", "cd_conta", "cd_forma", "cd_fornecedor", "cd_setor", "cd_marca", "cd_turma",  ]); 
            
            $data["dt_emissao"] = $request->dt_emissao;
            $data["ds_boleto"] = $request->descricao;
            $data["doc_boleto"] = $request->documento; 
            $data["cd_documento_boleto"] = $request->cd_documento_boleto;

            $Conta = ContaBancaria::find($request->cd_conta); 
            $parcelaPrincipal = DocumentoBoleto::where('cd_documento_boleto',$request->cd_documento_boleto)->update($data);
       
            $tpMov='LC';  
            foreach ($request->parcelas as $key => $parcela) {

                if($parcela['vl_pagrec']){
                    $parcela['vl_pagrec']= str_replace('.', '', $parcela['vl_pagrec']);
                    $parcela['vl_pagrec']= str_replace(',', '.', $parcela['vl_pagrec']);    
                }else{
                    $parcela['vl_pagrec']=null;
                }

                $parcela['vl_boleto']= str_replace('.', '', $parcela['vl_boleto']);
                $parcela['vl_boleto']= str_replace(',', '.', $parcela['vl_boleto']);

                if(($parcela['vl_pagrec']>0) && (!$parcela['dt_pagrec'])){ 
                    DB::rollback();
                    return response()->json(["message" =>  "Data de Pag./Rec. não informada" ], 500);
                }
                if(($parcela['vl_pagrec']<=0) && ($parcela['dt_pagrec'])){ 
                    DB::rollback();
                    return response()->json(["message" =>  " Valor de Pag./Rec. não informado" ], 500); 
                }
                if(($parcela['vl_pagrec']) && ($parcela['dt_pagrec'])){
                    $parcela["situacao"] = 'QUITADO';
                }
                if($request->cd_documento_boleto == $parcela['cd_documento_boleto']){
                    $parcela['ds_boleto']=$request->descricao;
                    $parcela["doc_boleto"] = $request->documento;
                }
                

                if ($Conta->tp_conta == "CA") {
                    $tpMov='CA';
                    $FaturaCartao=$this->fatura_cartao($parcela["dt_vencimento"],$request->cd_conta);
                    if($FaturaCartao['situacao']==true){
                        $parcela["cd_fatura_cartao"] = $FaturaCartao['codigo'];
                        $parcela["sn_cartao"] = 'S'; 
                        $parcela["data_compra"] = $FaturaCartao["vencimento_fatura"];
                    }else{
                        return response()->json(["message" => $FaturaCartao['descricao']]);
                    }
                    
                }else{
                    $dataValorRestante["cd_fatura_cartao"] = null;
                    $dataValorRestante["sn_cartao"] = null;
                    $dataValorRestante["data_compra"] = null;
                }

                $dataValorRestante["tp_mov"] = $tpMov; 
                $parcelaDoc = DocumentoBoleto::find($parcela["cd_documento_boleto"]);
                $parcelaDoc->update($parcela);

            }
            DB::commit();
            return response()->json(["message" => "Atualizações realizadas!"]);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => "Não foi possivel atualizar! ".$e->getMessage()], 500);
        }
    }

    // CARTÃO

    function fatura_cartao($data,$conta) {

        try {
            $dt =explode('-',$data);
            $dia=$dt[2];
            $mes=$dt[1];
            $ano=$dt[0];
            $retorno = null;
            $Conta = ContaBancaria::find($conta);
            if($Conta->tp_conta=='CA'){

                $Fechamento =  str_pad($Conta->dia_fechamento , 2 , '0' , STR_PAD_LEFT);
                $Vencimento  =  str_pad($Conta->dia_vencimento , 2 , '0' , STR_PAD_LEFT);
                if($dia<$Fechamento) {

                    $DataFechamento= $ano.'-'.$mes.'-'.$Fechamento;  
                    $Competencia = $ano.'-'.$mes.'-01';
                    $Comp =  date('Y-m', strtotime($ano.'-'.$mes.'-01 -1 month'));
                    $DataInicial =  date('Y-m-d', strtotime($Comp.'-'.$Fechamento.' +1 day'));

                    if($Conta->dia_fechamento > $Conta->dia_vencimento){
                        $Comp =  date('Y-m', strtotime($ano.'-'.$mes.'-01 +1 month'));
                        $Dia =  date('d', strtotime($ano.'-'.$mes.'-'.$Fechamento.' -1 day'));
                        $DataVencimento= $Comp.'-'.$Vencimento;
                    }else{
                        $DataVencimento= $ano.'-'.$mes.'-'.$Vencimento;
                    }

                }else{

                    $Comp =  date('Y-m', strtotime($ano.'-'.$mes.'-01 +1 month'));
                    $Competencia = $Comp.'-01';
                    $DataInicial =  date('Y-m-d', strtotime($ano.'-'.$mes.'-'.$Fechamento.' +1 day'));
                    $DataFechamento= $Comp.'-'.$Fechamento; 

                    if($Conta->dia_fechamento > $Conta->dia_vencimento){
                        $Comp =  date('Y-m', strtotime($Comp.'-01 +1 month'));
                        $DataVencimento= $Comp.'-'.$Vencimento;
                    }else{
                        $DataVencimento= $Comp.'-'.$Vencimento;
                    }
                }

                $fatura =CartaoFatura::where("competencia",$Competencia)->where("cd_conta",$Conta->cd_conta)->first();
                if($fatura){
                    if($fatura->sn_fechada =='S'){ 
                        $retorno = array('codigo'=>null,'vencimento_fatura'=> null,'situacao'=>false,'descricao'=>'A fatura do cartão usado já foi fechada!');
                    }else{ 
                        $retorno = array('codigo'=>$fatura->cd_fatura,'vencimento_fatura'=>$fatura->dt_vencimento,'situacao'=>true,'descricao'=>'');
                    }

                }else{

                    $create = array(
                        'cd_conta' =>$Conta->cd_conta,
                        'cd_empresa' =>request()->user()->cd_empresa,
                        'dt_abertura' =>$DataInicial,
                        'dt_fechamento' =>$DataFechamento,
                        'dt_vencimento' =>$DataVencimento,
                        'cd_usuario' => request()->user()->cd_usuario,
                        'situacao' => 'ABERTA',
                        'sn_fechada' => 'N',
                        'competencia' => $Competencia,
                        "descricao" => $Conta->nm_conta.' ( '.date('d/m/Y',strtotime($DataInicial))." - ".date('d/m/Y',strtotime($DataFechamento))." ) "
                    );
                    $Fatura = CartaoFatura::create($create);
                    $retorno = array('codigo'=>$Fatura->cd_fatura,'vencimento_fatura'=>$Fatura->dt_vencimento,'situacao'=>true,'descricao'=>'');

                }
    
            }else{
                $situacao = "Não é Cartão";
            }

            return $retorno;
        }
        catch (Exception $e) {
            return ['codigo'=>null,'vencimento_fatura'=>null,'situacao'=>false,"descricao" => "[ Rotina Fatura Cartão ] ".$e->getMessage()];
        }

 

         
    }

    public function cartao() {
        $cartoes = ContaBancaria::where("tp_conta", "CA")->get();
        $formasPagamento = FormaPagamento::all();
        $empresas = Empresa::all();
        $contas = ContaBancaria::all();

        $cartoes->each(function ($cartao, $key) {
            $cartao->saldo_lancamento = doubleval($cartao->vl_limite) - doubleval($cartao->lancamentos()->where("situacao", "ABERTO")->sum("vl_boleto"));
        });

        return view("rpclinica.financeiro.cartao", compact("cartoes", "formasPagamento", "empresas", "contas"));
    }

    public function relacaoCartao(Request $request) {
      
      
        if($request['aberto']=="true"){ $situacao[]='ABERTA';  }
        if($request['fechado']=="true"){ $situacao[]='FECHADA';  }
        if($request['quitado']=="true"){ $situacao[]='QUITADA';  }
        $queryCartao = CartaoFatura::whereRaw("competencia='".$request->ano.'-'.$request->mes."-01'")
        ->with('boleto_item.fornecedor')
        ->leftJoin('documento_boleto','documento_boleto.cd_fatura_cartao','cartao_fatura.cd_fatura');
         
        if($request['cd_forma']){
            $queryCartao = $queryCartao->where('cartao_fatura.cd_forma',$request['cd_forma']);
        }
        if($request['cd_cartao']){
            $queryCartao = $queryCartao->where('cartao_fatura.cd_conta',$request['cd_cartao']);
        }
        if($situacao){
            $queryCartao = $queryCartao->whereIn('cartao_fatura.situacao',$situacao);
        }else{
            $queryCartao = $queryCartao->where('1','2');
        }
        $queryCartao = $queryCartao->selectRaw("cd_fatura,descricao,cartao_fatura.situacao,competencia,dt_pagamento,vl_fatura, ds_pagamento,
        DATE_FORMAT(dt_abertura, '%d/%m/%Y') data_abertura, 
        DATE_FORMAT(cartao_fatura.dt_fechamento, '%d/%m/%Y') fechamento, 
        DATE_FORMAT(cartao_fatura.dt_vencimento, '%d/%m/%Y') vencimento,
        DATE_FORMAT(cartao_fatura.competencia, '%d/%m/%Y') competencia,
        cartao_fatura.cd_empresa,cartao_fatura.cd_conta,cartao_fatura.cd_conta_pag,
        cartao_fatura.cd_conta_pag,cartao_fatura.cd_forma,cartao_fatura.descricao,
        cartao_fatura.documento,FORMAT(vl_fatura, 2, 'de_DE') valor_fatura, 
        FORMAT(vl_pago, 2, 'de_DE') valor_pago,  sum(vl_boleto) vl_boletos ") 
        ->groupByRaw("cd_fatura,descricao,situacao,competencia,dt_pagamento,vl_fatura, ds_pagamento,
        DATE_FORMAT(dt_abertura, '%d/%m/%Y') , 
        DATE_FORMAT(cartao_fatura.dt_fechamento, '%d/%m/%Y'), 
        DATE_FORMAT(cartao_fatura.dt_vencimento, '%d/%m/%Y'),
        DATE_FORMAT(cartao_fatura.competencia, '%d/%m/%Y'),
        cartao_fatura.cd_empresa,cartao_fatura.cd_conta,
        cartao_fatura.cd_conta_pag,cartao_fatura.cd_conta_pag,
        cartao_fatura.cd_forma,cartao_fatura.descricao,
        cartao_fatura.documento,FORMAT(vl_fatura, 2, 'de_DE'),
        FORMAT(vl_pago, 2, 'de_DE')")
        ->get();
           

        return response()->json([ 
                                  'query'=>$queryCartao,
                                  'request'=>$request->toArray()
                                ]);
    }

    public function cadastro(Request $request) {

       
        $request->validate([ 
            "tp_cadastro" => "required",
            "descricao" => 'required|string',
            "tipo" => 'required_if:tp_cadastro,FORMA,FORN', 
            "tipo_pessoa" => 'required_if:tp_cadastro,FORN', 
            "documento" => 'required_if:tp_cadastro,FORN',
            "grupo" => 'required_if:tp_cadastro,SETOR' 
        ],[
            "tipo_pessoa.required_if" => "Campo Tipo de Pessoa Obrigatório",
            "tipo.required_if" => "Campo Tipo Obrigatório",
            "descricao.required" => "Campo Descrição Obrigatório",
            "documento.required_if" => "Campo Documento Obrigatório",
            "grupo.required_if" => "Campo Grupo Obrigatório",
        ]);

        try {

            if($request['tp_cadastro']=='FORN'){
                $retorno =Fornecedor::create([
                    'nm_fornecedor'=>$request['descricao'], 
                    'tp_pessoa'=>$request['tipo_pessoa'],
                    'tp_cadastro'=>$request['tipo'],
                    'documento'=>$request['tp_cadastro'],
                    'cd_usuario'=>$request->user()->cd_usuario,
                    'dt_cadastro'=>date('Y-m-d H:i'),
                    'sn_ativo'=>'S' 
                ]);
                return response()->json(['message'=>'Fornecedor Cadastro com Sucesso',
                                         'codigo'=> $retorno->cd_fornecedor ,
                                         'nome'=> $retorno->nm_fornecedor,
                                         'id_campo'=>'lancamentosFornecedor'
                                        ]
                                    );
            }

            if($request['tp_cadastro']=='MARCA'){
                $retorno =Marca::create([
                    'nm_marca'=>$request['descricao'],    
                    'cd_usuario'=>$request->user()->cd_usuario, 
                    'sn_ativo'=>'S' 
                ]);
                return response()->json(['message'=>'Marca Cadastra com Sucesso',
                                         'codigo'=> $retorno->cd_marca ,
                                         'nome'=> $retorno->nm_marca,
                                         'id_campo'=>'lancamentosMarcas'
                                        ]
                                    );
            }

            if($request['tp_cadastro']=='FORMA'){
                $retorno =FormaPagamento::create([
                    'nm_forma_pag'=>$request['descricao'],    
                    'tipo'=>$request['tipo'],  
                    'cd_usuario'=>$request->user()->cd_usuario, 
                    'dt_cadastro'=>date('Y-m-d H:i'),
                    'sn_ativo'=>'S' 
                ]);
                return response()->json(['message'=>'Forma de Pagamento Cadastro com Sucesso',
                                         'codigo'=> $retorno->cd_forma_pag ,
                                         'nome'=> $retorno->nm_forma_pag,
                                         'id_campo'=>'lancamentosFormaPagamento'
                                        ]
                                    );
            }

            if($request['tp_cadastro']=='SETOR'){
                $retorno =Setores::create([
                    'nm_setor'=>$request['descricao'],    
                    'grupo'=>$request['grupo'],  
                    'cd_usuario'=>$request->user()->cd_usuario, 
                    'cd_empresa'=>$request->user()->cd_empresa, 
                    'dt_cadastro'=>date('Y-m-d H:i'),
                    'sn_ativo'=>'S' 
                ]);
                return response()->json(['message'=>'Setor Cadastro com Sucesso',
                                         'codigo'=> $retorno->cd_setor ,
                                         'nome'=> $retorno->nm_setor,
                                         'id_campo'=>'lancamentosSetores'
                                        ]
                                    );
            }

            return response()->json(["message" => 'Função não configurada'], 500);
        }
        catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
       

    public function fecharFaturaCartao(Request $request) {
        $request->validate([
            "cd_fatura" => "required|integer|exists:cartao_fatura,cd_fatura" 
        ]);

        try {
            
            $valor = DocumentoBoleto::where('cd_fatura_cartao',$request['cd_fatura'])->sum("vl_boleto"); 
            $fatura = CartaoFatura::where('cd_fatura',$request['cd_fatura'])->update(["situacao" => "FECHADA","vl_fatura"=> $valor,"sn_fechada"=>'S']); 
            return response()->json($fatura);
        }
        catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function pagarFaturaCartao(Request $request) {
        
        $validated = $request->validate([
            "cd_fatura" => "required|integer|exists:cartao_fatura,cd_fatura", 
            "cd_conta_pag" => "required|integer|exists:conta_bancaria,cd_conta",
            "cd_forma" => "required|integer|exists:forma_pagamento,cd_forma_pag",
            "ds_pagamento" => "required|string",
            "documento" => "nullable|string",
            "dt_pagamento" => "required|date_format:Y-m-d",
            "vl_pago" => "required|currency"
        ]);
 
        try {
             
            $config=FinanceiroConfig::find('CF');
            $categoria = $config->cd_cateogria_cartao;
            if(empty($categoria)){ 
                return response()->json(["message" =>  'Categoria para Pagamento de Cartão não configurada.<br>Favor configurar!' ], 500);
            }

            DB::beginTransaction();

            $fatura = CartaoFatura::find($request->cd_fatura); 

            $request['vl_pago']= str_replace('.', '', $request['vl_pago']);
            $request['vl_pago']= str_replace(',', '.', $request['vl_pago']); 

            $Doc=($request->documento) ? $request->documento : (rand(10000,999999));
            $hash=date("YmdHis")."-".\Illuminate\Support\Str::random(10);
            $Transacao['cd_empresa']=$request->user()->cd_empresa;
            $Transacao['cd_forma']=$request->cd_forma;
            $Transacao['doc_boleto']=$Doc; 
            $Transacao['situacao']="QUITADO";
            $Transacao['cd_categoria']=$categoria;
            $Transacao['dt_vencimento']=date('Y-m-d'); 
            $Transacao['cd_usuario'] = $request->user()->cd_usuario;
            $Transacao['dt_pagrec']=date('Y-m-d'); 
            $Transacao['cd_usuario_pagrec'] = $request->user()->cd_usuario;
            $Transacao['data_pagrec']=date('Y-m-d H:i');
            $Transacao['hash_grupo'] = $hash;

           $Saida = DocumentoBoleto::create(array_merge($Transacao, [ 
                "cd_conta" => $request->cd_conta_pag, 
                "tp_mov" => 'LC',
                "tipo" => "despesa",
                "ds_boleto" => $request['ds_pagamento'] . " [ Competencia ".$fatura->competencia." ]",  
                "vl_boleto" => floatval($request->vl_pago),
                "vl_pagrec" => floatval($request->vl_pago),
            ]));

            $Entrada = DocumentoBoleto::create(array_merge($Transacao, [ 
                "cd_conta" => $fatura->cd_conta, 
                "tp_mov" => 'RC',
                "tipo" => "receita",
                "ds_boleto" =>  "Recebimento Farura [ Competencia ".$fatura->competencia." ]", 
                "vl_boleto" => floatval($fatura->vl_fatura),
                "vl_pagrec" => floatval($fatura->vl_fatura),
            ]));
  
            $sai = $Saida->cd_documento_boleto;
            $ent = $Entrada->cd_documento_boleto;

            $diff = null;
            if ( floatval($request['vl_pago']) < floatval($fatura['vl_fatura']) ) {

                $ProximaComp = date('Y-m-d', strtotime('+1 month', strtotime($fatura->competencia)));
                $FaturaCartao=$this->fatura_cartao($ProximaComp,$fatura->cd_conta);
                if($FaturaCartao['situacao']==true){
                    $cd_fatura_cartao = $FaturaCartao['codigo'];  
                    $vencimento_fatura = $FaturaCartao["vencimento_fatura"]; 
                }else{
                    return response()->json(["message" => $FaturaCartao['descricao']]);
                }
            
                $Diferenca = DocumentoBoleto::create([
                    "cd_empresa" => $request->user()->cd_empresa,
                    "cd_conta" => $fatura->cd_conta,
                    "cd_forma" => $request->cd_forma,
                    "ds_boleto" => "Diferença fatura anterior [ Competencia ".$fatura->competencia." ]",
                    "doc_boleto" => $Doc,
                    "vl_boleto" => floatval($fatura->vl_fatura) - floatval($request->vl_pago),
                    "situacao" => "ABERTO", 
                    "cd_categoria" => $categoria,
                    "data_compra" => date("Y-m-d"),
                    "dt_emissao" => null,
                    "cd_fatura_cartao" =>$cd_fatura_cartao,
                    "dt_vencimento" => $vencimento_fatura,
                    "cd_usuario" => $request->user()->cd_usuario,
                    "tipo" => "despesa",
                    "sn_cartao" => "S",
                    "tp_mov" => 'LC',
                    "hash_grupo" => $hash
                ]);
                $diff = $Diferenca->cd_documento_boleto; 
 
            }
            

            $fatura->update([
                'cd_conta_pag'=> $request->cd_conta_pag,
                'cd_forma'=> $request->cd_forma,
                'ds_pagamento'=> $request->ds_pagamento,
                'documento'=> ($request->documento) ? $request->documento : date('Ymd'),
                'dt_pagamento'=> $request->dt_pagamento,
                'vl_pago'=> $request['vl_pago'],
                "cd_doc_boleto_baixa"=>$sai,
                "cd_doc_boleto_credito"=>$ent,
                "cd_doc_boleto_dif"=>$diff,
                'situacao'=> 'QUITADA',
                'user_situacao'=> $request->user()->cd_usuario,
            ]);

            $compras = DocumentoBoleto::where("cd_fatura_cartao", $fatura->cd_fatura)->get();

            foreach($compras as $compra) {
                $compra->update([
                    "dt_pagrec" => $request->dt_pagamento,
                    "vl_pagrec" => $compra->vl_boleto,
                    "situacao" => "QUITADO",
                    "cd_usuario_pagrec" => $request->user()->cd_usuario,
                    "data_pagrec" => date("Y-m-d H:i"),
                ]);
            }

            DB::commit();  
            return response()->json($fatura);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function abrirFaturaCartao(Request $request) {
        $request->validate([
            "cd_fatura" => "required|integer|exists:cartao_fatura,cd_fatura",
        ]);

        try {
            $fatura = CartaoFatura::find($request->cd_fatura);
            $fatura->update(["situacao" => "ABERTA","sn_fechada" => "N",  "cd_conta_pag" => null,  "cd_forma" => null, 
            "vl_fatura" => null, "vl_pago" => null, "dt_pagamento" => null,  "documento" => null, "cd_doc_boleto_dif" => null  ]);

            return response()->json($fatura);
        }
        catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function estornarFaturaCartao(Request $request) {
        $request->validate([
            "cd_fatura" => "required|integer|exists:cartao_fatura,cd_fatura",
        ]);
        try {

            $fatura = CartaoFatura::find($request->cd_fatura);

            if($fatura->cd_doc_boleto){
                $Doc=DocumentoBoleto::find($fatura->cd_doc_boleto);
                $fat=CartaoFatura::find($Doc->cd_fatura_cartao);
                if($fat->sn_fechada == 'S'){
                    DB::rollback();
                    return response()->json(["message" => 'A Fatura da compentência '.$fat['competencia']. ' encontrasse fechada.'], 500);
                }
            }

            $compras = DocumentoBoleto::where("cd_fatura_cartao", $fatura->cd_fatura)->get(); 
            foreach($compras as $compra) {
                $compra->update([
                    "dt_pagrec" => null,
                    "vl_pagrec" => null,
                    "situacao" => "ABERTO",
                    "cd_usuario_pagrec" => null,
                    "data_pagrec" => null,
                ]);
            }
  
            $fatura->update(["situacao" => "FECHADA",   "cd_conta_pag" => null,  "cd_forma" => null, 
             "vl_pago" => null, "dt_pagamento" => null, "ds_pagamento" => null, "cd_doc_boleto_baixa" => null,  
              "documento" => null,"cd_doc_boleto_dif" => null, "cd_doc_boleto_credito" => null,
              "ds_pagamento " => null ]);

            DB::commit();  
            return response()->json($fatura);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
        }
        
    }
    
    public function infoFaturaCartao(Request $request,ContaBancaria $cartao) {
 
        try {
            $lanc = DocumentoBoleto::where('cd_conta',$cartao->cd_conta)->where('situacao','ABERTO')->sum('vl_boleto'); 
            return response()->json(['cartao'=>$cartao,'saldo'=>(empty($lanc)) ?  0 :  $lanc ]);
        }
        catch (Exception $e) {
            DB::rollback();
            return response()->json(["message" => $e->getMessage()], 500);
        }

    }

    //Fluxo Caixa
    public function fluxoCaixa(Request $request) {
        
        $saldo = DocumentoBoleto::PainelSaldo($request)->first();  
        $saldo = (isset($saldo->saldo)) ? $saldo->saldo : 0;
        
        $boletos = DocumentoBoleto::PainelFinanceiro($request) 
        ->selectRaw("documento_boleto.*,
            case
				when situacao='ABERTO' and tipo='receita'  then vl_boleto 
				when situacao='QUITADO' and tipo='receita'  then vl_pagrec 
            end vl_receita,
            case
				when situacao='ABERTO' and tipo='despesa'  then vl_boleto 
				when situacao='QUITADO' and tipo='despesa'  then vl_pagrec 
            end vl_despesa,
            case
				when situacao='ABERTO' and dt_vencimento>=CURDATE() and tipo='despesa' then 'A vencer'
				when situacao='ABERTO' and dt_vencimento<CURDATE()   then 'Vencido'
				when situacao='ABERTO' and dt_vencimento>=CURDATE() and tipo='receita' then 'A receber'
				when situacao='QUITADO' and tipo='receita' then 'Recebido'
				when situacao='QUITADO' and tipo='despesa' then 'Pago' else  situacao end statuss ")
        ->get();

        $vl_receita = array_sum(array_column($boletos->toArray(),'vl_receita'));
        $vl_despesa = array_sum(array_column($boletos->toArray(),'vl_despesa'));


        $ArrayComp[1] = MES_ANO['01'];
        $ArrayComp[2] = MES_ANO['02'];
        $ArrayComp[3] = MES_ANO['03'];
        $ArrayComp[4] = MES_ANO['04'];

        return response()->json([
            "saldo_anterior" => $saldo,
            "saldo_receita" => $vl_receita,
            "saldo_despesa" => $vl_despesa,
            "boletos" => $boletos,
            'request'=>$request->toArray(),
            "teste"=>$ArrayComp
        ]);
    }
    
}
