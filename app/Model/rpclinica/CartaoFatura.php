<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class CartaoFatura extends Model {
    protected $table = "cartao_fatura";
    protected $primaryKey = "cd_fatura";

    protected $fillable = [
        "cd_fatura",
        "cd_conta",
        "cd_empresa",
        "cd_conta_pag",
        "cd_forma",
        "descricao",
        "documento",
        "ds_pagamento",
        "dt_abertura",
        "dt_fechamento",
        "dt_vencimento",
        "dt_pagamento",
        "cd_usuario",
        "vl_fatura",
        "vl_pago",
        "cd_doc_boleto_dif",
        "cd_doc_boleto_baixa",
        "cd_doc_boleto_credito",
        "situacao",
        "user_situacao",
        "competencia",
        "sn_fechada"
    ];

    public function boleto_item() {
        return $this->hasMany(DocumentoBoleto::class, "cd_fatura_cartao", "cd_fatura")
        ->selectRaw("documento_boleto.*, 
            DATE_FORMAT(dt_vencimento, '%d/%m/%Y') data_vencimento,
            DATE_FORMAT(data_compra, '%d/%m/%Y') dt_compra");
    }
}