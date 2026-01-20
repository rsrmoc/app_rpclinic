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
use App\Model\rpclinica\Meses;
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
 
class FluxoCaixa extends Controller
{
 
    public static function index(){  
         
        $categorias = Categoria::selectRaw("categoria.cd_empresa,categoria.cd_categoria,nm_categoria,case when sn_lancamento ='S' then false else true end lanc,cod_estrutural ")
        ->whereRaw("categoria.deleted_at is null")
        ->orderByRaw("RPAD( cast(replace(cod_estrutural, '.' , '') as unsigned integer),20,'0')")->get();
        $setores = Setores::orderBy('nm_setor')->get(); 
        $fornecedores = Fornecedor::orderBy('nm_fornecedor')->get();
        $contasBancaria = ContaBancaria::orderBy('nm_conta')->get(); 
        $marcas = Marca::orderBy('nm_marca')->get();
        $turmas = null; 
     
        return view("rpclinica.fluxo_caixa.lista", compact("categorias", "setores", "fornecedores","contasBancaria", "marcas", "turmas"));
    }

    public function relacao(Request $request) {
 
        if($request['fluxo_caixa'] == 'M'){

            $request->validate([
                "ano" => "date_format:Y", 
                "visao" => "required|string",  
                "detalhe" => "required",
            ]);

            $request['mesInicial'] = ( $request['mes_inicial'] ) ? str_pad($request['mes_inicial'] , 2 , '0' , STR_PAD_LEFT) : '01';
            $request['mesFinal'] =  ( $request['mes_final'] ) ? str_pad($request['mes_final'] , 2 , '0' , STR_PAD_LEFT) : date('m');
            $ultimo_dia = date("t", mktime(0,0,0,$request['mesFinal'],'01',$request['ano']));  
            $request['ultimo_dia'] = $ultimo_dia;
            $request['dti'] = $request['ano'].'-'. $request['mesInicial']. '-01';
            $request['dtf'] = $request['ano'].'-'. $request['mesFinal']. '-'. $ultimo_dia; 
            $di = strtotime($request['dti']);
            $df=strtotime($request['dtf']);
            while($di<$df){
                $ArrayComp[] = array('codigo'=>date('m',$di), 'nome'=>MES_ANO[date('m',$di)]);
                $di = strtotime('@'.$di.'+ 1 month');
            }
                        
        }
         
        if($request['fluxo_caixa'] == 'A'){

            $request->validate([
                "ano" => "date_format:Y", 
                "ano_final" => "date_format:Y",  
                "detalhe" => "required",
                "visao" => "required|string",  
            ]);

            $request['mesInicial'] =  '01';
            $request['mesFinal'] =  '12';
            $request['dti'] = $request['ano'].'-'. $request['mesInicial']. '-01';
            $request['dtf'] = $request['ano_final'].'-'. $request['mesFinal']. '-31'; 
            $di = (int)$request['ano'];
            $df= (int)$request['ano_final'];
            while($di<=$df){ 
                $ArrayComp[] = array('codigo'=>$di, 'nome'=>$di);
                $di = ($di+1);
            } 

        }
         
        if($request['fluxo_caixa'] == 'D'){

            $request->validate([
                "ano" => "date_format:Y",  
                "detalhe" => "required",
                "visao" => "required|string",   
            ]); 

            $request['mesInicial'] =   ( $request['mes_inicial'] ) ? str_pad($request['mes_inicial'] , 2 , '0' , STR_PAD_LEFT) : date('m');
            $request['mesFinal'] =  ( $request['mes_inicial'] ) ? str_pad($request['mes_inicial'] , 2 , '0' , STR_PAD_LEFT) : date('m');
            $ultimo_dia = date("t", mktime(0,0,0,$request['mesFinal'],'01',$request['ano']));  
            $request['ultimo_dia'] = $ultimo_dia;
            $request['dti'] = $request['ano'].'-'. $request['mesInicial']. '-01';
            $request['dtf'] = $request['ano'].'-'. $request['mesFinal']. '-'. $ultimo_dia; 
            $di = strtotime($request['dti']);
            $df=strtotime($request['dtf']);
            while($di<=$df){ 
                $ArrayComp[] = array('codigo'=>date('d',$di), 'nome'=>date('d',$di));
                $di = strtotime('@'.$di.'+ 1 day');
            }

        }

        if($request['tpPesquisa'] =='receita'){
            return response()->json([ 
                'request'=>$request->toArray(), 
                'retorno' => $this->detalhe_movimento($request)
            ]);
        }
        
        if($request['tpPesquisa'] =='despesa'){
            return response()->json([ 
                'request'=>$request->toArray(), 
                'retorno' => $this->detalhe_movimento($request)
            ]);
        }
        if($request['tpPesquisa'] =='transferencia'){
            return response()->json([ 
                'request'=>$request->toArray(), 
                'retorno' => $this->detalhe_movimento($request)
            ]);
        }
        if($request['tpPesquisa'] =='saldo_final'){
            return response()->json([ 
                'request'=>$request->toArray(), 
                'retorno' => $this->detalhe_movimento($request)
            ]);
        }

        //Saldo Inicial
        $querySaldoInicial = DocumentoBoleto::where('situacao','QUITADO')->where('dt_pagrec','<',$request['dti'])
        ->select(DB::raw(" sum( if(tipo='despesa', (vl_pagrec*(-1)) , vl_pagrec ) ) valor  "))->first();
        $saldoInicial = (isset($querySaldoInicial->valor)) ? $querySaldoInicial->valor : 0; 

        //Movimentação 
        $queryMov = $this->saldo_movimento($request); 
        foreach($queryMov as $val){
            $ARRAYmov[$val->comp]=array('receita'=>($val->valor_receita) ? $val->valor_receita : 0,
                                        'despesa'=>($val->valor_despesa) ? $val->valor_despesa : 0,
                                        'transferencia'=>($val->valor_tranf) ? $val->valor_tranf : 0);
        }

        //Saldo Inicial
        $querySaldo = $this->saldo_inicial_mes($request); 
        foreach($querySaldo as $val){
            $ARRAYsaldo[$val->comp]=$val->valor; 
        }


        $saldoComp =0;  
        foreach ($ArrayComp as $key => $value) {
            $CODIGO= $value['codigo'];
            $saldoInicial = ($saldoInicial+$saldoComp); 
            $Saldo[]=$saldoInicial; 

            $saldoComp=(isset($ARRAYsaldo[$CODIGO])) ? $ARRAYsaldo[$CODIGO] : 0;  
            $receita=(isset($ARRAYmov[$CODIGO]['receita'])) ? $ARRAYmov[$CODIGO]['receita'] : 0;
            $ArrayReceita[]=$receita;
            $despesa=(isset($ARRAYmov[$CODIGO]['despesa'])) ? $ARRAYmov[$CODIGO]['despesa'] : 0;
            $ArrayDespesa[]=$despesa;
            $transferencia = (isset($ARRAYmov[$CODIGO]['transferencia'])) ? $ARRAYmov[$CODIGO]['transferencia'] : 0;
            $ArrayTransferencia[]=$transferencia;
            $saldo_ope=$receita-$despesa;
            $ArraySaldoOpe[]=$saldo_ope;
            $ArraySaldoFinal[]=( $saldoInicial + $receita - $despesa - $transferencia );
            
        }
 
        return response()->json([ 
            'request'=>$request->toArray(), 
            'retorno' =>[
                'saldo_inicial'=>$Saldo,
                'receita'=>$ArrayReceita,
                'despesa'=>$ArrayDespesa,
                'saldo_ope'=>$ArraySaldoOpe,
                'transferencia'=>$ArrayTransferencia,
                'saldo_final'=>$ArraySaldoFinal,
                'label'=>$ArrayComp,
            ]
        ]);

    }


