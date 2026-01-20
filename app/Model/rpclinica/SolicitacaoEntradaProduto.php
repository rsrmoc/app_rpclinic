<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitacaoEntradaProduto extends Model
{
    protected $table = 'solicitacao_ent_prod';
    protected $primaryKey = 'cd_entrada_produto';

    public $timestamps = false;

    protected $fillable = [
        'cd_solicitacao',
        'cd_produto',
        'cd_lote_produto',
        'qtde',
        'valor',
        'dt_lancamento',
        'cd_usuario'
    ];

    public function loteProduto() {
        return $this->hasOne(ProdutoLote::class, 'cd_lote', 'cd_lote_produto');
    }
}
