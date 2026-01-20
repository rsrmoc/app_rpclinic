<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class DevolucaoEstoqueProduto extends Model
{
    protected $table = 'devolucao_estoque_prod';
    protected $primaryKey = 'cd_devolucao_prod';

    public $timestamps = false;

    protected $fillable = [
        'cd_devolucao',
        'cd_produto',
        'cd_lote_produto',
        'qtde',
        'cd_usuario',
        'dt_lancamento'
    ];

    public function produto() {
        return $this->hasOne(Produto::class, 'cd_produto', 'cd_produto');
    }

    public function lote() {
        return $this->hasOne(ProdutoLote::class, 'cd_lote', 'cd_lote_produto');
    }
}