    public function relacaoMovimento(Request $request) {


        if($request['fluxo_caixa'] == 'M'){

            $request->validate([
                "ano" => "date_format:Y", 
                "visao" => "required|string",  
                "detalhe" => "required",
                "filtro" =>"required",
                "dt" =>"required",
            ]); 
            $request['mesInicial'] = ( $request['mes_inicial'] ) ? str_pad($request['mes_inicial'] , 2 , '0' , STR_PAD_LEFT) : '01';
            $request['mesFinal'] =  ( $request['mes_final'] ) ? str_pad($request['mes_final'] , 2 , '0' , STR_PAD_LEFT) : date('m');
            $ultimo_dia = date("t", mktime(0,0,0,$request['mesFinal'],'01',$request['ano']));  
            $request['ultimo_dia'] = $ultimo_dia;
            $request['dti'] = $request['ano'].'-'. $request['dt']. '-01';
            $request['dtf'] = $request['ano'].'-'. $request['dt']. '-'. $ultimo_dia; 
            $Titulo =  MES_ANO[$request['dt']].' [ '.$request['nm_filtro'].' ] ';
          
                        
        }
         
        if($request['fluxo_caixa'] == 'A'){

            $request->validate([
                "ano" => "date_format:Y", 
                "ano_final" => "date_format:Y",  
                "detalhe" => "required",
                "visao" => "required|string", 
                "filtro" =>"required", 
                "dt" =>"required",
            ]); 
            $request['mesInicial'] =  '01';
            $request['mesFinal'] =  '12';
            $request['dti'] = $request['dt'].'-'. $request['mesInicial']. '-01';
            $request['dtf'] = $request['dt'].'-'. $request['mesFinal']. '-31'; 
            $Titulo =   $request['dt'].' [ '.$request['nm_filtro'].' ] ';
            

        }
         
        if($request['fluxo_caixa'] == 'D'){

            $request->validate([
                "ano" => "date_format:Y",  
                "detalhe" => "required",
                "visao" => "required|string",   
                "filtro" =>"required",
                "dt" =>"required",
            ]);  
            $request['mesInicial'] =   ( $request['mes_inicial'] ) ? str_pad($request['mes_inicial'] , 2 , '0' , STR_PAD_LEFT) : date('m');
            $request['mesFinal'] =  ( $request['mes_inicial'] ) ? str_pad($request['mes_inicial'] , 2 , '0' , STR_PAD_LEFT) : date('m'); 
            $request['dti'] = $request['ano'].'-'. $request['mesInicial']. '-'.$request['dt'];
            $request['dtf'] = $request['ano'].'-'. $request['mesInicial']. '-'. $request['dt'];
            $Titulo =   $request['dt'].'/'. $request['mesInicial']. '/'. $request['ano'].' [ '.$request['nm_filtro'].' ] ';
             
        }
        
        $situacao=array('QUITADO','ABERTO');
        if($request['visao']=="REAL"){ $situacao=array('QUITADO'); $CampoComp="dt_pagrec";    }
        if($request['visao']=="PREV"){ $situacao=array('ABERTO'); $CampoComp="dt_vencimento";   }
        if($request['visao']=="PRRE"){ $situacao=array('QUITADO','ABERTO'); $CampoComp="dt_pesquisa"; }

        $etapa=array('receita','despesa');
        if($request['tpPesquisa']=="receita"){ $etapa=array('receita'); } 
        if($request['tpPesquisa']=="despesa"){ $etapa=array('despesa'); } 
         
        $querySaldo = DocumentoBoleto::whereIn('situacao',$situacao)
        ->with("fornecedor","conta") 
        ->whereIn('tipo',$etapa);

        
        if($request['tpPesquisa']=="transferencia"){
            $querySaldo=$querySaldo->where("tp_mov","TR"); 
            $request['detalhe']="cd_conta";
        }else{
            if($request['tpPesquisa']=="saldo_final"){
                $request['detalhe']="cd_conta";
            }else{
                $querySaldo=$querySaldo->where("tp_mov","<>","TR");
            }
            
        }

        if($request['detalhe'] == "cd_categoria"){
            $querySaldo=$querySaldo->where("cd_categoria",$request['filtro']);
        }
        if($request['detalhe'] == "cd_fornecedor"){
            $querySaldo=$querySaldo->where("cd_fornecedor",$request['filtro']);
        }
        if($request['detalhe'] == "cd_conta"){
            $querySaldo=$querySaldo->where("cd_conta",$request['filtro']);
        }

        if($request['categoria']){
            $CodEstrutural=Categoria::find($request['categoria']);
            $Cat = Categoria::where('cod_estrutural','like',$CodEstrutural->cod_estrutural.'%')->select("cd_categoria")->get();
            $querySaldo=$querySaldo->whereIn("documento_boleto.cd_categoria",$Cat->toArray());
        }
        if($request['conta']){
            $querySaldo=$querySaldo->where("cd_conta",$request['conta']);
        }
        if($request['setor']){
            $querySaldo=$querySaldo->where("cd_setor",$request['setor']);
        }
        if($request['turma']){
            $querySaldo=$querySaldo->where("cd_turma",$request['turma']);
        }
        if($request['fornecedor']){
            $querySaldo=$querySaldo->where("cd_fornecedor",$request['fornecedor']);
        }

        $querySaldo =$querySaldo->whereBetween($CampoComp,[$request['dti'], $request['dtf'] ])
        ->selectRaw("documento_boleto.*, date_format(dt_vencimento,'%d/%m/%Y') data_vencimento, date_format(dt_pagrec,'%d/%m/%Y') data_pagrec")
        ->orderByRaw('cd_documento_boleto')->get();

        return response()->json([ 
            'request'=>$request->toArray(),
            'retorno'=>$querySaldo->toArray(),
            'titulo'=> $Titulo  
        ]);

    }

   
    public function saldo_movimento($request) {
        
        $queryMov = DocumentoBoleto::where('tp_mov','<>','TR');

        if($request['visao'] == 'REAL'){
            $queryMov = $queryMov->whereBetween('dt_pagrec',[$request['dti'], $request['dtf'] ])
            ->where('situacao','QUITADO');
            $Campo='dt_pagrec';
            $vlCampo='vl_pagrec';
        }
        if($request['visao'] == 'PREV'){
            $queryMov = $queryMov->whereBetween('dt_vencimento',[$request['dti'], $request['dtf'] ])
            ->where('situacao','ABERTO');
            $Campo='dt_vencimento';
            $vlCampo='vl_boleto';
        }
        if($request['visao'] == 'PRRE'){
            $queryMov = $queryMov->whereBetween('dt_pesquisa',[$request['dti'], $request['dtf'] ]);
            $Campo='dt_pesquisa';
            $vlCampo="case when tp_pesquisa='Q' then vl_pagrec else vl_boleto end";
        }
 
        if($request['categoria']){
            $CodEstrutural=Categoria::find($request['categoria']);
            $Cat = Categoria::where('cod_estrutural','like',$CodEstrutural->cod_estrutural.'%')->select("cd_categoria")->get();
            $queryMov=$queryMov->whereIn("cd_categoria",$Cat->toArray());
        }
        if($request['conta']){
            $queryMov=$queryMov->where("cd_conta",$request['conta']);
        }
        if($request['setor']){
            $queryMov=$queryMov->where("cd_setor",$request['setor']);
        }
        if($request['turma']){
            $queryMov=$queryMov->where("cd_turma",$request['turma']);
        }
        if($request['fornecedor']){
            $queryMov=$queryMov->where("cd_fornecedor",$request['fornecedor']);
        }

        if($request['fluxo_caixa'] == 'M'){
            $queryMov = $queryMov->selectRaw("date_format(".$Campo.",'%m') comp,
                sum( if(tipo='despesa' , ".$vlCampo.", 0 )  ) valor_despesa,
                sum( if(tipo='receita' , ".$vlCampo.", 0 ) ) valor_receita,
                sum( case when tp_mov='TR' and tipo='despesa' then ".$vlCampo."*(-1) when tp_mov='TR' and tipo='receita' then ".$vlCampo." else 0 end  ) valor_tranf
            ")
            ->groupByRaw("date_format(".$Campo.",'%m')");
        }

        if($request['fluxo_caixa'] == 'D'){
            $queryMov = $queryMov->selectRaw("date_format(".$Campo.",'%d') comp,
                sum( if(tipo='despesa' , ".$vlCampo.", 0 )  ) valor_despesa,
                sum( if(tipo='receita' , ".$vlCampo.", 0 ) ) valor_receita,
                sum( case when tp_mov='TR' and tipo='despesa' then ".$vlCampo."*(-1) when tp_mov='TR' and tipo='receita' then ".$vlCampo." else 0 end  ) valor_tranf
            ")
            ->groupByRaw("date_format(".$Campo.",'%d')");
        }

        if($request['fluxo_caixa'] == 'A'){
            $queryMov = $queryMov->selectRaw("date_format(".$Campo.",'%Y') comp,
                sum( if(tipo='despesa' , ".$vlCampo.", 0 )  ) valor_despesa,
                sum( if(tipo='receita' , ".$vlCampo.", 0 ) ) valor_receita,
                sum( case when tp_mov='TR' and tipo='despesa' then ".$vlCampo."*(-1) when tp_mov='TR' and tipo='receita' then ".$vlCampo." else 0 end  ) valor_tranf
            ")
            ->groupByRaw("date_format(".$Campo.",'%Y')");
        } 
        $queryMov = $queryMov->get(); 
        return $queryMov;

    }
     

    public function saldo_inicial_mes($request) {

        $querySaldo = DocumentoBoleto::where('situacao','QUITADO')
        ->whereBetween('dt_pagrec',[$request['dti'], $request['dtf'] ]);
   
        if($request['fluxo_caixa'] == 'M'){
            $querySaldo = $querySaldo->select(DB::raw(" date_format(dt_pagrec,'%m') comp,
            sum( if(tipo='despesa', (vl_pagrec*(-1)) , vl_pagrec ) ) valor  
            "))
            ->groupByRaw("date_format(dt_pagrec,'%m')");
        }

        if($request['fluxo_caixa'] == 'D'){
            $querySaldo = $querySaldo->select(DB::raw(" date_format(dt_pagrec,'%d') comp,
            sum( if(tipo='despesa', (vl_pagrec*(-1)) , vl_pagrec ) ) valor  
            "))
            ->groupByRaw("date_format(dt_pagrec,'%d')");
        }

        if($request['fluxo_caixa'] == 'A'){
            $querySaldo = $querySaldo->select(DB::raw(" date_format(dt_pagrec,'%Y') comp,
            sum( if(tipo='despesa', (vl_pagrec*(-1)) , vl_pagrec ) ) valor  
            "))
            ->groupByRaw("date_format(dt_pagrec,'%Y')");
        }

        $querySaldo = $querySaldo->get(); 
        return $querySaldo;
    }

     
    public function detalhe_movimento($request) {
 
        $situacao=array('QUITADO','ABERTO');
        if($request['visao']=="REAL"){ $situacao=array('QUITADO'); $CampoComp="dt_pagrec"; $CampoValor="vl_pagrec ";  }
        if($request['visao']=="PREV"){ $situacao=array('ABERTO'); $CampoComp="dt_vencimento"; $CampoValor="vl_boleto"; }
        if($request['visao']=="PRRE"){ $situacao=array('QUITADO','ABERTO'); $CampoComp="dt_pesquisa"; $CampoValor="case when tp_pesquisa='Q' then vl_pagrec else vl_boleto end"; }

        $etapa=array('receita','despesa');
        if($request['tpPesquisa']=="receita"){ $etapa=array('receita'); } 
        if($request['tpPesquisa']=="despesa"){ $etapa=array('despesa'); } 
         
        $querySaldo = DocumentoBoleto::whereIn('situacao',$situacao)
        ->whereIn('tipo',$etapa);
 
        if($request['categoria']){
            $CodEstrutural=Categoria::find($request['categoria']);
            $Cat = Categoria::where('cod_estrutural','like',$CodEstrutural->cod_estrutural.'%')->select("cd_categoria")->get();
            $querySaldo=$querySaldo->whereIn("documento_boleto.cd_categoria",$Cat->toArray());
        }
        if($request['conta']){
            $querySaldo=$querySaldo->where("documento_boleto.cd_conta",$request['conta']);
        }
        if($request['setor']){
            $querySaldo=$querySaldo->where("documento_boleto.cd_setor",$request['setor']);
        }
        if($request['turma']){
            $querySaldo=$querySaldo->where("documento_boleto.cd_turma",$request['turma']);
        }
        if($request['fornecedor']){
            $querySaldo=$querySaldo->where("documento_boleto.cd_fornecedor",$request['fornecedor']);
        }

        if($request['tpPesquisa']=="transferencia"){
            $querySaldo=$querySaldo->where("tp_mov","TR");
            $request['detalhe']="cd_conta";
        }else{
            if($request['tpPesquisa']=="saldo_final"){
                $request['detalhe']="cd_conta";
            }else{
                $querySaldo=$querySaldo->where("tp_mov","<>","TR");
            }
            
        }


        
        if($request['detalhe']=="cd_categoria"){
            $querySaldo=$querySaldo->leftJoin('categoria','categoria.cd_categoria','documento_boleto.cd_categoria');
            $CamposCodigo="documento_boleto.cd_categoria ";
            $CamposNome="nm_categoria ";
        }
        if($request['detalhe']=="cd_fornecedor"){
            $querySaldo=$querySaldo->leftJoin('fornecedor','fornecedor.cd_fornecedor','documento_boleto.cd_fornecedor'); 
            $CamposCodigo="documento_boleto.cd_fornecedor ";
            $CamposNome="nm_fornecedor ";
        }
        if($request['detalhe']=="cd_conta"){
            $querySaldo=$querySaldo->leftJoin('conta_bancaria','conta_bancaria.cd_conta','documento_boleto.cd_conta');
            $CamposCodigo="documento_boleto.cd_conta ";
            $CamposNome="nm_conta ";
        }

        if($request['fluxo_caixa']=="M"){ $querySaldo=$querySaldo->selectRaw($CamposCodigo." codigo,".$CamposNome." nome, date_format(".$CampoComp.",'%m') dt, sum(".$CampoValor.") valor ")
                                                                 ->groupByRaw($CamposCodigo.",".$CamposNome.",date_format(".$CampoComp.",'%m')"); 
            $Dti=(int)$request['mes_inicial'];  
            $Dtf=(int)$request['mes_final'];                                                                    
        }

        if($request['fluxo_caixa']=="A"){ $querySaldo=$querySaldo->selectRaw($CamposCodigo." codigo,".$CamposNome." nome, date_format(".$CampoComp.",'%Y') dt, sum(".$CampoValor.") valor ")
                                                                 ->groupByRaw($CamposCodigo.",".$CamposNome.",date_format(".$CampoComp.",'%Y')");  
            $Dti=(int)$request['ano'];
            $Dtf=(int)$request['ano_final'];   
        }
        
        if($request['fluxo_caixa']=="D"){ $querySaldo=$querySaldo->selectRaw($CamposCodigo." codigo,".$CamposNome." nome, date_format(".$CampoComp.",'%d') dt, sum(".$CampoValor.") valor ")
                                                                 ->groupByRaw($CamposCodigo.",".$CamposNome.",date_format(".$CampoComp.",'%d')");   
            $Dti=1;
            $Dtf=(int)$request['ultimo_dia']; 
        }

        $querySaldo =$querySaldo->whereBetween($CampoComp,[$request['dti'], $request['dtf'] ])
        ->orderByRaw($CamposNome)->get();
 
        $Codigos=null;
        foreach($querySaldo as $val){
            $relacao[$val->nome][$val->dt]= $val->valor;
            $Codigos[$val->nome]=$val->codigo;
        }
        
        if(isset($relacao)){
            foreach($relacao as $key => $val){ 
                  
                $Colunas[]=array('codigo' => null, 'nome'  =>$key,'cd_filtro'=> null,'nm_filtro'=>null ); 
                for($i = $Dti; $i <= $Dtf; ++$i) {
                    $Colunas[] = array( 'codigo' => str_pad($i , 2 , '0' , STR_PAD_LEFT) ,
                                        'nome' => (isset($val[str_pad($i , 2 , '0' , STR_PAD_LEFT)])) ? $val[str_pad($i , 2 , '0' , STR_PAD_LEFT)] : 0,
                                        'cd_filtro'=>  (isset($Codigos[$key])) ? $Codigos[$key] : null,
                                        'nm_filtro'=> $key
                                    );
                }
                $retorno[] = $Colunas;
                $Colunas = null;
            }
        }else{
            $retorno = null;
        }
 
        return $retorno;

    }

 

}
