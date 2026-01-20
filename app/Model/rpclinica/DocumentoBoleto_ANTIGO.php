<?php

namespace App\Model\rpclinica;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;

class DocumentoBoleto extends Model {
   // use SoftDeletes;

    protected $table = "documento_boleto";
    protected $primaryKey = "cd_documento_boleto";

    protected $fillable = [
        "cd_documento",
        "cd_agendamento",
        "ds_boleto",
        "tp_mov",
        "doc_boleto",
        "dt_vencimento",
        "vl_boleto",
        "dt_pagrec",
        "vl_pagrec",
        "cd_usuario_pagrec",
        "data_pagrec",
        "situacao",
        "cd_boleto_pai",
        "cd_usuario",
        "cd_empresa",
        "cd_categoria",
        "cd_conta",
        "cd_forma",
        "cd_fornecedor",
        "cd_setor",
        "cd_marca",
        "cd_turma",
        "cd_evento",
        "dt_emissao",
        "tipo",
        "hash_grupo",
        "data_compra",
        "cd_fatura_cartao",
        "sn_transferencia",
        "sn_cartao"
    ];

    protected $appends = ["status"];

    protected $casts = [
        "vl_boleto" => "double",
        "vl_pagrec" => "double",
    ];
 

    public function fornecedor() {
        return $this->hasOne(Fornecedor::class, "cd_fornecedor", "cd_fornecedor");
    }

    public function empresa() {
        return $this->hasOne(Empresa::class, "cd_empresa", "cd_empresa");
    }

    public function forma() {
        return $this->hasOne(FormaPagamento::class, "cd_forma_pag", "cd_forma");
    }

    public function categoria() {
        return $this->hasOne(Categoria::class, "cd_categoria", "cd_categoria");
    }

    public function usuario() {
        return $this->hasOne(Categoria::class, "cd_usuario", "cd_usuario");
    }

    public function conta() {
        return $this->hasOne(ContaBancaria::class, "cd_conta", "cd_conta");
    }

    public function setor() {
        return $this->hasOne(Setores::class, "cd_setor", "cd_setor");
    }

    public function marca() {
        return $this->hasOne(Marca::class, "cd_marca", "cd_marca");
    }

    public function agendamento() {
        return $this->hasOne(Agendamento::class, "cd_agendamento", "cd_agendamento");
    }

    public function itens_cartao() {
        return $this->hasMany(DocumentoBoleto::class, "cd_documento_boleto", "cd_documento_boleto");
    }
    
    public function getStatusAttribute() {
        $status = null;

        if ($this->dt_pagrec && $this->tipo == "receita") $status = "Recebido";

        if ($this->dt_pagrec && $this->tipo == "despesa") $status = "Pago";

        if (!$this->dt_pagrec && $this->tipo == "receita") $status = "A receber";

        if (!$this->dt_pagrec && Carbon::parse($this->dt_vencimento)->eq(Carbon::now()->subDay())) $status = "A vencer";

        if (!$this->dt_pagrec && Carbon::parse($this->dt_vencimento)->lt(Carbon::now())) $status = "Vencido";

        return $status;
    }



    public function scopePainelSaldo(Builder $query, $request): Builder
    {
        $query =$query->where("situacao",'QUITADO'); 
       
        if($request->cd_conta){ 
            $query =$query->where("cd_conta",$request->cd_conta); 
        } 
        
        $query =$query->select(DB::raw(" sum( case when tipo='despesa' then vl_pagrec*(-1) else vl_pagrec end) saldo")); 
     

        return $query;
    }

