<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AjusteEstoqueProduto extends Model
{
    protected $table = 'ajuste_estoque_prod';
    protected $primaryKey = 'cd_ajuste_prod';

    public $timestamps = false;

    protected $fillable = [
        'cd_ajuste',
        'cd_produto',
        'cd_lote_prod',
        'qtde',
        'dt_lancamento',
        'cd_usuario',
    ];
}
