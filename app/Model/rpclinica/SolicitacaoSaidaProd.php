<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class SolicitacaoSaidaProd extends Model
{
    protected $table = 'solicitacao_saida_prod';
    protected $primaryKey = 'cd_solic_saida';

    const UPDATED_AT = null;

    protected $fillable = [
        'cd_solicitacao',
        'cd_produto',
        'cd_lote_produto',
        'qtde',
        'dt_lancamento',
        'cd_usuario'
    ];

    public function produto() {
        return $this->hasOne(Produto::class, 'cd_produto', 'cd_produto');
    }

    public function lote() {
        return $this->hasOne(ProdutoLote::class, 'cd_lote', 'cd_lote_produto');
    }
}