    public function scopePainelFinanceiro(Builder $query, $request): Builder
    {

        $query = $query->with("empresa", "fornecedor","forma","conta") 
        ->leftJoin("categoria","categoria.cd_categoria","documento_boleto.cd_categoria");
  
        if($request->dt_inicial){ 
            $query->whereDate(DB::raw("dt_pesquisa"), ">=", $request->dt_inicial);
        } 
        if($request->dt_final){ 
            $query->whereDate(DB::raw("dt_pesquisa"), "<=", $request->dt_final);
        } 

        if ((!$request->dt_final) && (!$request->dt_inicial) ) { 
            $query->where(DB::raw("DATE_FORMAT(dt_pesquisa, '%Y%m') "), "=", $request->ano.str_pad($request->mes , 2 , '0' , STR_PAD_LEFT));
        }

        if($request->ds_boleto){ 
            $query =$query->where("ds_boleto","like",$request->ds_boleto); 
        }

        if($request->nr_documento){ 
            $query =$query->where("doc_boleto","like",$request->nr_documento); 
        }
      
        if($request->cd_categoria){
            $Cat = Categoria::where('cd_categoria',$request->cd_categoria)->first(); 
            if($request->notCategoria=="false"){ 
                $query =$query->where("cod_estrutural","like",$Cat->cod_estrutural."%"); 
            }else{
                $query =$query->whereRaw("cd_categoria not in ( select cd_categoria from categoria where cod_estrutural like ".$Cat->cod_estrutural."% ) ");  
            }
        }
        if($request->cd_setor){ 
            if($request->notSetor=="false"){ 
                $query =$query->whereIn("documento_boleto.cd_setor",$request->cd_setor); 
            }else{
                $query =$query->whereNotIn("documento_boleto.cd_setor",$request->cd_setor); 
            } 
        } 
        if($request->cd_forma){ 
            if($request->notForma=="false"){ 
                $query =$query->whereIn("documento_boleto.cd_forma",$request->cd_forma); 
            }else{
                $query =$query->whereNotIn("documento_boleto.cd_forma",$request->cd_forma); 
            } 
        } 
        if($request->cd_turma){ 
            if($request->notTurma=="false"){  
                $query =$query->whereIn("documento_boleto.cd_turma",$request->cd_turma);
            }else{
                $query =$query->whereNotIn("documento_boleto.cd_turma",$request->cd_turma);
            } 
        } 

        if($request->cd_conta){ 
            if($request->notConta=="false"){ 
            $query =$query->whereIn("documento_boleto.cd_conta",$request->cd_conta); 
            }else{
                $query =$query->whereNotIn("documento_boleto.cd_conta",$request->cd_conta);  
            }
        } 
        if($request->cd_fornecedor){  
            if($request->notFornecedor=="false"){ 
                $query =$query->whereIn(DB::raw("ifnull(documento_boleto.cd_fornecedor,0)"),$request->cd_fornecedor);  
            }else{
                $query =$query->whereNotIn(DB::raw("ifnull(documento_boleto.cd_fornecedor,0)"),$request->cd_fornecedor); 
            }
        }   


        if ($request->credito == "true" && $request->debito == "false") {
            $query->where("tipo", "receita"); 
        } 
        if ($request->debito == "true" && $request->credito == "false") {
            $query->where("tipo", "despesa"); 
        }  
     
        if ($request->realizado == "true" && $request->n_realizado == "false") {
            $query->whereNotNull("dt_pagrec"); 
        } 
        if ($request->n_realizado == "true" && $request->realizado == "false") {
            $query->whereNull("dt_pagrec"); 
        }
  

        if ($request->vencido == "true" && $request->a_vencer == "false") {
            $query->whereNull("dt_pagrec")->whereDate("dt_vencimento", "<", date("Y-m-d")); 
        }
        if ($request->a_vencer == "true" && $request->vencido == "false") {
            $query->whereNull("dt_pagrec")->whereDate("dt_vencimento", ">", date("Y-m-d")); 
        }
 
        if ($request->transferencia == "true" && $request->lancamentos == "false") {
            $query->where("sn_transferencia", "S"); 
        }
        if ($request->lancamentos == "true" && $request->transferencia == "false") {
            $query->whereNull("sn_transferencia"); 
        }
   

        if ($request->has("colunaOrdenada") && !empty($request->colunaOrdenada)) {
            $colunaOrdenada = $request->get('colunaOrdenada');
            $direcaoOrdenacao = $request->get('direcaoOrdenacao', 'asc');

            if ($colunaOrdenada == 'data') {
                $query->orderBy("dt_pesquisa", $direcaoOrdenacao);

            } elseif ($colunaOrdenada == 'fornecedor') {
                $query = $query->leftjoin('fornecedor', 'documento_boleto.cd_fornecedor', '=', 'fornecedor.cd_fornecedor')
                   ->orderBy('fornecedor.nm_fornecedor', $direcaoOrdenacao); 

            } elseif ($colunaOrdenada == 'descricao') {
                $query->orderBy('ds_boleto', $direcaoOrdenacao); 

            } elseif ($colunaOrdenada == 'valor') {
                $query->orderBy('vl_boleto', $direcaoOrdenacao); 

            }  
        } else {
            $query->orderBy('dt_pagrec'); 
        }
        
 
        return $query;
    }
    
    public function scopeFluxoCaixa(Builder $query,$tipo, $request): Builder
    {
        if($tipo=='inicial'){
            $query = $query->where('situacao','QUITADO');
             
        }

        $query = $query->with("empresa", "fornecedor","forma","conta");
        
        return $query;
    }
 

}


